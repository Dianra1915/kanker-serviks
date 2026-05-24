@extends('layouts.admin')

@section('main-content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('Dashboard') }}</h1>

    @if (session('success'))
    <div class="alert alert-success border-left-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    {{-- ========================================================================= --}}
    {{-- HALAMAN DASHBOARD KHUSUS ROLE: ADMIN --}}
    {{-- ========================================================================= --}}
    @if(Auth::user()->role == 'admin')
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pasien Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $widget['users'] ?? '0' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Basis Gejala</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Gejala::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-notes-medical fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Riwayat Konsultasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\HasilKonsultasi::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-history fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Selamat Datang di Panel Admin Pakar</h6>
                </div>
                <div class="card-body">
                    <p class="text-dark">Halo <strong>{{ Auth::user()->username }}</strong>, Anda masuk sebagai Administrator sistem. Melalui panel kontrol ini Anda dapat mengelola data medis operasional berupa:</p>
                    <ul>
                        <li>Mengelola data indikator Gejala awal kanker serviks.</li>
                        <li>Mengatur pembobotan Rule (Nilai MB & MD) kombinasi Forward Chaining dan Certainty Factor.</li>
                        <li>Memantau dan mengunduh laporan rekapitulasi seluruh riwayat skrining kesehatan pasien.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- HALAMAN DASHBOARD KHUSUS ROLE: PASIEN --}}
    {{-- ========================================================================= --}}
    @else
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4 border-left-info">
                <div class="card-body py-4">
                    <h4 class="font-weight-bold text-info">Selamat Datang, {{ Auth::user()->username }}!</h4>
                    <p class="text-gray-800 lead mt-2" style="font-size: 1.1rem;">
                        Sistem Pakar ini dirancang sebagai sarana Skrining Awal Kesehatan Risiko Kanker Serviks di RSIA Budi Medika Kolaka.
                    </p>
                    <hr>
                    <p class="small text-muted mb-0 italic">
                        *Catatan: Sistem ini berfungsi sebagai skrining awal berbasis gejala dan aturan pakar medis, bukan sebagai pengganti diagnosis laboratorium.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100 text-center py-4">
                <div class="card-body">
                    <i class="fas fa-stethoscope fa-4x text-primary mb-3"></i>
                    <h5 class="font-weight-bold text-dark">Mulai Skrining Mandiri</h5>
                    <p class="text-muted small px-3">Pilih gejala medis yang Anda rasakan dan hitung tingkat kepastian risiko kesehatan Anda sekarang.</p>
                    <a href="{{ route('konsultasi.index') }}" class="btn btn-primary px-4 btn-md shadow-sm mt-2">
                        <i class="fas fa-play mr-2"></i>Konsultasi Sekarang
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100 text-center py-4">
                <div class="card-body">
                    <i class="fas fa-file-medical-alt fa-4x text-success mb-3"></i>
                    <h5 class="font-weight-bold text-dark">Riwayat Pemeriksaan</h5>
                    <p class="text-muted small px-3">Lihat kembali hasil analisis deteksi dini Anda sebelumnya atau cetak ulang laporan rekapitulasi PDF.</p>
                    <a href="{{ route('riwayat') }}" class="btn btn-success px-4 btn-md shadow-sm mt-2">
                        <i class="fas fa-history mr-2"></i>Buka Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection