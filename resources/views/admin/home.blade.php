@extends('layouts.layout_admin')

@section('title', 'Document')

@section('content')
    <div class="container-fluid">
        <div class="mt-5 row">
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="overflow-hidden card">
                            <div class="p-4 card-body">
                                <h5 class="card-title mb-9 fw-semibold">Selamat Datang Kembali !</h5>
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h4 class="mb-3 fw-semibold">{{ auth()->user()->name }}</h4>

                                        <div class="d-flex align-items-center">
                                            <div class="me-4">
                                                <span class="fs-2"><a href="{{ route('profile') }}"
                                                        class="m-1 btn btn-admin">Settings</a></span>
                                            </div>
                                            <div>
                                                <span class="fs-2"><button type="button" class="m-1 btn btn-approver"
                                                        onclick="document.getElementById('logout-form').submit();">Logout</button></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 row">
            <div class="col-sm-6 col-xl-3">
                <div class="overflow-hidden card rounded-2">
                    <div class="p-4 pt-3 card-body">
                        <h5 class="card-title mb-9 fw-semibold">
                            <span>
                                <i class="ti ti-files"></i>
                            </span>
                            Total Dokumen
                        </h5>
                        <div class="col-8">
                            <h4 class="mb-3 fw-semibold">{{ $totalDocs }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="overflow-hidden card rounded-2">
                    <div class="p-4 pt-3 card-body">
                        <h5 class="card-title mb-9 fw-semibold">
                            <span class="text-success">
                                <i class="ti ti-checkbox"></i>
                            </span>
                            Dokumen Aktif
                        </h5>
                        <div class="col-8">
                            <h4 class="mb-3 fw-semibold">{{ $totalApprovedDocs }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="overflow-hidden card rounded-2">
                    <div class="p-4 pt-3 card-body">
                        <h5 class="card-title mb-9 fw-semibold">
                            <span class="text-warning">
                                <i class="ti ti-clock"></i>
                            </span>
                            Proses Pengajuan
                        </h5>
                        <div class="col-8">
                            <h4 class="mb-3 fw-semibold">{{ $totalRevisedDocs }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="overflow-hidden card rounded-2">
                    <div class="p-4 pt-3 card-body">
                        <h5 class="card-title mb-9 fw-semibold">
                            <span class="text-danger">
                                <i class="ti ti-x"></i>
                            </span>
                            Dokumen Expired
                        </h5>
                        <div class="col-8">
                            <h4 class="mb-3 fw-semibold">{{ $totalDeniedDocs }}</h4>
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
                                                        @if (
                                                            $document->currentRevision->document_id === $document->id &&
                                                                ($document->latestHistory->revision->status == 'Disetujui' ||
                                                                    $document->latestHistory->revision->status == 'Pengajuan Revisi'))
                                                            <a href="{{ route('document_revision.edit', $document->latestHistory->revision->id) }}"
                                                                class="btn btn-sm btn-approver" title="Revisi Dokumen">
                                                                <i class="ti ti-pencil"></i>
                                                            </a>
                                                        @endif
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
@endsection
