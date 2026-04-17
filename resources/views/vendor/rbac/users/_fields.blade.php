<div class="card shadow-none border border-1 rounded-3 mb-4">
    <div class="card-header bg-light border-bottom">
        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
            <i class="ti ti-shield-check text-info fs-5"></i> Penugasan Role Aktif
        </h6>
    </div>
    <div class="card-body bg-white p-4">
        @if ($errors->has('roles'))
            <div class="alert alert-danger px-3 py-2 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="ti ti-alert-triangle fs-4"></i>
                <strong>{{ $errors->first('roles') }}</strong>
            </div>
        @endif

        <div class="row g-3" id="roles_form_group">
            @foreach($allRoles as $key => $role)
                <div class="col-md-4 col-sm-6">
                    @php
                        $isDisabled = Auth::user()->cannot(\Itstructure\LaRbac\Models\Permission::ASSIGN_ROLE_FLAG, \Itstructure\LaRbac\Classes\MemberToRole::make($user, $role));
                        $isChecked = isset($currentRoles) && in_array($role->id, $currentRoles);
                    @endphp
                    <div class="form-check custom-control custom-checkbox p-3 border border-2 rounded-3 m-0 d-flex align-items-center position-relative {{ $isChecked ? ($isDisabled ? 'bg-info border-info text-white' : 'border-info bg-info-subtle') : 'bg-light border-light' }} {{ $isDisabled ? 'opacity-75' : 'transition-hover' }}" style="min-height: 60px;">
                        <input class="form-check-input ms-0 me-3 form-check-input-lg shadow-sm @if($errors->has('roles')) is-invalid @endif" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_checkbox_{{$key}}"
                            @if($isDisabled) disabled @endif
                            @if($isChecked) checked @endif
                            @if(!$isDisabled) onchange="this.parentElement.classList.toggle('border-info'); this.parentElement.classList.toggle('bg-info-subtle'); this.parentElement.classList.toggle('bg-light'); this.parentElement.classList.toggle('border-light');" @endif>
                        <label class="form-check-label fw-medium stretched-link cursor-pointer m-0 lh-sm w-100 {{ $isChecked && $isDisabled ? 'text-white' : 'text-dark' }}" for="role_checkbox_{{$key}}">
                            {{ $role->name }}
                        </label>
                    </div>

                    @if($isChecked && $isDisabled)
                        <input type="hidden" name="roles[]" value="{{ $role->id }}">
                    @endif
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
    .custom-control.form-check.transition-hover:hover {
        border-color: var(--bs-info) !important;
        background-color: var(--bs-info-bg-subtle);
    }
</style>
