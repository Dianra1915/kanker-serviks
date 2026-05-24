<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Gejala, Rule, Jenis, Konsultasi, HasilKonsultasi};
use Illuminate\Support\Facades\DB;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan sudah install dompdf

class KonsultasiController extends Controller {

    public function index() {
    // Ambil gejala yang hanya ada di tabel Rule agar tidak muncul gejala kosong
    $gejalaIds = Rule::distinct()->pluck('gejala_id')->toArray();
    $gejalas = Gejala::whereIn('id', $gejalaIds)->get();
    
    // Skala dinamis agar mudah diubah jika ada instruksi dosen
    $skala = [
        '1.0' => 'Sangat Yakin',
        '0.6' => 'Yakin',
        '0.3' => 'Sedikit Yakin',
        '0'   => 'Tidak Yakin'
    ];

    return view('konsultasi.index', compact('gejalas', 'skala'));
    }

    public function prosesDiagnosa(Request $request) {
        $jawabanUser = $request->jawaban ?? []; // Berisi [gejala_id => nilai_cf]
        if (empty($jawabanUser)) {
            return redirect()->back()->with('error', 'Silakan pilih minimal satu gejala.');
        }

        $semuaJenis = Jenis::all();
        $hasilPerJenis = [];

        // Bersihkan data konsultasi lama user ini (untuk laporan detail)
        Konsultasi::where('user_id', Auth::id())->delete();

        foreach ($semuaJenis as $jenis) {
            $rules = Rule::where('jenis_id', $jenis->id)->get();
            $cfCombine = 0;
            $isFirst = true;

            foreach ($rules as $rule) {
                // Forward Chaining: Cek apakah gejala ini dipilih user
                if (isset($jawabanUser[$rule->gejala_id]) && $jawabanUser[$rule->gejala_id] > 0) {
                    $cfUser = (float)$jawabanUser[$rule->gejala_id];

                    // Simpan detail untuk laporan
                    Konsultasi::updateOrCreate(
                        ['user_id' => Auth::id(), 'gejala_id' => $rule->gejala_id],
                        ['nilai_cf_user' => $cfUser]
                    );

                    // Rumus CF: (MB - MD) * CF User
                    $cfE = ($rule->mb - $rule->md) * $cfUser;

                    // Combine CF
                    if ($isFirst) {
                        $cfCombine = $cfE;
                        $isFirst = false;
                    } else {
                        $cfCombine = $cfCombine + ($cfE * (1 - $cfCombine));
                    }
                }
            }
            if ($cfCombine > 0) $hasilPerJenis[$jenis->id] = $cfCombine;
        }

        if (empty($hasilPerJenis)) {
            return redirect()->back()->with('error', 'Gejala yang Anda pilih tidak mengarah ke diagnosa apapun.');
        }

        arsort($hasilPerJenis);
        $idTerpilih = array_key_first($hasilPerJenis);

        $hasil = HasilKonsultasi::create([
            'user_id' => Auth::id(),
            'jenis_id' => $idTerpilih,
            'total_cf' => $hasilPerJenis[$idTerpilih],
            'tgl_konsultasi' => now()
        ]);

        return redirect()->route('konsultasi.hasil', $hasil->id);
    }

    // Tambahkan fungsi hapus untuk Admin
    public function destroy($id) {
        if (Auth::user()->role !== 'admin') return abort(403);
        HasilKonsultasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Riwayat berhasil dihapus.');
    }

    public function show($id) {
        $hasil = HasilKonsultasi::with(['user', 'jenis'])->findOrFail($id);
        // Pastikan memanggil relasi gejala melalui tabel konsultasi
        $gejalaTerpilih = Konsultasi::with('gejala')->where('user_id', $hasil->user_id)->get();
        return view('konsultasi.hasil', compact('hasil', 'gejalaTerpilih'));
    }

    public function cetakPdf($id) {
        $hasil = HasilKonsultasi::with(['user', 'jenis'])->findOrFail($id);
        $gejalaTerpilih = Konsultasi::with('gejala')->where('user_id', $hasil->user_id)->get();
        
        // Tambahkan setPaper untuk stabilitas DomPDF
        $pdf = Pdf::loadView('konsultasi.cetak', compact('hasil', 'gejalaTerpilih'))
                ->setPaper('a4', 'portrait'); 
                
        return $pdf->download('Hasil-Diagnosa-'.$hasil->user->name.'.pdf');
    }

    public function cetakSemua() {
        // Pastikan memanggil relasi dengan benar
        $riwayat = HasilKonsultasi::with(['user', 'jenis'])->latest();
        if (Auth::user()->role !== 'admin') {
            $riwayat->where('user_id', Auth::id());
        }
        $riwayat = $riwayat->get();
        
        $pdf = Pdf::loadView('konsultasi.cetak_semua', compact('riwayat'))->setPaper('a4', 'landscape');
        return $pdf->download('Laporan-Riwayat-Konsultasi.pdf');
    }

    public function riwayat() {
        $query = HasilKonsultasi::with(['user', 'jenis'])->latest();
        // Jika bukan admin, hanya lihat milik sendiri
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }
        $riwayat = $query->get();
        return view('konsultasi.riwayat', compact('riwayat'));
    }
}