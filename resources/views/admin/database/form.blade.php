@extends('layouts.layout_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('db.show', $table) }}" class="btn btn-sm btn-light-primary rounded-circle p-2 me-2">
                                <i class="ti ti-arrow-left fs-5"></i>
                            </a>
                            <h4 class="card-title fw-bold m-0 text-dark">
                                {{ isset($record) ? 'Edit Record' : 'Create Record' }} 
                                <span class="text-primary mx-1">/</span> 
                                <span class="text-muted fs-4">{{ $table }}</span>
                            </h4>
                        </div>
                    </div>
                </div>
                
                <div class="card-body bg-light">
                    @if(session('error'))
                        <div class="alert alert-danger bg-danger-subtle text-danger border-0">{{ session('error') }}</div>
                    @endif

                    <div class="card border-0 shadow-none">
                        <div class="card-body">
                            <form action="{{ isset($record) ? route('db.update', [$table, $id]) : route('db.store', $table) }}" method="POST">
                                @csrf
                                @if(isset($record))
                                    @method('PUT')
                                @endif

                                <div class="row">
                                    @foreach($columns as $col)
                                        @php
                                            $type = $columnMetadata[$col] ?? 'string';
                                            $value = isset($record) ? $record->$col : '';
                                        @endphp
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">{{ $col }} <small class="text-muted fw-normal ms-2">({{ $type }})</small></label>
                                            
                                            @if($type === 'text' || $type === 'longtext' || $type === 'mediumtext')
                                                <textarea class="form-control" name="{{ $col }}" rows="3">{{ old($col, $value) }}</textarea>
                                            @elseif($type === 'boolean' || $type === 'tinyint')
                                                <select class="form-select" name="{{ $col }}">
                                                    <option value="0" {{ old($col, $value) == '0' ? 'selected' : '' }}>False / 0</option>
                                                    <option value="1" {{ old($col, $value) == '1' ? 'selected' : '' }}>True / 1</option>
                                                </select>
                                            @elseif($type === 'datetime' || $type === 'timestamp')
                                                @php
                                                    // Format for HTML datetime-local input
                                                    $formattedValue = $value ? date('Y-m-d\TH:i', strtotime($value)) : '';
                                                @endphp
                                                <input type="datetime-local" class="form-control" name="{{ $col }}" value="{{ old($col, $formattedValue) }}">
                                            @elseif($type === 'date')
                                                <input type="date" class="form-control" name="{{ $col }}" value="{{ old($col, $value) }}">
                                            @else
                                                <input type="{{ in_array($type, ['integer', 'bigint', 'smallint']) ? 'number' : 'text' }}" 
                                                       class="form-control" 
                                                       name="{{ $col }}" 
                                                       value="{{ old($col, $value) }}"
                                                       {{ ($col === 'id' && isset($record)) ? 'readonly' : '' }}>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-3">
                                    <a href="{{ route('db.show', $table) }}" class="btn btn-outline-secondary px-4 rounded-pill">Batal</a>
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill d-flex align-items-center gap-2">
                                        <i class="ti ti-device-floppy"></i> Simpan Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
