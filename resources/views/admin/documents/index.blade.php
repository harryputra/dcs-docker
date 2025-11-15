@extends('layouts.layout_admin')

@section('title', 'Daftar Dokumen')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4 card-title fw-semibold">Daftar Dokumen</h5>
                @can('create-documents')
                    <a href="{{ route('documents.create') }}" class="mb-3 btn btn-primary">Tambah Dokumen</a>
                @endcan
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Uploader</th>
                            <th>Tanggal Upload</th>
                            <th>Tanggal Terbit</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $document)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $document->title }}</td>
                                <td>{{ $document->category->name ?? '-' }}</td>
                                <td>{{ $document->uploader->name ?? '-' }}</td>
                                <td>{{ $document->created_at ? \Carbon\Carbon::parse($document->created_at)->format('d/m/Y') : '-' }}
                                </td>
                                <td>{{ $document->published_date ? \Carbon\Carbon::parse($document->published_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td>
                                    @if ($document->currentRevision)
                                        <a href="{{ route('file.dokumen', ['filename' => $document->currentRevision->file_path]) }}"
                                            target="_blank" class="btn btn-sm btn-link">Lihat Dokumen</a>
                                    @else
                                        <span class="text-danger">Revisi Tidak Tersedia</span>
                                    @endif
                                </td>

                                <td>
                                    @can('edit-documents')
                                        <a href="{{ route('documents.edit', $document->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                    @endcan
                                    @can('delete-documents')
                                        <form action="{{ route('documents.destroy', $document->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada dokumen</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $documents->links() }}
            </div>
        </div>
    </div>
@endsection
