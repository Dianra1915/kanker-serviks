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
        '0.8' => 'Yakin',
        '0.6' => 'Cukup Yakin',
        '0.4' => 'Kurang Yakin'
    ];

    return view('konsultasi.index', compact('gejalas', 'skala'));
    }

    public function prosesDiagnosa(Request $request) {
        $jawabanUser = $request->jawaban ?? []; // Berisi [gejala_id => nilai_cf]

        // Ambil ID gejala yang dipilih (yang nilainya di atas 0)
        $gejalaTerpilih = [];
        foreach ($jawabanUser as $gejalaId => $nilaiCf) {
            if ($nilaiCf > 0) {
                $gejalaTerpilih[] = $gejalaId;
            }
        }
        // 1. VALIDASI: Jika tidak ada gejala yang dipilih sama sekali
        if (empty($gejalaTerpilih)) {
            return redirect()->back()->with('error', 'Silakan pilih minimal tiga gejala.');
        }

        // 2. LOGIKA BARU ANDA: Jika jumlah gejala kurang dari 3, batalkan deteksi risiko
        if (count($gejalaTerpilih) < 3) {
            return redirect()->back()->with('error', 'Gejala yang dipilih belum cukup untuk menentukan kecenderungan jenis kanker serviks. Silakan lakukan konsultasi ulang jika merasakan gejala lainnya atau segera periksakan diri ke fasilitas kesehatan terdekat.');
        }

        $semuaJenis = Jenis::all(); //
        $hasilPerJenis = [];

        // --- TAHAP 1 & 2: FORWARD CHAINING & HITUNG CERTAINTY FACTOR ---
        foreach ($semuaJenis as $jenis) { //
            $rules = Rule::where('jenis_id', $jenis->id)->get(); //
            $cfCombine = 0;
            $isFirst = true; //

            foreach ($rules as $rule) { //
                if (in_array($rule->gejala_id, $gejalaTerpilih)) {
                    $cfUser = (float)$jawabanUser[$rule->gejala_id]; //
                    $cfE = ($rule->mb - $rule->md) * $cfUser; //

                    if ($isFirst) { //
                        $cfCombine = $cfE; //
                        $isFirst = false; //
                    } else { //
                        $cfCombine = $cfCombine + ($cfE * (1 - $cfCombine)); //
                    }
                }
            }
            if ($cfCombine > 0) $hasilPerJenis[$jenis->id] = $cfCombine; //
        }

        if (empty($hasilPerJenis)) { //
            return redirect()->back()->with('error', 'Gejala yang Anda pilih tidak mengarah ke diagnosa apapun.'); //
        }

        // --- TAHAP 3: RESOLUSI KONFLIK (ALUR EVALUASI BERTINGKAT/ Persentase kecocokan / matching rule) ---
        $cfTertinggi = max($hasilPerJenis);
        $kandidatPenyakit = array_keys($hasilPerJenis, $cfTertinggi);

        $statusDiagnosa = "Tunggal";
        $idTerpilih = $kandidatPenyakit[0];
        $kandidatIds = [];

        if (count($kandidatPenyakit) > 1) {
            $skorMatching = [];
            foreach ($kandidatPenyakit as $jenisId) {
                $totalGejalaDiRule = Rule::where('jenis_id', $jenisId)->count();
                $jumlahGejalaCocok = Rule::where('jenis_id', $jenisId)->whereIn('gejala_id', $gejalaTerpilih)->count();
                $persentase = $totalGejalaDiRule > 0 ? ($jumlahGejalaCocok / $totalGejalaDiRule) * 100 : 0;
                $skorMatching[$jenisId] = $persentase;
            }

            $matchingTertinggi = max($skorMatching);
            $kandidatBerdasarkanMatching = array_keys($skorMatching, $matchingTertinggi);

            if (count($kandidatBerdasarkanMatching) == 1) {
                $idTerpilih = $kandidatBerdasarkanMatching[0];
            } else {
                $statusDiagnosa = "Multi";
                $idTerpilih = $kandidatBerdasarkanMatching[0]; 
                $kandidatIds = $kandidatBerdasarkanMatching;  
            }
        }

        // --- TAHAP 4: SIMPAN UTAMA KE TABEL HASIL KONSULTASI DULU ---
        $hasil = HasilKonsultasi::create([ //
            'user_id' => Auth::id(), //
            'jenis_id' => $idTerpilih, //
            'total_cf' => $cfTertinggi, //
            'tgl_konsultasi' => now() //
        ]); //

        // --- TAHAP 5: SIMPAN DETAIL GEJALA TERPILIH (Mengikat ke hasil->id) ---
        foreach ($gejalaTerpilih as $gejalaId) {
            Konsultasi::create([
                'user_id' => Auth::id(),
                'hasil_konsultasi_id' => $hasil->id, // Mengikat data gejala ke sesi hasil konsultasi ini
                'gejala_id' => $gejalaId,
                'nilai_cf_user' => (float)$jawabanUser[$gejalaId]
            ]);
        }

        if ($statusDiagnosa == "Multi") {
            return redirect()->route('konsultasi.hasil', $hasil->id)->with([
                'status_diagnosa' => 'Multi',
                'kandidat_ids' => $kandidatIds,
                'pesan_khusus' => 'Pasien memiliki kecenderungan terhadap lebih dari satu jenis kanker serviks. Diperlukan pemeriksaan lanjutan oleh dokter spesialis Obstetri dan Ginekologi.'
            ]);
        }

        return redirect()->route('konsultasi.hasil', $hasil->id); //
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
        $gejalaTerpilih = Konsultasi::with('gejala')->where('hasil_konsultasi_id', $hasil->id)->get();
        return view('konsultasi.hasil', compact('hasil', 'gejalaTerpilih'));
    }

    public function cetakPdf($id) {
        $hasil = HasilKonsultasi::with(['user', 'jenis'])->findOrFail($id);
        $gejalaTerpilih = Konsultasi::with('gejala')->where('hasil_konsultasi_id', $hasil->id)->get();
        
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