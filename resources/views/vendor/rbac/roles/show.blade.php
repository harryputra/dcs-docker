@extends('layouts.layout_admin')
@section('title', __('rbac::roles.role_details'))
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-3">{!! __('rbac::roles.role_details') !!}: {{ $role->name }}</h2>
                <a href="{{ route('edit_role', ['role' => $role->id]) }}" class="btn btn-success">
                    {!! __('rbac::roles.edit_role') !!}</a>
                @can(Itstructure\LaRbac\Models\Permission::DELETE_MEMBER_FLAG, $role->id)
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRoleModal">
                        {!! __('rbac::roles.delete_role') !!}
                    </button>

                    <!-- Modal Hapus Role -->
                    <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="deleteRoleModalLabel">
                                        <i class="ti ti-alert-triangle me-2"></i>Konfirmasi Hapus
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-0">Apakah Anda yakin ingin menghapus role
                                        <strong>{{ $role->name }}</strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('delete_role') }}" method="post" style="display: inline-block;">
                                        @csrf
                                        <input type="hidden" value="{{ $role->id }}" name="items[]">
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
                                <td>{!! __('rbac::main.name') !!}</td>
                                <td>{{ $role->name }}</td>
                            </tr>
                            <tr>
                                <td>{!! __('rbac::main.slug') !!}</td>
                                <td>{{ $role->slug }}</td>
                            </tr>
                            <tr>
                                <td>{!! __('rbac::main.description') !!}</td>
                                <td>{{ $role->description }}</td>
                            </tr>
                            <tr>
                                <td>{!! __('rbac::permissions.permissions') !!}</td>
                                <td>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($role->permissions as $permission)
                                            <li class="list-group-item p-2">
                                                <a href="{{ route('show_permission', ['id' => $permission->id]) }}">
                                                    {{ $permission->name }}
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
