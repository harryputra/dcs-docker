@extends('layouts.layout_admin')

@section('title', 'Revisi Document')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-2">Kategori Dokumen</h2>
                                <x-breadcrumb :breadcrumbs="[['title' => 'Kategori Dokumen', 'url' => route('categories.index')]]" />
                            </div>
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="input-group input-group-modern shadow-sm" style="max-width: 350px;">
                                <span class="bg-white border-end-0 input-group-text"><i class="ti ti-search text-muted"></i></span>
                                <input type="text" id="customSearch" class="bg-white border-start-0 form-control fs-3" placeholder="Cari kategori...">
                            </div>
                            <a href="{{ route('categories.create') }}" class="btn btn-admin d-flex align-items-center rounded-pill px-4 shadow-sm">
                                <i class="ti ti-plus me-1"></i> Tambah Kategori Baru
                            </a>
                        </div>

                        <div class="mt-2 table-responsive">
                            <table id="myTable" class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th style="width: 50px;">No</th>
                                        <th>Kode Kategori</th>
                                        <th>Kategori</th>
                                        <th style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input row-checkbox"
                                                    value="{{ $category->id }}" data-id="{{ $category->id }}">
                                            </td>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->code }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <a href="{{ route('categories.edit', $category) }}"
                                                    class="btn btn-sm btn-admin" title="Edit Kategori">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" title="Hapus Kategori"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteCategoryModal{{ $category->id }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- Modal Delete Category --}}
                                        <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1"
                                            aria-labelledby="deleteCategoryModalLabel{{ $category->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="text-white modal-header bg-danger">
                                                        <h5 class="modal-title"
                                                            id="deleteCategoryModalLabel{{ $category->id }}">
                                                            <i class="ti ti-alert-triangle"></i> Konfirmasi Hapus Kategori
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-2">Apakah Anda yakin ingin menghapus kategori
                                                            <strong>{{ $category->name }}</strong>?
                                                        </p>
                                                        @php
                                                            $docCount = $category->documents()->count();
                                                        @endphp
                                                        @if ($docCount > 0)
                                                            <div class="mb-2 alert alert-warning" role="alert">
                                                                <i class="ti ti-alert-triangle"></i>
                                                                <strong>Peringatan:</strong> Kategori ini memiliki
                                                                <strong>{{ $docCount }} dokumen</strong> terkait yang
                                                                akan ikut terhapus!
                                                            </div>
                                                        @endif
                                                        <p class="mb-0 text-muted">
                                                            <i class="ti ti-info-circle"></i> Data yang sudah dihapus tidak
                                                            dapat dikembalikan.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="ti ti-x"></i> Batal
                                                        </button>
                                                        <form action="{{ route('categories.destroy', $category) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="ti ti-trash"></i> Hapus
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form untuk bulk delete -->
    <form id="bulkDeleteForm" action="{{ route('categories.bulkDelete') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="ids" id="selectedIds">
    </form>

    {{-- Modal Bulk Delete --}}
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="text-white modal-header bg-danger">
                    <h5 class="modal-title" id="bulkDeleteModalLabel">
                        <i class="ti ti-alert-triangle"></i> Konfirmasi Hapus Kategori
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Apakah Anda yakin ingin menghapus <strong id="bulkDeleteCount">0</strong> kategori
                        yang dipilih?</p>
                    <div id="bulkDocumentWarning" class="mb-2 alert alert-warning" style="display: none;"
                        role="alert">
                        <i class="ti ti-alert-triangle"></i>
                        <strong>Peringatan:</strong> Kategori yang dipilih memiliki <strong
                            id="bulkDocumentCount">0</strong> dokumen terkait yang akan ikut terhapus!
                    </div>
                    <p class="mb-0 text-muted">
                        <i class="ti ti-info-circle"></i> Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmBulkDelete">
                        <i class="ti ti-trash"></i> Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .input-group-modern { border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; }
        .input-group-modern input { border: none !important; box-shadow: none !important; padding: 10px 15px; }
        .input-group-modern:focus-within { border-color: #14b8a6; box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1); }
        .table-hover tbody tr:hover { background-color: #f8fafc; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DataTables Optimization
            const table = $('#myTable').DataTable({
                "dom": 'rt<"bottom"ip><"clear">',
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.5/i18n/id.json"
                }
            });

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            const selectAll = document.getElementById('selectAll');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            const selectedCount = document.getElementById('selectedCount');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');
            const selectedIdsInput = document.getElementById('selectedIds');

            // Function to update button visibility and count
            function updateDeleteButton() {
                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                const count = checkedBoxes.length;

                if (count > 0) {
                    deleteBtn.style.display = 'inline-block';
                    selectedCount.textContent = count;
                } else {
                    deleteBtn.style.display = 'none';
                    selectedCount.textContent = '0';
                }

                // Update select all checkbox state
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

            // Select all checkbox
            selectAll.addEventListener('change', function() {
                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateDeleteButton();
            });

            // Individual checkboxes
            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateDeleteButton);
            });

            // Delete selected button
            deleteBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                const ids = Array.from(checkedBoxes).map(cb => cb.value);

                if (ids.length === 0) {
                    return;
                }

                // Update modal dengan jumlah yang dipilih
                document.getElementById('bulkDeleteCount').textContent = ids.length;
                selectedIdsInput.value = JSON.stringify(ids);

                // Fetch jumlah dokumen yang akan terhapus via AJAX
                fetch('{{ route('categories.countDocuments') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ids: ids
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const warningDiv = document.getElementById('bulkDocumentWarning');
                        const documentCountSpan = document.getElementById('bulkDocumentCount');

                        if (data.totalDocuments > 0) {
                            documentCountSpan.textContent = data.totalDocuments;
                            warningDiv.style.display = 'block';
                        } else {
                            warningDiv.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching document count:', error);
                    });

                // Tampilkan modal
                const bulkDeleteModal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
                bulkDeleteModal.show();
            });

            // Handle konfirmasi dari modal
            document.getElementById('confirmBulkDelete').addEventListener('click', function() {
                bulkDeleteForm.submit();
            });

            // Initial state
            updateDeleteButton();
        });
    </script>

@endsection
