@extends('layouts.layout_admin')

@section('title', 'Edit Dokumen')

@section('content')

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4 card-title fw-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-file-edit">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                        <path d="M10 18l5 -5a1.414 1.414 0 0 0 -2 -2l-5 5v2h2z" />
                    </svg> Edit Dokumen
                </h5>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nomor Dokumen -->
                    <div class="mb-6">
                        <label for="code" class="block mb-2 text-sm font-medium text-gray-900">Nomor Dokumen<span
                                class="text-danger">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $document->code) }}" class="form-control"
                            id="code" placeholder="Nomor Dokumen" required />
                    </div>

                    <!-- Judul -->
                    <div class="mb-6">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Judul<span
                                class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $document->title) }}"
                            class="form-control" id="title" placeholder="Judul Dokumen" required />
                    </div>

                    <!-- Kategori -->
                    <div class="mb-6">
                        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900">Kategori<span
                                class="text-danger">*</span></label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $document->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi<span
                                class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="4" class="form-control" placeholder="Deskripsi Dokumen"
                            required>{{ old('description', $document->currentRevision->description ?? '') }}</textarea>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-900" for="file_path">Upload file<span
                                class="text-muted"> (Kosongkan jika tidak ingin mengubah file)</span></label>
                        <input class="form-control" id="file_path" type="file" name="file_path" accept=".pdf,.doc,.docx">
                        <p class="text-xs text-gray-500">PDF, DOC, DOCX (MAX. 5MB)</p>
                    </div>

                    <!-- Preview File yang Ada -->
                    @if ($document->currentRevision && $document->currentRevision->file_path)
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900">File Saat Ini:</label>
                            <div class="p-3 border rounded">
                                <div class="mb-2">
                                    <strong>{{ basename($document->currentRevision->file_path) }}</strong>
                                </div>
                                @php
                                    $extension = pathinfo($document->currentRevision->file_path, PATHINFO_EXTENSION);
                                    // Cari file di berbagai disk storage
                                    $fileUrl = null;
                                    if (Storage::disk('dokumen')->exists($document->currentRevision->file_path)) {
                                        $fileUrl = Storage::disk('dokumen')->url($document->currentRevision->file_path);
                                    } elseif (
                                        Storage::disk('dokumen-revision')->exists($document->currentRevision->file_path)
                                    ) {
                                        $fileUrl = Storage::disk('dokumen-revision')->url(
                                            $document->currentRevision->file_path,
                                        );
                                    } elseif (
                                        Storage::disk('dokumen-approved')->exists($document->currentRevision->file_path)
                                    ) {
                                        $fileUrl = Storage::disk('dokumen-approved')->url(
                                            $document->currentRevision->file_path,
                                        );
                                    }
                                @endphp
                                @if ($fileUrl && $extension === 'pdf')
                                    <!-- PDF Embed Preview -->
                                    <div class="mt-3" style="height: 600px;">
                                        <embed src="{{ $fileUrl }}" type="application/pdf" width="100%"
                                            height="100%" style="border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                @elseif ($fileUrl && in_array($extension, ['doc', 'docx']))
                                    <!-- DOC/DOCX Preview using Google Docs Viewer -->
                                    <div class="mt-3" style="height: 600px;">
                                        <iframe
                                            src="https://docs.google.com/viewer?url={{ urlencode($fileUrl) }}&embedded=true"
                                            width="100%" height="100%"
                                            style="border: 1px solid #ddd; border-radius: 4px;">
                                        </iframe>
                                    </div>
                                    <div class="mt-2 alert alert-info">
                                        <i class="ti ti-info-circle"></i> Preview menggunakan Google Docs Viewer.
                                        <a href="{{ route('document_revision.view-file', ['filename' => $document->currentRevision->file_path]) }}"
                                            class="btn btn-sm btn-admin">
                                            <i class="ti ti-eye"></i> Lihat File
                                        </a>
                                        <a href="{{ $fileUrl }}" download target="_blank" class="btn btn-sm btn-info">
                                            <i class="ti ti-download"></i> Download File
                                        </a>
                                    </div>
                                @elseif ($fileUrl)
                                    <!-- Non-PDF/DOC file download link -->
                                    <div class="mt-2 alert alert-info">
                                        <i class="ti ti-file"></i> File tidak bisa di-preview.
                                        <a href="{{ route('document_revision.view-file', ['filename' => $document->currentRevision->file_path]) }}"
                                            class="btn btn-sm btn-admin">
                                            <i class="ti ti-eye"></i> Lihat File
                                        </a>
                                        <a href="{{ $fileUrl }}" download target="_blank"
                                            class="btn btn-sm btn-info">
                                            <i class="ti ti-download"></i> Download File
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-2 alert alert-warning">
                                        <i class="ti ti-alert-triangle"></i> File tidak ditemukan.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->isRole('Administrator'))
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Opsi Tambahan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="noApproval"
                                    id="ckTanpaApproval">
                                <label class="form-check-label" for="ckTanpaApproval">
                                    Tanpa Approval
                                </label>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-center">
                        <a href="{{ route('document_revision.index') }}" class="m-1 btn btn-danger">
                            Batal
                        </a>
                        <button type="submit" class="m-1 btn btn-admin">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
