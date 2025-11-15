@extends('layouts.layout_admin')
@section('title', __('rbac::users.create_user'))
@section('content')
    <div class="container-fluid">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h2 class="fw-semibold mb-4">Create User</h2>
                    <form action="{{ route('store_user') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Enter name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone (WhatsApp)</label>
                            <input type="text" name="phone" id="phone" class="form-control"
                                placeholder="08xxx atau 628xxx">
                            <small class="text-muted">Format: 08xxx atau 628xxx (untuk notifikasi WhatsApp)</small>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="" disabled selected>Select role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="receive_all_notifications"
                                name="receive_all_notifications" value="1">
                            <label class="form-check-label" for="receive_all_notifications">
                                Terima semua notifikasi dokumen (khusus Admin)
                            </label>
                        </div>

                        <button type="submit" class="btn btn-admin">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
