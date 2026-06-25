@extends('layouts.admin')

@section('main-content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        
        {{-- KONDISI JIKA PASIEN TIDAK TERDIAGNOSA (SEHAT/GEJALA < 2) --}}
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
                <div>
                    @if($pertanyaanKe > 1)
                        <a href="{{ route('konsultasi.kembali') }}" class="btn btn-sm btn-light font-weight-bold text-primary shadow-sm mr-2" style="border-radius: 6px;">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    @endif
                    
                    <a href="{{ route('konsultasi.index', ['reset' => 1]) }}" class="btn btn-sm btn-danger font-weight-bold shadow-sm" style="border-radius: 6px;">
                        <i class="fas fa-redo-alt mr-1"></i> Ulangi
                    </a>
                </div>
            </div>
            <div class="card-body text-center py-5">
                <h5 class="mb-4 text-gray-600">Apakah mengalami kondisi berikut:</h5>
                
                <div class="alert alert-info py-4 mb-5 shadow-sm">
                    <h3 class="mb-0 text-primary font-weight-bold">"{{ $gejala->nama_gejala }}"</h3>
                </div>
                
                <form action="{{ route('konsultasi.jawab') }}" method="POST" id="formKonsultasi">
                    @csrf
                    <input type="hidden" name="gejala_id" value="{{ $gejala->id }}">
                    
                    <input type="hidden" name="jawaban" id="inputJawaban" value="">

                    <div id="opsiAwal" class="d-flex justify-content-center mt-3">
                        
                        <button type="button" class="btn btn-success btn-lg mx-2 px-5 py-3 font-weight-bold shadow" onclick="tampilComboBox()">
                            <i class="fas fa-check-circle mr-2"></i> YA
                        </button>
                        
                        <button type="button" class="btn btn-danger btn-lg mx-2 px-5 py-3 font-weight-bold shadow" onclick="submitTidak()">
                            <i class="fas fa-times-circle mr-2"></i> TIDAK
                        </button>
                    </div>

                    <div id="opsiLanjutan" class="mt-4" style="display: none;">
                        <h6 class="text-gray-800 mb-3 font-weight-bold">Seberapa yakin Anda dengan gejala ini?</h6>
                        
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <select id="comboKeyakinan" class="form-control form-control-lg mb-4 shadow-sm border-left-primary" style="cursor: pointer;">
                                    <option value="" disabled selected>-- Pilih Tingkat Keyakinan --</option>
                                    <option value="1.0">Sangat Yakin</option>
                                    <option value="0.8">Yakin</option>
                                    <option value="0.6">Cukup Yakin</option>
                                    <option value="0.4">Sedikit Yakin</option>
                                    <option value="0.2">Tidak Yakin</option>
                                </select>
                                
                                <div class="d-flex justify-content-between px-2">
                                    <button type="button" class="btn btn-outline-secondary px-3 font-weight-bold shadow-sm" onclick="batalYa()" style="border-radius: 8px;">
                                        <i class="fas fa-times mr-2"></i> Batal Pilih YA
                                    </button>
                                    
                                    <button type="button" class="btn btn-primary px-5 font-weight-bold shadow-sm" onclick="submitYa()" style="border-radius: 8px;">
                                        Lanjut <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
<script>
                    // Jika klik YA, sembunyikan tombol awal, munculkan Combo Box
                    function tampilComboBox() {
                        document.getElementById('opsiAwal').style.display = 'none';
                        document.getElementById('opsiLanjutan').style.display = 'block';
                    }

                    // Jika klik Batal, sembunyikan Combo Box, kembalikan tombol YA/TIDAK
                    function batalYa() {
                        document.getElementById('opsiLanjutan').style.display = 'none';
                        document.getElementById('opsiAwal').style.display = 'flex';
                        document.getElementById('comboKeyakinan').value = ""; // Reset pilihan
                    }

                    // Jika klik TIDAK, set nilai 0.0 dan langsung kirim data
                    function submitTidak() {
                        document.getElementById('inputJawaban').value = "0.0";
                        document.getElementById('formKonsultasi').submit();
                    }

                    // Jika klik Simpan & Lanjut, cek Combo Box dan kirim data
                    function submitYa() {
                        var nilaiCombo = document.getElementById('comboKeyakinan').value;
                        
                        // Validasi jika user belum memilih tingkat keyakinan
                        if (nilaiCombo === "") {
                            alert("Mohon pilih tingkat keyakinan Anda terlebih dahulu!");
                            return;
                        }

                        // Set nilai sesuai pilihan dan kirim data
                        document.getElementById('inputJawaban').value = nilaiCombo;
                        document.getElementById('formKonsultasi').submit();
                    }
                </script>

@endsection