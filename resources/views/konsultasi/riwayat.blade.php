@extends('layouts.admin')

@section('main-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Riwayat Konsultasi</h1>
        <a href="{{ route('riwayat.cetak_semua') }}" class="btn btn-danger shadow-sm">
            <i class="fas fa-file-pdf mr-2"></i> Cetak Laporan PDF
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead style="background-color: #5dade2; color: white;">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Tanggal & Waktu</th>
                            @if(Auth::user()->role == 'admin') <th>Nama Pasien</th> @endif
                            <th>Hasil Diagnosa</th>
                            <th>Kepastian</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $r)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->tgl_konsultasi)->format('d/m/Y H:i') }}</td>
                            @if(Auth::user()->role == 'admin') <td>{{ $r->user->username }}</td> @endif
                            <td><span class="font-weight-bold text-dark">{{ $r->jenis->nama_jenis }}</span></td>
                            <td class="text-center">
                                <div class="badge badge-info p-2" style="width: 70px;">
                                    {{ number_format($r->total_cf * 100, 0) }}%
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('konsultasi.hasil', $r->id) }}" class="btn btn-primary btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('konsultasi.cetak', $r->id) }}" class="btn btn-danger btn-sm" title="Cetak PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    @if(Auth::user()->role == 'admin')
                                    <form action="{{ route('konsultasi.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data riwayat ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-dark btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role == 'admin' ? '6' : '5' }}" class="text-center py-4 text-muted italic">
                                Belum ada riwayat konsultasi yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection