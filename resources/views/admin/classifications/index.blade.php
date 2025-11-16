@extends('layouts.layout_admin')

@section('title', 'Klasifikasi Dokumen')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h2 class="mb-2">Klasifikasi Dokumen</h2>
                                <x-breadcrumb :breadcrumbs="[
                                    ['title' => 'Klasifikasi Dokumen', 'url' => route('classifications.index')],
                                ]" />
                            </div>
                        </div>

                        <div class="mb-1 d-flex justify-content-end">
                            <a href="{{ route('classifications.create') }}" class="btn btn-admin d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-list-numbers">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M11 6h9" />
                                    <path d="M11 12h9" />
                                    <path d="M12 18h8" />
                                    <path d="M4 16a2 2 0 1 1 4 0c0 .591 -.5 1 -1 1.5l-3 2.5h4" />
                                    <path d="M6 10v-6l-2 2" />
                                </svg>
                                Tambah Klasifikasi Baru
                            </a>
                        </div>

                        <div class="mt-4 table-responsive">
                            <table id="myTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Kode Klasifikasi</th>
                                        <th>Nama Klasifikasi</th>
                                        <th style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classifications as $index => $classification)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $classification->kode_klasifikasi }}</td>
                                            <td>{{ $classification->nama_klasifikasi }}</td>
                                            <td>
                                                <a href="{{ route('classifications.edit', $classification) }}"
                                                    class="btn btn-sm btn-admin" title="Edit Klasifikasi">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    title="Hapus Klasifikasi" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $classification->id }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- Modal Delete --}}
                                        <div class="modal fade" id="deleteModal{{ $classification->id }}" tabindex="-1"
                                            aria-labelledby="deleteModalLabel{{ $classification->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="text-white modal-header bg-danger">
                                                        <h5 class="modal-title"
                                                            id="deleteModalLabel{{ $classification->id }}">
                                                            <i class="ti ti-alert-triangle"></i> Konfirmasi Hapus
                                                            Klasifikasi
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-2">Apakah Anda yakin ingin menghapus klasifikasi
                                                            <strong>{{ $classification->kode_klasifikasi }} -
                                                                {{ $classification->nama_klasifikasi }}</strong>?
                                                        </p>
                                                        @php
                                                            $docCount = $classification->documents()->count();
                                                        @endphp
                                                        @if ($docCount > 0)
                                                            <div class="alert alert-warning mb-2" role="alert">
                                                                <i class="ti ti-alert-triangle"></i>
                                                                <strong>Peringatan:</strong> Klasifikasi ini memiliki
                                                                <strong>{{ $docCount }} dokumen</strong> terkait!
                                                            </div>
                                                        @endif
                                                        <p class="mb-0 text-muted">
                                                            <i class="ti ti-info-circle"></i> Data yang sudah dihapus tidak
                                                            dapat
                                                            dikembalikan.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="ti ti-x"></i> Batal
                                                        </button>
                                                        <form
                                                            action="{{ route('classifications.destroy', $classification) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="ti ti-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
