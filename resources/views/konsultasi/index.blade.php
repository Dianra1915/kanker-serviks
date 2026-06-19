@extends('layouts.admin')

@section('main-content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        
        {{-- KONDISI JIKA PASIEN TIDAK TERDIAGNOSA (SEHAT/GEJALA < 5) --}}
        @if(session('tidak_terdeteksi'))
            <div class="alert alert-success shadow-sm mb-4 py-4 border-left-success">
                <h5 class="text-center font-weight-bold mb-0 text-dark" style="line-height: 1.6;">
                    <i class="fas fa-check-circle text-success mr-2 mb-2" style="font-size: 2rem;"></i><br>
                    {{ session('tidak_terdeteksi') }}
                </h5>
            </div>
            <div class="text-center mb-4">
                <a href="{{ route('konsultasi.index') }}" class="btn btn-primary"><i class="fas fa-redo mr-2"></i> Lakukan Skrining Ulang</a>
            </div>
        @else
        
        {{-- TAMPILAN PERTANYAAN 1 per 1 --}}
        <div class="card shadow mb-4 border-bottom-primary">
            <div class="card-header py-3 bg-primary d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-white">Pertanyaan ke-{{ $pertanyaanKe }} (Skrining Aktif)</h6>
                <a href="{{ route('konsultasi.index', ['reset' => 1]) }}" class="btn btn-sm btn-danger shadow-sm"><i class="fas fa-redo"></i> Ulangi</a>
            </div>
            <div class="card-body text-center py-5">
                <h5 class="mb-4 text-gray-600">Apakah Anda mengalami kondisi berikut:</h5>
                
                <div class="alert alert-info py-4 mb-5 shadow-sm">
                    <h3 class="mb-0 text-primary font-weight-bold">"{{ $gejala->nama_gejala }} ?"</h3>
                </div>
                
                <form action="{{ route('konsultasi.jawab') }}" method="POST" class="d-flex justify-content-center">
                    @csrf
                    <input type="hidden" name="gejala_id" value="{{ $gejala->id }}">
                    
                    {{-- TOMBOL YA (Kirim nilai 1) --}}
                    <button type="submit" name="jawaban" value="1" class="btn btn-success btn-lg mx-2 px-5 py-3 font-weight-bold shadow">
                        <i class="fas fa-check-circle mr-2"></i> YA
                    </button>
                    
                    {{-- TOMBOL TIDAK (Kirim nilai 0) --}}
                    <button type="submit" name="jawaban" value="0" class="btn btn-danger btn-lg mx-2 px-5 py-3 font-weight-bold shadow">
                        <i class="fas fa-times-circle mr-2"></i> TIDAK
                    </button>
                </form>
            </div>
            <div class="card-footer bg-white">
                <small class="text-muted mb-1 d-block"><i class="fas fa-microchip mr-1"></i> Mesin Inferensi Sedang Mengevaluasi Jawaban...</small>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 100%;"></div>
                </div>
            </div>
        </div>
        @endif
        
    </div>
</div>
@endsection