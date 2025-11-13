@extends('layouts.layout_admin')
@section('title', __('rbac::permissions.permission_details'))
@section('content')

    <div class="container-fluid">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h2 class="mb-3">{!! __('rbac::permissions.permission_details') !!}: {{ $permission->name }}</h2>
                    <a class="btn btn-success" href="{{ route('edit_permission', ['permission' => $permission->id]) }}"
                        title="{!! __('rbac::main.edit') !!}">{!! __('rbac::permissions.edit_permission') !!}</a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deletePermissionModal">
                        {!! __('rbac::permissions.delete_permission') !!}
                    </button>

                    <!-- Modal Hapus Permission -->
                    <div class="modal fade" id="deletePermissionModal" tabindex="-1"
                        aria-labelledby="deletePermissionModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="deletePermissionModalLabel">
                                        <i class="ti ti-alert-triangle me-2"></i>Konfirmasi Hapus
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-0">Apakah Anda yakin ingin menghapus permission
                                        <strong>{{ $permission->name }}</strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('delete_permission') }}" method="post"
                                        style="display: inline-block;">
                                        @csrf
                                        <input type="hidden" value="{{ $permission->id }}" name="items[]">
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{!! __('rbac::main.attribute') !!}</th>
                                    <th>{!! __('rbac::main.value') !!}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{!! __('rbac::main.name') !!}</td>
                                    <td>{{ $permission->name }}</td>
                                </tr>
                                <tr>
                                    <td>{!! __('rbac::main.slug') !!}</td>
                                    <td>{{ $permission->slug }}</td>
                                </tr>
                                <tr>
                                    <td>{!! __('rbac::main.description') !!}</td>
                                    <td>{{ $permission->description }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
