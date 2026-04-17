<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DCS Puskesmas - Intelligent Document Control System</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
            --secondary: #14b8a6;
            --secondary-dark: #0f766e;
            --accent: #f59e0b;
            --bg-pattern-color: rgba(14, 165, 233, 0.03);
            --font-display: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-body);
            color: #334155;
            background-color: #f8fafc;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
            letter-spacing: -0.02em;
        }

        /* Abstract Medical Background */
        .bg-medical {
            position: relative;
            background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
            z-index: 1;
        }
        .bg-medical::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(var(--bg-pattern-color) 2px, transparent 2px);
            background-size: 32px 32px;
            z-index: -1;
            opacity: 0.8;
        }
        
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.4;
            border-radius: 50%;
            animation: float 10s infinite ease-in-out alternate;
        }
        .blob-1 { top: -10%; left: -10%; width: 500px; height: 500px; background: #38bdf8; }
        .blob-2 { bottom: 10%; right: -5%; width: 600px; height: 600px; background: #2dd4bf; animation-delay: -5s; }
        .blob-3 { top: 40%; left: 30%; width: 400px; height: 400px; background: #818cf8; opacity: 0.2; animation-delay: -2s; }

        @keyframes float {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(30px) scale(1.05); }
        }

        /* Navbar Modern */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        .navbar-glass.scrolled {
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
        }
        .nav-logo-text { font-family: var(--font-display); font-weight: 800; font-size: 1.5rem; letter-spacing: -0.5px; }

        /* Premium Buttons */
        .btn-premium {
            border-radius: 100px;
            padding: 12px 32px;
            font-weight: 600;
            font-family: var(--font-display);
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-premium-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white !important;
            border: none;
            box-shadow: 0 10px 20px -10px rgba(14, 165, 233, 0.5);
        }
        .btn-premium-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -10px rgba(14, 165, 233, 0.6);
        }
        .btn-premium-outline {
            background: white;
            color: var(--primary) !important;
            border: 2px solid var(--primary-dark);
        }
        .btn-premium-outline:hover {
            background: var(--primary-dark);
            color: white !important;
            transform: translateY(-3px);
        }

        /* Hero Section */
        .hero-section {
            padding: 180px 0 120px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero-badge {
            background: rgba(14, 165, 233, 0.1);
            color: var(--primary-dark);
            padding: 8px 16px;
            border-radius: 100px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            margin-bottom: 24px;
            border: 1px solid rgba(14, 165, 233, 0.2);
        }
        .hero-title {
            font-size: clamp(3rem, 5vw, 4.5rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 24px;
            color: #0f172a;
        }
        .text-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-subtitle {
            font-size: 1.25rem;
            color: #64748b;
            margin-bottom: 40px;
            line-height: 1.7;
            max-width: 600px;
        }

        /* Metrics Float Cards */
        .metric-float-container {
            position: relative;
            height: 100%;
            min-height: 500px;
        }
        .float-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 20px 40px -20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            position: absolute;
            transition: all 0.3s ease;
            animation: hover-bob 6s infinite ease-in-out alternate;
        }
        .fc-1 { top: 10%; right: 10%; width: 280px; z-index: 3; }
        .fc-2 { top: 40%; left: 0%; width: 260px; animation-delay: -2s; z-index: 2; }
        .fc-3 { bottom: 10%; right: 20%; width: 300px; animation-delay: -4s; z-index: 1; border-left: 4px solid var(--secondary); }
        
        .float-card:hover { transform: translateY(-5px) scale(1.02); z-index: 10; }
        
        @keyframes hover-bob {
            0% { transform: translateY(0); }
            100% { transform: translateY(-15px); }
        }

        .icon-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Features Bento Grid */
        .bento-section { padding: 100px 0; background: #ffffff; position: relative; }
        .bento-card {
            background: #f8fafc;
            border-radius: 32px;
            padding: 40px;
            height: 100%;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid #f1f5f9;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }
        .bento-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 100%);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .bento-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -20px rgba(0, 0, 0, 0.05);
            background: white;
            border-color: #e2e8f0;
        }
        .bento-card:hover::before { opacity: 1; }
        .bento-icon {
            font-size: 2.5rem;
            margin-bottom: 24px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }
        .bento-title { font-weight: 800; font-size: 1.5rem; margin-bottom: 16px; color: #0f172a; }
        .bento-desc { color: #64748b; line-height: 1.6; }

        /* Footer */
        .footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 60px 0 30px;
        }
        .footer-logo {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Pulse Animation */
        .pulse-ring {
            position: absolute;
            left: 50%; top: 50%;
            transform: translate(-50%, -50%);
            width: 100%; height: 100%;
            border-radius: 50%;
            border: 2px solid var(--primary);
            animation: pulsing 2s infinite cubic-bezier(0.215, 0.61, 0.355, 1);
            opacity: 0;
        }
        @keyframes pulsing {
            0% { transform: translate(-50%, -50%) scale(0.8); opacity: 0.8; }
            100% { transform: translate(-50%, -50%) scale(1.5); opacity: 0; }
        }
    </style>
</head>
<body class="bg-medical">

    <!-- Blobs Background -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <!-- Smart Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-glass py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <img src="{{ asset('assets/images/logos/logoupt.png') }}" width="45" alt="Logo Puskesmas Garuda" class="d-inline-block align-text-top drop-shadow-sm">
                <span class="nav-logo-text text-dark">DCS <span class="text-primary">Puskesmas Garuda</span></span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="ti ti-menu-2 fs-2"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link fw-semibold text-dark px-3" href="#fitur">Fitur Premium</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-dark px-3" href="#keamanan">Keamanan</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-dark px-3" href="#fitur">Arsip Akreditasi</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-premium btn-premium-primary">
                            <span>Buka Dashboard</span>
                            <i class="ti ti-arrow-right"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-premium btn-premium-primary">
                            <i class="ti ti-login"></i>
                            <span>Portal Akses</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="hero-badge">
                        <i class="ti ti-sparkles me-2"></i> Sistem Akreditasi Generasi Baru
                    </div>
                    <h1 class="hero-title">
                        Kontrol Cerdas <br> <span class="text-gradient">Dokumen Mutu</span> Puskesmas.
                    </h1>
                    <p class="hero-subtitle">
                        Sentralisasi ekosistem arsip rekaman, standarisasi regulasi internal, dan pantau jejak audit secara real-time dengan infrastruktur Document Control System berarsitektur mutakhir.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-premium btn-premium-primary px-5 py-3 fs-5">
                                Masuk Workspace
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-premium btn-premium-primary px-5 py-3 fs-5">
                                LOGIN
                            </a>
                        @endauth
                        <a href="#fitur" class="btn-premium btn-premium-outline px-4 py-3 fs-5 bg-white">
                            Pelajari Ekosistem
                        </a>
                    </div>
                    
                    <div class="mt-5 pt-4 border-top d-flex align-items-center gap-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ti ti-lock-check text-success fs-4"></i>
                            <span class="fw-semibold text-dark">ISO 27001 Ready</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="ti ti-server-cog text-primary fs-4"></i>
                            <span class="fw-semibold text-dark">On-Premise Secure</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200">
                    <div class="metric-float-container">
                        <!-- Card 1: Active Documents -->
                        <div class="float-card fc-1">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-circle bg-primary-subtle text-primary">
                                    <i class="ti ti-folders"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted small fw-bold">Dokumen Aktif</p>
                                    <h4 class="mb-0 fw-bold text-dark">{{ number_format($dokumenAktif) }}+ <span class="fs-6 text-muted fw-normal">Arsip</span></h4>
                                </div>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: 85%"></div>
                            </div>
                        </div>

                        <!-- Card 2: Validasi -->
                        <div class="float-card fc-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-circle bg-success-subtle text-success position-relative">
                                    <div class="pulse-ring"></div>
                                    <i class="ti ti-shield-check"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted small fw-bold">Status Sistem</p>
                                    <h5 class="mb-0 fw-bold text-success">Ter-enkripsi</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Audit Tail -->
                        <div class="float-card fc-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-dark"><i class="ti ti-timeline text-secondary me-2"></i>Audit Matrix</span>
                                <span class="badge bg-light text-dark">{{ number_format($aktivitas) }} Event</span>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                    <div class="bg-primary rounded-circle" style="width: 8px; height: 8px;"></div>
                                    <small class="fw-medium text-dark flex-grow-1">Persetujuan Kepala PKM</small>
                                    <small class="text-muted text-nowrap">Baru saja</small>
                                </div>
                                <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                    <div class="bg-secondary rounded-circle" style="width: 8px; height: 8px;"></div>
                                    <small class="fw-medium text-dark flex-grow-1">Revisi SOP KIA</small>
                                    <small class="text-muted text-nowrap">2 jam lalu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bento Grid Features -->
    <section id="fitur" class="bento-section">
        <div class="container border-top pt-5">
            <div class="text-center mb-5 pb-3 w-75 mx-auto" data-aos="fade-up">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold mb-3">Arsitektur Modular</span>
                <h2 class="display-5 fw-900 text-dark mb-3">Ekosistem Tata Kelola Komprehensif</h2>
                <p class="text-muted fs-5">Dibangun secara spesifik untuk mengatasi birokrasi arsip lembaga, percepatan akreditasi, dan standarisasi operasional pelayanan kesehatan primer.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="bento-card">
                        <i class="ti ti-git-merge bento-icon"></i>
                        <h3 class="bento-title">Workflow Sirkulasi & Revisi Dinamis</h3>
                        <p class="bento-desc fs-5">Tinggalkan proses manual. Setiap unggahan otomatis melalui matriks eskalasi berjenjang (Uploader &rarr; Pengendali Dokumen &rarr; Bagian Mutu &rarr; Kepala Puskesmas) dengan pelacakan jejak riwayat revisi 100% terekam.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="bento-card">
                        <i class="ti ti-shield-lock bento-icon"></i>
                        <h3 class="bento-title">Keamanan Akses Berjenjang</h3>
                        <p class="bento-desc border-top pt-3 mt-2">Infrastruktur Role-Based Access Control (RBAC) cerdas yang membatasi visibilitas dan wewenang dokumen secara ketat berdasarkan divisi dan tingkat hierarki otoritas fungsional.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="bento-card bg-primary text-white" style="border-color: var(--primary);">
                        <i class="ti ti-timeline text-white fs-1 mb-4 d-block"></i>
                        <h3 class="bento-title text-white">Log Audit Intelligence</h3>
                        <p class="text-white-50 border-top border-white-50 pt-3 mt-2">Seluruh aktivitas persetujuan dan perubahan terdokumentasi presisi beserta catatan waktunya untuk kelengkapan administrasi.</p>
                    </div>
                </div>
                <div class="col-md-8" data-aos="fade-up" data-aos-delay="400">
                    <div class="bento-card">
                        <i class="ti ti-hierarchy-2 bento-icon"></i>
                        <h3 class="bento-title">Klasifikasi & Penomoran Elektronik Pintar</h3>
                        <p class="bento-desc fs-5">Tidak ada lagi nomor SK ganda. Sistem mengurutkan hierarki klasifikasi kategori dokumen regulasi dan secara otomatis mengkalkulasi pengindeksan huruf kode dokumen sesuai pedoman Tata Naskah Dinas Kesehatan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4 align-items-center border-bottom border-secondary pb-4 mb-4" style="border-color: rgba(255,255,255,0.1) !important;">
                <div class="col-md-4">
                    <div class="footer-logo">
                        <img src="{{ asset('assets/images/logos/logoupt.png') }}" width="36" alt="Logo Puskesmas Garuda" class="drop-shadow-sm">
                        DCS Puskesmas Garuda
                    </div>
                    <p class="small text-muted mb-0">Sistem Kendali Dokumen Elektronik Akreditasi.</p>
                </div>
                <div class="col-md-8 text-md-end">
                    <div class="d-flex flex-wrap justify-content-md-end gap-3 gap-md-4">
                        <a href="#" class="text-muted text-decoration-none hover-white small transition-all">Panduan Penggunaan</a>
                        <a href="#" class="text-muted text-decoration-none hover-white small transition-all">Pedoman Tata Naskah</a>
                        <a href="#" class="text-muted text-decoration-none hover-white small transition-all">Hubungi IT Support</a>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <small>&copy; {{ date('Y') }} Puskesmas Garuda. Hak Cipta Dilindungi Undang-Undang.</small>
                <small class="mt-2 mt-md-0 d-flex align-items-center gap-2">
                    <span class="text-muted">Desain & Pengembangan oleh</span>
                    <strong class="text-white">TRIN | POLMAN BANDUNG</strong>
                </small>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize Animations
        AOS.init({
            once: true,
            offset: 50,
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-glass');
            if (window.scrollY > 20) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
