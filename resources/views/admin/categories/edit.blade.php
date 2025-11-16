@extends('layouts.layout_admin')

@section('title', 'Edit Kategori')

@section('content')

    <div class="container-fluid">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Edit Kategori Dokumen</h5>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1" class="form-label">Nama Kategori<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="exampleInputEmail1"
                                    value="{{ $category->name }}" style="width: 100%;">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="code" class="form-label">Kode Kategori<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="code" id="code"
                                    value="{{ $category->code }}" placeholder="Contoh: SK, SPO, LB" style="width: 100%;">
                                <small class="text-muted">Singkatan untuk kategori (max 10 karakter)</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('categories.index') }}" class="btn btn-danger">Kembali</a>
                            <button type="submit" class="btn btn-admin">Submit</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
