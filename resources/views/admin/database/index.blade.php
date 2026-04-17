@extends('layouts.layout_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ti ti-database fs-8 text-danger"></i>
                            <h4 class="card-title fw-bold m-0 text-danger">Database Manager <span class="badge bg-danger-subtle text-danger fw-normal ms-2">Dev Mode</span></h4>
                        </div>
                    </div>
                    
                    <div class="row">
                        @foreach($tables as $table)
                            <div class="col-md-3 mb-4">
                                <a href="{{ route('db.show', $table) }}" class="card border border-2 border-primary-subtle text-decoration-none shadow-none h-100 table-card transition-all dropdown-item">
                                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-primary-subtle p-2 text-primary">
                                                <i class="ti ti-table fs-6"></i>
                                            </div>
                                            <h6 class="m-0 fw-bold text-dark">{{ $table }}</h6>
                                        </div>
                                        <i class="ti ti-chevron-right text-muted"></i>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table-card:hover {
    border-color: #5D87FF !important;
    background-color: #f0f5ff;
    transform: translateY(-2px);
}
.transition-all {
    transition: all 0.2s ease-in-out;
}
</style>
@endsection
