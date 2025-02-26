@extends('layouts.layout_admin')

@section('title', 'Document')

@section('content')

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h2 class="text-2xl font-bold pb-2">
                    <span>
                        <i class="ti ti-file-description"></i>
                    </span>
                    Daftar Dokumen
                </h2>




                <!-- Tabel Dokumen -->
                <div class="table-responsive mt-4">
                    <table class="table table-striped" id="tableDocument">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nomor Dokumen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pengunggah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-300">
                            @foreach ($documents as $document)
                                <tr>
                                    <td class="px-6 py-4 text-center">{{ $document->code }}</td>
                                    <td class="px-6 py-4 text-center">{{ $document->title }}</td>
                                    <td class="px-6 py-4 text-center">{{ $document->category->name }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="badge
                                @if ($document->latestRevision->status === 'Disetujui' && $document->is_active) bg-admin 
                                @elseif($document->latestRevision->status === 'Proses Revisi' || $document->latestRevision->status === 'Pengajuan Revisi')
                                    bg-warning
                                @elseif($document->latestRevision->status === 'Draft')
                                    bg-light text-dark
                                @else
                                    bg-danger @endif
                                ">
                                            {{ $document->latestRevision->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">{{ $document->uploader->name }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('documents.show', ['document' => $document->id]) }}"
                                            class="btn btn-admin btn-sm">Detail</a>
                                        @if (!empty(array_intersect(['administrator', 'bagian-mutu', 'pengendali-dokumen','kepala-puskesmas'], $userRoles)) && $document->latestRevision->status === 'Disetujui' && $document->is_active)
                                            <button type="button" id="btn-modalTolak" class="btn btn-approver btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#modalTolak"
                                                data-id="{{ $document->currentRevision->id }}">
                                                Revisi
                                            </button>
                                            <!-- Modal Revisi-->
                                            <div class="modal fade text-start" id="modalTolak" data-bs-backdrop="static"
                                                data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">Tolak Dokumen
                                                            </h5>
                                                        </div>
                                                        <div class="modal-body px-4">
                                                            <form id="formTolak" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status"
                                                                    value="Pengajuan Revisi">
                                                                <div class="row mb-3 align-items-center">
                                                                    <div class="col-md-6">
                                                                        <label for="exampleInputEmail1"
                                                                            class="form-label">Judul</label>
                                                                        <input type="text" id="rev_judul_doc" disabled
                                                                            class="form-control"
                                                                            aria-describedby="emailHelp">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="exampleInputEmail1"
                                                                            class="form-label">ID
                                                                            Dokumen</label>
                                                                        <input type="text" class="form-control"
                                                                            id="rev_code_doc" disabled
                                                                            aria-describedby="emailHelp">
                                                                    </div>

                                                                    <div class="col-md-12 mt-2">
                                                                        <div class="form-group">
                                                                            <label for="exampleInputEmail1"
                                                                                class="form-label">Kategori
                                                                                Dokumen</label>
                                                                            <select class="form-control"
                                                                                id="rev_category_doc" disabled>
                                                                                <option>{{ $document->category->name }}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3 align-items-center mt-2">

                                                                        <div class="col-md-6">
                                                                            <label for="exampleInputEmail1"
                                                                                class="form-label">Pengunggah</label>
                                                                            <input type="text" class="form-control"
                                                                                id="rev_uploader_doc" disabled
                                                                                aria-describedby="emailHelp">
                                                                        </div>
                                                                        <div class="col-md-6 d-flex flex-column">
                                                                            <label for="exampleInputEmail1"
                                                                                class="form-label">Berkas
                                                                                Dokumen</label>
                                                                            <a href="/dokumen/DOC-002.pdf" id="rev_url_doc"
                                                                                target="_blank">Download</a>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Alasan
                                                                        Pengajuan Revisi<span class="text-danger">*</span></label>
                                                                    <textarea class="form-control" name="reason" rows="2" required></textarea>
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Konfirmasi
                                                                Penolakan</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <!-- Tambahkan baris lainnya di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@section('customJS')
    <script src="{{ asset('assets/js/datatablesDocuments.js') }}"></script>
    <script src="{{asset('assets/js/datatablesApproval.js')}}"></script>
@endsection
@endsection
