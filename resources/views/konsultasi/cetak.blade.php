<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Diagnosa - {{ $hasil->user->username }}</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; font-size: 12px; }
        .container { padding: 25px; }
        
        /* Layout Kop Surat */
        .kop-table { width: 100%; border-bottom: 3px solid #4e73df; padding-bottom: 10px; margin-bottom: 25px; border-collapse: collapse; }
        .kop-logo { width: 70px; text-align: left; vertical-align: middle; }
        .kop-text-container { text-align: left; vertical-align: middle; padding-left: 10px; }
        .logo-text { color: #4e73df; font-size: 20px; font-weight: bold; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }
        .sub-logo { font-size: 11px; color: #7f8c8d; margin-top: 3px; font-weight: bold; }
        
        /* Informasi Pasien */
        .info-section { margin-bottom: 20px; width: 100%; border-collapse: collapse; }
        .info-section td { padding: 4px 0; vertical-align: top; }
        .label-blue { color: #4e73df; font-weight: bold; }
        
        /* Section Title */
        .section-title { font-size: 13px; color: #2c3e50; font-weight: bold; border-bottom: 1px solid #e3e6f0; padding-bottom: 5px; margin-top: 20px; margin-bottom: 10px; }
        
        /* Daftar Gejala */
        .gejala-list { padding-left: 15px; margin: 0; }
        .gejala-list li { margin-bottom: 5px; text-align: justify; color: #2c3e50; }
        
        /* Box Hasil Utama (Mengikuti Kalimat Baru) */
        .box-hasil { background-color: #f8f9fc; border: 1px solid #e3e6f0; border-left: 5px solid #e74c3c; padding: 15px 20px; margin: 20px 0; border-radius: 4px; text-align: center; }
        .hasil-text { font-size: 13px; color: #2c3e50; line-height: 1.8; margin: 0; }
        .highlight-cf { color: #e74c3c; font-weight: bold; font-size: 14px; }
        .highlight-jenis { color: #4e73df; font-weight: bold; font-size: 15px; text-transform: uppercase; }
        
        /* Solusi Paragraf Teks Murni */
        .solusi-content { text-align: justify; background: #fff; padding: 5px 0; color: #2c3e50; line-height: 1.7; }
        
        /* Disclaimer Box */
        .disclaimer-box { background-color: #fff3cd; border: 1px solid #ffeeba; border-left: 4px solid #f6c23e; padding: 10px 15px; margin-top: 25px; border-radius: 4px; color: #664d03; font-size: 10.5px; text-align: justify; line-height: 1.5; }
        
        /* Footer & Tanda Tangan */
        .footer { margin-top: 40px; width: 100%; border-collapse: collapse; }
        .footer td { vertical-align: top; }
        .signature-space { height: 60px; }
    </style>
</head>
<body>
    <div class="container">
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    <img src="{{ public_path('img/logo.png') }}" style="height: 55px; width: auto; max-width: 60px;">
                </td>
                <td class="kop-text-container">
                    <h2 class="logo-text">Laporan Skrining Sistem Pakar</h2>
                    <div class="sub-logo">RSIA BUDI MEDIKA KOLAKA</div>
                </td>
            </tr>
        </table>

        <table class="info-section">
            <tr>
                <td width="18%" class="label-blue">Nama Pasien</td>
                <td width="2%">:</td>
                <td width="30%"><strong>{{ $hasil->user->username }}</strong></td>
                <td width="18%" class="label-blue">Tanggal Skrining</td>
                <td width="2%">:</td>
                <td>{{ \Carbon\Carbon::parse($hasil->tgl_konsultasi)->locale('id')->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label-blue">No. Handphone</td>
                <td>:</td>
                <td>{{ $hasil->user->phone_number ?? '-' }}</td>
                <td class="label-blue">Waktu Sesi</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($hasil->tgl_konsultasi)->format('H:i') }} WITA</td>
            </tr>
        </table>

        <h4 class="section-title"><i class="fas fa-notes-medical"></i> Gejala Klinis Yang Dikonfirmasi:</h4>
        <ol class="gejala-list">
            @foreach($gejalaTerpilih as $item)
                <li>[{{ $item->gejala->kode_gejala }}] {{ $item->gejala->nama_gejala }}</li>
            @endforeach
        </ol>

        <div class="box-hasil">
            <p class="hasil-text">
                Berdasarkan Konsultasi yang dilakukan oleh pasien, <br>
                memiliki risiko terhadap kanker serviks sebesar 
                <span class="highlight-cf">{{ number_format($hasil->total_cf * 100, 0) }}%</span> 
                dengan kecenderungan pola gejala mengarah pada <br>
                <span class="highlight-jenis">{{ $hasil->jenis->nama_jenis }}</span>
            </p>
        </div>

        <h4 class="section-title">Solusi & Saran Tindakan Rekomendasi:</h4>
        <div class="solusi-content">
            {!! nl2br(e($hasil->jenis->solusi)) !!}
        </div>

        <div class="disclaimer-box">
            <strong>Pemberitahuan Penting:</strong> Hasil yang ditampilkan merupakan hasil deteksi risiko berdasarkan gejala dan faktor risiko yang dipilih pengguna menggunakan metode 
            Forward Chaining dan Certainty Factor. Hasil ini bukan merupakan diagnosis medis pasti dan tidak dapat menggantikan pemeriksaan langsung oleh dokter. Untuk memperoleh diagnosis yang akurat, 
            pasien tetap disarankan melakukan konsultasi dan pemeriksaan lebih lanjut dengan dokter spesialis Obstetri dan Ginekologi (Obgyn).
        </div>
    </div>
</body>
</html>