@extends('layouts.layout_admin')

@section('title', 'Dokumen')

@section('content')

    <div class="container-fluid">
        <!-- Repository Header & Metrics -->
        <div class="mb-4 row align-items-center">
            <div class="col-md-6">
                <h2 class="mb-1 fw-bolder text-dark fs-8">Repository Dokumen Utama</h2>
                <p class="text-muted fs-4">Akses dokumen aktif, regulasi, dan arsip digital Puskesmas Garuda.</p>
            </div>
            <div class="col-md-6">
                <div class="gap-3 d-flex justify-content-md-end">
                    <div class="p-3 shadow-sm card border-0 rounded-4 bg-teal-subtle flex-grow-1 flex-md-grow-0" style="min-width: 140px;">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-file-check fs-7 text-teal me-2"></i>
                            <div>
                                <h5 class="mb-0 fw-bold">{{ $documents->where('status_document', 'Aktif')->count() }}</h5>
                                <small class="text-teal-emphasis fw-bold uppercase">AKTIF</small>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 shadow-sm card border-0 rounded-4 bg-amber-subtle flex-grow-1 flex-md-grow-0" style="min-width: 140px;">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-alert-triangle fs-7 text-amber me-2"></i>
                            <div>
                                <h5 class="mb-0 fw-bold">{{ $documents->where('status_document', 'Kadaluarsa')->count() }}</h5>
                                <small class="text-amber-emphasis fw-bold uppercase">EXPIRED</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Command Center (Filter Hub) -->
        <div class="mb-5 border-0 shadow-sm card rounded-4 overflow-hidden">
            <div class="p-0 card-body">
                <div class="p-4 bg-white border-bottom border-light">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-8">
                            <div class="input-group input-group-lg search-command-bar shadow-sm">
                                <span class="bg-white border-0 input-group-text ps-4">
                                    <i class="ti ti-search text-primary fs-7"></i>
                                </span>
                                <input id="customSearch" type="text" class="bg-white border-start-0 border-0 form-control fs-4 py-3" 
                                    placeholder="Cari Judul, Kode, atau Kata Kunci Dokumen...">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group input-group-lg search-precision-bar shadow-sm">
                                <span class="bg-white border-0 input-group-text ps-3">
                                    <i class="ti ti-hash text-muted fs-5"></i>
                                </span>
                                <input id="searchCode" type="text" class="bg-white border-start-0 border-0 form-control fs-4" 
                                    placeholder="Presisi Kode...">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-light-subtle">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-6">
                            <div id="statusFilter" class="status-pill-group d-inline-flex bg-white p-1 rounded-pill shadow-sm border">
                                <label class="mb-0 cursor-pointer">
                                    <input type="radio" name="filterDocument" id="fAll" value="all" checked class="d-none">
                                    <span class="px-4 py-2 pill-item d-inline-block rounded-pill fw-bold small">Semua</span>
                                </label>
                                <label class="mb-0 cursor-pointer">
                                    <input type="radio" name="filterDocument" id="fAktif" value="aktif" class="d-none">
                                    <span class="px-4 py-2 pill-item d-inline-block rounded-pill fw-bold small text-success">Aktif</span>
                                </label>
                                <label class="mb-0 cursor-pointer">
                                    <input type="radio" name="filterDocument" id="fExpired" value="expired" class="d-none">
                                    <span class="px-4 py-2 pill-item d-inline-block rounded-pill fw-bold small text-danger">Expired</span>
                                </label>
                                <label class="mb-0 cursor-pointer">
                                    <input type="radio" name="filterDocument" id="fRevisi" value="revisi" class="d-none">
                                    <span class="px-4 py-2 pill-item d-inline-block rounded-pill fw-bold small text-warning">Revisi</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="gap-3 d-flex justify-content-lg-end">
                                <div class="filter-select-wrapper shadow-sm">
                                    <select id="filterKategori" class="bg-white border-0 form-select form-select-lg fs-3 rounded-3">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="filter-select-wrapper shadow-sm">
                                    <select id="filterTahun" class="bg-white border-0 form-select form-select-lg fs-3 rounded-3">
                                        <option value="">Semua Tahun</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Matrix Table -->
        <div class="card shadow-sm border-0 border-start border-4 border-info rounded-4">
            <div class="card-body p-4">
                <div class="mb-4 pb-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bolder mb-1 text-dark d-flex align-items-center gap-2">
                            <i class="ti ti-file-certificate text-info"></i> Daftar Dokumen Aktif
                        </h3>
                        <p class="text-muted small mb-0">Matriks komprehensif dokumen elektronik yang valid.</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableDocument">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Kode Dokumen</th>
                                <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Judul Dokumen</th>
                                <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Kategori</th>
                                <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3 text-center">Status</th>
                                <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Pengunggah</th>
                                <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                                <tr class="transition-all">
                                    <td class="fw-bold text-dark fs-3">{{ $document->code }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark fs-4 mb-1">{{ $document->title }}</span>
                                            <small class="text-muted"><i class="ti ti-calendar-event me-1"></i>Diterbitkan: {{ $document->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark-emphasis border px-3 rounded-pill fw-semibold">{{ $document->category->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($document->status_document === 'Aktif')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bolder">Aktif</span>
                                        @elseif($document->status_document === 'Kadaluarsa' || $document->status_document === 'Expired')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-bolder">Expired</span>
                                        @elseif($document->status_document === 'Proses Revisi' || $document->status_document === 'Revisi')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill fw-bolder">Revisi</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill fw-bolder">Lainnya</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary-subtle rounded-circle p-2 me-2">
                                                <i class="ti ti-user-check fs-3 text-primary"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $document->uploader->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="gap-2 d-flex justify-content-center">
                                            <a href="{{ route('documents.show', ['document' => $document->id]) }}"
                                                class="btn btn-primary-light btn-icon rounded-3" title="Lihat Detail">
                                                <i class="ti ti-eye fs-5"></i>
                                            </a>
                                            @if (!empty(array_intersect(['administrator', 'bagian-mutu', 'pengendali-dokumen', 'kepala-puskesmas'], $userRoles)) &&
                                                  $document->latestRevision->status === 'Disetujui' && $document->is_active)
                                                <button type="button" class="btn btn-warning-light btn-icon rounded-3" 
                                                    data-bs-toggle="modal" data-bs-target="#modalTolak"
                                                    data-id="{{ $document->currentRevision->id }}" title="Revisi Dokumen">
                                                    <i class="ti ti-refresh fs-5"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Professional Revision Modal -->
    <div class="modal fade" id="modalTolak" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="border-0 shadow-lg modal-content rounded-4">
                <div class="px-4 pt-4 pb-0 border-0 modal-header">
                    <h5 class="mb-0 fw-bolder d-flex align-items-center text-danger">
                        <i class="ti ti-alert-triangle fs-7 me-2"></i> Pengajuan Revisi Dokumen
                    </h5>
                </div>
                <div class="p-4 modal-body">
                    <form id="formTolak" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Pengajuan Revisi">
                        
                        <div class="p-3 mb-4 rounded-4 bg-light border-start border-danger border-4">
                            <h6 id="rev_judul_doc" class="mb-1 fw-bolder text-dark">-</h6>
                            <p id="rev_code_doc" class="mb-0 text-muted small">-</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alasan Pengajuan Revisi <span class="text-danger">*</span></label>
                            <textarea class="form-control rounded-3" name="reason" rows="4" placeholder="Jelaskan bagian mana yang perlu diperbaiki..." required></textarea>
                            <small class="text-muted">Komentar ini akan terlihat oleh pembuat dokumen.</small>
                        </div>

                        <div class="mt-4 gap-2 d-flex justify-content-end">
                            <button type="button" class="px-4 btn btn-light rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="px-4 btn btn-danger rounded-pill fw-bold">Konfirmasi Revisi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fs-8 { font-size: 1.5rem; }
        .bg-teal-subtle { background-color: #f0fdfa !important; }
        .text-teal { color: #14b8a6 !important; }
        .text-teal-emphasis { color: #0d9488 !important; }
        .bg-amber-subtle { background-color: #fffbeb !important; }
        .text-amber { color: #f59e0b !important; }
        .text-amber-emphasis { color: #b45309 !important; }
        
        /* Enterprise Command Center Styles */
        .search-command-bar, .search-precision-bar {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .search-command-bar:focus-within {
            border-color: #14b8a6;
            box-shadow: 0 10px 25px -5px rgba(20, 184, 166, 0.15) !important;
            transform: translateY(-2px);
        }
        .search-precision-bar:focus-within {
            border-color: #0284c7;
            box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.1) !important;
            transform: translateY(-2px);
        }
        .filter-select-wrapper {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: white;
            min-width: 180px;
        }
        .status-pill-group input:checked + .pill-item {
            background-color: #14b8a6 !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.25);
        }
        .pill-item { color: #64748b; transition: all 0.2s ease; }
        .pill-item:hover { color: #14b8a6; background-color: #f0fdfa; }
        
        .btn-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            transition: all 0.2s ease;
        }
        .btn-icon:hover { transform: translateY(-2px); }
        .btn-primary-light { background: #e0f2fe; color: #0284c7; border: none; }
        .btn-primary-light:hover { background: #bae6fd; color: #0369a1; }
        .btn-warning-light { background: #fef3c7; color: #d97706; border: none; }
        .btn-warning-light:hover { background: #fde68a; color: #b45309; }
        
        .transition-all { transition: all 0.2s ease; }
        #tableDocument tbody tr:hover {
            background-color: #f8fafc;
        }
    </style>

    @section('customJS')
        <script src="{{ asset('assets/js/datatablesDocuments.js') }}?v={{ time() }}"></script>
        <script>
            // Handle Modal data injection
            document.addEventListener('DOMContentLoaded', function() {
                const modalTolak = document.getElementById('modalTolak');
                if (modalTolak) {
                    modalTolak.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;
                        const docId = button.getAttribute('data-id');
                        const row = button.closest('tr');
                        const title = row.querySelector('.fs-4').innerText;
                        const code = row.querySelector('.fs-3').innerText;
                        
                        document.getElementById('rev_judul_doc').innerText = title;
                        document.getElementById('rev_code_doc').innerText = code;
                        document.getElementById('formTolak').action = `/document_approval/${docId}`;
                    });
                }
            });
        </script>
    @endsection
@endsection
