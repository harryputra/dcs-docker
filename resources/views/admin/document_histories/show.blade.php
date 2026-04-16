@extends('layouts.layout_admin')

@section('title', 'Detail Riwayat Dokumen')

@section('content')
    <div class="container-fluid pb-5">
        <!-- Event Hero Section -->
        @php
            $action = $documentHistory->action;
            $accentClass = 'primary';
            $icon = 'ti-file-plus';
            if($action === 'Revised') { $accentClass = 'warning'; $icon = 'ti-edit'; }
            elseif($action === 'Approved') { $accentClass = 'success'; $icon = 'ti-circle-check'; }
            elseif($action === 'Rejected') { $accentClass = 'danger'; $icon = 'ti-circle-x'; }
        @endphp

        <div class="mb-5 border-0 shadow-sm card rounded-4 overflow-hidden border-start border-5 border-{{ $accentClass }}">
            <div class="p-4 card-body d-flex flex-column flex-md-row align-items-center">
                <div class="p-3 shadow-sm bg-{{ $accentClass }}-subtle text-{{ $accentClass }} rounded-4 me-md-4 mb-3 mb-md-0">
                    <i class="ti {{ $icon }} display-6"></i>
                </div>
                <div class="text-center text-md-start">
                    <h5 class="mb-1 text-uppercase fw-bold text-muted small letter-spacing-1">Log Kejadian Audit</h5>
                    <h2 class="mb-0 display-6 fw-bold text-dark">{{ $action }} Dokumen</h2>
                    <p class="mb-0 text-muted fs-4">{{ $documentHistory->document->title }}</p>
                </div>
                <div class="ms-md-auto mt-3 mt-md-0">
                    <span class="px-3 py-2 badge bg-white border text-muted rounded-pill shadow-sm">
                        ID Event: #TRX-{{ str_pad($documentHistory->id, 6, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Document Identity -->
            <div class="col-lg-8">
                <div class="border-0 shadow-sm card rounded-4 h-100">
                    <div class="p-4 card-header bg-white border-0">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="ti ti-file-certificate me-2 text-primary"></i> Identitas Aset & Revisi
                        </h5>
                    </div>
                    <div class="p-4 card-body pt-0">
                        <div class="mb-4 p-3 rounded-4 bg-light d-flex align-items-center">
                            <div class="p-2 bg-white shadow-sm rounded-3 me-3">
                                <i class="ti ti-hash fs-6 text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-bold text-uppercase">Nomor Dokumen</small>
                                <p class="mb-0 fw-bold text-dark fs-5">{{ $documentHistory->document->code }}</p>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="mb-1 text-muted small fw-bold uppercase">PENYUSUN (REVISER)</label>
                                <div class="d-flex align-items-center">
                                    <div class="p-2 bg-primary-subtle text-primary rounded-circle me-3 fw-bold small">
                                        {{ strtoupper(substr($documentHistory->revision->reviser->name, 0, 2)) }}
                                    </div>
                                    <span class="fw-bold text-dark">{{ $documentHistory->revision->reviser->name }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <label class="mb-1 text-muted small fw-bold uppercase">ITERASI REVISI</label>
                                <h4 class="mb-0 fw-extrabold text-primary">REV {{ $documentHistory->revision->revision_number }}</h4>
                            </div>
                        </div>

                        <hr class="my-4 border-light">

                        <div class="mb-0">
                            <label class="mb-2 text-muted small fw-bold uppercase d-block">CATATAN & JUSTIFIKASI</label>
                            <div class="p-4 bg-light rounded-4 border border-light-subtle">
                                <i class="ti ti-quote text-muted fs-7 opacity-25 float-end"></i>
                                <p class="mb-0 text-dark fs-4 italic">
                                    @if($documentHistory->reason)
                                        {{ $documentHistory->reason }}
                                    @else
                                        <span class="text-muted">-- Tidak ada catatan tambahan untuk kejadian ini --</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Logistics -->
            <div class="col-lg-4">
                <div class="border-0 shadow-sm card rounded-4 h-100">
                    <div class="p-4 card-header bg-white border-0">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="ti ti-rocket me-2 text-primary"></i> Logistik Kejadian
                        </h5>
                    </div>
                    <div class="p-4 card-body pt-0">
                        <div class="mb-4 d-flex align-items-center">
                            <div class="p-3 bg-{{ $accentClass }}-subtle text-{{ $accentClass }} rounded-4 me-3">
                                <i class="ti ti-user-check fs-6"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-bold d-block">DIPROSES OLEH</small>
                                <span class="fw-bold text-dark fs-4">{{ $documentHistory->performer->name }}</span>
                            </div>
                        </div>

                        <div class="mb-4 d-flex align-items-center">
                            <div class="p-3 bg-light rounded-4 me-3 text-muted">
                                <i class="ti ti-clock fs-6"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-bold d-block">WAKTU EKSEKUSI</small>
                                <span class="fw-bold text-dark fs-4">{{ \Carbon\Carbon::parse($documentHistory->created_at)->format('H:i:s') }}</span>
                            </div>
                        </div>

                        <div class="mb-4 d-flex align-items-center">
                            <div class="p-3 bg-light rounded-4 me-3 text-muted">
                                <i class="ti ti-calendar-event fs-6"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-bold d-block">TANGGAL KEJADIAN</small>
                                <span class="fw-bold text-dark fs-4">{{ \Carbon\Carbon::parse($documentHistory->created_at)->format('d F Y') }}</span>
                            </div>
                        </div>

                        <div class="mt-5">
                            <a href="{{ route('document_histories.index') }}" class="btn btn-light w-100 rounded-pill mb-3 fw-bold border shadow-sm">
                                <i class="ti ti-arrow-left me-1"></i> Kembali ke Log
                            </a>
                            <a href="{{ route('documents.show', $documentHistory->document->id) }}" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm animate-on-hover">
                                <i class="ti ti-file-search me-1"></i> Lihat Dokumen Saat Ini
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .letter-spacing-1 { letter-spacing: 0.1em; }
        .bg-primary-subtle { background-color: #f0fdf4 !important; color: #14b8a6 !important; }
        .text-primary { color: #14b8a6 !important; }
        .btn-primary { background-color: #14b8a6; border-color: #14b8a6; }
        .btn-primary:hover { background-color: #0d9488; border-color: #0d9488; }
        .animate-on-hover:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(20, 184, 166, 0.2) !important; }
    </style>
@endsection
