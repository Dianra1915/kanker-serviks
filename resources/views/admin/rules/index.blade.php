@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahRuleModal">
    <i class="fas fa-plus mr-2"></i> Tambah Aturan 
</button>

<div class="card shadow mb-4">
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="bg-primary text-white text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Jenis Kanker</th>
                    <th>Daftar Gejala Terkait</th>
                    <th width="8%">MB</th>
                    <th width="8%">MD</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $currentJenis = null; $no = 1; @endphp
                @foreach ($rules->groupBy('jenis_id') as $jenisId => $groupedRules)
                    @foreach ($groupedRules as $index => $r)
                    <tr>
                        {{-- Perbaikan: Nomor hanya muncul di baris pertama tiap jenis --}}
                        @if ($currentJenis !== $r->jenis->nama_jenis)
                            <td rowspan="{{ count($groupedRules) }}" class="text-center align-middle border-group-bottom">
                                {{ $no++ }}
                            </td>
                            <td rowspan="{{ count($groupedRules) }}" class="align-middle font-weight-bold text-dark border-group-bottom">
                                {{ $r->jenis->nama_jenis }}
                            </td>
                            @php $currentJenis = $r->jenis->nama_jenis; @endphp
                        @endif

                        {{-- Menggunakan $loop->last bawaan Laravel untuk mendeteksi baris terakhir di kelompok ini --}}
                        <td class="{{ $loop->last ? 'border-group-bottom' : '' }}">({{ $r->gejala->kode_gejala }}) {{ $r->gejala->nama_gejala }}</td>
                        <td class="text-center {{ $loop->last ? 'border-group-bottom' : '' }}"><span class="badge badge-success">{{ $r->mb }}</span></td>
                        <td class="text-center {{ $loop->last ? 'border-group-bottom' : '' }}"><span class="badge badge-secondary">{{ $r->md }}</span></td>
                        <td class="text-center {{ $loop->last ? 'border-group-bottom' : '' }}">
                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editRuleModal{{$r->id}}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('rules.destroy', $r->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="tambahRuleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('rules.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Atur Gejala Pakar</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">1. Pilih Jenis Kanker:</label>
                        <select name="jenis_id" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($jenisKankers as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <hr>
                    <label class="font-weight-bold text-primary">2. Pilih Gejala & Isi Nilai MB:</label>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th width="10%">Pilih</th>
                                    <th>Gejala</th>
                                    <th width="35%">Nilai MB</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gejalas as $g)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="gejala-checkbox" data-id="{{ $g->id }}" style="transform: scale(1.3);">
                                    </td>
                                    <td>[{{ $g->kode_gejala }}] {{ $g->nama_gejala }}</td>
                                    <td>
                                        <select name="mb[{{ $g->id }}]" id="mb-select-{{ $g->id }}" class="form-control form-control-sm mb-input" disabled>
                                            <option value="0">-- Pilih Nilai --</option>
                                            <option value="1">Pasti Ya (1)</option>
                                            <option value="0.8">Kemungkinan Besar (0.8)</option>
                                            <option value="0.6">Mungkin (0.6)</option>
                                            <option value="0.4">Kemungkinan Kecil (0.4)</option>
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block">Simpan Basis Pengetahuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- LOOPING ULANG UNTUK MEMBUAT MODAL EDIT PER ID RULE --}}
@foreach ($rules as $r)
<div class="modal fade" id="editRuleModal{{ $r->id }}" tabindex="-1" role="dialog" aria-labelledby="editRuleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editRuleModalLabel"><i class="fas fa-edit mr-2"></i>Edit Nilai Aturan (MB & MD)</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('rules.update', $r->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Jenis Kanker Serviks</label>
                        <input type="text" class="form-control" value="{{ $r->jenis->nama_jenis }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Gejala Terkait</label>
                        <textarea class="form-control" rows="2" disabled>{{ $r->gejala->nama_gejala }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mb_{{ $r->id }}" class="font-weight-bold text-primary">Nilai MB (Pakar)</label>
                                <select name="mb" id="mb_{{ $r->id }}" class="form-control" required>
                                    <option value="1.0" {{ $r->mb == 1.0 ? 'selected' : '' }}>Sangat Yakin (1.0)</option>
                                    <option value="0.8" {{ $r->mb == 0.8 ? 'selected' : '' }}>Yakin (0.8)</option>
                                    <option value="0.6" {{ $r->mb == 0.6 ? 'selected' : '' }}>Cukup Yakin (0.6)</option>
                                    <option value="0.4" {{ $r->mb == 0.4 ? 'selected' : '' }}>Kurang Yakin (0.4)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Nilai MD (Otomatis)</label>
                                <input type="text" class="form-control" value="{{ $r->md }}" disabled style="background-color: #f8f9fc;">
                                <small class="text-muted italic">*MD dihitung dari 1 - MB</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning font-weight-bold text-white">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    // Script untuk mengaktifkan/menonaktifkan dropdown MB berdasarkan checkbox
    document.querySelectorAll('.gejala-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            const select = document.getElementById('mb-select-' + id);
            if (this.checked) {
                select.disabled = false;
                select.required = true;
            } else {
                select.disabled = true;
                select.required = false;
                select.value = "0";
            }
        });
    });
</script>
{{-- TAMBAHAN CSS UNTUK MEMPERTEGAS GARIS ABSTRAKSI ROWSPAN --}}
<style>
    .table-bordered td.border-group-bottom {
        /* Memberikan efek bayangan lembut di bagian bawah baris */
        box-shadow: inset 0 -6px 6px -6px rgba(0, 0, 0, 0.3);
        border-bottom: 2px solid #e3e6f0 !important; 
    }
</style>
@endsection