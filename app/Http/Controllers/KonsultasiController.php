<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Gejala, Rule, Jenis, Konsultasi, HasilKonsultasi};
use Illuminate\Support\Facades\DB;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan sudah install dompdf

class KonsultasiController extends Controller {

    public function index(Request $request) {
        if ($request->has('reset')) {
            session()->forget('konsultasi_answers');
            return redirect()->route('konsultasi.index');
        }

        $answers = session('konsultasi_answers', []); 
        
        // Ambil semua jenis penyakit untuk dievaluasi satu per satu
        $semuaJenis = Jenis::orderBy('id', 'asc')->get();
        
        $nextGejalaId = null;

        foreach ($semuaJenis as $jenis) {
            // Ambil rule / gejala yang khusus dimiliki oleh penyakit ini saja
            $rules = Rule::where('jenis_id', $jenis->id)->orderBy('id', 'asc')->get();
            
            $jumlahTidak = 0;
            $jumlahYa = 0;
            $gejalaBelumDijawab = [];

            // Evaluasi jawaban pasien terhadap gejala-gejala penyakit ini
            foreach ($rules as $rule) {
                $gId = $rule->gejala_id;
                if (isset($answers[$gId])) {
                    if ($answers[$gId] == 0) $jumlahTidak++;
                    if ($answers[$gId] == 1) $jumlahYa++;
                } else {
                    $gejalaBelumDijawab[] = $gId;
                }
            }

            // ---> LOGIKA KECERDASAN FORWARD CHAINING (ELIMINASI HIPOTESIS) <---
            // Jika pasien sudah menjawab "TIDAK" sebanyak 5 kali pada gejala penyakit ini
            // DAN pasien belum pernah menjawab "YA" satupun untuk penyakit ini...
            // MAKA: Gugurkan penyakit ini (Skip sisa pertanyaannya) dan cek penyakit berikutnya.
            if ($jumlahTidak >= 5 && $jumlahYa == 0) {
                continue; // Lompati! (Sisa gejala penyakit ini tidak akan ditanyakan)
            }

            // Jika penyakit ini belum gugur dan masih ada gejala yang belum ditanyakan, tanyakan sekarang!
            if (!empty($gejalaBelumDijawab)) {
                $nextGejalaId = $gejalaBelumDijawab[0];
                break; // Hentikan pencarian, kita tampilkan pertanyaan ini ke layar
            }
        }

        // Jika semua penyakit sudah dievaluasi/digugurkan, otomatis hitung hasilnya
        if (!$nextGejalaId) {
            return redirect()->route('konsultasi.proses');
        }

        $gejala = Gejala::find($nextGejalaId);
        $pertanyaanKe = count($answers) + 1;

        return view('konsultasi.index', compact('gejala', 'pertanyaanKe'));
    }

    // Fungsi Baru: Menyimpan jawaban 1/0 ke memori sementara
    public function simpanJawaban(Request $request) {
        $answers = session('konsultasi_answers', []);
        $answers[$request->gejala_id] = $request->jawaban; // 1 (YA) atau 0 (TIDAK)
        session(['konsultasi_answers' => $answers]);

        return redirect()->route('konsultasi.index'); // Lanjut ke pertanyaan berikutnya
    }

