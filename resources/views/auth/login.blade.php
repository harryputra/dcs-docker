<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - UPT Puskesmas Garuda</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/logoupt.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    <style>
        :root {
            --medical-primary: #14b8a6; /* Teal dari logo */
            --medical-secondary: #f43f5e; /* Pink-cross dari logo */
            --medical-bg-soft: #f0fdfa;
        }

    <style>
        :root {
            --medical-primary: #14b8a6;
            --medical-secondary: #f43f5e;
            --medical-bg-soft: #f0fdfa;
        }

        .login-wrapper {
            background-color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Seamless Background Pattern */
        .bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('assets/images/backgrounds/medical_hex_bg.png') }}');
            background-size: cover;
            background-position: center bottom;
            background-repeat: no-repeat;
            z-index: 0;
            opacity: 0.4;
            animation: bg-drift 120s linear infinite;
        }

        /* Multi-layer Gradient Mask (Fixed Clipping) */
        .blur-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 10%, #fff 20%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0.4) 100%);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            z-index: 1;
        }

        /* Floating Organic Particles (Lightweight & Life-like) */
        .floating-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 2;
        }

        .particle {
            position: absolute;
            background: var(--medical-primary);
            opacity: 0.1;
            border-radius: 50%;
            animation: float-around 20s infinite ease-in-out;
        }

        .particle-1 { width: 40px; height: 40px; top: 10%; left: 10%; animation-duration: 25s; }
        .particle-2 { width: 60px; height: 60px; top: 60%; right: 10%; animation-duration: 35s; animation-delay: -5s; }
        .particle-3 { width: 30px; height: 30px; bottom: 15%; left: 20%; background: var(--medical-secondary); animation-duration: 22s; }
        
        @keyframes float-around {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -50px) rotate(10deg); }
            66% { transform: translate(-20px, 40px) rotate(-10deg); }
        }

        @keyframes bg-drift {
            0% { background-position: center 100%; }
            50% { background-position: center 95%; }
            100% { background-position: center 100%; }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 28px;
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.08), 
                        0 0 20px 0 rgba(20, 184, 166, 0.05);
            width: 100%;
            max-width: 440px;
            padding: 4rem 3rem;
            z-index: 10;
            opacity: 0;
            transform: translateY(20px) scale(0.98);
            animation: card-reveal 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes card-reveal {
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .logo-section img {
            animation: logo-entrance 1s ease-out;
        }

        @keyframes logo-entrance {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        .app-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.04em;
        }

        .login-card :nth-child(n) {
            transition: all 0.2s ease;
        }

        .form-control {
            border: 1.5px solid #eef2f6;
            background: rgba(248, 250, 252, 0.5);
            padding: 0.85rem 1.25rem;
            border-radius: 14px;
        }

        .form-control:focus {
            background: #fff;
            border-color: var(--medical-primary);
            box-shadow: 0 0 0 5px rgba(20, 184, 166, 0.08);
        }

        .btn-medical {
            background: linear-gradient(135deg, var(--medical-primary) 0%, #0d9488 100%);
            border: none;
            padding: 1rem;
            border-radius: 14px;
            box-shadow: 0 12px 20px -5px rgba(20, 184, 166, 0.4);
        }

        .footer-text {
            color: #94a3b8;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <!-- Background Layers -->
        <div class="bg-pattern"></div>
        <div class="blur-overlay"></div>
        
        <!-- Floating Elements (Living Identity) -->
        <div class="floating-particles">
            <div class="particle particle-1"></div>
            <div class="particle particle-2"></div>
            <div class="particle particle-3"></div>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <div class="text-center logo-section mb-4">
                <img src="{{ asset('assets/images/logos/logoupt.png') }}" width="100" alt="Logo UPT Puskesmas Garuda">
            </div>
            
            <div class="text-center mb-5">
                <h1 class="app-title mb-1">UPT Puskesmas Garuda</h1>
                <p class="app-subtitle fw-semibold">Sistem Informasi Pengendali Dokumen</p>
            </div>

            <form action="/login" method="post">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Email Institusi</label>
                    <input type="email" value="{{ old('email') }}" class="form-control" name="email" placeholder="contoh@puskesmas.go.id" required>
                    @error('email')
                        <small class="text-danger mt-2 d-block fw-bold font-monospace">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Kata Sandi</label>
                    <input type="password" class="form-control" name="password" placeholder="••••••••" required>
                    @error('password')
                        <small class="text-danger mt-2 d-block fw-bold font-monospace">{{ $message }}</small>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" checked>
                        <label class="form-check-label text-muted" for="remember" style="font-size: 0.85rem;">
                            Ingat sesi saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-medical w-100 mb-3 text-white fw-bold">
                    Otentikasi & Masuk
                </button>
            </form>

            <div class="footer-text">
                &copy; {{ date('Y') }} Garuda DCS &bull; Secure Healthcare Network
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
