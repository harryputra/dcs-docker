@extends('layouts.layout_admin')

@section('title', 'Preview Dokumen')

@section('content')

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title fw-semibold mb-0">Preview Dokumen: {{ $filename }}</h5>
                    <div>
                        <a href="{{ route('document_revision.show-file', ['filename' => $filename]) }}"
                            class="btn btn-sm btn-info" download target="_blank">
                            <i class="ti ti-download"></i> Download
                        </a>
                        <a href="javascript:history.back()" class="btn btn-sm btn-secondary">
                            <i class="ti ti-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                @if ($extension === 'pdf')
                    <!-- PDF Embed Preview -->
                    <div class="mt-3" style="height: 80vh;">
                        <embed src="{{ $fileUrl }}" type="application/pdf" width="100%" height="100%"
                            style="border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                @elseif (in_array($extension, ['doc', 'docx']))
                    <!-- DOC/DOCX Preview using Google Docs Viewer -->
                    <div class="mt-3" style="height: 80vh;">
                        <iframe src="https://docs.google.com/viewer?url={{ urlencode($fileUrl) }}&embedded=true"
                            width="100%" height="100%" style="border: 1px solid #ddd; border-radius: 4px;">
                        </iframe>
                    </div>
                @else
                    <!-- Non-PDF/DOC file -->
                    <div class="mt-2 alert alert-info">
                        <i class="ti ti-file"></i> File tidak bisa di-preview. Silakan download untuk melihat file.
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
