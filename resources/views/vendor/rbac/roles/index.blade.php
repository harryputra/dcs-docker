@extends('layouts.layout_admin')
@section('title', __('rbac::roles.roles'))
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2 class="mb-2">Roles</h2>
                        <x-breadcrumb :breadcrumbs="[['title' => 'Roles', 'url' => route('list_roles')]]" />
                    </div>
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <button id="deleteSelectedBtn" class="btn btn-danger" style="display: none;">
                            <i class="ti ti-trash"></i> Hapus Dipilih (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                    <a class="btn btn-admin" href="{{ route('create_role') }}">
                        <i class="ti ti-plus"></i> {!! __('rbac::roles.create_role') !!}
                    </a>
                </div>

                @if ($errors->has('items'))
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first('items') }}
                    </div>
                @endif

                <form id="delete-form" action="{{ route('delete_role') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table id="myTable" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 30px;">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th style="width: 50px;">No</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th>Permission</th>
                                    <th>Created</th>
                                    <th style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataProvider->get() as $key => $role)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="items[]" value="{{ $role->id }}"
                                                class="form-check-input row-checkbox">
                                        </td>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a href="{{ route('show_role', ['id' => $role->id]) }}">
                                                {{ $role->name }}
                                            </a>
                                        </td>
                                        <td>{{ $role->slug }}</td>
                                        <td>{{ $role->description }}</td>
                                        <td>
                                            <ul class="list-group list-group-flush">
                                                @foreach ($role->permissions as $permission)
                                                    <li class="p-2 list-group-item">
                                                        <a href="{{ route('show_permission', ['id' => $permission->id]) }}">
                                                            {{ $permission->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $role->created_at }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('show_role', ['id' => $role->id]) }}"
                                                    class="btn btn-sm btn-admin" title="Lihat Detail">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('edit_role', ['role' => $role->id]) }}"
                                                    class="btn btn-sm btn-approver" title="Edit Role">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" title="Hapus Role"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteRoleModal{{ $role->id }}">
                                                    <i class="ti ti-trash"></i>
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
