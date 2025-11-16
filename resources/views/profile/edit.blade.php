@extends('layouts.layout_admin')

@section('title', 'Edit Profile')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Edit Profile</h2>

        @if ($errors->updatePassword->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->updatePassword->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->updateProfileInformation->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->updateProfileInformation->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mt-4 row">

            <!-- Card Change Credentials -->
            <div class="col-md-6">
                <div class="border-2 card border-top border-success">

                    <div class="card-header">
                        <h5>Ubah Profile</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user-profile-information.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    value="{{ auth()->user()->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="{{ auth()->user()->email }}" required>
                            </div>

                            <div class="mt-3 text-center">
                                <button type="submit" class="btn btn-admin">
                                    <i class="ti ti-save"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Card Change Password -->
            <div class="col-md-6">
                <div class="border-2 card border-top border-warning">
                    <div class="card-header">
                        <h5>Ubah Kata Sandi</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user-password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" required>
                            </div>

                            <div class="mt-3 text-center">
                                <button type="submit" class="btn btn-danger">
                                    <i class="ti ti-key"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification untuk Success -->
    @if (session('status') === 'profile-information-updated' || session('status') === 'password-updated')
        <div class="top-0 p-3 position-fixed end-0" style="z-index: 11">
            <div class="text-white border-0 toast show align-items-center bg-success" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fa fa-check-circle me-2"></i>
                        @if (session('status') === 'profile-information-updated')
                            Profile berhasil diperbarui
                        @else
                            Kata sandi berhasil diperbarui
                        @endif
                    </div>
                    <button type="button" class="m-auto btn-close btn-close-white me-2" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    var toastEl = document.querySelector('.toast');
                    if (toastEl) {
                        var toast = new bootstrap.Toast(toastEl, {
                            autohide: true,
                            delay: 3000
                        });
                        toast.show();
                    }
                }, 100);
            });
        </script>
    @endif

@endsection
