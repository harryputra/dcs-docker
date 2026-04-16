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
                <div class="border-0 shadow-sm card h-100">
                    <div class="p-4 card-body d-flex flex-column justify-content-between">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <div class="p-3 badge bg-primary-subtle rounded-3">
                                <i class="ti ti-clipboard-heart fs-6 text-primary"></i>
                            </div>
                            <span class="badge bg-light text-muted fw-bold">TOTAL DATA</span>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted fw-bold">Total Arsip Dokumen</h6>
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
            <div class="mb-4 shadow-sm card">
                <div class="card-body">
                    <h2 class="mb-3 card-title fw-semibold">
                        <span>
                            <i class="ti ti-filter"></i>
                        </span> Filter Dokumen
                    </h2>
                    <div class="mb-3 row">
                        <div class="col-12">
                            <input id="searchCode" type="text" class="form-control form-control-md"
                                placeholder="Cari kode dokumen...">
                        </div>
                    </div>

                    {{-- Baris 2: Kategori dan Tahun --}}
                    <div class="mb-3 row">
                        <div class="mb-2 col-md-6 mb-md-0">
                            <select id="filterKategori" class="form-select form-select-md">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select id="filterTahun" class="form-select form-select-md">
                                <option value="">Semua Tahun</option>
                            </select>
                        </div>
                    </div>

                    {{-- Baris 3: Cari dokumen + Radio status --}}
                    <div class="row align-items-center">
                        <div class="mb-3 col-md-6 mb-md-0">
                            <input id="customSearch" type="text" class="form-control form-control-md"
                                placeholder="Cari dokumen...">
                        </div>
                        <div class="col-md-6">
                            <div id="statusFilter" class="flex-wrap gap-3 d-flex align-items-center">
                                <div class="m-0 form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterDocument" id="fAll"
                                        value="all" checked>
                                    <label class="form-check-label" for="fAll">Semua</label>
                                </div>
                                <div class="m-0 form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterDocument" id="fAktif"
                                        value="aktif">
                                    <label class="form-check-label" for="fAktif">Aktif</label>
                                </div>
                                <div class="m-0 form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterDocument" id="fKadaluarsa"
                                        value="kadaluarsa">
                                    <label class="form-check-label" for="fKadaluarsa">Kadaluarsa</label>
                                </div>
                                <div class="m-0 form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterDocument" id="fProsesRev"
                                        value="prosesrev">
                                    <label class="form-check-label" for="fProsesRev">Proses Revisi</label>
                                </div>
                                <div class="m-0 form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterDocument"
                                        id="fPengajuanRev" value="pengajuanrev">
                                    <label class="form-check-label" for="fPengajuanRev">Pengajuan Revisi</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4 shadow-sm card">
                <div class="card-body">
                    <h2 class="mb-3 card-title fw-semibold"><span>
                            <i class="ti ti-file"></i>
                        </span> Daftar Dokumen</h2>
                    <div class="mt-2 table-responsive">
                        <table id="tableDocument" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor Dokumen</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Uploader</th>
                                    <th>Tanggal Upload</th>
                                    <th>Tanggal Terbit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $document)
                                    <tr>
                                        <td>{{ $document->code ?? '-' }}</td>
                                        <td>{{ $document->title }}</td>
                                        <td>{{ $document->category->name }}</td>
                                        <td>
                                            @php
                                                $currentStatus =
                                                    $document->currentRevision->document_id === $document->id
                                                        ? $document->latestRevision->status
                                                        : 'Expired';
                                            @endphp
                                            <span
                                                class="badge
                            @if ($currentStatus === 'Draft') bg-light text-dark
                            @elseif ($currentStatus === 'Disetujui')
                                bg-success
                            @elseif ($currentStatus === 'Pengajuan Revisi' || $currentStatus === 'Proses Revisi')
                                bg-warning
                            @elseif ($currentStatus === 'Expired')
                                bg-danger @endif
                            ">
                                                {{ $document->latestRevision->status }}
                                            </span>
                                        </td>
                                        <td>{{ $document->uploader->name }}</td>
                                        <td>{{ $document->created_at ? \Carbon\Carbon::parse($document->created_at)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>{{ $document->published_date ? \Carbon\Carbon::parse($document->published_date)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            <div class="gap-1 d-flex">

                                                @canany(['edit-documents', 'edit-revisions'])
                                                    @if ($document->currentRevision->checkUploaderRoles())
                                                        <a href="{{ route('document_revision.show', ['documentRevision' => $document->currentRevision->latestRevision($document->id)->id]) }}"
                                                            class="btn btn-sm btn-admin" title="Lihat Detail">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    @elseif($document->is_active || $document->currentRevision->latestRevision($document->id)->status === 'Expired')
                                                        <a href="{{ route('documents.show', ['document' => $document->id]) }}"
                                                            class="btn btn-sm btn-admin" title="Lihat Detail">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if ($document->is_active || $document->currentRevision->latestRevision($document->id)->status === 'Expired')
                                                        <a href="{{ route('documents.show', ['document' => $document->id]) }}"
                                                            class="btn btn-sm btn-admin" title="Lihat Detail">
                                                            <i class="ti ti-eye"></i>
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
    </div>
    </div>
    </div>
    </div>
    </div>

@section('customJS')
    <script src="{{ asset('assets/js/datatablesDocuments.js') }}"></script>
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
