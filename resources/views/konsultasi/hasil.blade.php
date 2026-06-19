@extends('layouts.admin')

@section('main-content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow mb-4 border-left-primary">
                
                <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-medical-alt mr-2"></i>Hasil Analisis Skrining Sistem Pakar
                    </h6>
                    <a href="{{ route('konsultasi.cetak', $hasil->id) }}" class="btn btn-danger btn-sm shadow-sm font-weight-bold px-3">
                        <i class="fas fa-file-pdf fa-sm text-white mr-2"></i> Cetak PDF
                    </a>
                </div>
                
                <div class="card-body px-4 py-4">
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light border-0 shadow-sm">
                                <div class="card-body py-3">
                                    <h6 class="text-primary font-weight-bold border-bottom pb-2 mb-3">
                                        <i class="fas fa-user pr-2 mr-1"></i> Informasi Pasien
                                    </h6>
                                    <table class="table table-sm table-borderless text-dark mb-0" style="font-size: 0.95rem;">
                                        <tr>
                                            <td width="20%" class="text-muted">Nama Pasien</td>
                                            <td>: <strong>{{ $hasil->user->username }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">No. Handphone</td>
                                            <td>: {{ $hasil->user->phone_number ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Tanggal Skrining</td>
                                            <td>: {{ \Carbon\Carbon::parse($hasil->tgl_konsultasi)->locale('id')->translatedFormat('d F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Waktu Sesi</td>
                                            <td>: {{ \Carbon\Carbon::parse($hasil->tgl_konsultasi)->format('H:i') }} WITA</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light py-2 border-0">
                                    <h6 class="m-0 font-weight-bold text-dark" style="font-size: 0.95rem;">
                                        <i class="fas fa-notes-medical text-primary mr-2"></i> Gejala Klinis Yang Dikonfirmasi (Fakta Pasien)
                                    </h6>
                                </div>
                                <div class="card-body p-3 bg-white border">
                                    <div class="row">
                                        @forelse($gejalaTerpilih as $item)
                                            <div class="col-md-6 my-1">
                                                <div class="d-flex align-items-start text-dark" style="font-size: 0.95rem;">
                                                    <i class="fas fa-check-circle text-success mr-2 mt-1" style="font-size: 0.9rem;"></i>
                                                    <span><strong class="text-primary">[{{ $item->gejala->kode_gejala }}]</strong> {{ $item->gejala->nama_gejala }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center text-muted py-2 italic">
                                                Tidak ada detail riwayat gejala spesifik yang tercatat.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm text-center" style="background-color: #f8f9fc; border-top: 4px solid #e74c3c !important;">
                                <div class="card-body py-4">
                                    <h6 class="text-muted text-uppercase font-weight-bold mb-3" style="font-size: 0.85rem; letter-spacing: 1px;">
                                        Kesimpulan Risiko Skrining
                                    </h6>
                                    <p class="text-dark mb-0 px-3" style="font-size: 1.15rem; line-height: 1.8; font-weight: 500; text-align: center;">
                                        Berdasarkan Konsultasi yang dilakukan oleh pasien, <br>
                                        memiliki risiko terhadap kanker serviks sebesar 
                                        <span class="badge badge-danger px-3 py-1 font-weight-bold shadow-sm mx-1" style="font-size: 1.1rem; border-radius: 4px;">
                                            {{ number_format($hasil->total_cf * 100, 0) }}%
                                        </span> 
                                        dengan kecenderungan pola gejala mengarah pada 
                                        <span class="text-danger font-weight-bold text-uppercase d-block mt-2" style="font-size: 1.4rem; letter-spacing: 0.5px;">
                                            {{ $hasil->jenis->nama_jenis }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm border-0" style="border-left: 5px solid #36b9cc !important;">
                                <div class="card-body bg-white py-4 px-4 border border-left-0 rounded-right">
                                    <h5 class="font-weight-bold text-info mb-3">
                                        <i class="fas fa-heartbeat mr-2"></i>Solusi & Saran Tindakan Rekomendasi
                                    </h5>
                                    
                                    <div class="text-gray-800 p-3 rounded" style="font-size: 1.05rem; line-height: 1.8; text-align: justify; background-color: #fdfefe; border: 1px dashed #a3e4d7;">
                                        {!! nl2br(e($hasil->jenis->solusi)) !!}
                                    </div>
                                    
                                    <div class="alert alert-warning mt-3 mb-0 border-0 shadow-sm" style="font-size: 0.9rem; border-left: 4px solid #f6c23e !important; color: #664d03; background-color: #fff3cd; text-align: justify;">
                                        <i class="fas fa-info-circle mr-2 text-warning" style="font-size: 1.1rem;"></i>
                                        <strong>Pemberitahuan Penting:</strong> Hasil yang ditampilkan oleh sistem merupakan hasil deteksi risiko berdasarkan gejala dan faktor risiko yang dipilih pengguna menggunakan metode 
                                        Forward Chaining dan Certainty Factor. Hasil ini bukan merupakan diagnosis medis pasti dan tidak dapat menggantikan pemeriksaan langsung oleh dokter. Untuk memperoleh diagnosis yang akurat, 
                                        pasien tetap disarankan melakukan konsultasi dan pemeriksaan lebih lanjut dengan dokter spesialis Obstetri dan Ginekologi (Obgyn).
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <a href="{{ route('konsultasi.index') }}" class="btn btn-primary btn-lg px-5 py-2.5 mx-2 shadow border-0 font-weight-bold" style="border-radius: 30px; font-size: 0.95rem; transition: 0.3s;">
                                <i class="fas fa-redo-alt mr-2"></i> Skrining Ulang
                            </a>
                            <a href="{{ route('riwayat') }}" class="btn btn-outline-secondary btn-lg px-5 py-2.5 mx-2 shadow-sm font-weight-bold" style="border-radius: 30px; font-size: 0.95rem; transition: 0.3s;">
                                <i class="fas fa-history mr-2"></i> Lihat Riwayat
                            </a>
                        </div>
                    </div>

                </div> </div> </div>
    </div>
</div>
@endsection