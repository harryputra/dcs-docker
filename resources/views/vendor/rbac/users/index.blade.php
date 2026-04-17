@extends('layouts.layout_admin')
@section('title', __('rbac::users.users'))
@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 border-start border-4 border-info">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bolder mb-1 text-dark">Manajemen Akses Pengguna</h3>
                        <p class="text-muted small mb-0">Kelola akun pengguna, email, dan penugasan role sistem.</p>
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center bg-light p-3 rounded-3">
                    <div>
                        <button id="deleteSelectedBtn" class="btn btn-danger d-flex align-items-center gap-2 shadow-sm" style="display: none;">
                            <i class="ti ti-trash"></i> Hapus Dipilih (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                    <a href="{{ route('create_users') }}" class="btn btn-info d-flex align-items-center gap-2 shadow-sm px-4">
                        <i class="ti ti-user-plus"></i> Tambah Pengguna Baru
                    </a>
                </div>

                @if ($errors->has('items'))
                    <div class="alert alert-danger bg-danger-subtle text-danger border-0 d-flex align-items-center" role="alert">
                        <i class="ti ti-alert-circle me-2"></i> {{ $errors->first('items') }}
                    </div>
                @endif

                <form id="delete-form" action="{{ route('delete_user') }}" method="POST">
                    @csrf
                    <div class="table-responsive bg-white rounded-3 border">
                        <table id="myTable" class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 40px;" class="text-center border-bottom-0">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th style="width: 50px;" class="fw-bolder text-uppercase border-bottom-0" style="font-size: 11px;">No</th>
                                    <th class="fw-bolder text-uppercase border-bottom-0" style="font-size: 11px; width: 250px;">Nama Pengguna</th>
                                    <th class="fw-bolder text-uppercase border-bottom-0" style="font-size: 11px; width: 200px;">Email</th>
                                    <th class="fw-bolder text-uppercase border-bottom-0" style="font-size: 11px;">Roles Ditugaskan</th>
                                    <th style="width: 140px;" class="fw-bolder text-uppercase border-bottom-0" style="font-size: 11px;">Waktu Register</th>
                                    <th style="width: 110px;" class="text-center fw-bolder text-uppercase border-bottom-0" style="font-size: 11px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @foreach ($dataProvider->get() as $key => $user)
                                    <tr>
                                        <td class="text-center">
                                            @if (Gate::allows(\Itstructure\LaRbac\Models\Permission::DELETE_MEMBER_FLAG, $user->memberKey) &&
                                                    auth()->user()->memberKey != $user->memberKey)
                                                <input type="checkbox" name="items[]" value="{{ $user->memberKey }}"
                                                    class="form-check-input row-checkbox cursor-pointer">
                                            @endif
                                        </td>
                                        <td class="text-muted fw-semibold small">{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                    <i class="ti ti-user fs-5"></i>
                                                </div>
                                                <a href="{{ route('show_user', ['id' => $user->memberKey]) }}" class="text-dark fw-bold text-decoration-none" style="font-size: 14px;">
                                                    {{ $user->memberName }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 13px;">
                                                <i class="ti ti-mail"></i> {{ $user->email ?: '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $roles = collect($user->roles);
                                                $displayCount = 3;
                                                $displayed = $roles->take($displayCount);
                                                $hiddenCount = $roles->count() - $displayCount;
                                            @endphp
                                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                                @forelse ($displayed as $role)
                                                    <a href="{{ route('show_role', ['id' => $role->id]) }}" class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle text-decoration-none px-2 py-1 transition-hover fw-normal" style="font-size: 11px;">
                                                        <i class="ti ti-shield-check fs-9 me-1"></i> {{ $role->name }}
                                                    </a>
                                                @empty
                                                    <span class="text-muted small border px-2 py-1 rounded bg-light border-dashed" style="font-size: 11px;"><i class="ti ti-ban"></i> Belum ada role</span>
                                                @endforelse
                                                
                                                @if($hiddenCount > 0)
                                                    <span class="badge bg-light text-dark border px-2 py-1 fw-medium cursor-pointer" style="font-size: 11px;" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $roles->slice($displayCount)->pluck('name')->implode(', ') }}">
                                                        +{{ $hiddenCount }} lainnya
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-muted text-nowrap" style="font-size: 13px;">
                                            <div class="d-flex align-items-center gap-1">
                                                <i class="ti ti-clock fs-5"></i> {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y, H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('show_user', ['id' => $user->memberKey]) }}"
                                                    class="btn btn-sm btn-light-info text-info shadow-none border-0 px-2" title="Lihat Detail">
                                                    <i class="ti ti-eye fs-5"></i>
                                                </a>
                                                <a href="{{ route('edit_user', ['id' => $user->memberKey]) }}"
                                                    class="btn btn-sm btn-light-warning text-warning shadow-none border-0 px-2" title="Edit User">
                                                    <i class="ti ti-edit fs-5"></i>
                                                </a>
                                                @if (auth()->user()->memberKey != $user->memberKey)
                                                    <button type="button" class="btn btn-sm btn-light-danger text-danger shadow-none border-0 px-2" title="Hapus User"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteUserModal{{ $user->memberKey }}">
                                                        <i class="ti ti-trash fs-5"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Modal Delete User --}}
                                    @if (auth()->user()->memberKey != $user->memberKey)
                                        <div class="modal fade" id="deleteUserModal{{ $user->memberKey }}" tabindex="-1"
                                            aria-labelledby="deleteUserModalLabel{{ $user->memberKey }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="text-white modal-header bg-danger">
                                                        <h5 class="modal-title"
                                                            id="deleteUserModalLabel{{ $user->memberKey }}">
                                                            <i class="ti ti-alert-triangle"></i> Konfirmasi Hapus User
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-2">Apakah Anda yakin ingin menghapus user ini?</p>
                                                        <div class="alert alert-warning">
                                                            <strong>Nama:</strong> {{ $user->memberName }}<br>
                                                            <strong>Email:</strong> {{ $user->email ?? '-' }}
                                                        </div>
                                                        <p class="mb-0 text-danger">
                                                            <i class="ti ti-alert-circle"></i>
                                                            <strong>Perhatian:</strong> Data user akan dihapus permanen.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="ti ti-x"></i> Batal
                                                        </button>
                                                        <form action="{{ route('delete_user') }}" method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="items[]"
                                                                value="{{ $user->memberKey }}">
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="ti ti-trash"></i> Ya, Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Bulk Delete Confirmation --}}
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="text-white modal-header bg-danger">
                    <h5 class="modal-title" id="bulkDeleteModalLabel">
                        <i class="ti ti-alert-triangle"></i> Konfirmasi Hapus User
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Apakah Anda yakin ingin menghapus <strong><span id="bulkDeleteCount">0</span>
                            user</strong> yang dipilih?</p>
                    <p class="mb-0 text-danger">
                        <i class="ti ti-alert-circle"></i>
                        <strong>Perhatian:</strong> Data user akan dihapus permanen dan tidak dapat dikembalikan.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x"></i> Batal
                    </button>
                    <button type="button" id="confirmBulkDelete" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="ti ti-trash"></i> Ya, Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            const selectedCount = document.getElementById('selectedCount');
            const deleteForm = document.getElementById('delete-form');

            function updateDeleteButton() {
                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                const count = checkedBoxes.length;

                if (count > 0) {
                    deleteBtn.style.display = 'inline-block';
                    selectedCount.textContent = count;
                } else {
                    deleteBtn.style.display = 'none';
                }

                if (count === rowCheckboxes.length && count > 0) {
                    selectAll.checked = true;
                    selectAll.indeterminate = false;
                } else if (count > 0) {
                    selectAll.checked = false;
                    selectAll.indeterminate = true;
                } else {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;
                }
            }

            selectAll.addEventListener('change', function() {
                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateDeleteButton();
            });

            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateDeleteButton);
            });

            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');

                if (checkedBoxes.length === 0) {
                    return;
                }

                // Update modal dengan jumlah yang dipilih
                document.getElementById('bulkDeleteCount').textContent = checkedBoxes.length;

                // Tampilkan modal
                const bulkDeleteModal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
                bulkDeleteModal.show();
            });

            // Handle konfirmasi dari modal
            document.getElementById('confirmBulkDelete').addEventListener('click', function() {
                deleteForm.submit();
            });

            updateDeleteButton();
        });
    </script>
@endsection
