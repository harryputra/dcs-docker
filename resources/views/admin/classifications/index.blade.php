@extends('layouts.layout_admin')

@section('title', 'Klasifikasi Dokumen')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h2 class="mb-2">Klasifikasi Dokumen</h2>
                                <x-breadcrumb :breadcrumbs="[
                                    ['title' => 'Klasifikasi Dokumen', 'url' => route('classifications.index')],
                                ]" />
                            </div>
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="input-group input-group-modern shadow-sm" style="max-width: 350px;">
                                <span class="bg-white border-end-0 input-group-text"><i class="ti ti-search text-muted"></i></span>
                                <input type="text" id="customSearch" class="bg-white border-start-0 form-control fs-3" placeholder="Cari klasifikasi...">
                            </div>
                            <a href="{{ route('classifications.create') }}" class="btn btn-admin d-flex align-items-center rounded-pill px-4 shadow-sm">
                                <i class="ti ti-plus me-1"></i> Tambah Klasifikasi Baru
                            </a>
                        </div>

                        <div class="mt-2 table-responsive">
                            <table id="myTable" class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Kode Klasifikasi</th>
                                        <th>Nama Klasifikasi</th>
                                        <th style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classifications as $index => $classification)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $classification->kode_klasifikasi }}</td>
                                            <td>{{ $classification->nama_klasifikasi }}</td>
                                            <td>
                                                <a href="{{ route('classifications.edit', $classification) }}"
                                                    class="btn btn-sm btn-admin" title="Edit Klasifikasi">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    title="Hapus Klasifikasi" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $classification->id }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- Modal Delete --}}
                                        <div class="modal fade" id="deleteModal{{ $classification->id }}" tabindex="-1"
                                            aria-labelledby="deleteModalLabel{{ $classification->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="text-white modal-header bg-danger">
                                                        <h5 class="modal-title"
                                                            id="deleteModalLabel{{ $classification->id }}">
                                                            <i class="ti ti-alert-triangle"></i> Konfirmasi Hapus
                                                            Klasifikasi
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-2">Apakah Anda yakin ingin menghapus klasifikasi
                                                            <strong>{{ $classification->kode_klasifikasi }} -
                                                                {{ $classification->nama_klasifikasi }}</strong>?
                                                        </p>
                                                        @php
                                                            $docCount = $classification->documents()->count();
                                                        @endphp
                                                        @if ($docCount > 0)
                                                            <div class="alert alert-warning mb-2" role="alert">
                                                                <i class="ti ti-alert-triangle"></i>
                                                                <strong>Peringatan:</strong> Klasifikasi ini memiliki
                                                                <strong>{{ $docCount }} dokumen</strong> terkait!
                                                            </div>
                                                        @endif
                                                        <p class="mb-0 text-muted">
                                                            <i class="ti ti-info-circle"></i> Data yang sudah dihapus tidak
                                                            dapat
                                                            dikembalikan.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="ti ti-x"></i> Batal
                                                        </button>
                                                        <form
                                                            action="{{ route('classifications.destroy', $classification) }}"
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
    <style>
        .input-group-modern { border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; }
        .input-group-modern input { border: none !important; box-shadow: none !important; padding: 10px 15px; }
        .input-group-modern:focus-within { border-color: #14b8a6; box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1); }
        .table-hover tbody tr:hover { background-color: #f8fafc; }
    </style>

    @section('customJS')
    <script>
        $(document).ready(function() {
            const table = $('#myTable').DataTable({
                "dom": 'rt<"bottom"ip><"clear">',
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.5/i18n/id.json"
                }
            });

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
    @endsection
@endsection
