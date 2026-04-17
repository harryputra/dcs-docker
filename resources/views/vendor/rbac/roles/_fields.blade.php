<div class="row g-4 mb-4">
    <div class="col-md-6">
        <label for="role_name" class="form-label fw-bold text-dark mb-2">{!! __('rbac::main.name') !!} <span class="text-danger">*</span></label>
        <div class="input-group input-group-flat shadow-sm">
            <span class="input-group-text bg-white text-muted border-end-0 pe-2"><i class="ti ti-shield-check fs-5"></i></span>
            <input id="role_name" type="text" class="form-control border-start-0 ps-1 @if ($errors->has('name')) is-invalid @endif"
                name="name" value="{{ old('name', isset($role) ? $role->name : null) }}" required autofocus placeholder="Contoh: Administrator">
        </div>
        @if ($errors->has('name'))
            <div class="text-danger small mt-1">
                <strong><i class="ti ti-alert-circle"></i> {{ $errors->first('name') }}</strong>
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <label for="role_description" class="form-label fw-bold text-dark mb-2">{!! __('rbac::main.description') !!} <span class="text-danger">*</span></label>
        <div class="input-group input-group-flat shadow-sm">
            <span class="input-group-text bg-white text-muted border-end-0 pe-2"><i class="ti ti-align-justified fs-5"></i></span>
            <input id="role_description" type="text" class="form-control border-start-0 ps-1 @if ($errors->has('description')) is-invalid @endif"
                name="description" value="{{ old('description', isset($role) ? $role->description : null) }}" required placeholder="Penjelasan tugas role">
        </div>
        @if ($errors->has('description'))
            <div class="text-danger small mt-1">
                <strong><i class="ti ti-alert-circle"></i> {{ $errors->first('description') }}</strong>
            </div>
        @endif
    </div>
</div>

@if ($edit)
    <div class="mb-4">
        <label for="role_slug" class="form-label fw-bold text-dark mb-2">{!! __('rbac::main.slug') !!} <small class="text-muted fw-normal ms-1">({!! __('rbac::main.generated_automatically') !!})</small></label>
        <div class="input-group input-group-flat shadow-sm">
            <span class="input-group-text bg-light text-muted border-end-0 pe-2"><i class="ti ti-link fs-5"></i></span>
            <input id="role_slug" type="text" class="form-control bg-light border-start-0 ps-1 text-muted" value="{{ $role->slug }}" disabled readonly>
        </div>
        <div class="form-text mt-1"><i class="ti ti-info-circle"></i> Identifikasi unik sistem (Tidak dapat diubah).</div>
    </div>
@endif

<div class="card shadow-none border border-1 rounded-3 mb-4">
    <div class="card-header bg-light border-bottom">
        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
            <i class="ti ti-key text-primary fs-5"></i> Penugasan Otoritas Akses (Permissions)
        </h6>
    </div>
    <div class="card-body bg-white p-4">
        @if ($errors->has('permissions'))
            <div class="alert alert-danger px-3 py-2 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="ti ti-alert-triangle fs-4"></i>
                <strong>{{ $errors->first('permissions') }}</strong>
            </div>
        @endif

        @php
            $groupedPermissions = [
                'Manajemen Dokumen' => [],
                'Alur Revisi & Persetujuan' => [],
                'Manajemen Kategori & Pengguna' => [],
                'Administrasi Sistem & Lainnya' => [],
            ];

            foreach ($allPermissions as $id => $name) {
                $lowerName = strtolower($name);
                if (str_contains($lowerName, 'document')) {
                    $groupedPermissions['Manajemen Dokumen'][$id] = $name;
                } elseif (str_contains($lowerName, 'revis') || str_contains($lowerName, 'approv') || str_contains($lowerName, 'histor')) {
                    $groupedPermissions['Alur Revisi & Persetujuan'][$id] = $name;
                } elseif (str_contains($lowerName, 'categor') || str_contains($lowerName, 'user')) {
                    $groupedPermissions['Manajemen Kategori & Pengguna'][$id] = $name;
                } else {
                    $groupedPermissions['Administrasi Sistem & Lainnya'][$id] = $name;
                }
            }
            
            // Clean empty groups just in case
            $groupedPermissions = array_filter($groupedPermissions, fn($group) => count($group) > 0);
        @endphp

        <div class="row g-4">
            @foreach ($groupedPermissions as $groupName => $permissions)
                <div class="col-12">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2 d-flex align-items-center gap-2">
                        <i class="ti ti-folder fs-5"></i> Konfigurasi {{ $groupName }}
                    </h6>
                    <div class="row g-3">
                        @foreach ($permissions as $id => $name)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-check custom-control custom-checkbox p-3 border border-2 rounded-3 transition-hover m-0 d-flex align-items-center position-relative {{ isset($currentPermissions) && in_array($id, $currentPermissions) ? 'border-primary bg-primary-subtle' : 'bg-light border-light' }}" style="min-height: 60px;">
                                    <input class="form-check-input ms-0 me-3 form-check-input-lg shadow-sm @if ($errors->has('permissions')) is-invalid @endif" type="checkbox"
                                        name="permissions[]" value="{{ $id }}" id="permission_checkbox_{{ $id }}"
                                        @if (isset($currentPermissions) && in_array($id, $currentPermissions)) checked @endif
                                        onchange="this.parentElement.classList.toggle('border-primary'); this.parentElement.classList.toggle('bg-primary-subtle'); this.parentElement.classList.toggle('bg-light'); this.parentElement.classList.toggle('border-light');">
                                    <label class="form-check-label fw-medium text-dark stretched-link cursor-pointer m-0 lh-sm w-100 fs-9" for="permission_checkbox_{{ $id }}">
                                        {{ ucwords(str_replace('_', ' ', $name)) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .form-check-input-lg {
        width: 1.25em;
        height: 1.25em;
        margin-top: 0;
    }
    .custom-control.form-check:hover {
        border-color: var(--bs-primary) !important;
        background-color: var(--bs-primary-bg-subtle);
    }
</style>
