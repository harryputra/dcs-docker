@extends('layouts.layout_admin')
@section('title', __('rbac::roles.roles'))
@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 border-start border-4 border-primary">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bolder mb-1 text-dark">Manajemen Hak Akses (Roles)</h3>
                        <p class="text-muted small mb-0">Kelola peran administrator dan akses fungsional sistem.</p>
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center bg-light p-3 rounded-3">
                    <div>
                        <button id="deleteSelectedBtn" class="btn btn-danger d-flex align-items-center gap-2" style="display: none;">
                            <i class="ti ti-trash"></i> Hapus Dipilih (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                    <a class="btn btn-primary d-flex align-items-center gap-2 px-4 shadow-sm" href="{{ route('create_role') }}">
                        <i class="ti ti-plus"></i> Buat Role Baru
                    </a>
                </div>

                @if ($errors->has('items'))
                    <div class="alert alert-danger bg-danger-subtle text-danger border-0 d-flex align-items-center" role="alert">
                        <i class="ti ti-alert-circle me-2"></i> {{ $errors->first('items') }}
                    </div>
                @endif

                <form id="delete-form" action="{{ route('delete_role') }}" method="POST">
                    @csrf
                    <div class="table-responsive bg-white rounded-3 border">
                        <table id="myTable" class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 40px;" class="text-center border-bottom-0">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th style="width: 50px;" class="fw-bolder text-uppercase border-bottom-0" style="font-size: 11px;">No</th>
                                    <th class="fw-bolder text-uppercase border-bottom-0" style="font-size: 11px; width: 200px;">Nama Role</th>
                                    <th class="fw-bolder text-uppercase border-bottom-0" style="font-size: 11px; width: 150px;">Kode Sistem</th>
                                    <th class="fw-bolder text-uppercase border-bottom-0" style="font-size: 11px; width: 200px;">Deskripsi</th>
                                    <th class="fw-bolder text-uppercase border-bottom-0" style="font-size: 11px;">Otoritas Akses</th>
                                    <th style="width: 130px;" class="fw-bolder text-uppercase border-bottom-0" style="font-size: 11px;">Dibuat</th>
                                    <th style="width: 110px;" class="text-center fw-bolder text-uppercase border-bottom-0" style="font-size: 11px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @foreach ($dataProvider->get() as $key => $role)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="items[]" value="{{ $role->id }}"
                                                class="form-check-input row-checkbox cursor-pointer">
                                        </td>
                                        <td class="text-muted fw-semibold small">{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="ti ti-shield-check fs-5"></i>
                                                </div>
                                                <a href="{{ route('show_role', ['id' => $role->id]) }}" class="text-dark fw-bold text-decoration-none" style="font-size: 14px;">
                                                    {{ $role->name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-muted border px-2 py-1" style="font-size: 12px; font-weight: 500;">{{ $role->slug }}</span></td>
                                        <td>
                                            <div class="text-muted text-break" style="font-size: 13px; line-height: 1.4;">
                                                {{ $role->description ?: '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $permissions = collect($role->permissions);
                                                $displayCount = 3;
                                                $displayed = $permissions->take($displayCount);
                                                $hiddenCount = $permissions->count() - $displayCount;
                                            @endphp
                                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                                @forelse ($displayed as $permission)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fw-normal" style="font-size: 11px;">
                                                        {{ $permission->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-muted small border px-2 py-1 rounded bg-light border-dashed" style="font-size: 11px;"><i class="ti ti-ban"></i> Belum ada izin</span>
                                                @endforelse
                                                
                                                @if($hiddenCount > 0)
                                                    <span class="badge bg-light text-dark border px-2 py-1 fw-medium cursor-pointer" style="font-size: 11px;" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $permissions->slice($displayCount)->pluck('name')->implode(', ') }}">
                                                        +{{ $hiddenCount }} lainnya
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-muted text-nowrap" style="font-size: 13px;">
                                            <div class="d-flex align-items-center gap-1">
                                                <i class="ti ti-calendar fs-5"></i> {{ \Carbon\Carbon::parse($role->created_at)->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="text-nowrap text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('show_role', ['id' => $role->id]) }}"
                                                    class="btn btn-sm btn-light-primary text-primary shadow-none border-0 px-2" title="Lihat Detail">
                                                    <i class="ti ti-eye fs-5"></i>
                                                </a>
                                                <a href="{{ route('edit_role', ['role' => $role->id]) }}"
                                                    class="btn btn-sm btn-light-warning text-warning shadow-none border-0 px-2" title="Edit Role">
                                                    <i class="ti ti-edit fs-5"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-light-danger text-danger shadow-none border-0 px-2" title="Hapus Role"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteRoleModal{{ $role->id }}">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>


                                    {{-- Modal Delete Role --}}
                                    <div class="modal fade" id="deleteRoleModal{{ $role->id }}" tabindex="-1"
                                        aria-labelledby="deleteRoleModalLabel{{ $role->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteRoleModalLabel{{ $role->id }}">
                                                        <i class="ti ti-alert-triangle"></i> Konfirmasi Hapus Role
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-2">Apakah Anda yakin ingin menghapus role ini?</p>
                                                    <div class="alert alert-warning">
                                                        <strong>Nama Role:</strong> {{ $role->name }}<br>
                                                        <strong>Slug:</strong> {{ $role->slug }}
                                                    </div>
                                                    <p class="text-danger mb-0">
                                                        <i class="ti ti-alert-circle"></i>
                                                        <strong>Perhatian:</strong> Data role akan dihapus permanen.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        <i class="ti ti-x"></i> Batal
                                                    </button>
                                                    <form action="{{ route('delete_role') }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="items[]" value="{{ $role->id }}">
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="ti ti-trash"></i> Ya, Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="bulkDeleteModalLabel">
                        <i class="ti ti-alert-triangle"></i> Konfirmasi Hapus Role
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Apakah Anda yakin ingin menghapus <strong><span id="bulkDeleteCount">0</span>
                            role</strong> yang dipilih?</p>
                    <p class="text-danger mb-0">
                        <i class="ti ti-alert-circle"></i>
                        <strong>Perhatian:</strong> Data role akan dihapus permanen dan tidak dapat dikembalikan.
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
