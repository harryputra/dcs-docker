@extends('layouts.layout_admin')

@section('title', 'Edit Profile')

@section('content')
    <div class="container-fluid">
        <!-- Profile Header Section -->
        <div class="mb-5 row">
            <div class="col-12">
                <div class="border-0 shadow-sm card rounded-4 overflow-hidden position-relative">
                    <div class="p-5 card-body z-1 position-relative bg-success-subtle">
                        <div class="row align-items-center">
                            <div class="col-md-auto text-center mb-4 mb-md-0">
                                <!-- Avatar Upload with Preview -->
                                <div class="position-relative d-inline-block">
                                    <div class="avatar-container position-relative overflow-hidden rounded-circle shadow-lg bg-white" style="width: 150px; height: 150px; border: 5px solid #fff;">
                                        @php
                                            $photoUrl = auth()->user()->photo 
                                                ? asset('storage/' . auth()->user()->photo) 
                                                : asset('assets/images/profile/user-1.jpg');
                                        @endphp
                                        <img id="avatar-preview" src="{{ $photoUrl }}" alt="Profile Photo" class="w-100 h-100 object-fit-cover">
                                        
                                        <!-- Hover Overlay -->
                                        <div class="avatar-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0 transition-all cursor-pointer" onclick="document.getElementById('photo-input').click()">
                                            <i class="ti ti-camera text-white fs-7"></i>
                                        </div>
                                    </div>
                                    <button type="button" class="position-absolute bottom-0 end-0 p-2 border-0 shadow-sm btn btn-success rounded-circle" onclick="document.getElementById('photo-input').click()">
                                        <i class="ti ti-edit fs-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md">
                                <h1 class="mb-1 display-6 fw-bolder text-dark">{{ auth()->user()->name }}</h1>
                                <p class="mb-3 text-muted fs-4">Atur informasi profil dan keamanan akun institusi Anda.</p>
                                <div class="gap-2 d-flex flex-wrap">
                                    <span class="px-3 py-2 badge bg-white text-dark rounded-pill shadow-sm border border-success-subtle">
                                        <i class="ti ti-mail-opened me-2 text-primary"></i> {{ auth()->user()->email }}
                                    </span>
                                    <span class="px-3 py-2 badge bg-white text-dark rounded-pill shadow-sm border border-success-subtle">
                                        <i class="ti ti-shield-lock me-2 text-success"></i> Account Verified
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->updatePassword->any() || $errors->updateProfileInformation->any())
            <div class="top-0 p-4 alert alert-danger alert-dismissible fade show shadow-lg position-fixed start-50 translate-middle-x mt-4 z-3" style="max-width: 500px;" role="alert">
                <div class="d-flex align-items-start">
                    <i class="ti ti-alert-circle fs-6 me-3"></i>
                    <div>
                        <strong class="d-block mb-1">Terjadi Kesalahan!</strong>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->updatePassword->all() as $error) <li>{{ $error }}</li> @endforeach
                            @foreach ($errors->updateProfileInformation->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Sidebar Navigation (Optional, can be integrated or simplified) -->
            <div class="col-lg-7">
                <div class="border-0 shadow-sm card rounded-4 h-100">
                    <div class="px-4 py-3 card-header bg-white border-bottom border-light d-flex align-items-center">
                        <i class="ti ti-user-edit fs-5 text-success me-2"></i>
                        <h5 class="mb-0 fw-bolder">Informasi Khusus</h5>
                    </div>
                    <div class="p-4 card-body">
                        <form action="{{ route('user-profile-information.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <!-- Hidden File Input -->
                            <input type="file" id="photo-input" name="photo" class="d-none" accept="image/*" onchange="previewAvatar(this)">

                            <div class="mb-4 row">
                                <div class="col-md-12">
                                    <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="bg-light border-0 input-group-text"><i class="ti ti-user text-muted"></i></span>
                                        <input type="text" id="name" name="name" class="form-control form-control-lg bg-light border-0"
                                            value="{{ auth()->user()->name }}" required>
                                    </div>
                                    <small class="text-muted mt-1 d-block">Gunakan nama resmi institusi untuk akuntabilitas dokumen.</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <div class="col-md-12">
                                    <label for="email" class="form-label fw-bold">Alamat Email Institusi</label>
                                    <div class="input-group">
                                        <span class="bg-light border-0 input-group-text"><i class="ti ti-mail text-muted"></i></span>
                                        <input type="email" id="email" name="email" class="form-control form-control-lg bg-light border-0"
                                            value="{{ auth()->user()->email }}" required>
                                    </div>
                                    <small class="text-muted mt-1 d-block">Alamat email akan digunakan untuk notifikasi sistem.</small>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="px-5 py-3 btn btn-success rounded-3 fw-bold">
                                    <i class="ti ti-device-floppy me-2"></i> Simpan Perubahan Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="border-0 shadow-sm card rounded-4 h-100">
                    <div class="px-4 py-3 card-header bg-white border-bottom border-light d-flex align-items-center">
                        <i class="ti ti-shield-lock fs-5 text-warning me-2"></i>
                        <h5 class="mb-0 fw-bolder">Keamanan Akun</h5>
                    </div>
                    <div class="p-4 card-body">
                        <form action="{{ route('user-password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="current_password" class="form-label fw-bold">Kata Sandi Saat Ini</label>
                                <input type="password" id="current_password" name="current_password" class="form-control form-control-lg bg-warning-subtle border-0"
                                    required placeholder="••••••••">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold">Kata Sandi Baru</label>
                                <input type="password" id="password" name="password" class="form-control form-control-lg bg-light border-0" 
                                    required placeholder="Paling sedikit 8 karakter">
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Kata Sandi</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control form-control-lg bg-light border-0" required placeholder="Ulangi kata sandi baru">
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="px-5 py-3 btn btn-warning rounded-3 fw-bold w-100">
                                    <i class="ti ti-key me-2"></i> Perbarui Kata Sandi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Success Notification -->
    @if (session('status') === 'profile-information-updated' || session('status') === 'password-updated')
        <div class="bottom-0 p-4 position-fixed end-0 z-3">
            <div class="text-white border-0 shadow-lg toast show align-items-center bg-success rounded-3" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex p-3">
                    <i class="ti ti-circle-check fs-6 me-3"></i>
                    <div class="toast-body p-0 fw-bold">
                        @if (session('status') === 'profile-information-updated')
                            Berhasil! Profil instititusi Anda telah diperbarui.
                        @else
                            Berhasil! Pengaturan keamanan telah diperbarui.
                        @endif
                    </div>
                    <button type="button" class="m-auto btn-close btn-close-white me-2" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <style>
        .avatar-container:hover .avatar-overlay {
            opacity: 1 !important;
        }
        .form-control-lg {
            font-size: 0.95rem;
        }
        .bg-success-subtle { background-color: #ecfdf5 !important; }
        .bg-warning-subtle { background-color: #fefce8 !important; }
        .transition-all { transition: all 0.3s ease; }
        .cursor-pointer { cursor: pointer; }
    </style>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