    public function prosesDiagnosa() {
        $answers = session('konsultasi_answers', []);

        if (empty($answers)) {
             return redirect()->route('konsultasi.index')->with('error', 'Sesi tidak valid.');
        }

        // Langkah 1. : Filter, Hanya ambil ID Gejala yang dijawab "YA" (Nilai 1)
        $gejalaTerpilih = [];
        foreach ($answers as $gId => $val) {
            if ($val == 1) {
                $gejalaTerpilih[] = $gId;
            }
        }

        // --- SKENARIO TIDAK TERDIAGNOSA (Kurang dari 2 Gejala) ---
        if (count($gejalaTerpilih) < 2) {
            session()->forget('konsultasi_answers');
            return redirect()->route('konsultasi.index')->with('tidak_terdeteksi', 'Berdasarkan Konsultasi yang dilakukan oleh pasien, 
            pasien tidak terdeteksi memiliki risiko terhadap kanker serviks.');
        }

        $semuaJenis = Jenis::all();
        $hasilPerJenis = [];

        // --->DEKLARASI SYARAT GEJALA MAYOR DI SINI <---
        // Jenis 4 WAJIB memiliki minimal salah satu dari Gejala: 21, 30, 31, 35, atau 37
        $gejalaMayor = [
            5 => [61, 70, 71, 75, 77], 
        ];

        // --- TAHAP CF: KARENA USER JAWAB YA, NILAI CF USER = 1 ---
        // 2. Ambil semua aturan (rules) penyakit dari database
        foreach ($semuaJenis as $jenis) {

            // ---> 2. PENGECEKAN SYARAT MUTLAK (GEJALA MAYOR) <---
                if (isset($gejalaMayor[$jenis->id])) {
                    $punyaGejalaMayor = false;
                    
                    // Cek apakah ada minimal 1 gejala mayor yang dijawab "YA" oleh pasien
                    foreach ($gejalaMayor[$jenis->id] as $mayorId) {
                        if (in_array($mayorId, $gejalaTerpilih)) {
                            $punyaGejalaMayor = true;
                            break; 
                        }
                    }
                    // Jika pasien TIDAK menjawab YA pada satupun gejala mayor penyakit ini...
                    if (!$punyaGejalaMayor) {
                        continue; // ...MAKA GUGURKAN! (Lompati perhitungan CF Jenis 4 ini)
                    }
                }
                
            $rules = Rule::where('jenis_id', $jenis->id)->get();
            $cfCombine = 0;
            $isFirst = true;

            foreach ($rules as $rule) {
                // ---> LETAK FORWARD CHAINING BEKERJA <---
                // 3. IF Fakta (Gejala Pasien) == Premis (Gejala di Rule) THEN (Jalankan aturan)
                if (in_array($rule->gejala_id, $gejalaTerpilih)) {
                    // 4. MENGHITUNG CF GEJALA TUNGGAL (CF Pakar x CF User)
                    // Rumus: (MB - MD) * CF User. (CF User selalu 1)
                    $cfE = ($rule->mb - $rule->md) * 1; 

                    // 5. MENGGABUNGKAN CF (Kombinasi jika ada >1 gejala yang mengarah pada satu penyakit yang sama)
                    if ($isFirst) {
                        // Jika ini gejala pertama yang cocok, jadikan nilai CF awal
                        $cfCombine = $cfE;
                        $isFirst = false;
                    } else {
                        // 6. RUMUS CF COMBINE: CF_Lama + (CF_Baru * (1 - CF_Lama))
                        $cfCombine = $cfCombine + ($cfE * (1 - $cfCombine));
                    }
                }
            }
            // Simpan hasil CF kombinasi penyakit tersebut ke dalam array
            if ($cfCombine > 0) $hasilPerJenis[$jenis->id] = $cfCombine;
        }

        // --- SKENARIO TIDAK TERDIAGNOSA (CF Kosong / Gejala Acak) ---
        if (empty($hasilPerJenis)) {
            session()->forget('konsultasi_answers');
            return redirect()->route('konsultasi.index')->with('tidak_terdeteksi', 
            'Berdasarkan Konsultasi yang dilakukan oleh pasien, pasien tidak terdeteksi memiliki risiko terhadap kanker serviks.');
        }

        // --- TAHAP RESOLUSI KONFLIK (NILAI SAMA) ---
        $cfTertinggi = max($hasilPerJenis);
        $kandidatPenyakit = array_keys($hasilPerJenis, $cfTertinggi);
        $idTerpilih = $kandidatPenyakit[0];

        // Jika ada nilai CF yang sama, gunakan kepadatan Matching Rule
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
            
            $idTerpilih = $kandidatBerdasarkanMatching[0]; // Ambil yang persentasenya paling tinggi
        }

        // --- TAHAP SIMPAN HASIL ---
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
                'nilai_cf_user' => 1 // Selalu 1 karena pasien menjawab YA
            ]);
        }

        session()->forget('konsultasi_answers'); // Bersihkan sesi agar bisa konsultasi lagi
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