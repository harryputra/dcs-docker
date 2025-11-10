@extends('errors::layout')

@section('title', 'Server Error')

@section('content')
    <!-- Error Code -->
    <div class="mb-2 error-code">500</div>

    <!-- Error Icon -->
    <div class="error-icon">
        <i class="ti ti-alert-triangle"></i>
    </div>

    <!-- Error Message -->
    <h3 class="mb-3">Terjadi Kesalahan Server</h3>
    <p class="mb-0 text-muted">
        Maaf, terjadi kesalahan pada server. Tim kami sedang memperbaikinya. Silakan coba lagi nanti.
    </p>
@endsection
