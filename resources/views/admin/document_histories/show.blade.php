@extends('layouts.layout_admin')

@section('title', 'Detail Riwayat Dokumen')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h2>Detail Riwayat Dokumen</h2>
                <table class="table mt-4 table-bordered">
                    <tr>
                        <th>Document Title</th>
                        <td>{{ $documentHistory->document->title }}</td>
                    </tr>
                    <tr>
                        <th>Reviser</th>
                        <td>{{ $documentHistory->revision->reviser->name }}</td>
                    </tr>
                    <tr>
                        <th>Revision Number</th>
                        <td>{{ $documentHistory->revision->revision_number }}</td>
                    </tr>
                    <tr>
                        <th>Action</th>
                        <td>
                            <span
                                class="badge
                    @if ($documentHistory->action === 'Created') bg-primary
                    @elseif ($documentHistory->action === 'Revised')
                        bg-warning
                    @elseif ($documentHistory->action === 'Approved')
                        bg-success
                    @elseif ($documentHistory->action === 'Rejected')
                        bg-danger @endif
                    ">
                                {{ $documentHistory->action }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Performed By</th>
                        <td>{{ $documentHistory->performer->name }}</td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td>{{ \Carbon\Carbon::parse($documentHistory->created_at)->format('H:i:s-d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Reason</th>
                        <td>{{ $documentHistory->reason }}</td>
                    </tr>
                </table>
                <a href="{{ route('document_histories.index') }}" class="mt-3 btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
@endsection
