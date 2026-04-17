@extends("layouts.layout_admin")
@section('title', __('rbac::users.assign_roles'))
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 border-start border-4 border-info">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h3 class="fw-bolder mb-1 text-dark d-flex align-items-center gap-2">
                        <i class="ti ti-user-edit text-info"></i> Edit Roles: <span class="text-info">{{ $user->memberName }}</span>
                    </h3>
                    <p class="text-muted small mb-0">Kelola dan delegasikan peran (roles) untuk pengguna ini.</p>
                </div>
                <a href="{{ route('list_users') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm bg-white">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
            </div>

            <form action="{{ route('update_user', ['id' => $user->memberKey]) }}" method="post">
                @include('rbac::users._fields')
                
                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('show_user', ['id' => $user->memberKey]) }}" class="btn btn-light text-dark shadow-sm px-4">Batal</a>
                    <button class="btn btn-info text-white fw-bold d-flex align-items-center gap-2 shadow-sm px-4" type="submit">
                        <i class="ti ti-device-floppy"></i> Simpan Penugasan
                    </button>
                </div>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
            </form>
        </div>
    </div>
</div>
@endsection
