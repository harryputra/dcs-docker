@extends('layouts.layout_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('db.index') }}" class="btn btn-sm btn-light-primary rounded-circle p-2 me-2">
                                <i class="ti ti-arrow-left fs-5"></i>
                            </a>
                            <i class="ti ti-table fs-8 text-dark"></i>
                            <h4 class="card-title fw-bold m-0 text-dark">Table: <span class="text-primary">{{ $table }}</span></h4>
                        </div>
                        <a href="{{ route('db.create', $table) }}" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="ti ti-plus"></i> Tambah Data
                        </a>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success bg-success-subtle text-success border-0">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger bg-danger-subtle text-danger border-0">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 text-nowrap rounded-3 overflow-hidden">
                            <thead class="bg-primary text-white">
                                <tr>
                                    @foreach($columns as $col)
                                        <th class="fw-bold">{{ $col }}</th>
                                    @endforeach
                                    <th class="fw-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $row)
                                    <tr>
                                        @foreach($columns as $col)
                                            <td class="text-truncate" style="max-width: 200px;" title="{{ $row->$col }}">{{ Str::limit((string)$row->$col, 50) }}</td>
                                        @endforeach
                                        <td class="text-center">
                                            @if($primaryKey)
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('db.edit', [$table, $row->$primaryKey]) }}" class="btn btn-sm btn-warning text-white rounded-pill px-3">
                                                        <i class="ti ti-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('db.destroy', [$table, $row->$primaryKey]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger rounded-pill px-3">
                                                            <i class="ti ti-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="badge bg-secondary">No PK</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($columns) + 1 }}" class="text-center text-muted py-5">
                                            <i class="ti ti-database-off fs-8 d-block mb-2"></i>
                                            Tidak ada data ditemukan pada tabel ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $records->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
