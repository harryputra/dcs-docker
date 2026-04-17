@extends('layouts.layout_admin')
@section('title', __('rbac::roles.role_details'))
@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 border-start border-4 border-primary">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bolder mb-1 text-dark">Detail Role: <span class="text-primary">{{ $role->name }}</span></h3>
                        <p class="text-muted small mb-0">Informasi lengkap spesifikasi dan hak otorisasi peran.</p>
                    </div>
                </div>

                <div class="mb-4 d-flex gap-2 align-items-center bg-light p-3 rounded-3">
                    <a href="{{ route('list_roles') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm bg-white">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('edit_role', ['role' => $role->id]) }}" class="btn btn-warning text-dark fw-semibold d-flex align-items-center gap-2 shadow-sm">
                        <i class="ti ti-edit"></i> Edit Role
                    </a>

                    @can(Itstructure\LaRbac\Models\Permission::DELETE_MEMBER_FLAG, $role->id)
                        <button type="button" class="btn btn-danger d-flex align-items-center gap-2 shadow-sm ms-auto" data-bs-toggle="modal" data-bs-target="#deleteRoleModal">
                            <i class="ti ti-trash"></i> Hapus Role
                        </button>

                        <!-- Modal Hapus Role -->
                        <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="deleteRoleModalLabel">
                                            <i class="ti ti-alert-triangle me-2"></i>Konfirmasi Hapus
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-0">Apakah Anda yakin ingin menghapus role <strong>{{ $role->name }}</strong> secara permanen?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="ti ti-x"></i> Batal</button>
                                        <form action="{{ route('delete_role') }}" method="post" style="display: inline-block;">
                                            @csrf
                                            <input type="hidden" value="{{ $role->id }}" name="items[]">
                                            <button type="submit" class="btn btn-danger"><i class="ti ti-trash"></i> Ya, Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>

                <div class="bg-white border rounded-4 overflow-hidden">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th style="width: 250px;" class="fw-bold border-bottom-0 py-3 ps-4">Atribut</th>
                                <th class="fw-bold border-bottom-0 py-3">Nilai Data</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <tr>
                                <td class="text-muted fw-semibold ps-4">Nama Role</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 text-dark fw-bold">
                                        <i class="ti ti-shield-check text-primary fs-5"></i> {{ $role->name }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold ps-4">Kode Sistem (Slug)</td>
                                <td><span class="badge bg-light text-muted border px-3 py-2 fs-6">{{ $role->slug }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold ps-4">Deskripsi</td>
                                <td class="text-muted">{{ $role->description ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold ps-4 py-4">Otoritas Akses (Permissions)</td>
                                <td class="py-4">
                                    <div class="d-flex flex-wrap gap-2">
                                        @forelse ($role->permissions as $permission)
                                            <a href="{{ route('show_permission', ['id' => $permission->id]) }}" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none px-3 py-2 transition-hover fs-6">
                                                <i class="ti ti-key fs-7 me-1"></i> {{ $permission->name }}
                                            </a>
                                        @empty
                                            <span class="text-muted border px-3 py-2 rounded bg-light border-dashed"><i class="ti ti-ban"></i> Belum ada hak akses yang ditugaskan</span>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
