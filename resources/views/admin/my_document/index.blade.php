@extends('layouts.layout_admin')

@section('title', 'Dokumen Anda')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 border-start border-4 border-info rounded-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-4 pb-3 border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="fw-bolder mb-1 text-dark d-flex align-items-center gap-2">
                                    <i class="ti ti-folder-star text-info"></i> Daftar Dokumen Anda
                                </h3>
                                <p class="text-muted small mb-0">Pantau dan kelola seluruh dokumen yang Anda unggah beserta statusnya.</p>
                            </div>
                        </div>

                        @can('create-documents')
                            <div class="mb-4 d-flex justify-content-end gap-2">
                                <a href="{{ route('documents.create') }}" class="btn btn-admin d-flex align-items-center rounded-pill px-4 shadow-sm">
                                    <i class="ti ti-file-upload me-2 fs-5"></i>
                                    Unggah Dokumen Baru
                                </a>
                                <a href="{{ route('document_revision.create') }}"
                                    class="btn btn-secondary d-flex align-items-center rounded-pill px-4 shadow-sm">
                                    <i class="ti ti-refresh me-2 fs-5"></i>
                                    Perbarui Dokumen (Revisi)
                                </a>
                            </div>
                        @endcan


                        <div class="table-responsive">
                            <table id="myTable" class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Kode Dokumen</th>
                                        <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Judul Dokumen</th>
                                        <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Kategori</th>
                                        <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3 text-center">Status</th>
                                        <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Pengunggah</th>
                                        <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Dibuat</th>
                                        <th class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3">Terbit</th>
                                        <th style="width: 120px;" class="text-uppercase text-muted fw-bold fs-2 border-bottom-0 pb-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documents as $document)
                                        <tr class="transition-all">
                                            <td class="fw-bold text-dark fs-3">{{ $document->code ?? '-' }}</td>
                                            <td class="fw-bold text-dark fs-4">{{ $document->title }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark-emphasis border border-secondary-subtle px-3 py-1 rounded-pill fw-semibold">{{ $document->category->name }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $currentStatus = 'Expired';
                                                    if (
                                                        $document->currentRevision &&
                                                        $document->currentRevision->document_id === $document->id &&
                                                        $document->latestRevision
                                                    ) {
                                                        $currentStatus = $document->latestRevision->status;
                                                    }
                                                @endphp
                                                <span
                                                    class="badge border px-3 py-2 rounded-pill fw-bolder
                                        @if ($currentStatus === 'Draft') bg-light text-dark border-secondary-subtle
                                        @elseif ($currentStatus === 'Disetujui') bg-success-subtle text-success border-success-subtle
                                        @elseif ($currentStatus === 'Pengajuan Revisi' || $currentStatus === 'Proses Revisi') bg-warning-subtle text-warning border-warning-subtle
                                        @elseif ($currentStatus === 'Expired') bg-danger-subtle text-danger border-danger-subtle @endif
                                        ">
                                                    {{ $currentStatus }}
                                                </span>
                                            </td>
                                            <td class="fw-semibold text-dark">{{ $document->uploader->name }}</td>
                                            <td class="text-muted"><i class="ti ti-calendar-event me-1"></i>{{ $document->created_at ? \Carbon\Carbon::parse($document->created_at)->format('d/m/Y') : '-' }}</td>
                                            <td class="text-muted"><i class="ti ti-calendar-event me-1"></i>{{ $document->published_date ? \Carbon\Carbon::parse($document->published_date)->format('d/m/Y') : '-' }}</td>
                                            <td class="text-center">
                                                @php
                                                    $isAdmin = auth()->user()->roles->contains('slug', 'administrator');
                                                    $isOwner = $document->uploaded_by === auth()->id();
                                                @endphp

                                                @canany(['edit-documents', 'edit-revisions'])
                                                    @if ($document->latestHistory && $document->latestHistory->revision)
                                                        <div class="gap-2 d-flex justify-content-center">
                                                            <a href="{{ route('document_revision.show', ['documentRevision' => $document->latestHistory->revision->id]) }}"
                                                                class="btn btn-primary-light btn-icon rounded-3" title="Lihat Detail">
                                                                <i class="ti ti-eye fs-5"></i>
                                                            </a>

                                                            {{-- Button Edit/Revisi untuk Admin atau Owner --}}
                                                            @if ($isAdmin || $isOwner)
                                                                @if (
                                                                    $isAdmin &&
                                                                        $document->latestHistory->revision->status === 'Draft' &&
                                                                        !$document->latestHistory->revision->acc_format &&
                                                                        !$document->latestHistory->revision->acc_content)
                                                                    {{-- Untuk dokumen baru yang masih Draft, HANYA ADMIN yang bisa edit --}}
                                                                    <a href="{{ route('documents.edit', $document->id) }}"
                                                                        class="btn btn-warning-light btn-icon rounded-3" title="Edit Dokumen">
                                                                        <i class="ti ti-pencil fs-5"></i>
                                                                    </a>
                                                                @elseif ($document->latestHistory->revision->status === 'Pengajuan Revisi')
                                                                    {{-- Untuk dokumen yang diminta revisi, Admin atau Owner bisa edit --}}
                                                                    <a href="{{ route('document_revision.edit', $document->latestHistory->revision->id) }}"
                                                                        class="btn btn-warning-light btn-icon rounded-3" title="Revisi Dokumen">
                                                                        <i class="ti ti-pencil fs-5"></i>
                                                                    </a>
                                                                @endif
                                                            @endif

                                                            @if ($isAdmin)
                                                                @can('delete-documents')
                                                                    @if (in_array($document->latestHistory->revision->status, ['Draft', 'Proses Revisi', 'Pengajuan Revisi']) &&
                                                                            !$document->latestHistory->revision->acc_format &&
                                                                            !$document->latestHistory->revision->acc_content)
                                                                        <button type="button" class="btn btn-danger-light btn-icon rounded-3"
                                                                            title="Hapus Dokumen" data-bs-toggle="modal"
                                                                            data-bs-target="#deleteModal{{ $document->id }}"
                                                                            data-doc-title="{{ $document->title }}">
                                                                            <i class="ti ti-trash fs-5"></i>
                                                                        </button>
                                                                    @endif
                                                                @endcan
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endcanany
                                            </td>
                                        </tr>

                                        {{-- Modal Delete untuk setiap dokumen - Hanya untuk Admin --}}
                                        @if ($isAdmin)
                                            @can('delete-documents')
                                                @if (
                                                    $document->latestHistory &&
                                                        $document->latestHistory->revision &&
                                                        in_array($document->latestHistory->revision->status, ['Draft', 'Proses Revisi', 'Pengajuan Revisi']) &&
                                                        !$document->latestHistory->revision->acc_format &&
                                                        !$document->latestHistory->revision->acc_content)
                                                    <div class="modal fade" id="deleteModal{{ $document->id }}" tabindex="-1"
                                                        aria-labelledby="deleteModalLabel{{ $document->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="text-white modal-header bg-danger">
                                                                    <h5 class="modal-title"
                                                                        id="deleteModalLabel{{ $document->id }}">
                                                                        <i class="ti ti-alert-triangle"></i> Konfirmasi Hapus
                                                                        Dokumen
                                                                    </h5>
                                                                    <button type="button" class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p class="mb-2">Apakah Anda yakin ingin menghapus dokumen
                                                                        ini?
                                                                    </p>
                                                                    <div class="alert alert-warning">
                                                                        <strong>Dokumen:</strong> {{ $document->title }}<br>
                                                                        <strong>Nomor:</strong> {{ $document->code }}
                                                                    </div>
                                                                    <p class="mb-0 text-danger">
                                                                        <i class="ti ti-alert-circle"></i>
                                                                        <strong>Perhatian:</strong> Semua data terkait akan
                                                                        dihapus
                                                                        secara permanen dan tidak dapat dikembalikan.
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">
                                                                        <i class="ti ti-x"></i> Batal
                                                                    </button>
                                                                    <form
                                                                        action="{{ route('document_revision.destroy', $document->latestHistory->revision->id) }}"
                                                                        method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger">
                                                                            <i class="ti ti-trash"></i> Ya, Hapus
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endcan
                                        @endif
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
        .btn-warning-light { background: #fef3c7; color: #d97706; border: none; }
        .btn-warning-light:hover { background: #fde68a; color: #b45309; }
        .transition-all { transition: all 0.2s ease; }
    </style>
    
    @section('customJS')
    <script>
        $(document).ready(function() {
            const table = $('#myTable').DataTable({
                "dom": '<"top"f>rt<"bottom"ip><"clear">',
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.5/i18n/id.json"
                }
            });
        });
    </script>
    @endsection
@endsection
