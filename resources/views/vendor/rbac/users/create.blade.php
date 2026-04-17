@extends('layouts.layout_admin')
@section('title', __('rbac::users.create_user'))
@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 border-start border-4 border-info">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                        <h3 class="fw-bolder mb-1 text-dark d-flex align-items-center gap-2">
                            <i class="ti ti-user-plus text-info"></i> Buat Pengguna Baru
                        </h3>
                        <p class="text-muted small mb-0">Daftarkan akun pengguna baru ke dalam sistem aplikasi.</p>
                    </div>
                    <a href="{{ route('list_users') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm bg-white">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('store_user') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold text-dark mb-2">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group input-group-flat shadow-sm">
                                <span class="input-group-text bg-white text-muted border-end-0 pe-2"><i class="ti ti-user fs-5"></i></span>
                                <input type="text" name="name" id="name" class="form-control border-start-0 ps-1"
                                    placeholder="Masukkan nama lengkap" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold text-dark mb-2">Alamat Email <span class="text-danger">*</span></label>
                            <div class="input-group input-group-flat shadow-sm">
                                <span class="input-group-text bg-white text-muted border-end-0 pe-2"><i class="ti ti-mail fs-5"></i></span>
                                <input type="email" name="email" id="email" class="form-control border-start-0 ps-1"
                                    placeholder="contoh@puskesmas.go.id" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-bold text-dark mb-2">Password Akses <span class="text-danger">*</span></label>
                            <div class="input-group input-group-flat shadow-sm">
                                <span class="input-group-text bg-white text-muted border-end-0 pe-2"><i class="ti ti-lock fs-5"></i></span>
                                <input type="password" name="password" id="password" class="form-control border-start-0 ps-1"
                                    placeholder="Masukkan password yang kuat" required>
                            </div>
                        </div>

                    </div>
                    
                    <div class="card shadow-none border border-1 rounded-3 mb-4">
                        <div class="card-header bg-light border-bottom">
                            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="ti ti-shield-check text-info fs-5"></i> Role Akses Default
                            </h6>
                        </div>
                        <div class="card-body bg-white p-4">
                            <div class="col-12">
                                <label for="role" class="form-label fw-bold text-dark mb-2">Pilih Role Utama <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="form-select shadow-sm" required>
                                    <option value="" disabled selected>-- Silakan Pilih Role --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('list_users') }}" class="btn btn-light text-dark shadow-sm px-4">Batal</a>
                        <button class="btn btn-info text-white fw-bold d-flex align-items-center gap-2 shadow-sm px-4" type="submit">
                            <i class="ti ti-device-floppy"></i> Daftarkan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
