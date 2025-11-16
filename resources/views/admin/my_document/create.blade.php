@extends('layouts.layout_admin')

@section('title', 'Tambah Revisi Dokumen')

@section('content')

    <div class="container-fluid">
        <div class="container-fluid">
            <!-- Form Card Utama Kiri -->
            <form action="{{ route('document_revision.store') }}" method="POST" enctype="multipart/form-data" class="w-100">
                @csrf
                <div class="row d-flex">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <label id="labelToChange" class="form-label">Dokumen Anda</label>

                                <h5 class="mb-4 card-title fw-semibold">Perbarui Dokumen </h5>
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
                                        <input type="text" name="title" value="{{ old('title') ?? '' }}"
                                            class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1" class="form-label">Kategori Dokumen<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="kategori_select" name="category_id" required>
                                                <option value="">-- Pilih --</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id') ? 'selected' : '' }}>{{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 row align-items-center">
                                    <div class="col-md-12">
                                        <label for="created_at" class="form-label">Tanggal Dokumen<span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="created_at"
                                            value="{{ old('created_at', date('Y-m-d')) }}" class="form-control"
                                            id="created_at" max="{{ date('Y-m-d') }}" required>
                                        <small class="text-muted">Tanggal dokumen dibuat (maksimal hari ini)</small>
                                    </div>
                                </div>
                                <div class="mb-3 row align-items-center">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_old_document"
                                                name="is_old_document" value="1">
                                            <label class="form-check-label" for="is_old_document">
                                                <strong>Dokumen lama yang sudah disahkan?</strong>
                                                <small class="text-muted d-block">Centang jika dokumen ini sudah disahkan
                                                    sebelumnya (otomatis disetujui tanpa approval)</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 row align-items-center" id="code_field" style="display: none;">
                                    <div class="col-md-12">
                                        <label for="code" class="form-label">Nomor Dokumen<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="code" id="code" class="form-control"
                                            value="{{ old('code') }}" placeholder="Contoh: SOP/001/2024">
                                        <small class="text-muted">Nomor dokumen yang sudah disahkan</small>
                                    </div>
                                </div>
                                <div class="mb-3 row align-items-center" id="published_date_field" style="display: none;">
                                    <div class="col-md-12">
                                        <label for="published_date" class="form-label">Tanggal Terbit<span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="published_date" value="{{ old('published_date') }}"
                                            class="form-control" id="published_date" max="{{ date('Y-m-d') }}">
                                        <small class="text-muted">Tanggal dokumen disahkan (untuk dokumen lama)</small>
                                    </div>
                                </div>
                                <script>
                                    document.getElementById('is_old_document').addEventListener('change', function() {
                                        const codeField = document.getElementById('code_field');
                                        const codeInput = document.getElementById('code');
                                        const publishedField = document.getElementById('published_date_field');
                                        const publishedInput = document.getElementById('published_date');
                                        if (this.checked) {
                                            codeField.style.display = 'block';
                                            codeInput.required = true;
                                            publishedField.style.display = 'block';
                                            publishedInput.required = true;
                                        } else {
                                            codeField.style.display = 'none';
                                            codeInput.required = false;
                                            codeInput.value = '';
                                            publishedField.style.display = 'none';
                                            publishedInput.required = false;
                                            publishedInput.value = '';
                                        }
                                    });
                                </script>
                                <div class="mb-3 row align-items-center">
                                    <div class="col-md-12">
                                        <label for="dokumen" class="form-label">Berkas Dokumen<span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="file_path" class="form-control" id="dokumen"
                                            aria-describedby="dokumenHelp" accept=".pdf,.doc,.docx,.txt" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Deskripsi<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="2" required>{{ old('description') ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Alasan<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" name="reason" id="exampleFormControlTextarea1" rows="2" required>{{ old('reason') ?? '' }}</textarea>
                                </div>
                                <div class="gap-2 d-flex justify-content-center" style="width: 400px; margin: auto;">
                                    <a href="{{ route('document_revision.index') }}" class="btn btn-danger">Kembali</a>
                                    <button type="submit" class="btn btn-admin">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Status Kecil Kanan -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-4 card-title fw-semibold">Status Dokumen</h5>
                                <div class="col-md-12">
                                    <div class="container">
                                        <div class="d-flex flex-column">
                                            <div class="p-2 fw-bolder"
                                                style=" background-color: #343a4012;padding: 15px;">
                                                Mengubah:
                                            </div>
                                            <div class="p-2">
                                                <select id="my-select" name="rev[]" multiple="multiple"
                                                    class="form-control"></select>
                                            </div>
                                        </div>
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
        </div>
    </div>
    </div>
@section('customJS')
    {{-- Select 2 SearchBox --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/selectrevision.js') }}"></script>
@endsection
@endsection
