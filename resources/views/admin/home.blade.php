@extends('layouts.layout_admin')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Dashboard Hero Section -->
        <div class="mb-5 row">
            <div class="col-12">
                <div class="border-0 shadow-sm card bg-success-subtle rounded-3 overflow-hidden position-relative">
                    <div class="p-5 card-body z-1 position-relative">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <h1 class="mb-2 display-6 fw-bolder text-dark">
                                    @php
                                        $hour = date('H');
                                        if ($hour < 12) { $greeting = 'Selamat Pagi'; }
                                        elseif ($hour < 15) { $greeting = 'Selamat Siang'; }
                                        elseif ($hour < 18) { $greeting = 'Selamat Sore'; }
                                        else { $greeting = 'Selamat Malam'; }
                                    @endphp
                                    {{ $greeting }}, {{ auth()->user()->name }}!
                                </h1>
                                <p class="mb-4 text-muted fs-4 font-monospace" style="letter-spacing: -0.01em;">Pusat Pengelolaan Dokumen <span class="text-success fw-bold">Garuda DCS</span>. Menjaga integritas data layanan kesehatan Anda tetap aman, terstruktur, dan akuntabel.</p>
                                <div class="gap-3 d-flex flex-wrap">
                                    <div class="px-3 py-2 bg-white rounded-pill d-flex align-items-center shadow-sm border border-success-subtle">
                                        <i class="ti ti-calendar-event me-2 text-success"></i>
                                        <span class="text-dark fw-bold" style="font-size: 0.85rem;">{{ date('d F Y') }}</span>
                                    </div>
                                    @foreach(auth()->user()->roles as $role)
                                        <div class="px-3 py-2 bg-white rounded-pill d-flex align-items-center shadow-sm border border-success-subtle">
                                            <i class="ti ti-heart-medical me-2 text-danger"></i>
                                            <span class="text-dark fw-bold" style="font-size: 0.85rem;">{{ $role->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-5 d-none d-md-block text-end">
                                <img src="{{ asset('assets/images/backgrounds/medical_dashboard_hero.png') }}" alt="Medical Dashboard" class="img-fluid" style="max-height: 220px; opacity: 1;">
                            </div>
                        </div>
                    </div>
                    <!-- Medical Pulse Decoration -->
                    <div class="position-absolute bottom-0 start-0 w-100 opacity-10">
                        <svg viewBox="0 0 1000 100" xmlns="http://www.w3.org/2000/svg">
                            <path class="pulse-line" d="M0 50 L100 50 L120 20 L140 80 L160 50 L300 50 L320 10 L340 90 L360 50 L1000 50" stroke="#198754" stroke-width="2" fill="none" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Overview -->
        <div class="mb-4 row">
            <!-- Total Documents -->
            <div class="col-md-3">
                <div class="border-0 shadow-sm card h-100 border-start border-4 border-primary">
                    <div class="p-4 card-body d-flex flex-column justify-content-between">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <div class="p-3 badge bg-primary-subtle rounded-3">
                                <i class="ti ti-clipboard-heart fs-6 text-primary"></i>
                            </div>
                            <span class="badge bg-primary text-white fw-bold">TOTAL DATA</span>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted fw-bold">Arsip Dokumen</h6>
                            <h3 class="mb-0 fw-bolder text-dark">{{ $totalDocs }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Documents -->
            <div class="col-md-3">
                <div class="border-0 shadow-sm card h-100 border-start border-4 border-success">
                    <div class="p-4 card-body d-flex flex-column justify-content-between">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <div class="p-3 badge bg-success-subtle rounded-3">
                                <i class="ti ti-activity fs-6 text-success"></i>
                            </div>
                            <span class="badge bg-success text-white fw-bold">OPERASIONAL</span>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted fw-bold">Dokumen Aktif</h6>
                            <h3 class="mb-0 fw-bolder text-dark">{{ $totalApprovedDocs }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Review -->
            <div class="col-md-3">
                <div class="border-0 shadow-sm card h-100 border-start border-4 border-warning">
                    <div class="p-4 card-body d-flex flex-column justify-content-between">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <div class="p-3 badge bg-warning-subtle rounded-3">
                                <i class="ti ti-microscope fs-6 text-warning"></i>
                            </div>
                            <span class="badge bg-warning text-white fw-bold">REVIEW MUTU</span>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted fw-bold">Proses Verifikasi</h6>
                            <h3 class="mb-0 fw-bolder text-dark">{{ $totalRevisedDocs }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expired Documents -->
            <div class="col-md-3">
                <div class="border-0 shadow-sm card h-100 border-start border-4 border-danger">
                    <div class="p-4 card-body d-flex flex-column justify-content-between">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <div class="p-3 badge bg-danger-subtle rounded-3">
                                <i class="ti ti-emergency-bed fs-6 text-danger"></i>
                            </div>
                            <span class="badge bg-danger text-white fw-bold">INAKTIF</span>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted fw-bold">Dokumen Expired</h6>
                            <h3 class="mb-0 fw-bolder text-dark">{{ $totalDeniedDocs }}</h3>
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
                                <input id="customSearch" type="text" class="bg-white border-0 form-control fs-4 py-3" 
                                    placeholder="Cari Judul, Kode, atau Kata Kunci Dokumen...">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group input-group-lg search-precision-bar shadow-sm">
                                <span class="bg-white border-0 input-group-text ps-3">
                                    <i class="ti ti-hash text-muted fs-5"></i>
                                </span>
                                <input id="searchCode" type="text" class="bg-white border-0 form-control fs-4" 
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
                                    <span class="px-4 py-2 pill-item d-inline-block rounded-pill fw-bold small">Aktif</span>
                                </label>
                                <label class="mb-0 cursor-pointer">
                                    <input type="radio" name="filterDocument" id="fKadaluarsa" value="kadaluarsa" class="d-none">
                                    <span class="px-4 py-2 pill-item d-inline-block rounded-pill fw-bold small">Expired</span>
                                </label>
                                <label class="mb-0 cursor-pointer">
                                    <input type="radio" name="filterDocument" id="fProsesRev" value="prosesrev" class="d-none">
                                    <span class="px-4 py-2 pill-item d-inline-block rounded-pill fw-bold small">Revisi</span>
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
        <div class="border-0 shadow-sm card rounded-4">
            <div class="p-4 card-body">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <i class="ti ti-file-description fs-6 text-primary me-2"></i> Daftar Dokumen Terbaru
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableDocument">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Kode Dokumen</th>
                                <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Judul</th>
                                <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Kategori</th>
                                <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3 text-center">Status</th>
                                <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Uploader</th>
                                <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                                @php
                                    $currentStatus = $document->currentRevision->document_id === $document->id
                                        ? $document->latestRevision->status
                                        : 'Expired';
                                @endphp
                                <tr class="transition-all">
                                    <td class="fw-bold text-dark fs-3">{{ $document->code ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark fs-4 mb-1">{{ $document->title }}</span>
                                            <small class="text-muted">
                                                <i class="ti ti-calendar-event me-1"></i>Terbit: {{ $document->published_date ? \Carbon\Carbon::parse($document->published_date)->format('d/m/Y') : '-' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark-emphasis border px-3 rounded-pill fw-semibold">{{ $document->category->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($currentStatus === 'Disetujui' || $currentStatus === 'Aktif')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bolder">Aktif</span>
                                        @elseif ($currentStatus === 'Expired' || $currentStatus === 'Kadaluarsa')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-bolder">Expired</span>
                                        @elseif ($currentStatus === 'Pengajuan Revisi' || $currentStatus === 'Proses Revisi')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill fw-bolder">Revisi</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill fw-bolder">{{ $currentStatus }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $document->uploader->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="gap-2 d-flex justify-content-center">
                                            @canany(['edit-documents', 'edit-revisions'])
                                                @if ($document->currentRevision->checkUploaderRoles())
                                                    <a href="{{ route('document_revision.show', ['documentRevision' => $document->currentRevision->latestRevision($document->id)->id]) }}"
                                                        class="btn btn-primary-light btn-icon rounded-3" title="Lihat Detail">
                                                        <i class="ti ti-eye fs-5"></i>
                                                    </a>
                                                @elseif($document->is_active || $document->currentRevision->latestRevision($document->id)->status === 'Expired')
                                                    <a href="{{ route('documents.show', ['document' => $document->id]) }}"
                                                        class="btn btn-primary-light btn-icon rounded-3" title="Lihat Detail">
                                                        <i class="ti ti-eye fs-5"></i>
                                                    </a>
                                                @endif
                                            @else
                                                @if ($document->is_active || $document->currentRevision->latestRevision($document->id)->status === 'Expired')
                                                    <a href="{{ route('documents.show', ['document' => $document->id]) }}"
                                                        class="btn btn-primary-light btn-icon rounded-3" title="Lihat Detail">
                                                        <i class="ti ti-eye fs-5"></i>
                                                    </a>
                                                @endif
                                            @endcanany
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

    <style>
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
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        .btn-primary-light { background: #e0f2fe; color: #0284c7; border: none; }
        .transition-all { transition: all 0.2s ease; }
        #tableDocument tbody tr:hover {
            background-color: #f8fafc;
        }
    </style>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>

@section('customJS')
    <script src="{{ asset('assets/js/datatablesDocuments.js') }}?v={{ time() }}"></script>
@endsection

<style>
    .bg-primary-subtle { background-color: #e0e7ff !important; }
    .bg-success-subtle { background-color: #dcfce7 !important; }
    .bg-warning-subtle { background-color: #fef9c3 !important; }
    .bg-danger-subtle { background-color: #fee2e2 !important; }
    .bg-info-subtle { background-color: #e0f2fe !important; }
    
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }
    
    .display-6 {
        letter-spacing: -0.02em;
    }
    
    .pulse-line {
        stroke-dasharray: 1000;
        stroke-dashoffset: 2000;
        animation: pulse-move 10s linear infinite;
    }
    @keyframes pulse-move {
        0% { stroke-dashoffset: 2000; }
        100% { stroke-dashoffset: 0; }
    }
</style>
@endsection
