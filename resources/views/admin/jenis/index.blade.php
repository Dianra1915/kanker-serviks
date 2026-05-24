@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>

{{-- Alert Validasi & Sukses --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahJenisModal">
    <i class="fas fa-plus"></i> Tambah Jenis
</button>

<div class="card shadow mb-4">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="bg-primary text-white">
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Kode</th>
                    <th>Nama Jenis</th>
                    <th>Solusi</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jenisKanker as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $j->kode_jenis }}</td>
                    <td>{{ $j->nama_jenis }}</td>
                    <td>{{ Str::limit($j->solusi, 100) }}</td>
                    <td>
                        {{-- Icon Edit --}}
                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editJenisModal{{$j->id}}">
                            <i class="fas fa-edit"></i>
                        </button>
                        
                        {{-- Icon Delete --}}
                        <form action="{{ route('jenis.destroy', $j->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <div class="modal fade" id="editJenisModal{{$j->id}}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('jenis.update', $j->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-header"><h5 class="modal-title">Edit Jenis Kanker</h5></div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Kode Jenis</label>
                                        <input type="text" class="form-control" value="{{ $j->kode_jenis }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Jenis</label>
                                        <input type="text" name="nama_jenis" class="form-control" value="{{ $j->nama_jenis }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Solusi</label>
                                        <textarea name="solusi" class="form-control" rows="4" required>{{ $j->solusi }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
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

<div class="modal fade" id="tambahJenisModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('jenis.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Jenis Kanker</h5></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Jenis</label>
                        <input type="text" name="kode_jenis" class="form-control" value="{{ $kodeOtomatis }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Jenis</label>
                        <input type="text" name="nama_jenis" class="form-control" placeholder="Contoh: Karsinoma sel skuamosa" required>
                    </div>
                    <div class="form-group">
                        <label>Solusi</label>
                        <textarea name="solusi" class="form-control" rows="4" placeholder="Saran penanganan medis..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection