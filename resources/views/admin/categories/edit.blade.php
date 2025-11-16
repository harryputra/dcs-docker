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
                                <label for="name" class="form-label">Nama Kategori<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name"
                                    value="{{ $category->name }}" placeholder="Contoh: Surat Keputusan"
                                    style="width: 100%;">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="code" class="form-label">Kode Kategori<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="code" id="code"
                                    value="{{ $category->code }}" placeholder="Otomatis dari singkatan nama"
                                    style="width: 100%;" readonly>
                                <small class="text-muted">Otomatis diambil dari huruf kapital nama kategori (max 10
                                    karakter)</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('categories.index') }}" class="btn btn-danger">Kembali</a>
                            <button type="submit" class="btn btn-admin">Submit</button>
                        </div>
                    </form>

                    <script>
                        document.getElementById('name').addEventListener('input', function() {
                            const name = this.value;
                            let code = '';

                            // Ambil huruf kapital dari setiap kata
                            const words = name.trim().split(/\s+/);
                            words.forEach(word => {
                                if (word.length > 0) {
                                    code += word[0].toUpperCase();
                                }
                            });

                            // Batasi max 10 karakter
                            code = code.substring(0, 10);

                            document.getElementById('code').value = code;
                        });
                    </script>


                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
