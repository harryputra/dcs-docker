@extends('layouts.layout_admin')
@section('title', __('rbac::users.user_details'))
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-3">{!! __('rbac::users.user_details') !!}: {{ $user->memberName }}</h2>
                <a href="{{ route('edit_user', ['id' => $user->memberKey]) }}" class="mb-3 btn btn-success">
                    {!! __('rbac::users.assign_roles') !!}
                </a>
                @can(Itstructure\LaRbac\Models\Permission::DELETE_MEMBER_FLAG, $user->memberKey)
                    <button type="button" class="mb-3 btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                        {!! __('rbac::users.delete_user') !!}
                    </button>

                    <!-- Modal Hapus User -->
                    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="text-white modal-header bg-danger">
                                    <h5 class="modal-title" id="deleteUserModalLabel">
                                        <i class="ti ti-alert-triangle me-2"></i>Konfirmasi Hapus
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-0">Apakah Anda yakin ingin menghapus user
                                        <strong>{{ $user->memberName }}</strong>?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('delete_user') }}" method="post" style="display: inline-block;">
                                        @csrf
                                        <input type="hidden" value="{{ $user->memberKey }}" name="items[]">
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                <div class="table-responsive">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>{!! __('rbac::main.attribute') !!}</th>
                                <th>{!! __('rbac::main.value') !!}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{!! __('rbac::users.name') !!}</td>
                                <td>{{ $user->memberName }}</td>
                            </tr>
                            <tr>
                                <td>{!! __('rbac::roles.roles') !!}</td>
                                <td>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($user->roles as $role)
                                            <li class="p-2 list-group-item">
                                                <a href="{{ route('show_role', ['id' => $role->id]) }}">
                                                    {{ $role->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
