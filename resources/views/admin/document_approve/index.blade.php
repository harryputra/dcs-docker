@extends('layouts.layout_admin')

@section('title', 'Pengesahan Dokumen')

@section('content')
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-1 fw-bolder text-dark">Pusat Pengesahan Dokumen</h2>
                <p class="mb-0 text-muted">Kelola dan verifikasi revisi dokumen terbaru dalam satu antarmuka terpadu.</p>
            </div>
            <div class="gap-2 d-flex">
                <span class="px-3 py-2 border shadow-sm badge rounded-pill bg-white text-dark border-light">
                    <i class="ti ti-calendar-event me-1 text-primary"></i> {{ date('d M Y') }}
                </span>
            </div>
        </div>

        <!-- Meta Stats -->
        <div class="mb-4 row">
            <div class="col-md-3">
                <div class="border-0 shadow-sm card bg-primary-subtle text-primary h-100">
                    <div class="py-3 card-body">
                        <div class="d-flex align-items-center">
                            <div class="p-3 badge bg-primary me-3">
                                <i class="ti ti-hospital fs-6"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Total Dokumen Fasilitas</h6>
                                <h4 class="mb-0 fw-bolder">{{ count($documents) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border-0 shadow-sm card bg-warning-subtle text-warning h-100">
                    <div class="py-3 card-body">
                        <div class="d-flex align-items-center">
                            <div class="p-3 badge bg-warning me-3">
                                <i class="ti ti-microscope fs-6"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Menunggu Review Mutu</h6>
                                <h4 class="mb-0 fw-bolder">
                                    {{ $documents->where(function($d) { return $d->currentRevision->latestRevision($d->id)->status !== 'Disetujui'; })->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>

        <div class="border-0 shadow-sm card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mt-4 table-responsive">
                            <table id="tableApproval" class="table align-middle table-hover custom-approval-table">
                                <thead>
                                    <tr>
                                        <th class="ps-4">No. Dokumen</th>
                                        <th>Nama Dokumen</th>
                                        <th>Relasi Revisi</th>
                                        <th>Status Alur</th>
                                        <th>Pratinjau</th>
                                        <th class="pe-4 text-end">Konfirmasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documents as $document)
                                        @php
                                            $latestDocRevision = $document->currentRevision->latestRevision($document->id);
                                        @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark">{{ $document->code }}</span>
                                                    <small class="text-muted">{{ $document->category->name }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded p-2 me-3 d-none d-lg-block">
                                                        <i class="ti ti-file-text fs-5 text-primary"></i>
                                                    </div>
                                                    <span class="fw-medium">{{ $document->title }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @forelse ($latestDocRevision->revisedDocument() as $doc)
                                                        @php
                                                            $rev = $doc->currentRevision->latestRevision($doc->id);
                                                        @endphp
                                                        <a href="javascript:void(0)" 
                                                           onclick="previewDocument('{{ route('document.preview', ['revision' => $rev->id]) }}')"
                                                           class="badge bg-light text-primary border border-primary-subtle text-decoration-none px-2 py-1">
                                                            <i class="ti ti-link fs-2 me-1"></i> {{ Str::limit($doc->title, 15) }}
                                                        </a>
                                                    @empty
                                                        <span class="text-muted">-</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $status = $latestDocRevision->status;
                                                    $statusClass = 'bg-light text-dark';
                                                    $icon = 'ti-circle-dotted';
                                                    
                                                    if ($status === 'Disetujui') {
                                                        $statusClass = 'bg-success-subtle text-success';
                                                        $icon = 'ti-circle-check-filled';
                                                    } elseif (in_array($status, ['Proses Revisi', 'Pengajuan Revisi', 'Proses Pengesahan'])) {
                                                        $statusClass = 'bg-warning-subtle text-warning';
                                                        $icon = 'ti-clock-filled';
                                                    } elseif ($status === 'Draft') {
                                                        $statusClass = 'bg-info-subtle text-info';
                                                        $icon = 'ti-edit-circle';
                                                    } elseif (in_array($status, ['Ditolak', 'Dicabut', 'Kadaluarsa', 'Expired'])) {
                                                        $statusClass = 'bg-danger-subtle text-danger';
                                                        $icon = 'ti-circle-x-filled';
                                                    } elseif ($status === 'Diganti') {
                                                        $statusClass = 'bg-secondary-subtle text-secondary';
                                                        $icon = 'ti-replace-filled';
                                                    }
                                                @endphp
                                                <span class="badge rounded-pill {{ $statusClass }} px-3 py-2 fw-bold d-inline-flex align-items-center" style="font-size: 0.75rem; min-width: 100px;">
                                                    <i class="ti {{ $icon }} fs-3 me-1"></i> {{ $status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group shadow-sm">
                                                    <button type="button" 
                                                        onclick="previewDocument('{{ route('document.preview', ['revision' => $latestDocRevision->id]) }}')"
                                                        class="btn btn-white btn-sm border" data-bs-toggle="tooltip" title="Lihat Pratinjau">
                                                        <i class="ti ti-eye text-primary"></i>
                                                    </button>
                                                    <a href="{{ route('document_revision.show-file', ['filename' => $latestDocRevision->file_path]) }}"
                                                        class="btn btn-white btn-sm border" data-bs-toggle="tooltip" title="Unduh File" download target="_blank">
                                                        <i class="ti ti-download text-info"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            @can('edit-approval')
                                                <td class="pe-4 text-end">
                                                    @if (($roles->contains('administrator') && $latestDocRevision->acc_format && $latestDocRevision->acc_content) ||
                                                         (($roles->contains('bagian-mutu') && $latestDocRevision->acc_content) || ($roles->contains('bagian-mutu') && !$latestDocRevision->acc_format)) ||
                                                         ($roles->contains('pengendali-dokumen') && $latestDocRevision->acc_format && !$latestDocRevision->acc_content) ||
                                                         $latestDocRevision->status !== 'Draft' ||
                                                         ($roles->contains('kepala-puskesmas') && (!$latestDocRevision->acc_format || !$latestDocRevision->acc_content)))
                                                        
                                                        <button type="button" id="btn-modalTerima"
                                                            class="btn btn-light-info btn-sm rounded-2 px-3 py-1 fw-bold d-inline-flex align-items-center shadow-sm border border-info-subtle" 
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalTerima" data-id="{{ $latestDocRevision->id }}"
                                                            title="Detail Verifikasi">
                                                            <i class="ti ti-shield-check fs-4 me-1 text-info"></i> 
                                                            <span class="text-info" style="font-size: 0.75rem;">DETAIL</span>
                                                        </button>
                                                    @else
                                                        <div class="gap-1 d-flex justify-content-end">
                                                            @if (auth()->user()->isRole('pengendali-dokumen') && $latestDocRevision->acc_format && $latestDocRevision->acc_content)
                                                                <a href="{{ route('document_approval.edit', ['documentRevision' => $latestDocRevision->id]) }}"
                                                                    class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" title="Finalisasi Pengesahan">
                                                                    <i class="ti ti-cloud-upload me-1"></i> Sahkan
                                                                </a>
                                                            @else
                                                                <button type="button" id="btn-modalTerima"
                                                                    class="btn btn-success btn-sm rounded-circle p-2 shadow-sm" data-bs-toggle="modal"
                                                                    data-bs-target="#modalTerima"
                                                                    data-id="{{ $latestDocRevision->id }}" title="Terima Dokumen">
                                                                    <i class="ti ti-check"></i>
                                                                </button>
                                                            @endif
                                                            <button type="button" id="btn-modalTolak"
                                                                class="btn btn-danger btn-sm rounded-circle p-2 shadow-sm" data-bs-toggle="modal"
                                                                data-bs-target="#modalTolak" data-id="{{ $latestDocRevision->id }}"
                                                                title="Tolak / Revisi">
                                                                <i class="ti ti-x"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Modal Revisi-->
                            <div class="modal fade" id="modalTolak" data-bs-backdrop="static" data-bs-keyboard="false"
                                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Tolak Dokumen</h5>
                                        </div>
                                        <div class="px-4 modal-body">
                                            <form id="formTolak" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Pengajuan Revisi">
                                                <div class="mb-3 row align-items-center">
                                                    <div class="col-md-6">
                                                        <label for="exampleInputEmail1" class="form-label">Judul</label>
                                                        <input type="text" id="rev_judul_doc" disabled
                                                            class="form-control" aria-describedby="emailHelp">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="exampleInputEmail1" class="form-label">Nomor
                                                            Dokumen</label>
                                                        <input type="text" class="form-control" id="rev_code_doc"
                                                            disabled aria-describedby="emailHelp">
                                                    </div>

                                                    <div class="mt-2 col-md-12">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1" class="form-label">Kategori
                                                                Dokumen</label>
                                                            <select class="form-control" id="rev_category_doc" disabled>
                                                                @foreach ($categories as $category)
                                                                    <option>{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="mt-2 mb-3 row align-items-center">

                                                        <div class="col-md-6">
                                                            <label for="exampleInputEmail1"
                                                                class="form-label">Pengunggah</label>
                                                            <input type="text" class="form-control" id="rev_uploader_doc"
                                                                disabled aria-describedby="emailHelp">
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Alasan
                                                        Penolakan<span class="text-danger">*</span></label>
                                                    <textarea class="form-control" name="reason" rows="2" required></textarea>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Konfirmasi Penolakan</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Terima-->
                            <div class="modal fade" id="modalTerima" data-bs-backdrop="static" data-bs-keyboard="false"
                                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="p-2 modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Terima Dokumen</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form id="formTerima" method="POST">
                                                @csrf
                                                @method('PUT')
                                                @if (auth()->user()->isRole('Kepala-Puskesmas'))
                                                    <input type="hidden" name="status" value="Disetujui">
                                                @else
                                                    <input type="hidden" name="status" value="Draft">
                                                @endif
                                                <div class="mb-3 row align-items-center">
                                                    <div class="col-md-12">
                                                        <label for="exampleInputEmail1" class="form-label">Judul</label>
                                                        <input type="text" class="form-control"
                                                            aria-describedby="emailHelp" id="acc_judul_doc" disabled>
                                                    </div>
                                                </div>

                                                <!-- Klasifikasi Dokumen (Hanya untuk Pengendali Dokumen) -->
                                                @if (auth()->user()->isRole('Pengendali-Dokumen'))
                                                    <div class="mb-3 row align-items-center"
                                                        id="classification_container">
                                                        <div class="col-md-12">
                                                            <label class="form-label">Klasifikasi Dokumen<span
                                                                    class="text-danger">*</span></label>

                                                            <select id="classification_select" name="classification_id"
                                                                class="form-control" required>
                                                                <option value="">-- Pilih Klasifikasi Dokumen --
                                                                </option>
                                                            </select>

                                                            <small class="mt-1 text-muted d-block">
                                                                <i class="ti ti-info-circle"></i> Pilih klasifikasi dokumen
                                                            </small>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Kategori
                                                            Dokumen</label>
                                                        <select class="form-control" id="acc_category_doc" disabled>
                                                            @foreach ($categories as $category)
                                                                <option>{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-3 row align-items-center">
                                                    <div class="col-md-6">
                                                        <label for="exampleInputEmail1"
                                                            class="form-label">Pengunggah</label>
                                                        <input type="text" class="form-control" id="acc_uplodeder_doc"
                                                            disabled aria-describedby="emailHelp">
                                                    </div>
                                                </div>
                                                <div id="reason_container" class="mb-3 row align-items-center"
                                                    style="display: none;">
                                                    <div class="col-md-12">
                                                        <label class="form-label">Alasan
                                                            Revisi</label>
                                                        <textarea id="acc_reason" class="form-control" disabled></textarea>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row align-items-center">
                                                    <div class="col-md-12">
                                                        <label for="exampleInputEmail1" class="form-label">Status
                                                            @if (auth()->user()->isRole('administrator'))
                                                                <span class="text-danger">*</span>
                                                            @endif
                                                        </label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="1" id="acc_status1_doc" name="acc_format"
                                                                {{ auth()->user()->isRole('administrator') ? '' : 'disabled' }}>
                                                            <label class="form-check-label" for="flexCheckDefault">
                                                                Telah diverivikasi Pengendali dokumen
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="1" id="acc_status2_doc" name="acc_content"
                                                                {{ auth()->user()->isRole('administrator') ? '' : 'disabled' }}>
                                                            <label class="form-check-label" for="flexCheckChecked">
                                                                Telah diverifikasi bagian mutu
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success" id="acc-btn"
                                                style="display: none">Konfirmasi Pengesahan</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

        </div>
    </div>

    <!-- Page Specific Styles -->
    <style>
        .custom-approval-table thead th {
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }
        .custom-approval-table tbody tr {
            transition: all 0.2s ease-in-out;
        }
        .custom-approval-table tbody tr:hover {
            background-color: #f1f5f9;
        }
        .badge {
            letter-spacing: 0.025em;
        }
        .bg-success-subtle { background-color: #dcfce7 !important; }
        .bg-warning-subtle { background-color: #fef9c3 !important; }
        .bg-info-subtle { background-color: #e0f2fe !important; }
        .btn-light-info {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .btn-light-info:hover {
            background-color: #bae6fd;
            color: #0c4a6e;
        }
        
        .btn-white {
            background: #fff;
            color: #1e293b;
            border-color: #e2e8f0;
        }
        .btn-white:hover {
            background: #f8fafc;
            color: #0f172a;
        }
        
        /* Modern DataTables Styling Integration */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            outline: none !important;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 4px 24px 4px 8px; /* Extra right padding for arrow */
            font-size: 0.875rem;
            outline: none !important;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 12px 12px;
            background-color: #fff;
            min-width: 60px;
        }
        
        /* Modal Refinements */
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        .modal-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .form-label {
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
        }
        .form-control:disabled {
            background-color: #f1f5f9;
            opacity: 0.7;
        }
    </style>

@section('customJS')
    <script>
        $(document).ready(function() {
            // Enable Tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        });
    </script>
    <script src="{{ asset('assets/js/datatablesApproval.js') }}"></script>
@endsection
@include('components.pdf-preview-modal')
@endsection
