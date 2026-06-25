<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Gejala, Rule, Jenis, Konsultasi, HasilKonsultasi};
use Illuminate\Support\Facades\DB;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf; 

class KonsultasiController extends Controller {

    public function index(Request $request) {
        if ($request->has('reset')) {
            session()->forget('konsultasi_answers');
            return redirect()->route('konsultasi.index');
        }

        $answers = session('konsultasi_answers', []); 
        
        $semuaJenis = Jenis::orderBy('id', 'asc')->get();
        
        $nextGejalaId = null;

        foreach ($semuaJenis as $jenis) {
            $rules = Rule::where('jenis_id', $jenis->id)->orderBy('id', 'asc')->get();
            
            $jumlahTidak = 0;
            $jumlahYa = 0;
            $gejalaBelumDijawab = [];

            foreach ($rules as $rule) {
                $gId = $rule->gejala_id;
                if (isset($answers[$gId])) {
                    if ($answers[$gId] == 0) $jumlahTidak++;
                    // CF User lebih dari 0 dianggap "YA" untuk Forward Chaining
                    if ($answers[$gId] > 0) $jumlahYa++; 
                } else {
                    $gejalaBelumDijawab[] = $gId;
                }
            }

            if ($jumlahTidak >= 5 && $jumlahYa == 0) {
                continue; 
            }

            if (!empty($gejalaBelumDijawab)) {
                $nextGejalaId = $gejalaBelumDijawab[0];
                break; 
            }
        }

        if (!$nextGejalaId) {
            return redirect()->route('konsultasi.proses');
        }

        $gejala = Gejala::find($nextGejalaId);
        $pertanyaanKe = count($answers) + 1;

        return view('konsultasi.index', compact('gejala', 'pertanyaanKe'));
    }

    public function simpanJawaban(Request $request) {
        $answers = session('konsultasi_answers', []);
        // Menyimpan nilai CF User (1.0, 0.8, 0.6, 0.4, 0.2, atau 0.0)
        $answers[$request->gejala_id] = $request->jawaban; 
        session(['konsultasi_answers' => $answers]);

        return redirect()->route('konsultasi.index'); 
    }
    
    public function kembali() {
        $answers = session('konsultasi_answers', []);
        
        if (!empty($answers)) {
            // Mengambil kunci (gejala_id) terakhir yang dijawab
            $keys = array_keys($answers);
            $lastKey = end($keys);
            
            // Menghapus jawaban terakhir dari memori (Undo)
            unset($answers[$lastKey]);
            session(['konsultasi_answers' => $answers]);
        }

        return redirect()->route('konsultasi.index');
    }

    public function prosesDiagnosa() {
        $answers = session('konsultasi_answers', []);

        if (empty($answers)) {
             return redirect()->route('konsultasi.index')->with('error', 'Sesi tidak valid.');
        }

        // Langkah 1. : Filter, Hanya ambil ID Gejala yang dijawab "YA" (Nilai > 0)
        $gejalaTerpilih = [];
        foreach ($answers as $gId => $val) {
            if ($val > 0) {
                $gejalaTerpilih[] = $gId;
            }
        }

        if (count($gejalaTerpilih) < 2) {
            session()->forget('konsultasi_answers');
            return redirect()->route('konsultasi.index')->with('tidak_terdeteksi', 'Berdasarkan Konsultasi yang dilakukan oleh pasien, 
            pasien tidak terdeteksi memiliki risiko terhadap kanker serviks.');
        }

        //2. Ambil semua aturan (rules) penyakit dari database
        $semuaJenis = Jenis::all();
        $hasilPerJenis = [];

        $gejalaMayor = [
            5 => [61, 70, 71, 75, 77], // Sesuaikan dengan ID asli di database Anda
        ];

        foreach ($semuaJenis as $jenis) {

            if (isset($gejalaMayor[$jenis->id])) {
                $punyaGejalaMayor = false;
                
                foreach ($gejalaMayor[$jenis->id] as $mayorId) {
                    if (in_array($mayorId, $gejalaTerpilih)) {
                        $punyaGejalaMayor = true;
                        break; 
                    }
                }
                if (!$punyaGejalaMayor) {
                    continue; 
                }
            }
                
            $rules = Rule::where('jenis_id', $jenis->id)->get();
            $cfCombine = 0;
            $isFirst = true;

            // ---> Letak Forward Chaining Bekerja <---
            // 3. IF Fakta (Gejala Pasien) == Premis (Gejala di Rule) THEN (Jalankan aturan)
            foreach ($rules as $rule) {
                if (in_array($rule->gejala_id, $gejalaTerpilih)) {
                    
                    // Ambil nilai CF User dari session
                    $cfUser = $answers[$rule->gejala_id];
                    
                    // Hitung CF Pakar dikalikan CF User
                    $cfE = ($rule->mb - $rule->md) * $cfUser; 

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
            session()->forget('konsultasi_answers');
            return redirect()->route('konsultasi.index')->with('tidak_terdeteksi', 
            'Berdasarkan Konsultasi yang dilakukan oleh pasien, pasien tidak terdeteksi memiliki risiko terhadap kanker serviks.');
        }

        $cfTertinggi = max($hasilPerJenis);
        $kandidatPenyakit = array_keys($hasilPerJenis, $cfTertinggi);
        $idTerpilih = $kandidatPenyakit[0];

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
            
            $idTerpilih = $kandidatBerdasarkanMatching[0]; 
        }

        $hasil = HasilKonsultasi::create([
            'user_id' => Auth::id(),
            'jenis_id' => $idTerpilih,
            'total_cf' => $cfTertinggi,
            'tgl_konsultasi' => now()
        ]);

        foreach ($gejalaTerpilih as $gejalaId) {
            Konsultasi::create([
                'user_id' => Auth::id(),
                'hasil_konsultasi_id' => $hasil->id,
                'gejala_id' => $gejalaId,
                // Simpan CF User yang dipilih ke dalam database
                'nilai_cf_user' => $answers[$gejalaId] 
            ]);
        }

        session()->forget('konsultasi_answers'); 
        return redirect()->route('konsultasi.hasil', $hasil->id);
    }

    public function destroy($id) {
        if (Auth::user()->role !== 'admin') return abort(403);
        HasilKonsultasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Riwayat berhasil dihapus.');
    }

    public function show($id) {
        $hasil = HasilKonsultasi::with(['user', 'jenis'])->findOrFail($id);
        $gejalaTerpilih = Konsultasi::with('gejala')->where('hasil_konsultasi_id', $hasil->id)->get();
        return view('konsultasi.hasil', compact('hasil', 'gejalaTerpilih'));
    }

    public function cetakPdf($id) {
        $hasil = HasilKonsultasi::with(['user', 'jenis'])->findOrFail($id);
        $gejalaTerpilih = Konsultasi::with('gejala')->where('hasil_konsultasi_id', $hasil->id)->get();
        
        $pdf = Pdf::loadView('konsultasi.cetak', compact('hasil', 'gejalaTerpilih'))
                ->setPaper('a4', 'portrait'); 
                
        return $pdf->download('Hasil-Diagnosa-'.$hasil->user->name.'.pdf');
    }

    public function cetakSemua() {
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
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }
        $riwayat = $query->get();
        return view('konsultasi.riwayat', compact('riwayat'));
    }
}