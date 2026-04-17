@extends("layouts.layout_admin")
@section('title', __('rbac::roles.create_role'))
@section('content')

<div class="container-fluid">
    <div class="card shadow-sm border-0 border-start border-4 border-primary">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h3 class="fw-bolder mb-1 text-dark d-flex align-items-center gap-2">
                        <i class="ti ti-plus text-primary"></i> {!! __('rbac::roles.create_role') !!}
                    </h3>
                    <p class="text-muted small mb-0">Rancang grup peran baru dan distribusikan izin otorisasi yang tepat.</p>
                </div>
                <a href="{{ route('list_roles') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm bg-white">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
            </div>

            <form action="{{ route('store_role') }}" method="post">
                @include('rbac::roles._fields', ['edit' => false])

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('list_roles') }}" class="btn btn-light text-dark shadow-sm px-4">Batal</a>
                    <button class="btn btn-primary fw-bold d-flex align-items-center gap-2 shadow-sm px-4" type="submit">
                        <i class="ti ti-device-floppy"></i> {!! __('rbac::main.create') !!}
                    </button>
                </div>
                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </form>
        </div>
    </div>
</div>

@stop
