@extends('layouts.layout_admin')
@section('title', __('rbac::users.user_details'))
@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 border-start border-4 border-info">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bolder mb-1 text-dark">Detail Pengguna: <span class="text-info">{{ $user->memberName }}</span></h3>
                        <p class="text-muted small mb-0">Informasi spesifik tentang profil dan role pengguna ini.</p>
                    </div>
                </div>

                <div class="mb-4 d-flex gap-2 align-items-center bg-light p-3 rounded-3">
                    <a href="{{ route('list_users') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm bg-white">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('edit_user', ['id' => $user->memberKey]) }}" class="btn btn-warning text-dark fw-semibold d-flex align-items-center gap-2 shadow-sm">
                        <i class="ti ti-edit"></i> Assign Role (Edit)
                    </a>

                    @can(Itstructure\LaRbac\Models\Permission::DELETE_MEMBER_FLAG, $user->memberKey)
                        @if(auth()->user()->memberKey != $user->memberKey)
                            <button type="button" class="btn btn-danger d-flex align-items-center gap-2 shadow-sm ms-auto" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                <i class="ti ti-trash"></i> Hapus Pengguna
                            </button>

                            <!-- Modal Hapus User -->
                            <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="text-white modal-header bg-danger">
                                            <h5 class="modal-title" id="deleteUserModalLabel">
                                                <i class="ti ti-alert-triangle me-2"></i>Konfirmasi Hapus
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-0">Apakah Anda yakin ingin menghapus akun pengguna <strong>{{ $user->memberName }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="ti ti-x"></i> Batal</button>
                                            <form action="{{ route('delete_user') }}" method="post" style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" value="{{ $user->memberKey }}" name="items[]">
                                                <button type="submit" class="btn btn-danger"><i class="ti ti-trash"></i> Ya, Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                                <td class="text-muted fw-semibold ps-4">Nama Pengguna</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 text-dark fw-bold">
                                        <i class="ti ti-user fs-5 text-info"></i> {{ $user->memberName }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold ps-4 py-4">Roles Ditugaskan</td>
                                <td class="py-4">
                                    <div class="d-flex flex-wrap gap-2">
                                        @forelse ($user->roles as $role)
                                            <a href="{{ route('show_role', ['id' => $role->id]) }}" class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle text-decoration-none px-3 py-2 transition-hover fs-6">
                                                <i class="ti ti-shield-check fs-6 me-1"></i> {{ $role->name }}
                                            </a>
                                        @empty
                                            <span class="text-muted border px-3 py-2 rounded bg-light border-dashed"><i class="ti ti-ban"></i> Belum ada role ditugaskan</span>
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
