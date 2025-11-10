@extends('layouts.layout_admin')
@section('title', __('rbac::users.users'))
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-4">Users</h2>
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <button id="deleteSelectedBtn" class="btn btn-danger" style="display: none;">
                            <i class="ti ti-trash"></i> Hapus Dipilih (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                    <a href="{{ route('create_users') }}" class="btn btn-admin">
                        <i class="ti ti-user-plus"></i> Create User
                    </a>
                </div>

                @if ($errors->has('items'))
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first('items') }}
                    </div>
                @endif

                <form id="delete-form" action="{{ route('delete_user') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table id="myTable" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Roles</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataProvider->get() as $key => $user)
                                    <tr>
                                        <td>
                                            @if (Gate::allows(\Itstructure\LaRbac\Models\Permission::DELETE_MEMBER_FLAG, $user->memberKey) &&
                                                    auth()->user()->memberKey != $user->memberKey)
                                                <input type="checkbox" name="items[]" value="{{ $user->memberKey }}"
                                                    class="form-check-input row-checkbox">
                                            @endif
                                        </td>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a href="{{ route('show_user', ['id' => $user->memberKey]) }}">
                                                {{ $user->memberName }}
                                            </a>
                                        </td>
                                        <td>
                                            <ul class="list-group list-group-flush">
                                                @foreach ($user->roles as $role)
                                                    <li class="p-2 list-group-item">
                                                        <a href="{{ route('show_role', ['id' => $role->id]) }}">
                                                            {{ $role->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>
                                            <div class="gap-1 d-flex">
                                                <a href="{{ route('show_user', ['id' => $user->memberKey]) }}"
                                                    class="btn btn-sm btn-admin" title="Lihat Detail">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('edit_user', ['id' => $user->memberKey]) }}"
                                                    class="btn btn-sm btn-approver" title="Edit User">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                @if (auth()->user()->memberKey != $user->memberKey)
                                                    <button type="button" class="btn btn-sm btn-danger" title="Hapus User"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteUserModal{{ $user->memberKey }}">
                                                        <i class="ti ti-trash"></i>
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
                    alert('Pilih minimal 1 user untuk dihapus');
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
