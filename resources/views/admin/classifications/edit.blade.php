@extends('layouts.layout_admin')

@section('title', 'Edit Klasifikasi Dokumen')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-4">Edit Klasifikasi Dokumen</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('classifications.update', $classification) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="kode_klasifikasi" class="form-label">Kode Klasifikasi <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_klasifikasi') is-invalid @enderror"
                            id="kode_klasifikasi" name="kode_klasifikasi"
                            value="{{ old('kode_klasifikasi', $classification->kode_klasifikasi) }}"
                            placeholder="Contoh: HM.01.01.13" required>
                        <small class="text-muted">Gunakan format: HM.01.01.13 (bisa 1-4 level)</small>
                        @error('kode_klasifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama_klasifikasi" class="form-label">Nama Klasifikasi <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_klasifikasi') is-invalid @enderror"
                            id="nama_klasifikasi" name="nama_klasifikasi"
                            value="{{ old('nama_klasifikasi', $classification->nama_klasifikasi) }}"
                            placeholder="Contoh: Cuti Tahunan" required>
                        @error('nama_klasifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-admin">
                            <i class="ti ti-check"></i> Update
                        </button>
                        <a href="{{ route('classifications.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
