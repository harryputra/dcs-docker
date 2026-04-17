@extends('layouts.layout_admin')

@section('title', 'Penugasan Dokumen')

@section('content')
    <div class="container-fluid">
        <!-- Enterprise Header -->
        <div class="mb-5 border-0 shadow-sm card rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 100%);">
            <div class="p-4 card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div>
                    <h2 class="mb-1 display-6 fw-bold text-dark">Manajemen Penugasan</h2>
                    <p class="mb-0 text-muted fs-4">Distribusi tugas penyusunan dan revisi dokumen secara terstruktur.</p>
                </div>
                @if(auth()->user()->isRole('Kepala-Puskesmas') || auth()->user()->isRole('Administrator'))
                <div class="mt-3 mt-md-0">
                    <button class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddTask">
                        <i class="ti ti-plus me-1"></i> BUAT PENUGASAN BARU
                    </button>
                </div>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ti ti-check fs-6 me-2"></i>
                    <div><strong>Berhasil!</strong> {{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ti ti-alert-circle fs-6 me-2"></i>
                    <div><strong>Gagal!</strong> {{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Task Matrix -->
        <div class="card shadow-sm border-0 border-start border-4 border-teal rounded-4">
            <div class="card-body p-4">
                <div class="mb-4 pb-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bolder mb-1 text-dark d-flex align-items-center gap-2">
                            <i class="ti ti-list-check text-teal"></i> Matriks Penugasan Dokumen
                        </h3>
                        <p class="text-muted small mb-0">Daftar tugas aktif dan riwayat penugasan sistem.</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0" id="tableTasks">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3 ps-4">Informasi Tugas</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3">Tipe & Referensi</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3">Target Role</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3">Pelaksana</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3 text-center">Status</th>
                                <th class="text-uppercase text-muted fw-bold small border-bottom-0 pb-3 text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr class="transition-all">
                                    <td class="ps-4">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark fs-4 mb-1">{{ $task->title }}</span>
                                            <small class="text-muted text-truncate" style="max-width: 200px;">{{ $task->instruction }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="badge {{ $task->task_type === 'Baru' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning' }} rounded-pill px-3 py-1 fw-bold mb-1" style="width: fit-content;">
                                                {{ $task->task_type }}
                                            </span>
                                            @if($task->document_id)
                                                <small class="text-primary fw-medium">{{ $task->referenceDocument->code }}</small>
                                            @else
                                                <small class="text-muted italic">Dokumen Baru</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark-emphasis border px-3 rounded-pill fw-semibold">
                                            {{ $task->targetRole->name }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($task->assignedUser)
                                            <div class="d-flex align-items-center">
                                                <div class="bg-teal-subtle rounded-circle p-2 me-2">
                                                    <i class="ti ti-user-check fs-3 text-teal"></i>
                                                </div>
                                                <span class="fw-semibold">{{ $task->assignedUser->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted small italic">- Belum Diambil -</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $pillClass = 'bg-secondary-subtle text-secondary';
                                            if($task->status === 'Selesai') $pillClass = 'bg-success-subtle text-success border-success-subtle';
                                            elseif($task->status === 'Dikerjakan') $pillClass = 'bg-primary-subtle text-primary border-primary-subtle';
                                            elseif($task->status === 'Menunggu Ketersediaan') $pillClass = 'bg-warning-subtle text-warning border-warning-subtle';
                                        @endphp
                                        <span class="badge rounded-pill {{ $pillClass }} px-3 py-2 fw-bold">
                                            {{ strtoupper($task->status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        @if($task->status === 'Menunggu Ketersediaan' && auth()->user()->roles->contains('id', $task->target_role_id))
                                            <form action="{{ route('document-tasks.update-status', $task->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Dikerjakan">
                                                <button type="submit" class="btn btn-teal btn-sm rounded-pill px-4 fw-bold shadow-sm">
                                                    TERIMA TUGAS
                                                </button>
                                            </form>
                                        @elseif($task->status === 'Dikerjakan' && $task->assigned_user_id === auth()->id())
                                            <div class="btn-group">
                                                <a href="{{ $task->task_type === 'Revisi' ? route('document_revision.edit', ['documentRevision' => $task->referenceDocument->current_revision_id]) : route('document_revision.create') }}" class="btn btn-primary btn-sm rounded-start-pill px-3 fw-bold">
                                                    KERJAKAN
                                                </a>
                                                <form action="{{ route('document-tasks.update-status', $task->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="Selesai">
                                                    <button type="submit" class="btn btn-success btn-sm rounded-end-pill px-3 fw-bold border-start border-white border-opacity-25">
                                                        SELESAI
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <button class="btn btn-light-subtle btn-sm rounded-pill px-3 fw-bold disabled">
                                                <i class="ti ti-lock"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Task -->
    <div class="modal fade" id="modalAddTask" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="border-0 shadow-lg modal-content rounded-4 overflow-hidden">
                <div class="p-4 modal-header border-0 bg-teal-subtle d-flex align-items-center">
                    <div class="p-2 bg-teal text-white rounded-3 me-3"><i class="ti ti-clipboard-plus fs-6"></i></div>
                    <h5 class="mb-0 modal-title fw-bold text-teal">Buat Penugasan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="p-4 modal-body">
                    <form id="formAddTask" action="{{ route('document-tasks.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="mb-2 form-label fw-bold small text-muted">JUDUL PENUGASAN <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control rounded-3" placeholder="Contoh: Revisi SOP Alur Pendaftaran" required>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label class="mb-2 form-label fw-bold small text-muted">TIPE TUGAS <span class="text-danger">*</span></label>
                                <select name="task_type" id="task_type_select" class="form-select rounded-3" required>
                                    <option value="Baru">Dokumen Baru</option>
                                    <option value="Revisi">Revisi Dokumen</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="mb-2 form-label fw-bold small text-muted">TARGET ROLE <span class="text-danger">*</span></label>
                                <select name="target_role_id" class="form-select rounded-3" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3" id="doc_selection_container" style="display: none;">
                            <label class="mb-2 form-label fw-bold small text-muted">PILIH DOKUMEN REFERENSI <span class="text-danger">*</span></label>
                            <select name="document_id" class="form-select rounded-3 select2-task" style="width: 100%;">
                                <option value="">-- Cari Dokumen --</option>
                                @foreach(\App\Models\Document::where('is_active', true)->get() as $doc)
                                    <option value="{{ $doc->id }}">{{ $doc->code }} - {{ $doc->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="mb-2 form-label fw-bold small text-muted">INSTRUKSI PENUGASAN <span class="text-danger">*</span></label>
                            <textarea class="form-control rounded-3" name="instruction" rows="4" placeholder="Tuliskan detail instruksi untuk pelaksana..." required></textarea>
                        </div>
                </div>
                <div class="p-4 border-0 modal-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-white rounded-pill px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-teal rounded-pill px-4 fw-bold shadow-sm">Kirim Penugasan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .text-teal { color: #14b8a6 !important; }
        .bg-teal { background-color: #14b8a6 !important; }
        .bg-teal-subtle { background-color: #f0fdfa !important; }
        .border-teal { border-color: #14b8a6 !important; }
        .btn-teal { background: #14b8a6; color: white; border: none; }
        .btn-teal:hover { background: #0d9488; color: white; }
        
        .transition-all { transition: all 0.2s ease; }
        #tableTasks tbody tr:hover {
            background-color: #f8fafc;
            cursor: default;
        }
    </style>

    @section('customJS')
    <script>
        $(document).ready(function() {
            $('.select2-task').select2({
                dropdownParent: $('#modalAddTask')
            });

            $('#task_type_select').on('change', function() {
                if ($(this).val() === 'Revisi') {
                    $('#doc_selection_container').slideDown();
                } else {
                    $('#doc_selection_container').slideUp();
                }
            });
        });
    </script>
    @endsection
@endsection
