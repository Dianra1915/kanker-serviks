<!DOCTYPE html>
<html>
<head>
    <title>Laporan Riwayat Konsultasi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        
        /* Layout Kop Surat */
        .kop-table { width: 100%; border-bottom: 3px solid #5dade2; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-logo { width: 60px; text-align: left; vertical-align: middle; }
        .kop-text-container { text-align: left; vertical-align: middle; padding-left: 15px; }
        .logo-text { color: #5dade2; font-size: 20px; text-align: center; font-weight: bold; margin: 0; }
        .sub-logo { font-size: 12px; color: #7f8c8d; text-align: center; margin-top: 3px; }

        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th, table.data-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table.data-table th { background-color: #5dade2; color: white; font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                <img src="{{ public_path('img/logo.png') }}" style="height: 60px; width: auto;">
            </td>
            <td class="kop-text-container">
                <h2 class="logo-text">LAPORAN RIWAYAT KONSULTASI</h2>
                <div class="sub-logo">RSIA BUDI MEDIKA KOLAKA</div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Tanggal</th>
                <th>Nama Pasien</th>
                <th width="18%">No. Handphone</th>
                <th>Hasil Diagnosa</th>
                <th width="10%" class="text-center">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riwayat as $r)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($r->tgl_konsultasi)->format('d/m/Y H:i') }}</td>
                <td>{{ $r->user->username }}</td>
                <td>{{ $r->user->phone_number ?? '-' }}</td>
                <td>{{ $r->jenis->nama_jenis }}</td>
                <td class="text-center">{{ number_format($r->total_cf * 100, 0) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>