<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Pakar Kanker Serviks - RSIA Budi Medika Kolaka</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f8faff; color: #334155; scroll-behavior: smooth; }
        .navbar { padding: 1.2rem 2rem; background: white !important; }
        .hero-section { padding: 80px 0; background: linear-gradient(135deg, #ffffff 0%, #eef2ff 100%); }
        .hero-title { font-weight: 800; font-size: 3rem; color: #1e293b; line-height: 1.2; }
        .btn-medical { background-color: #2563eb; color: white; padding: 12px 30px; border-radius: 8px; font-weight: 600; transition: 0.3s; border:none; }
        .btn-medical:hover { background-color: #1d4ed8; color: white; transform: translateY(-2px); text-decoration: none; }
        
        /* Section Styling */
        section { padding: 80px 0; }
        .section-title { font-weight: 700; margin-bottom: 50px; text-align: center; color: #1e293b; }
        
        .card-custom { background: white; border: none; border-radius: 15px; padding: 30px; transition: 0.3s; height: 100%; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .card-custom:hover { transform: translateY(-10px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        
        .icon-box { width: 50px; height: 50px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; border-radius: 10px; margin-bottom: 20px; font-size: 20px; }
        .contact-info i { color: #2563eb; width: 30px; }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('img/logo.png') }}" alt="Logo RSIA" style="height: 45px; width: auto;" class="mr-2">
                <span class="font-weight-bold text-primary" style="font-size: 1.2rem; line-height: 1;">
                    RSIA Budi Medika <br> <small class="text-secondary">Kolaka</small>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto align-items-center text-uppercase" style="font-size: 13px; font-weight: 700; letter-spacing: 1px;">
                    <li class="nav-item"><a class="nav-link mx-2" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link mx-2" href="#edukasi">Edukasi</a></li>
                    <li class="nav-item"><a class="nav-link mx-2" href="#layanan">Layanan</a></li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item"><a class="btn btn-medical ml-lg-3 py-2" href="{{ url('/home') }}">Dashboard</a></li>
                        @else
                            <li class="nav-item"><a class="nav-link mx-2" href="{{ route('login') }}">Masuk</a></li>
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="btn btn-outline-primary ml-lg-2 py-2" href="{{ route('register') }}">Daftar</a></li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title mb-4">Kesehatan Anda,<br><span class="text-primary">Prioritas Utama Kami</span></h1>
                    <p class="lead text-muted mb-5">RSIA Budi Medika menghadirkan kemudahan deteksi dini kanker serviks bagi wanita di Kolaka menggunakan teknologi Sistem Pakar.</p>
                    <a href="{{ route('konsultasi.index') }}" class="btn btn-medical btn-lg shadow">Mulai Konsultasi</a>
                </div>
                <div class="col-lg-6 text-right d-none d-lg-block">
                    <img src="{{ asset('img/lokasi_RSIA.png') }}" alt="lokasi_RSIA" class="img-fluid rounded shadow-lg" style="width: 90%;">
                </div>
            </div>
        </div>
    </header>

    <section id="edukasi" class="bg-white">
        <div class="container">
            <h2 class="section-title">Edukasi Kanker Serviks</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card-custom border-top border-primary">
                        <div class="icon-box"><i class="fas fa-virus"></i></div>
                        <h4>Apa Itu Kanker Serviks?</h4>
                        <p class="text-muted small">Kanker yang tumbuh pada sel-sel di leher rahim, umumnya disebabkan oleh Human Papillomavirus (HPV).</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card-custom border-top border-primary">
                        <div class="icon-box"><i class="fas fa-exclamation-triangle"></i></div>
                        <h4>Pentingnya Deteksi Dini</h4>
                        <p class="text-muted small">Kanker serviks seringkali tidak menunjukkan gejala pada stadium awal, sehingga pemeriksaan dini sangat krusial.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card-custom border-top border-primary">
                        <div class="icon-box"><i class="fas fa-shield-alt"></i></div>
                        <h4>Langkah Pencegahan</h4>
                        <p class="text-muted small">Vaksinasi HPV, pola hidup sehat, dan melakukan screening rutin melalui sistem pakar ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="layanan">
        <div class="container">
            <h2 class="section-title">Layanan Sistem Pakar</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card-custom">
                        <div class="icon-box"><i class="fas fa-user-md"></i></div>
                        <h4>Konsultasi Gejala</h4>
                        <p class="text-muted">Jawab pertanyaan mengenai kondisi fisik Anda secara jujur untuk mendapatkan diagnosa risiko awal.</p>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card-custom">
                        <div class="icon-box"><i class="fas fa-calculator"></i></div>
                        <h4>Metode Certainty Factor</h4>
                        <p class="text-muted">Hasil diagnosa dihitung berdasarkan tingkat keyakinan pakar kesehatan dan gejala yang Anda rasakan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-5">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h2 class="font-weight-bold mb-3 text-dark">Hubungi Kami</h2>
                    <p class="text-muted" style="font-size: 1.1rem; line-height: 1.6;">
                        Kami siap melayani Anda di Rumah Sakit Ibu & Anak Budi Medika Kolaka. Silakan kunjungi atau hubungi kami melalui kanal berikut:
                    </p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="card-body text-center py-4 px-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm" style="width: 70px; height: 70px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: white;">
                                <i class="fab fa-instagram" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark">Instagram</h5>
                            <p class="text-muted mb-0">@rsiabudimedikakolaka</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="card-body text-center py-4 px-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm bg-success text-white" style="width: 70px; height: 70px;">
                                <i class="fab fa-whatsapp" style="font-size: 2.2rem;"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark">WhatsApp</h5>
                            <p class="text-muted mb-0">+62 812-1212-5100</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="card-body text-center py-4 px-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm bg-danger text-white" style="width: 70px; height: 70px;">
                                <i class="fas fa-map-marker-alt" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark">Alamat</h5>
                            <p class="text-muted mb-0" style="font-size: 0.95rem;">Jl. Pramuka No.113, Lamokato<br>Kabupaten Kolaka, Sultra</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <footer class="py-4 bg-light border-top text-center text-muted small">
        <div class="container">
            &copy; {{ date('Y') }} RSIA Budi Medika Kolaka. 
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>