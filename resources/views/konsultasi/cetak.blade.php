<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Diagnosa - {{ $hasil->user->username }}</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; line-height: 1.5; color: #333; margin: 0; padding: 0; }
        .container { padding: 20px; }
        
        /* Layout Kop Surat */
        .kop-table { width: 100%; border-bottom: 3px solid #5dade2; padding-bottom: 10px; margin-bottom: 30px; }
        .kop-logo { width: 70px; text-align: left; vertical-align: middle; }
        .kop-text-container { text-align: left; vertical-align: middle; padding-left: 15px; }
        .logo-text { color: #5dade2; font-size: 24px; text-align: center; font-weight: bold; margin: 0; }
        .sub-logo { font-size: 14px; color: #7f8c8d; text-align: center; margin-top: 5px; }
        
        .info-section { margin-bottom: 25px; width: 100%; }
        .label-blue { color: #2e86c1; font-weight: bold; }
        
        .box-hasil { background-color: #ebf5fb; border: 1px solid #5dade2; padding: 25px; text-align: center; border-radius: 10px; margin: 30px 0; }
        .diagnosa-title { font-size: 18px; margin-bottom: 10px; color: #2c3e50; }
        .diagnosa-nama { font-size: 24px; color: #e74c3c; font-weight: bold; margin: 0; }
        .persentase { font-size: 30px; color: #2e86c1; font-weight: bold; margin: 10px 0; }
        
        .section-title { border-bottom: 1px solid #5dade2; color: #2e86c1; padding-bottom: 5px; margin-top: 20px; }
        .gejala-list { margin-left: 20px; padding: 0; }
        .gejala-list li { margin-bottom: 5px; }
        
        .footer { margin-top: 50px; text-align: right; font-size: 12px; }
        .watermark { position: fixed; bottom: 0; left: 0; font-size: 10px; color: #bdc3c7; }
    </style>
</head>
<body>
    <div class="container">
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    <img src="{{ public_path('img/logo.png') }}" style="height: 70px; width: auto;">
                </td>
                <td class="kop-text-container">
                    <h1 class="logo-text">RSIA BUDI MEDIKA KOLAKA</h1>
                    <div class="sub-logo">Sistem Pakar Deteksi Dini Kanker Serviks (Metode CF)</div>
                </td>
            </tr>
        </table>

        <table class="info-section">
            <tr>
                <td width="20%" class="label-blue">Nama Pasien</td>
                <td width="3%">:</td>
                <td>{{ $hasil->user->username }}</td>
                <td width="20%" class="label-blue">ID Konsultasi</td>
                <td width="3%">:</td>
                <td>#RSIA-{{ str_pad($hasil->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td class="label-blue">Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($hasil->tgl_konsultasi)->format('d F Y') }}</td>
                <td class="label-blue">No. Handphone</td>
                <td>:</td>
                <td>{{ $hasil->user->phone_number ?? '-' }}</td>
            </tr>
        </table>

        <h4 class="section-title">Gejala yang Dirasakan:</h4>
        <ul class="gejala-list">
            @foreach($gejalaTerpilih as $item)
                <li>{{ $item->gejala->nama_gejala }}</li>
            @endforeach
        </ul>

        <div class="box-hasil">
            <div class="diagnosa-title">Hasil Analisis Risiko Kecenderungan Mengarah Pada:</div>
            <div class="diagnosa-nama">{{ strtoupper($hasil->jenis->nama_jenis) }}</div>
            <div class="persentase">{{ number_format($hasil->total_cf * 100, 0) }}%</div>
            <div style="font-size: 12px; color: #7f8c8d;">Persentase berdasarkan data medis yang diberikan</div>
        </div>

        <h4 class="section-title">Solusi / Saran Medis:</h4>
        <div style="background: #fff; padding: 5px 10px;">
            <ul style="padding-left: 15px; margin: 0; line-height: 1.6; text-align: justify;">
                @foreach(explode(PHP_EOL, $hasil->jenis->solusi) as $poin)
                    @if(trim($poin) != null)
                        <li style="margin-bottom: 5px;">{{ trim($poin) }}.</li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div class="footer">
            <p>Kolaka, {{ now()->translatedFormat('d F Y') }}</p>
            <br><br>
            <p><strong>Sistem Pakar RSIA Budi Medika</strong></p>
        </div>

        <div class="watermark">
            *Laporan ini dihasilkan secara otomatis oleh sistem dan dapat digunakan sebagai acuan awal sebelum pemeriksaan laboratorium.
        </div>
    </div>
</body>
</html>