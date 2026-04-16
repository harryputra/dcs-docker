@extends('layouts.layout_admin')

@section('title', 'Pengesahan Dokumen')

@section('content')
    <div class="container-fluid">
    <div class="container-fluid pb-4">
        <!-- Enterprise Header -->
        <div class="mb-5 border-0 shadow-sm card rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);">
            <div class="p-4 card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div>
                    <h2 class="mb-1 display-6 fw-bold text-dark">Pusat Pengesahan Dokumen</h2>
                    <p class="mb-0 text-muted fs-4">Otorisasi, verifikasi, dan validasi rekaman sistem dokumen secara terpadu.</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <div class="px-4 py-2 bg-white shadow-sm badge rounded-pill border border-light-subtle d-flex align-items-center">
                        <i class="ti ti-calendar-event me-2 text-primary fs-5"></i>
                        <span class="text-dark fw-bold fs-3">{{ date('d F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytical Metrics -->
        <div class="mb-5 row g-4">
            <div class="col-md-4">
                <div class="border-0 shadow-sm card rounded-4 h-100 border-start border-4 border-primary metric-card-interactive border-pulse-primary">
                    <div class="p-4 card-body d-flex align-items-center">
                        <div class="p-3 shadow-sm badge bg-primary-subtle text-primary rounded-3 me-4 badge-scaling">
                            <i class="ti ti-box-multiple fs-7"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-uppercase fw-bold text-muted small letter-spacing-1">Arsip Fasilitas</p>
                            <h3 class="mb-0 fw-extrabold text-dark">{{ count($documents) }} <small class="text-muted fw-normal fs-3">Berkas</small></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border-0 shadow-sm card rounded-4 h-100 border-start border-4 border-warning metric-card-interactive border-pulse-warning">
                    <div class="p-4 card-body d-flex align-items-center">
                        <div class="p-3 shadow-sm badge bg-warning-subtle text-warning rounded-3 me-4 badge-scaling">
                            <i class="ti ti-microscope fs-7"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-uppercase fw-bold text-muted small letter-spacing-1">Menunggu Review</p>
                            <h3 class="mb-0 fw-extrabold text-dark">
                                {{ $documents->where(function($d) { return $d->currentRevision->latestRevision($d->id)->status !== 'Disetujui'; })->count() }}
                                <small class="text-muted fw-normal fs-3">Antrean</small>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end d-none d-md-flex align-items-center justify-content-end">
                <div class="text-end">
                    <p class="mb-1 fw-bold text-muted small">Status Keamanan</p>
                    <div class="d-flex align-items-center justify-content-end text-success fw-bold">
                        <i class="ti ti-shield-check me-2 fs-5"></i>
                        <span>Enksripsi AES-256 Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Matrix -->
        <div class="border-0 shadow-sm card rounded-4 overflow-hidden">
            <div class="p-4 card-body">
                <!-- Unified Filter Actions -->
                <div class="mb-4 row g-3 align-items-center">
                    <div class="col-md-6">
                        <div id="statusFilter" class="status-pill-group d-inline-flex bg-light p-1 rounded-pill border">
                            <label class="mb-0 cursor-pointer">
                                <input type="radio" name="filterDocument" id="fAll" value="all" checked class="d-none">
                                <span class="px-3 py-2 pill-item d-inline-block rounded-pill fw-bold small">Semua</span>
                            </label>
                            <label class="mb-0 cursor-pointer">
                                <input type="radio" name="filterDocument" id="fApproved" value="Disetujui" class="d-none">
                                <span class="px-3 py-2 pill-item d-inline-block rounded-pill fw-bold small text-success">Approved</span>
                            </label>
                            <label class="mb-0 cursor-pointer">
                                <input type="radio" name="filterDocument" id="fPending" value="Pending" class="d-none">
                                <span class="px-3 py-2 pill-item d-inline-block rounded-pill fw-bold small text-warning">Pending</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-modern shadow-sm">
                            <span class="bg-white border-end-0 input-group-text"><i class="ti ti-search text-muted"></i></span>
                            <input type="text" id="customSearch" class="bg-white border-start-0 form-control fs-3" placeholder="Nama atau kode dokumen...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tableApproval" class="table align-middle table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3 ps-4">Otoritas Dokumen</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3">Identitas Visual</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3">Relasi Revisi</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3 text-center">Status Alur</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3 text-center">Pratinjau</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3 text-end pe-4">Aksi Otorisasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                                @php $latestDocRevision = $document->currentRevision->latestRevision($document->id); @endphp
                                <tr class="transition-all">
                                    <td class="ps-4">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark fs-4 mb-1">{{ $document->code }}</span>
                                            <span class="badge bg-light text-muted border px-2 py-1 rounded-pill small d-inline-block" style="width: fit-content;">{{ $document->category->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 shadow-sm bg-white rounded-3 me-3 border border-light-subtle d-none d-lg-block">
                                                <i class="ti ti-file-certificate fs-6 text-primary"></i>
                                            </div>
                                            <span class="fw-bold text-dark fs-4">{{ $document->title }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="gap-1 d-flex flex-wrap">
                                            @forelse ($latestDocRevision->revisedDocument() as $doc)
                                                @php $rev = $doc->currentRevision->latestRevision($doc->id); @endphp
                                                <a href="javascript:void(0)" onclick="previewDocument('{{ route('document.preview', ['revision' => $rev->id]) }}')"
                                                   class="px-2 py-1 badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none rounded-pill small">
                                                    {{ Str::limit($doc->title, 12) }}
                                                </a>
                                            @empty
                                                <span class="text-muted small italic">- Tanpa Relasi -</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $status = $latestDocRevision->status;
                                            $pillClass = 'bg-secondary-subtle text-secondary';
                                            if($status === 'Disetujui') $pillClass = 'bg-success-subtle text-success border-success-subtle';
                                            elseif(in_array($status, ['Proses Revisi', 'Pengajuan Revisi'])) $pillClass = 'bg-warning-subtle text-warning border-warning-subtle';
                                            elseif($status === 'Draft') $pillClass = 'bg-info-subtle text-info border-info-subtle';
                                        @endphp
                                        <span class="badge rounded-pill {{ $pillClass }} px-3 py-2 fw-bold" style="min-width: 90px;">
                                            {{ strtoupper($status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group rounded-pill overflow-hidden shadow-sm border">
                                            <button type="button" onclick="previewDocument('{{ route('document.preview', ['revision' => $latestDocRevision->id]) }}')" class="btn btn-white btn-sm px-3 border-0 border-end" title="Lihat">
                                                <i class="ti ti-eye text-primary fs-4"></i>
                                            </button>
                                            <a href="{{ route('document_revision.show-file', ['filename' => $latestDocRevision->file_path]) }}" class="btn btn-white btn-sm px-3 border-0 text-info" download target="_blank">
                                                <i class="ti ti-download fs-4"></i>
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
                                                
                                                <button type="button" id="btn-modalTerima" class="btn btn-primary-subtle btn-sm rounded-pill px-4 fw-bold border border-primary-subtle shadow-sm animate-on-hover" data-bs-toggle="modal"
                                                    data-bs-target="#modalTerima" data-id="{{ $latestDocRevision->id }}">
                                                    <i class="ti ti-report-analytics me-1"></i> RINCIAN
                                                </button>
                                            @else
                                                <div class="gap-2 d-flex justify-content-end">
                                                    @if (auth()->user()->isRole('pengendali-dokumen') && $latestDocRevision->acc_format && $latestDocRevision->acc_content)
                                                        <a href="{{ route('document_approval.edit', ['documentRevision' => $latestDocRevision->id]) }}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm fw-bold">
                                                            <i class="ti ti-cloud-upload me-1"></i> SAHKAN
                                                        </a>
                                                    @else
                                                        <button type="button" id="btn-modalTerima" class="btn btn-success btn-icon rounded-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTerima" data-id="{{ $latestDocRevision->id }}">
                                                            <i class="ti ti-check"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button" id="btn-modalTolak" class="btn btn-danger btn-icon rounded-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTolak" data-id="{{ $latestDocRevision->id }}">
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
                </div>
            </div>
        </div>

                            <!-- Modal Revisi -->
                            <div class="modal fade" id="modalTolak" data-bs-backdrop="static" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="border-0 shadow-lg modal-content rounded-4 overflow-hidden">
                                        <div class="p-4 modal-header border-0 bg-danger-subtle d-flex align-items-center">
                                            <div class="p-2 bg-danger text-white rounded-3 me-3"><i class="ti ti-circle-x fs-6"></i></div>
                                            <h5 class="mb-0 modal-title fw-bold text-danger">Tolak & Ajukan Revisi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="p-4 modal-body">
                                            <form id="formTolak" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Pengajuan Revisi">
                                                
                                                <div class="p-3 mb-4 border-0 shadow-sm card rounded-3 bg-light">
                                                    <div class="mb-2 d-flex align-items-center">
                                                        <i class="ti ti-file-description text-muted me-2"></i>
                                                        <span class="text-uppercase small fw-bold text-muted letter-spacing-1">Identitas Dokumen</span>
                                                    </div>
                                                    <h6 class="mb-1 fw-bold text-dark" id="rev_judul_doc">Memuat judul...</h6>
                                                    <small class="text-primary fw-medium" id="rev_code_doc">Memuat kode...</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="mb-2 form-label fw-bold small text-muted">ALASAN PENOLAKAN <span class="text-danger">*</span></label>
                                                    <textarea class="form-control border-danger-subtle rounded-3" name="reason" rows="3" placeholder="Tuliskan alasan teknis penolakan dokuman..." required></textarea>
                                                </div>
                                        </div>
                                        <div class="p-4 border-0 modal-footer bg-light d-flex justify-content-between">
                                            <button type="button" class="btn btn-white rounded-pill px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm animate-on-hover">Konfirmasi Penolakan</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Terima -->
                            <div class="modal fade" id="modalTerima" data-bs-backdrop="static" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="border-0 shadow-lg modal-content rounded-4 overflow-hidden">
                                        <div class="p-4 modal-header border-0 bg-primary-subtle d-flex align-items-center">
                                            <div class="p-2 bg-primary text-white rounded-3 me-3">
                                                <i class="ti ti-shield-check fs-6"></i>
                                            </div>
                                            <h5 class="mb-0 modal-title fw-bold text-primary">Panel Otorisasi Dokumen</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="p-4 modal-body">
                                            <form id="formTerima" method="POST">
                                                @csrf
                                                @method('PUT')
                                                @if (auth()->user()->isRole('Kepala-Puskesmas'))
                                                    <input type="hidden" name="status" value="Disetujui">
                                                @else
                                                    <input type="hidden" name="status" value="Draft">
                                                @endif
                                                
                                                <!-- Identity Section -->
                                                <div class="p-3 mb-4 border shadow-sm rounded-4 bg-light-subtle d-flex align-items-center">
                                                    <div class="p-2 bg-white shadow-sm rounded-circle me-3 border border-light">
                                                        <i class="ti ti-file-text fs-6 text-primary"></i>
                                                    </div>
                                                    <div class="overflow-hidden">
                                                        <h6 class="mb-0 fw-bold text-dark text-truncate" id="acc_judul_doc">...</h6>
                                                        <span class="badge bg-white text-muted border px-2 py-0 rounded-pill small" id="acc_category_doc">...</span>
                                                    </div>
                                                </div>

                                                <!-- Classification Selection -->
                                                @if (auth()->user()->isRole('Pengendali-Dokumen'))
                                                    <div class="mb-4" id="classification_container">
                                                        <label class="mb-2 form-label fw-bold small text-muted">KLASIFIKASI DOKUMEN <span class="text-danger">*</span></label>
                                                        <select id="classification_select" name="classification_id" class="form-select rounded-3 shadow-sm border-primary-subtle" required>
                                                            <option value="">-- Pilih Klasifikasi --</option>
                                                        </select>
                                                    </div>
                                                @endif

                                                <!-- Verification Matrix -->
                                                <div class="mb-4">
                                                    <label class="mb-3 form-label fw-bold small text-muted">MATRIKS VERIFIKASI</label>
                                                    <div class="gap-3 d-flex flex-column">
                                                        <div class="p-3 border shadow-sm verification-card rounded-4 d-flex align-items-center justify-content-between @if(!auth()->user()->isRole('administrator')) opacity-75 @endif">
                                                            <div class="d-flex align-items-center">
                                                                <div class="p-2 bg-info-subtle text-info rounded-3 me-3"><i class="ti ti-checklist fs-5"></i></div>
                                                                <div>
                                                                    <p class="mb-0 fw-bold text-dark">Validasi Format</p>
                                                                    <small class="text-muted">Diverifikasi Pengendali Dokumen</small>
                                                                </div>
                                                            </div>
                                                            <div class="form-check form-switch fs-5">
                                                                <input class="form-check-input" type="checkbox" id="acc_status1_doc" name="acc_format" {{ auth()->user()->isRole('administrator') ? '' : 'disabled' }}>
                                                            </div>
                                                        </div>

                                                        <div class="p-3 border shadow-sm verification-card rounded-4 d-flex align-items-center justify-content-between @if(!auth()->user()->isRole('administrator')) opacity-75 @endif">
                                                            <div class="d-flex align-items-center">
                                                                <div class="p-2 bg-success-subtle text-success rounded-3 me-3"><i class="ti ti-microscope fs-5"></i></div>
                                                                <div>
                                                                    <p class="mb-0 fw-bold text-dark">Validasi Konten</p>
                                                                    <small class="text-muted">Diverifikasi Bagian Mutu</small>
                                                                </div>
                                                            </div>
                                                            <div class="form-check form-switch fs-5">
                                                                <input class="form-check-input" type="checkbox" id="acc_status2_doc" name="acc_content" {{ auth()->user()->isRole('administrator') ? '' : 'disabled' }}>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="reason_container" class="mb-3" style="display: none;">
                                                    <label class="mb-2 form-label fw-bold small text-muted">CATATAN REVISI</label>
                                                    <div class="p-3 bg-warning-subtle rounded-3 border-warning-subtle text-warning-emphasis fw-medium" id="acc_reason"></div>
                                                </div>
                                        </div>
                                        <div class="p-4 border-0 modal-footer bg-light d-flex justify-content-between">
                                            <button type="button" class="btn btn-white rounded-pill px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm animate-on-hover" id="acc-btn" style="display: none">
                                                <i class="ti ti-check me-1"></i> Konfirmasi Pengesahan
                                            </button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

        </div>
    </div>

    <!-- Page Specific Styles -->
    <style>
        /* Card Hover Elevations */
        .metric-card-interactive {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
        }
        .metric-card-interactive:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        }
        .metric-card-interactive:hover .badge-scaling {
            transform: scale(1.1) rotate(5deg);
        }
        .badge-scaling { transition: transform 0.4s ease; }

        /* Elastic Table Rows */
        .table tbody tr {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .table tbody tr:hover {
            background-color: #fff !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.02);
            z-index: 10;
            position: relative;
        }

        /* Pill Group Interaction */
        .pill-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .pill-item:hover {
            background-color: rgba(20, 184, 166, 0.05);
            transform: scale(1.02);
        }

        /* Border Pulse Effect */
        .border-pulse-primary:hover { border-left-width: 8px !important; }
        .border-pulse-warning:hover { border-left-width: 8px !important; }

        /* Icon Button Organic Hover */
        .btn-icon {
            transition: all 0.3s ease;
        }
        .btn-icon:hover {
            transform: scale(1.15) rotate(8deg);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
        }

        /* Input Glass Focus */
        .input-group-modern {
            transition: all 0.3s ease;
        }
        .input-group-modern:focus-within {
            border-color: #14b8a6;
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1);
            transform: translateX(5px);
        }

        .verification-card {
            background-color: #fff;
            transition: all 0.3s ease;
        }
        .verification-card:hover {
            border-color: #14b8a6 !important;
            background-color: #f0fdfa;
        }
        .form-switch .form-check-input:checked {
            background-color: #14b8a6;
            border-color: #14b8a6;
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
