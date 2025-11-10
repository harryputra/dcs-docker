@extends('errors::layout')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
    <!-- Error Code -->
    <div class="mb-2 error-code">404</div>

    <!-- Error Icon -->
    <div class="error-icon">
        <i class="ti ti-error-404"></i>
    </div>

    <!-- Error Message -->
    <h3 class="mb-3">Halaman Tidak Ditemukan</h3>
    <p class="mb-0 text-muted">
        Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin sudah dihapus atau URL yang Anda masukkan salah.
    </p>
@endsection
