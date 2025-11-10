@extends('errors::layout')

@section('title', 'Maintenance Mode')

@section('content')
    <!-- Error Code -->
    <div class="mb-2 error-code">503</div>

    <!-- Error Icon -->
    <div class="error-icon">
        <i class="ti ti-tool"></i>
    </div>

    <!-- Error Message -->
    <h3 class="mb-3">Mode Maintenance</h3>
    <p class="mb-0 text-muted">
        Sistem sedang dalam pemeliharaan. Kami akan segera kembali. Terima kasih atas kesabaran Anda.
    </p>
@endsection
