@extends('layouts.layout_admin')

@section('title', 'Dokumen Anda')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-2">Dokumen Anda</h2>
                                <x-breadcrumb :breadcrumbs="[
                                    ['title' => 'Dokumen', 'url' => '#'],
                                    ['title' => 'Dokumen Anda', 'url' => route('documents.index')],
                                ]" />
                            </div>
                        </div>

                        @can('create-documents')
                            <div class="mb-1 d-flex justify-content-end">
                                <a href="{{ route('documents.create') }}" class="btn btn-admin d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-file-code-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 12h-1v5h1" />
                                        <path d="M14 12h1v5h-1" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    </svg>
                                    Tambah Dokumen
                                </a>
                                <div class="mx-1"></div>
                                <a href="{{ route('document_revision.create') }}"
                                    class="btn btn-secondary d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-file-code-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 12h-1v5h1" />
                                        <path d="M14 12h1v5h-1" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    </svg>
                                    Perbarui Dokumen
                                </a>
                            </div>
                        @endcan


                        <div class="mt-4 table-responsive">
                            <table id="myTable" class="table table-striped">
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
                                                @php
                                                    $isAdmin = auth()->user()->roles->contains('slug', 'administrator');
                                                    $isOwner = $document->uploaded_by === auth()->id();
                                                @endphp

                                                @canany(['edit-documents', 'edit-revisions'])
                                                    <div class="gap-1 d-flex">
                                                        <a href="{{ route('document_revision.show', ['documentRevision' => $document->latestHistory->revision->id]) }}"
                                                            class="btn btn-sm btn-admin" title="Lihat Detail">
                                                            <i class="ti ti-eye"></i>
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
                                                                    class="btn btn-sm btn-approver" title="Edit Dokumen">
                                                                    <i class="ti ti-pencil"></i>
                                                                </a>
                                                            @elseif ($document->latestHistory->revision->status === 'Pengajuan Revisi')
                                                                {{-- Untuk dokumen yang diminta revisi, Admin atau Owner bisa edit --}}
                                                                <a href="{{ route('document_revision.edit', $document->latestHistory->revision->id) }}"
                                                                    class="btn btn-sm btn-approver" title="Revisi Dokumen">
                                                                    <i class="ti ti-pencil"></i>
                                                                </a>
                                                            @endif
                                                        @endif

                                                        @if ($isAdmin)
                                                            @can('delete-documents')
                                                                @if (in_array($document->latestHistory->revision->status, ['Draft', 'Proses Revisi', 'Pengajuan Revisi']) &&
                                                                        !$document->latestHistory->revision->acc_format &&
                                                                        !$document->latestHistory->revision->acc_content)
                                                                    <button type="button" class="btn btn-sm btn-danger"
                                                                        title="Hapus Dokumen" data-bs-toggle="modal"
                                                                        data-bs-target="#deleteModal{{ $document->id }}"
                                                                        data-doc-title="{{ $document->title }}">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                @endif
                                                            @endcan
                                                        @endif
                                                    </div>
                                                @endcanany
                                            </td>
                                        </tr>

                                        {{-- Modal Delete untuk setiap dokumen - Hanya untuk Admin --}}
                                        @if ($isAdmin)
                                            @can('delete-documents')
                                                @if (in_array($document->latestHistory->revision->status, ['Draft', 'Proses Revisi', 'Pengajuan Revisi']) &&
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
@endsection
