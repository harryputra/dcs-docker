@extends('layouts.layout_admin')

@section('title', 'Riwayat Dokumen')

@section('content')
    <div class="container-fluid pb-4">
        <!-- Audit Intelligence Header -->
        <div class="mb-5 border-0 shadow-sm card rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);">
            <div class="p-4 card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div>
                    <h2 class="mb-1 display-6 fw-bold text-dark">Audit Intelligence Center</h2>
                    <p class="mb-0 text-muted fs-4">Log aktivitas menyeluruh untuk pengawasan kepatuhan dan integritas sistem.</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <div class="px-3 py-2 bg-success-subtle text-success border border-success-subtle badge rounded-pill d-flex align-items-center">
                        <i class="ti ti-shield-check me-2 fs-5"></i>
                        <span class="fw-bold">Audit Real-time Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Metrics -->
        <div class="mb-5 row g-4">
            <div class="col-md-3">
                <div class="border-0 shadow-sm card rounded-4 h-100 border-bottom border-4 border-primary metric-card-interactive">
                    <div class="p-4 card-body">
                        <div class="mb-3 p-3 shadow-sm badge bg-primary-subtle text-primary rounded-3 badge-scaling">
                            <i class="ti ti-history fs-7"></i>
                        </div>
                        <p class="mb-1 text-uppercase fw-bold text-muted small letter-spacing-1">Total Aktivitas</p>
                        <h3 class="mb-0 fw-extrabold text-dark">{{ $documentHistories->count() }} <small class="text-muted fw-normal fs-3">Event</small></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border-0 shadow-sm card rounded-4 h-100 border-bottom border-4 border-success metric-card-interactive">
                    <div class="p-4 card-body">
                        <div class="mb-3 p-3 shadow-sm badge bg-success-subtle text-success rounded-3 badge-scaling">
                            <i class="ti ti-check fs-7"></i>
                        </div>
                        <p class="mb-1 text-uppercase fw-bold text-muted small letter-spacing-1">Disetujui Hari Ini</p>
                        <h3 class="mb-0 fw-extrabold text-dark">
                            {{ $documentHistories->where('action', 'Approved')->where('created_at', '>=', now()->startOfDay())->count() }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-end d-none d-md-flex align-items-center justify-content-end">
                <div class="text-end">
                    <p class="mb-1 fw-bold text-muted small">Integritas Data</p>
                    <div class="d-flex align-items-center justify-content-end text-success fw-bold">
                        <i class="ti ti-lock-check me-2 fs-5"></i>
                        <span>SHA-256 Verifikasi Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Matrix -->
        <div class="border-0 shadow-sm card rounded-4">
            <div class="p-4 card-body">
                <!-- Header Actions -->
                <div class="mb-4 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold text-dark">Log Kronologis</h5>
                    <div class="gap-3 d-flex align-items-center">
                        <div class="input-group input-group-modern shadow-sm" style="min-width: 300px;">
                            <span class="bg-white border-end-0 input-group-text"><i class="ti ti-search text-muted"></i></span>
                            <input type="text" id="customSearch" class="bg-white border-start-0 form-control fs-3" placeholder="Pencarian audit...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0" id="myTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3 ps-4">Judul Dokumen</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3">Pelaku (Performer)</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3 text-center">Revisi</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3 text-center">Aksi Otoritas</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3 text-center">Waktu & Tanggal</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3 text-end pe-4">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documentHistories as $history)
                                <tr class="transition-all">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 bg-light-subtle rounded-3 me-3 border border-light-subtle d-none d-lg-block">
                                                <i class="ti ti-file-analytics fs-6 text-primary"></i>
                                            </div>
                                            <span class="fw-bold text-dark fs-4">{{ $history->document->title }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="p-1 bg-white shadow-sm avatar-name me-3 rounded-circle border border-primary-subtle d-flex align-items-center justify-content-center fw-bold text-primary small" style="width: 32px; height: 32px; font-size: 10px;">
                                                {{ strtoupper(substr($history->performer->name, 0, 2)) }}
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark fs-3">{{ $history->performer->name }}</span>
                                                <span class="text-muted small">ID: #{{ str_pad($history->performer->id, 4, '0', STR_PAD_LEFT) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="px-3 py-1 badge bg-light text-primary border border-primary-subtle rounded-pill fw-bold">Rev {{ $history->revision->revision_number }}</span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $action = $history->action;
                                            $pillClass = 'bg-secondary-subtle text-secondary';
                                            $icon = 'ti-bolt';
                                            if($action === 'Created') { $pillClass = 'bg-primary-subtle text-primary border-primary-subtle'; $icon = 'ti-file-plus'; }
                                            elseif($action === 'Revised') { $pillClass = 'bg-warning-subtle text-warning border-warning-subtle'; $icon = 'ti-edit'; }
                                            elseif($action === 'Approved') { $pillClass = 'bg-success-subtle text-success border-success-subtle'; $icon = 'ti-circle-check'; }
                                            elseif($action === 'Rejected') { $pillClass = 'bg-danger-subtle text-danger border-danger-subtle'; $icon = 'ti-circle-x'; }
                                        @endphp
                                        <span class="badge rounded-pill {{ $pillClass }} px-3 py-2 fw-bold d-inline-flex align-items-center">
                                            <i class="ti {{ $icon }} me-1 fs-3"></i> {{ strtoupper($action) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark fs-3">{{ \Carbon\Carbon::parse($history->created_at)->format('H:i:s') }}</span>
                                            <span class="text-muted small">{{ \Carbon\Carbon::parse($history->created_at)->format('d F Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('document_histories.show', $history) }}"
                                            class="btn btn-primary-subtle btn-sm rounded-pill px-3 shadow-sm animate-on-hover border border-primary-subtle">
                                            <i class="ti ti-arrow-right fs-5"></i>
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

    <style>
        .metric-card-interactive { transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); }
        .metric-card-interactive:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important; }
        .badge-scaling { transition: transform 0.3s ease; }
        .metric-card-interactive:hover .badge-scaling { transform: scale(1.1) rotate(5deg); }
        
        .transition-all { transition: all 0.2s ease; }
        tr.transition-all:hover { background-color: #f8fafc !important; }
        
        .btn-primary-subtle { background-color: #f0fdf4; color: #14b8a6; }
        .btn-primary-subtle:hover { background-color: #14b8a6; color: white; }
        .animate-on-hover:hover { transform: translateY(-2px); }

        .input-group-modern { border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; }
        .input-group-modern input { border: none !important; box-shadow: none !important; padding: 10px 15px; }
        .input-group-modern:focus-within { border-color: #14b8a6; box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1); }
        
        .avatar-name { transition: all 0.3s ease; }
        tr:hover .avatar-name { background-color: #14b8a6 !important; color: white !important; transform: scale(1.1); }
    </style>
@endsection

@section('customJS')
    <script>
        $(document).ready(function() {
            const table = $('#myTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "order": [[4, "desc"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.5/i18n/id.json"
                },
                "dom": '<"top">rt<"bottom"ip><"clear">'
            });

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
@endsection
