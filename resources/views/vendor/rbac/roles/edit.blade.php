@extends("layouts.layout_admin")
@section('title', __('rbac::roles.edit_role'))
@section('content')

<div class="container-fluid">
    <div class="card shadow-sm border-0 border-start border-4 border-warning">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h3 class="fw-bolder mb-1 text-dark d-flex align-items-center gap-2">
                        <i class="ti ti-edit text-warning"></i> Edit Role: <span class="text-warning">{{ $role->name }}</span>
                    </h3>
                    <p class="text-muted small mb-0">Ubah atribut dan kelola izin hak akses (permissions) untuk role ini.</p>
                </div>
                <a href="{{ route('list_roles') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm bg-white">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
            </div>

            <form action="{{ route('update_role', ['role' => $role->id]) }}" method="post">
                @include('rbac::roles._fields', ['edit' => true])

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('show_role', ['id' => $role->id]) }}" class="btn btn-light text-dark shadow-sm px-4">Batal</a>
                    <button class="btn btn-warning text-dark fw-bold d-flex align-items-center gap-2 shadow-sm px-4" type="submit">
                        <i class="ti ti-device-floppy"></i> Simpan Perubahan
                    </button>
                </div>
                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </form>
        </div>
    </div>
</div>

@stop
