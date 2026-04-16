@extends('layouts.layout_admin')

@section('title', 'Detail Dokumen')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
<style>
    /* Stepper Refinement */
    .stepper-premium {
        display: flex;
        justify-content: space-between;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    .stepper-item {
        position: relative;
        flex: 1;
        text-align: center;
    }
    .stepper-item::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 50%;
        width: 100%;
        height: 3px;
        background-color: #f1f5f9;
        z-index: 1;
    }
    .stepper-item:last-child::before { display: none; }
    .stepper-item.completed::before { background-color: #14b8a6; }
    
    .stepper-icon {
        position: relative;
        z-index: 2;
        width: 44px;
        height: 44px;
        background-color: #f1f5f9;
        color: #94a3b8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        transition: all 0.3s ease;
        border: 4px solid #fff;
        box-shadow: 0 0 0 1px #e2e8f0;
    }
    .stepper-item.completed .stepper-icon {
        background-color: #14b8a6;
        color: #fff;
        box-shadow: 0 0 0 1px #14b8a6, 0 4px 10px rgba(20, 184, 166, 0.3);
    }
    .stepper-label { font-size: 0.85rem; font-weight: 700; color: #475569; display: block; }
    .stepper-date { font-size: 0.75rem; color: #94a3b8; }

    /* Identity Matrix Styles */
    .info-matrix-item {
        padding: 16px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        height: 100%;
        transition: all 0.2s ease;
    }
    .info-matrix-item:hover { background: #fff; border-color: #14b8a6; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
    .matrix-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
    .matrix-label { font-size: 0.75rem; text-uppercase; letter-spacing: 0.05em; font-weight: 800; color: #94a3b8; margin-bottom: 4px; display: block; }
    .matrix-value { font-size: 1rem; font-weight: 600; color: #1e293b; word-break: break-word; }

    /* Action Hub Card */
    .action-hub-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #fff;
        border-radius: 20px;
        overflow: hidden;
    }
    .action-hub-btn {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .btn-view-premium { background: #14b8a6; color: white; border: none; }
    .btn-view-premium:hover { background: #0d9488; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(20, 184, 166, 0.4); }
    .btn-download-premium { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); }
    .btn-download-premium:hover { background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.4); }

    /* Relation Link */
    .relation-link {
        padding: 12px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid #e2e8f0;
        display: block;
        transition: all 0.2s ease;
        text-decoration: none !important;
        margin-bottom: 8px;
    }
    .relation-link:hover { border-color: #14b8a6; background: #f0fdfa; transform: translateX(5px); }
</style>
@section('content')
    @php
        $is_active =
            $document->currentRevision->latestRevision($document->id)->status === 'Disetujui' && $document->is_active;
        $currentStatus = $document->currentRevision->latestRevision($document->id)->status;
    @endphp
    <div class="container-fluid pb-5">
        <!-- Hero Header Document -->
        <div class="mb-4 col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative" style="background: linear-gradient(to right, #ffffff, #f8fafc);">
                <div class="p-4 card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between z-1">
                    <div class="mb-3 mb-md-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2">
                                <li class="breadcrumb-item small"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item small"><a href="{{ route('document.active') }}" class="text-muted">Repository</a></li>
                                <li class="breadcrumb-item small active text-primary" aria-current="page">Detail Dokumen</li>
                            </ol>
                        </nav>
                        <h2 class="mb-1 display-6 fw-bold text-dark">{{ $document->title }}</h2>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill fw-bold me-3">
                                <i class="ti ti-hash me-1"></i>{{ $document->code }}
                            </span>
                            @if($document->status_document === 'Aktif')
                                <span class="badge bg-success text-white px-3 py-1 rounded-pill fw-bolder animate-pulse">AKTIF</span>
                            @else
                                <span class="badge bg-danger text-white px-3 py-1 rounded-pill fw-bolder">{{ strtoupper($document->status_document) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="position-absolute end-0 top-0 h-100 d-none d-lg-flex align-items-center pe-5 opacity-10">
                    <i class="ti ti-file-certificate" style="font-size: 10rem;"></i>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Tracking Pulse (Stepper) -->
                @can('view-revisions')
                    @if ($document->currentRevision->checkUploaderRoles())
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="p-4 card-body">
                                <h6 class="mb-4 text-uppercase fw-extrabold text-muted small letter-spacing-1">
                                    <i class="ti ti-route me-2 text-success"></i> Alur Verifikasi & Validasi
                                </h6>
                                <div class="stepper-premium">
                                    <div class="stepper-item @if($document->currentRevision) completed @endif">
                                        <div class="stepper-icon"><i class="bi bi-file-earmark-plus"></i></div>
                                        <span class="stepper-label">Draft</span>
                                        <small class="stepper-date">{{ $document->currentRevision->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <div class="stepper-item @if($document->currentRevision->acc_format) completed @endif">
                                        <div class="stepper-icon"><i class="bi bi-layout-text-window"></i></div>
                                        <span class="stepper-label">Format</span>
                                        <small class="stepper-date">{{ empty($document->currentRevision->accFormat()) ? '-' : $document->currentRevision->latestRevision()->accFormat()->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <div class="stepper-item @if($document->currentRevision->acc_content) completed @endif">
                                        <div class="stepper-icon"><i class="bi bi-journal-text"></i></div>
                                        <span class="stepper-label">Konten</span>
                                        <small class="stepper-date">{{ empty($document->currentRevision->accContent()) ? '-' : $document->currentRevision->latestRevision()->accContent()->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <div class="stepper-item @if($document->is_active) completed @endif">
                                        <div class="stepper-icon"><i class="bi bi-check2-all"></i></div>
                                        <span class="stepper-label">Selesai</span>
                                        <small class="stepper-date">{{ empty($document->currentRevision->accKepalaPuskesmas()) ? '-' : $document->currentRevision->latestRevision()->accKepalaPuskesmas()->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endcan

                <!-- Identity Matrix Card -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="p-4 card-body">
                        <h6 class="mb-4 text-uppercase fw-extrabold text-muted small letter-spacing-1">
                            <i class="ti ti-info-square me-2 text-primary"></i> Atribut Matriks Dokumen
                        </h6>

                        @if ($document->status_document === 'Diganti')
                            <div class="p-3 mb-4 rounded-3 bg-warning-subtle border border-warning shadow-sm">
                                <div class="d-flex">
                                    <i class="ti ti-rotate fs-6 text-warning me-3"></i>
                                    <div>
                                        <strong class="text-warning-emphasis">Status: Diganti</strong>
                                        <p class="mb-0 text-dark">Keterangan: Dokumen ini telah diperbarui oleh dokumen baru.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6 col-lg-4">
                                <div class="info-matrix-item">
                                    <div class="matrix-icon bg-blue-subtle text-blue"><i class="ti ti-category"></i></div>
                                    <span class="matrix-label">Kategori</span>
                                    <span class="matrix-value text-dark">{{ $document->category->name }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="info-matrix-item">
                                    <div class="matrix-icon bg-green-subtle text-green"><i class="ti ti-user-edit"></i></div>
                                    <span class="matrix-label">Pengunggah</span>
                                    <span class="matrix-value text-dark">{{ $document->uploader->name }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="info-matrix-item">
                                    <div class="matrix-icon bg-purple-subtle text-purple"><i class="ti ti-calendar-check"></i></div>
                                    <span class="matrix-label">Terbit</span>
                                    <span class="matrix-value text-dark">{{ $document->published_date ? \Carbon\Carbon::parse($document->published_date)->format('d F Y') : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="info-matrix-item">
                                    <div class="matrix-icon bg-orange-subtle text-orange"><i class="ti ti-notes"></i></div>
                                    <span class="matrix-label">Deskripsi & Catatan</span>
                                    <p class="matrix-value text-dark mb-0">{{ $document->currentRevision->latestRevision($document->id)->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Action Hub (File Controls) -->
                <div class="card border-0 shadow-sm rounded-4 action-hub-card mb-4">
                    <div class="p-4 card-body">
                        <div class="mb-4 text-center">
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center bg-white rounded-circle" style="width: 80px; height: 80px; box-shadow: 0 0 20px rgba(20, 184, 166, 0.4);">
                                <i class="ti ti-file-type-pdf text-danger fs-9"></i>
                            </div>
                            <h5 class="mb-1 text-white fw-bold">Kontrol Berkas Digital</h5>
                            <p class="text-white-50 small mb-0">Kelola akses dan versi dokumen Anda</p>
                        </div>

                        <div class="gap-3 d-flex flex-column">
                            <button type="button" 
                                onclick="previewDocument('{{ route('document.preview', ['revision' => $document->currentRevision->id]) }}')"
                                class="action-hub-btn btn-view-premium shadow-sm">
                                <i class="ti ti-eye fs-5"></i> Lihat Dokumen
                            </button>
                            
                            <a href="{{ route('document_revision.show-file', ['filename' => $document->currentRevision->latestRevision($document->id)->file_path]) }}"
                                class="action-hub-btn btn-download-premium" download target="_blank">
                                <i class="ti ti-download fs-5"></i> Unduh PDF
                            </a>

                            @canany(['edit-documents', 'edit-revisions'])
                                @if (in_array($document->currentRevision->latestRevision($document->id)->status, ['Disetujui','Draft','Pengajuan Revisi']) && $document->currentRevision->checkUploaderRoles())
                                    <hr class="my-2 border-white opacity-10">
                                    <a href="{{ route('document_revision.edit', $document->currentRevision) }}" 
                                        class="action-hub-btn btn-light text-dark shadow-sm">
                                        <i class="ti ti-edit fs-5"></i> Perbarui Versi
                                    </a>
                                @endif
                            @endcanany
                        </div>
                    </div>
                </div>

                <!-- Related Links Card -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="p-4 card-body">
                        <h6 class="mb-4 text-uppercase fw-extrabold text-muted small letter-spacing-1">
                            <i class="ti ti-link me-2 text-danger"></i> Relasi & Referensi
                        </h6>

                        <div class="mb-4">
                            <span class="mb-2 matrix-label">MENGGANTIKAN / MENGUBAH</span>
                            @foreach ($document->currentRevision->latestRevision($document->id)->revisedDocument() as $doc)
                                <a href="{{ route('documents.show', ['document' => $doc->id]) }}" class="relation-link">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-subtle rounded-3 p-2 me-3">
                                            <i class="ti ti-file-text text-primary"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="text-dark fw-bold text-truncate" style="max-width: 180px;">{{ $doc->title }}</div>
                                            <small class="text-muted">{{ $doc->code }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                            @if (count($document->currentRevision->latestRevision($document->id)->revisedDocument()) == 0)
                                <div class="p-3 text-center border rounded-3 border-dashed">
                                    <span class="text-muted small italic">Tidak ada referensi dokumen lama</span>
                                </div>
                            @endif
                        </div>

                        @if (!$is_active && $document->currentRevision->latestRevision($document->id)->status !== 'Proses Revisi')
                            <div class="mb-2">
                                <span class="mb-2 matrix-label">DIGANTI OLEH (DOKUMEN BARU)</span>
                                <a href="{{ route('documents.show', ['document' => $document->currentRevision->document->id]) }}" class="relation-link border-warning">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning-subtle rounded-3 p-2 me-3">
                                            <i class="ti ti-file-star text-warning"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="text-dark fw-bold text-truncate" style="max-width: 180px;">{{ $document->currentRevision->document->title }}</div>
                                            <small class="text-muted">Versi Terbaru</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @can('view-histories')
                @php
                    $reviserRole = $document->uploader->roles->pluck('id');
                    $userRoles = auth()->user()->roles->pluck('id');

                    $rightRole = $reviserRole->intersect($userRoles)->isNotEmpty();
                @endphp
                @if ($rightRole)
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <h5 class="mb-4 card-title fw-semibold">
                                    <i class="fa fa-history me-2"></i> Riwayat Revisi
                                </h5>

                                <div class="table-responsive">
                                    <table id="revisionTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No. Revisi</th>
                                                <th>Pengunggah</th>
                                                <th>Tanggal Revisi</th>
                                                <th>Status</th>
                                                <th>Berkas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($document->revisions->sortByDesc('created_at') as $rev)
                                                <tr>
                                                    <td>{{ $rev->revision_number }}</td>
                                                    <td>{{ $rev->reviser->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($rev->created_at)->format('H:i:s-d/m/Y') }}
                                                    </td>
                                                    <td><span
                                                            class="badge p-2
                                                @if ($rev->status === 'Disetujui') bg-admin
                                                @elseif($rev->status === 'Proses Revisi')
                                                    bg-warning
                                                @elseif ($rev->status === 'Expired')
                                                    bg-danger @endif
                                                ">{{ $rev->status }}</span>
                                                    </td>
                                                    <td>
                                                        <button type="button" 
                                                            onclick="previewDocument('{{ route('document.preview', ['revision' => $rev->id]) }}')"
                                                            class="btn btn-sm btn-admin" title="Lihat File">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                        <a href="{{ route('document_revision.show-file', ['filename' => $rev->file_path]) }}"
                                                            class="btn btn-sm btn-info" title="Download File" download
                                                            target="_blank">
                                                            <i class="ti ti-download"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan
        </div>
    </div>
    @include('components.pdf-preview-modal')
@endsection
