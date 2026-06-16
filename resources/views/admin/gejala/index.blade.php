@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">Data Gejala</h1>
{{-- TAMPILKAN PESAN ERROR VALIDASI --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach ($errors->all() as $error)
            <i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}
        @endforeach
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- TAMPILKAN PESAN SUKSES --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahModal">
    <i class="fas fa-plus mr-2"></i> Tambah Gejala
</button>

<div class="card shadow mb-4">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="bg-primary text-white">
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode</th>
                    <th>Nama Gejala</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gejalas as $g)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $g->kode_gejala }}</td>
                    <td>{{ $g->nama_gejala }}</td>
                    <td>
                        {{-- Tombol Edit dengan Icon --}}
                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{$g->id}}" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>

                        {{-- Tombol Delete dengan Icon --}}
                        <form action="{{ route('gejala.destroy', $g->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus gejala ini?')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                {{-- MODAL EDIT (Harus di dalam foreach agar mendapatkan ID yang sesuai) --}}
                <div class="modal fade" id="editModal{{$g->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Gejala</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('gejala.update', $g->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    
                                    <div class="form-group">
                                        <label>Nama Gejala</label>
                                        <input type="text" name="nama_gejala" class="form-control" value="{{ $g->nama_gejala }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Gejala Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('gejala.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Gejala</label>
                        <input type="text" name="kode_gejala" class="form-control" value="{{ $kodeOtomatis }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Gejala</label>
                        <input type="text" name="nama_gejala" class="form-control" placeholder="Masukkan nama gejala" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JAVASCRIPT UNTUK OTOMATIS MEMBUKA MODAL KEMBALI --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Cek jika ada session sukses dari server Laravel
        @if (session('success'))
            // Buka kembali modal tambah secara otomatis
            $('#tambahModal').modal('show');
        @endif

        // 2. Otomatis arahkan kursor ke input 'Nama Gejala' saat modal terbuka
        $('#tambahModal').on('shown.bs.modal', function () {
            $('input[name="nama_gejala"]').focus();
        });
    });
</script>
@endsection