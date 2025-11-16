@extends('layouts.layout_admin')

@section('title', 'Tambah Dokumen')

@section('content')

    <div class="container-fluid">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4 card-title fw-semibold"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-file-plus">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            <path d="M12 11l0 6" />
                            <path d="M9 14l6 0" />
                        </svg> Tambah Dokumen Baru</h5>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('documents.store') }}" method="POST" class="space-y-6"
                        enctype="multipart/form-data">
                        @csrf
                        <!-- Judul -->
                        <div class="mb-6">
                            <label for="judul"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control"
                                id="title" placeholder="Judul Dokumen" required />
                        </div>

                        <!-- Kategori -->
                        <div class="mb-6">
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori<span
                                    class="text-danger">*</span></label>
                            <select id="category" name="category_id" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}" @if (old('category_id') == $id) selected @endif>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tanggal Dokumen -->
                        <div class="mb-6">
                            <label for="created_at"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Dokumen<span
                                    class="text-danger">*</span></label>
                            <input type="date" name="created_at" value="{{ old('created_at', date('Y-m-d')) }}"
                                class="form-control" id="created_at" max="{{ date('Y-m-d') }}" required />
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal dokumen dibuat (maksimal hari ini)
                            </p>
                        </div>

                        <!-- Checkbox Dokumen Lama -->
                        <div class="mb-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_old_document" name="is_old_document"
                                    value="1">
                                <label class="form-check-label" for="is_old_document">
                                    <strong>Dokumen lama yang sudah disahkan?</strong>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Centang jika dokumen ini sudah
                                        disahkan sebelumnya (otomatis disetujui tanpa approval)</p>
                                </label>
                            </div>
                        </div>

                        <!-- Klasifikasi Dokumen (untuk dokumen lama) -->
                        <div class="mb-6" id="classification_field" style="display: none;">
                            <label for="classification_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Klasifikasi
                                Dokumen<span class="text-danger">*</span></label>
                            <select id="classification_id" name="classification_id" class="form-control">
                                <option value="">-- Pilih Klasifikasi --</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Pilih klasifikasi untuk dokumen lama</p>
                        </div>

                        <!-- Nomor Urut (untuk dokumen lama) -->
                        <div class="mb-6" id="sequence_field" style="display: none;">
                            <label for="sequence_number"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Urut<span
                                    class="text-danger">*</span></label>
                            <input type="number" name="sequence_number" id="sequence_number" class="form-control"
                                value="{{ old('sequence_number') }}" placeholder="Contoh: 90" min="1" />
                            <p class="text-xs text-gray-500 dark:text-gray-400">Masukkan nomor urut dokumen lama (angka
                                saja, contoh: 1, 50, 90, 100). Sistem akan format otomatis menjadi 001, 050, 090, 100 di
                                nomor dokumen.</p>
                        </div>

                        <!-- Tanggal Terbit (Hidden by default) -->
                        <div class="mb-6" id="published_date_field" style="display: none;">
                            <label for="published_date"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Terbit<span
                                    class="text-danger">*</span></label>
                            <input type="date" name="published_date" value="{{ old('published_date') }}"
                                class="form-control" id="published_date" max="{{ date('Y-m-d') }}" />
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal dokumen disahkan (untuk dokumen
                                lama)</p>
                        </div>

                        <script>
                            document.getElementById('is_old_document').addEventListener('change', function() {
                                const publishedField = document.getElementById('published_date_field');
                                const publishedInput = document.getElementById('published_date');
                                if (this.checked) {
                                    publishedField.style.display = 'block';
                                    publishedInput.required = true;
                                } else {
                                    publishedField.style.display = 'none';
                                    publishedInput.required = false;
                                    publishedInput.value = '';
                                }
                            });
                        </script>

                        <!-- Deskripsi -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi<span
                                    class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="4" class="form-control" placeholder="Deskripsi Dokumen "
                                required>{{ old('description') }}</textarea>
                        </div>

                        <!-- File -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="file_input">Upload
                                file<span class="text-danger">*</span></label>
                            <input class="form-control" id="file_input" type="file" name="file_path" required
                                accept=".doc,.docx,.xls,.xlsx">
                            <p class="text-xs text-gray-500 dark:text-gray-400" id="oldDocLabel" style="display:none;">
                                PDF (MAX. 20MB) - untuk dokumen lama yang sudah disahkan</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400" id="normalDocLabel">DOC, DOCX, XLS, XLSX
                                (MAX. 20MB) - untuk dokumen baru</p>
                        </div>

                        @if (auth()->user()->isRole('Administrator'))
                            <div class="mb-6">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                    for="file_input">Opsi Tambahan</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="noApproval"
                                        id="ckTanpaApproval" @if (old('noApproval')) checked @endif>
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
    </div>

    <script>
        // Load classifications saat halaman load
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/classifications/all')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('classification_id');
                    data.forEach(classification => {
                        const option = new Option(
                            `${classification.kode_klasifikasi} - ${classification.nama_klasifikasi}`,
                            classification.id
                        );
                        select.add(option);
                    });
                })
                .catch(error => console.error('Error loading classifications:', error));
        });

        document.getElementById('is_old_document').addEventListener('change', function() {
            const classificationField = document.getElementById('classification_field');
            const classificationInput = document.getElementById('classification_id');
            const sequenceField = document.getElementById('sequence_field');
            const sequenceInput = document.getElementById('sequence_number');
            const publishedField = document.getElementById('published_date_field');
            const publishedInput = document.getElementById('published_date');
            const fileInput = document.getElementById('file_input');
            const oldDocLabel = document.getElementById('oldDocLabel');
            const normalDocLabel = document.getElementById('normalDocLabel');

            if (this.checked) {
                // Dokumen lama - hanya PDF
                classificationField.style.display = 'block';
                classificationInput.required = true;
                sequenceField.style.display = 'block';
                sequenceInput.required = true;
                publishedField.style.display = 'block';
                publishedInput.required = true;
                fileInput.accept = '.pdf';
                oldDocLabel.style.display = 'block';
                normalDocLabel.style.display = 'none';
            } else {
                // Dokumen baru - DOC, DOCX, XLS, XLSX
                classificationField.style.display = 'none';
                classificationInput.required = false;
                classificationInput.value = '';
                sequenceField.style.display = 'none';
                sequenceInput.required = false;
                sequenceInput.value = '';
                publishedField.style.display = 'none';
                publishedInput.required = false;
                publishedInput.value = '';
                fileInput.accept = '.doc,.docx,.xls,.xlsx';
                oldDocLabel.style.display = 'none';
                normalDocLabel.style.display = 'block';
            }
        });
    </script>

@endsection
