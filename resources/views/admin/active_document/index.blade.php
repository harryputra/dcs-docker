@extends('layouts.layout_admin')

@section('title', 'Document')

@section('content')

    <div class="container-fluid">
        <h2 class="pb-2 text-2xl font-bold">
            Dokumen
        </h2>
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3 card-title">
                    <span>
                        <i class="ti ti-filter"></i>
                    </span> Filter Dokumen
                </h5>
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
                                <input class="form-check-input" type="radio" name="filterDocument" id="fPengajuanRev"
                                    value="pengajuanrev">
                                <label class="form-check-label" for="fPengajuanRev">Pengajuan Revisi</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3 card-title">
                    <span>
                        <i class="ti ti-file"></i>
                    </span> Daftar Dokumen
                </h5>
                <!-- Tabel Dokumen -->
                <div class="mt-4 table-responsive">
                    <table class="table table-striped" id="tableDocument">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Nomor Dokumen</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Judul</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Kategori</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Status</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Pengunggah</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
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
                                        @if (
                                            !empty(array_intersect(['administrator', 'bagian-mutu', 'pengendali-dokumen', 'kepala-puskesmas'], $userRoles)) &&
                                                $document->latestRevision->status === 'Disetujui' &&
                                                $document->is_active)
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
                                                        <div class="px-4 modal-body">
                                                            <form id="formTolak" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status"
                                                                    value="Pengajuan Revisi">
                                                                <div class="mb-3 row align-items-center">
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

                                                                    <div class="mt-2 col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="exampleInputEmail1"
                                                                                class="form-label">Kategori
                                                                                Dokumen</label>
                                                                            <select class="form-control"
                                                                                id="rev_category_doc" disabled>
                                                                                <option>{{ $document->category->name }}
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mt-2 mb-3 row align-items-center">

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
                                                                            <a href="/dokumen/DOC-002.pdf"
                                                                                id="rev_url_doc"
                                                                                target="_blank">Download</a>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Alasan
                                                                        Pengajuan Revisi<span
                                                                            class="text-danger">*</span></label>
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
    <script src="{{ asset('assets/js/datatablesApproval.js') }}"></script>
@endsection
@endsection
