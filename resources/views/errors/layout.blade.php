<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/logoupt.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />

    <!-- Custom Styles -->
    <style>
        .radial-gradient {
            margin: 0;
            padding: 0;
            background-image: url('{{ asset('assets/images/backgrounds/bglogin.png') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .error-code {
            font-size: 120px;
            font-weight: 900;
            color: #5D87FF;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            line-height: 1;
        }

        .error-icon {
            font-size: 80px;
            color: #5D87FF;
            margin: 20px 0;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body>
    <div class="page-wrapper" id="main-wrapper">
        <div class="overflow-hidden position-relative radial-gradient d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xxl-4">
                        <div class="mb-0 shadow-lg card">
                            <div class="p-5 text-center card-body">
                                <!-- Logo -->
                                <a href="/" class="mb-3 d-block">
                                    <img src="{{ asset('assets/images/logos/logoupt.png') }}" width="100"
                                        alt="Logo UPT Puskesmas Garuda">
                                </a>

                                @yield('content')

                                <!-- Action Buttons -->
                                <div class="gap-2 mt-4 d-flex justify-content-center">
                                    <a href="javascript:history.back()" class="btn btn-outline-primary">
                                        <i class="ti ti-arrow-left"></i> Kembali
                                    </a>
                                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                                        <i class="ti ti-home"></i> Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    </div>
</body>

</html>
