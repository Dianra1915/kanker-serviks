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
                            <td rowspan="{{ count($groupedRules) }}" class="text-center align-middle">
                                {{ $no++ }}
                            </td>
                            <td rowspan="{{ count($groupedRules) }}" class="align-middle font-weight-bold text-dark">
                                {{ $r->jenis->nama_jenis }}
                            </td>
                            @php $currentJenis = $r->jenis->nama_jenis; @endphp
                        @endif

                        <td>({{ $r->gejala->kode_gejala }}) {{ $r->gejala->nama_gejala }}</td>
                        <td class="text-center"><span class="badge badge-success">{{ $r->mb }}</span></td>
                        <td class="text-center"><span class="badge badge-secondary">{{ $r->md }}</span></td>
                        <td class="text-center">
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
                                            <option value="1.0">Pasti Ya (1.0)</option>
                                            <option value="0.6">Kemungkinan Besar (0.6)</option>
                                            <option value="0.4">Mungkin (0.4)</option>
                                            <option value="0.2">Kecil Kemungkinan (0.2)</option>
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
@endsection