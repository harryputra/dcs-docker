@extends('layouts.layout_admin')

@section('title', 'Upload File Dokumen')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <!-- Form Card Utama -->
            <form action="{{ route('document_revision.update', ['documentRevision' => $documentRevision->id]) }}"
                method="POST" enctype="multipart/form-data" class="w-100">
                @csrf
                @method('PUT')

                <div class="row d-flex">
                    <!-- Card Utama -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-4 card-title fw-semibold">Revisi Dokumen</h5>

                                @if ($documentRevision->acc_format || $documentRevision->acc_content)
                                    <div class="alert alert-warning" role="alert">
                                        <strong><i class="ti ti-alert-triangle"></i> Peringatan!</strong><br>
                                        Dokumen ini sedang dalam proses approval:
                                        <ul class="mt-2 mb-0">
                                            @if ($documentRevision->acc_format)
                                                <li>✓ Format telah di-approve oleh Pengendali Dokumen</li>
                                            @endif
                                            @if ($documentRevision->acc_content)
                                                <li>✓ Konten telah di-approve oleh Bagian Mutu</li>
                                            @endif
                                        </ul>
                                        <strong class="mt-2 d-block">Perubahan tidak dapat dilakukan saat ini.</strong>
                                        Silakan hubungi approver untuk membatalkan approval terlebih dahulu.
                                    </div>
                                @endif

                                @if ($documentRevision->status === 'Disetujui')
                                    <div class="alert alert-info" role="alert">
                                        <strong><i class="ti ti-info-circle"></i> Informasi!</strong><br>
                                        Dokumen ini sudah disetujui. Untuk melakukan perubahan, silakan buat revisi baru.
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="mb-3 row align-items-center">
                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1" class="form-label">Judul<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title"
                                            value="{{ old('title') ?? $documentRevision->document->title }}"
                                            class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1" class="form-label">Kategori Dokumen<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="exampleFormControlSelect1" name="category_id">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $documentRevision->document->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 row align-items-center">
                                    <div class="col-md-12">
                                        <label for="dokumen" class="form-label">Berkas Dokumen<span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="file_path" class="form-control" id="dokumen"
                                            aria-describedby="dokumenHelp" accept=".pdf,.doc,.docx,.txt">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Deskripsi<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="2">{{ old('description', $documentRevision->description) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Alasan Pengajuan Revisi<span
                                            class="text-danger">{{ $documentRevision->status === 'Disetujui' ? '*' : '' }}</span></label>
                                    <textarea class="form-control" name="reason" id="exampleFormControlTextarea1" rows="2"
                                        {{ $documentRevision->status === 'Pengajuan Revisi' ? 'disabled' : '' }}>{{ old('reason', $reason) }}</textarea>
                                </div>

                                <div class="gap-2 d-flex justify-content-center" style="width: 400px; margin: auto;">
                                    <div class="gap-2 d-flex justify-content-center" style="width: 400px; margin: auto;">
                                        <a href="{{ route('document_revision.index') }}" class="btn btn-danger">Kembali</a>
                                        <button type="submit" class="btn btn-admin"
                                            @if ($documentRevision->acc_format || $documentRevision->acc_content || $documentRevision->status === 'Disetujui') disabled
                                                title="Dokumen sedang dalam proses approval atau sudah disetujui" @endif>
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </form>
            <input type="hidden" id="oldSelections"
                value="{{ json_encode(old('rev', isset($documentRevision) ? $documentRevision->revised_doc : [])) }}">
            <input type="hidden" id="selectedOption"
                value="{{ isset($documentRevision) && !is_null($documentRevision->id) ? $documentRevision->document_id : '' }}">
            <input type="hidden" id="categoryDoc"
                value="{{ isset($documentRevision) && !is_null($documentRevision->document->category_id) ? $documentRevision->document->category_id : '' }}">
        </div>
    </div>
@section('customJS')
    {{-- Select 2 SearchBox --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/selectrevision.js') }}"></script>

    <script>
        // Disable form jika dokumen sedang dalam proses approval atau sudah disetujui
        @if ($documentRevision->acc_format || $documentRevision->acc_content || $documentRevision->status === 'Disetujui')
            document.addEventListener('DOMContentLoaded', function() {
                // Disable semua input, textarea, dan select
                const formInputs = document.querySelectorAll('input:not([type="hidden"]), textarea, select');
                formInputs.forEach(function(input) {
                    input.disabled = true;
                    input.style.backgroundColor = '#f5f5f5';
                    input.style.cursor = 'not-allowed';
                });

                // Disable tombol submit
                const submitBtn = document.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.style.cursor = 'not-allowed';
                    submitBtn.style.opacity = '0.6';
                }
            });
        @endif
    </script>
@endsection
@endsection
