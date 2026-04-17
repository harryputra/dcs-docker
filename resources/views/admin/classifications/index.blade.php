@extends('layouts.layout_admin')

@section('title', 'Klasifikasi Dokumen')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 border-start border-4 border-info rounded-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-4 pb-3 border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="fw-bolder mb-1 text-dark d-flex align-items-center gap-2">
                                    <i class="ti ti-tags text-info"></i> Klasifikasi Dokumen
                                </h3>
                                <p class="text-muted small mb-0">Kelola standar struktur dan hierarki indeks dokumen elektronik.</p>
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

                        <div class="table-responsive">
                            <table id="myTable" class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;" class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">No</th>
                                        <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Kode Klasifikasi</th>
                                        <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Nama Klasifikasi</th>
                                        <th style="width: 120px;" class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classifications as $index => $classification)
                                        <tr class="transition-all">
                                            <td class="fw-bold text-dark fs-3">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark-emphasis border border-secondary-subtle px-3 py-1 rounded-pill fw-semibold">{{ $classification->kode_klasifikasi }}</span>
                                            </td>
                                            <td class="fw-bold text-dark fs-4">{{ $classification->nama_klasifikasi }}</td>
                                            <td class="text-center">
                                                <div class="gap-2 d-flex justify-content-center">
                                                    <a href="{{ route('classifications.edit', $classification) }}"
                                                        class="btn btn-primary-light btn-icon rounded-3" title="Edit Klasifikasi">
                                                        <i class="ti ti-edit fs-5"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger-light btn-icon rounded-3"
                                                        title="Hapus Klasifikasi" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $classification->id }}">
                                                        <i class="ti ti-trash fs-5"></i>
                                                    </button>
                                                </div>
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
        
        .btn-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            transition: all 0.2s ease;
        }
        .btn-icon:hover { transform: translateY(-2px); }
        .btn-primary-light { background: #e0f2fe; color: #0284c7; border: none; }
        .btn-primary-light:hover { background: #bae6fd; color: #0369a1; }
        .btn-danger-light { background: #fee2e2; color: #dc2626; border: none; }
        .btn-danger-light:hover { background: #fecaca; color: #b91c1c; }
        .transition-all { transition: all 0.2s ease; }
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
