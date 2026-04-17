@extends("layouts.layout_admin")

@section("title", "Profile")

@section('content')
    <div class="container-fluid">
        <!-- Main Profile Dashboard -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header Identity Card -->
                <div class="border-0 shadow-lg card rounded-4 overflow-hidden mb-4">
                    <div class="p-0 card-body">
                        <div class="row g-0">
                            <!-- Left: Profile Gradient & Photo -->
                            <div class="col-md-4 bg-primary-gradient d-flex flex-column align-items-center justify-content-center p-5 text-white">
                                <div class="position-relative mb-4">
                                    <div class="rounded-circle shadow-lg bg-white p-1" style="width: 160px; height: 160px;">
                                        @php
                                            $photoUrl = $user->photo 
                                                ? asset('storage/' . $user->photo) 
                                                : asset('assets/images/profile/user-1.jpg');
                                        @endphp
                                        <img src="{{ $photoUrl }}" alt="Profile" class="w-100 h-100 rounded-circle object-fit-cover">
                                    </div>
                                    <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle p-2 shadow-sm" style="width: 20px; height: 20px;"></span>
                                </div>
                                <h3 class="fw-bolder mb-1 text-center text-white">{{ $user->name }}</h3>
                                <p class="opacity-75 mb-3 text-center small text-uppercase fw-bold letter-spacing-1">{{ $roles }}</p>
                                
                                <a href="{{ route('profile.edit') }}" class="btn btn-white-glass rounded-pill px-4 fw-bold">
                                    <i class="ti ti-edit me-2"></i> Pengaturan Akun
                                </a>
                            </div>

                            <!-- Right: Detailed Information -->
                            <div class="col-md-8 bg-white p-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-success-subtle p-2 rounded-3 me-3">
                                        <i class="ti ti-id-badge-2 fs-7 text-success"></i>
                                    </div>
                                    <h4 class="mb-0 fw-bolder">Informasi Profil Institusi</h4>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 border border-light bg-light-subtle h-100 transition-hover">
                                            <p class="text-muted small mb-1 fw-bold">NAMA LENGKAP</p>
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-user me-2 text-success"></i>
                                                <span class="fw-bold text-dark">{{ $user->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 border border-light bg-light-subtle h-100 transition-hover overflow-hidden">
                                            <p class="text-muted small mb-1 fw-bold">EMAIL KOORDINATOR</p>
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-mail me-2 text-primary"></i>
                                                <span class="fw-bold text-dark text-truncate" title="{{ $user->email }}">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 border border-light bg-light-subtle h-100 transition-hover">
                                            <p class="text-muted small mb-1 fw-bold">HAK AKSES / PERAN</p>
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-shield-check me-2 text-warning"></i>
                                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3">{{ $roles }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 border border-light bg-light-subtle h-100 transition-hover">
                                            <p class="text-muted small mb-1 fw-bold">KEAMANAN AKUN</p>
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-key me-2 text-danger"></i>
                                                <span class="text-dark">Terproteksi Sesuai Standar</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if(Auth::check() && Auth::user()->isRole('Administrator'))
                                    <div class="col-md-12">
                                        <div class="p-4 rounded-4 border border-danger-subtle bg-danger-subtle h-100 mt-2">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <p class="text-danger small mb-1 fw-bold">DEVELOPER TOOLS</p>
                                                    <div class="d-flex align-items-center">
                                                        <i class="ti ti-code me-2 fs-5 text-danger"></i>
                                                        <span class="text-dark fw-bold">Mode Developer & Database Manager</span>
                                                    </div>
                                                    <small class="text-muted d-block mt-1">Mengaktifkan mode ini akan memunculkan menu RDBMS di sidebar kiri.</small>
                                                </div>
                                                <form action="{{ route('dev-mode.toggle') }}" method="POST" class="m-0">
                                                    @csrf
                                                    <div class="form-check form-switch m-0" style="transform: scale(1.5); margin-right: 20px !important;">
                                                        <input class="form-check-input cursor-pointer shadow-sm border-danger" type="checkbox" role="switch" name="dev_mode" onchange="this.form.submit()" {{ Auth::user()->is_dev_mode ? 'checked' : '' }}>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="mt-5 pt-4 border-top">
                                    <div class="alert alert-info border-0 shadow-none bg-info-subtle rounded-4 d-flex align-items-center">
                                        <i class="ti ti-info-circle fs-6 text-primary me-3"></i>
                                        <small class="text-primary-emphasis fw-medium">Profil Anda digunakan untuk akuntabilitas jejak audit (Audit Trail) pada seluruh dokumen yang diterbitkan dalam sistem ini.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Info / Activity Log Quick View -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="row text-center">
                            <div class="col-4 border-end">
                                <h2 class="fw-bolder text-dark mb-0">AKTIF</h2>
                                <p class="text-muted mb-0 small uppercase fw-bold">Account Status</p>
                            </div>
                            <div class="col-4 border-end">
                                <h2 class="fw-bolder text-dark mb-0">SECURE</h2>
                                <p class="text-muted mb-0 small uppercase fw-bold">Security Level</p>
                            </div>
                            <div class="col-4">
                                <h2 class="fw-bolder text-dark mb-0">CLOUD</h2>
                                <p class="text-muted mb-0 small uppercase fw-bold">Storage Type</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-primary-gradient {
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
        }
        .btn-white-glass {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-white-glass:hover {
            background: white;
            color: #0d9488;
            transform: translateY(-2px);
        }
        .letter-spacing-1 { letter-spacing: 1px; }
        .bg-success-subtle { background-color: #ecfdf5 !important; }
        .bg-warning-subtle { background-color: #fefce8 !important; }
        .bg-info-subtle { background-color: #f0f9ff !important; }
        .transition-hover { transition: all 0.2s ease; cursor: default; }
        .transition-hover:hover { 
            background-color: #fff !important; 
            border-color: #14b8a6 !important;
            box-shadow: 0 10px 20px -10px rgba(0,0,0,0.1);
        }
    </style>
@endsection
