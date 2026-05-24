@extends('layouts.admin')

@section('main-content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow mb-4 border-bottom-primary">
                <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-file-medical-alt mr-2"></i>Hasil Analisis Diagnosa</h6>
                    <a href="{{ route('konsultasi.cetak', $hasil->id) }}" class="btn btn-danger btn-sm shadow-sm">
                        <i class="fas fa-file-pdf fa-sm text-white-50 mr-1"></i> Cetak PDF
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-primary border-bottom pb-2">Informasi Pasien</h5>
                            <table class="table table-sm table-borderless text-dark">
                                <tr>
                                    <td width="30%">Nama Pasien</td>
                                    <td>: <strong>{{ $hasil->user->username }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Waktu Konsultasi</td>
                                    <td>: {{ \Carbon\Carbon::parse($hasil->tgl_konsultasi)->translatedFormat('l, d F Y H:i') }} WITA</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="alert alert-info border-left-info shadow-sm py-3 mt-3">
                        <h6 class="font-weight-bold"><i class="fas fa-notes-medical mr-2"></i>Gejala yang Anda Rasakan:</h6>
                        <div class="d-flex flex-wrap mt-2">
                            @foreach($gejalaTerpilih as $item)
                                <span class="badge badge-light text-primary border m-1 p-2 shadow-sm">
                                    <i class="fas fa-check-circle mr-1 text-success"></i> {{ $item->gejala->nama_gejala }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-center py-5 my-3" style="background-color: #f8fbff; border-radius: 15px;">
                        <p class="text-dark mb-1">Berdasarkan hasil analisis sistem, Anda memiliki risiko:</p>
                        <h1 class="text-danger font-weight-bold mb-0" style="font-size: 2.5rem;">{{ $hasil->jenis->nama_jenis }}</h1>
                        <div class="display-3 font-weight-bold text-primary mb-2">{{ number_format($hasil->total_cf * 100, 0) }}%</div>
                        <p class="text-muted small italic">Tingkat kepastian dihitung menggunakan metode Certainty Factor (CF)</p>
                    </div>

                    <div class="card shadow-sm mb-4" style="border-left: 5px solid #5dade2;">
                        <div class="card-body">
                            <h5 class="font-weight-bold text-primary"><i class="fas fa-lightbulb mr-2"></i>Solusi / Saran Medis:</h5>
                            <p class="text-gray-800 lead" style="font-size: 1.1rem; line-height: 1.6;">
                                {{ $hasil->jenis->solusi }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        <a href="{{ route('konsultasi.index') }}" class="btn btn-primary px-4 py-2 mx-2">
                            <i class="fas fa-redo mr-2"></i>Konsultasi Ulang
                        </a>
                        <a href="{{ route('riwayat') }}" class="btn btn-outline-secondary px-4 py-2 mx-2">
                            <i class="fas fa-history mr-2"></i>Lihat Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection