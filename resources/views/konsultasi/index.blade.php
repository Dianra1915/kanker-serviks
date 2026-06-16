@extends('layouts.admin')

@section('main-content')
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Pilih Gejala yang Anda Rasakan</h6>
    </div>
    <div class="card-body">
        {{-- TAMBAHAN: TAMPILKAN PESAN ERROR DARI VALIDASI MINIMAL 3 GEJALA --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fas fa-exclamation-triangle mr-3 mt-1" style="font-size: 1.2rem;"></i>
                    <span class="text-dark font-weight-bold" style="line-height: 1.5;">
                        {{ session('error') }}
                    </span>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('konsultasi.proses') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">Pilih</th>
                            <th>Gejala</th>
                            <th width="30%">Tingkat Keyakinan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gejalas as $g)
                        <tr>
                            <td>
                                <input type="checkbox" class="gejala-check" data-id="{{ $g->id }}" style="transform: scale(1.5)">
                            </td>
                            <td>{{ $g->nama_gejala }}</td>
                            <td>
                                <select name="jawaban[{{ $g->id }}]" id="select-{{ $g->id }}" class="form-control" disabled>
                                    <option value="0">-- Pilih Keyakinan --</option>
                                    @foreach($skala as $nilai => $label)
                                        <option value="{{ $nilai }}">{{ $label }} ({{ $nilai }})</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg mt-3 shadow">Proses Konsultasi Sekarang</button>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.gejala-check').forEach(check => {
    check.addEventListener('change', function() {
        const select = document.getElementById('select-' + this.dataset.id);
        select.disabled = !this.checked;
        if(!this.checked) select.value = "0";
    });
});
</script>
@endsection