@extends('errors::layout')

@section('title', 'Akses Ditolak')

@section('content')
    <!-- Error Code -->
    <div class="mb-2 error-code">403</div>

    <!-- Error Icon -->
    <div class="error-icon">
        <i class="ti ti-lock"></i>
    </div>

    <!-- Error Message -->
    <h3 class="mb-3">Akses Ditolak</h3>
    <p class="mb-0 text-muted">
        {{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}
    </p>
@endsection
