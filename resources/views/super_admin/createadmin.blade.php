@extends('layouts/contentNavbarLayout')

@section('title', 'Create Admin')

@push('styles')
<style>
:root {
    --accent: #696cff;
    --accent-soft: #eef0ff;
    --card-radius: 20px;
    --shadow: 0 8px 28px rgba(18,38,63,.08);
}

.page-card {
    background: #fff;
    border-radius: var(--card-radius);
    box-shadow: var(--shadow);
    border: none;
    overflow: hidden;
}

.page-card-header {
    padding: 28px 32px;
    border-bottom: 1px solid #f1f3f9;
    background: linear-gradient(135deg, #696cff 0%, #9c9eff 100%);
    color: #fff;
}

.form-section-title {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .8px;
    text-transform: uppercase;
    color: #8592a3;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f1f3f9;
}

.form-control, .form-select {
    border-radius: 12px;
    border-color: #e2e6ef;
    padding: 12px 14px;
    font-size: 14px;
    transition: .2s ease;
}
.form-control:focus, .form-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(105,108,255,.12);
}

.perm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 8px;
}

.perm-item {
    background: #fafbff;
    border: 1.5px solid #e8eaf2;
    border-radius: 12px;
    padding: 11px 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: .2s ease;
}

.perm-item:has(input:checked) {
    background: var(--accent-soft);
    border-color: var(--accent);
}

.perm-item input { flex-shrink: 0; cursor: pointer; }
.perm-item span  { font-size: 13px; font-weight: 600; cursor: pointer; }

.select-all-box {
    background: #f8f9fc;
    border: 1.5px dashed #d0d4e8;
    border-radius: 12px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    margin-bottom: 12px;
    transition: .2s ease;
}
.select-all-box:hover { background: var(--accent-soft); border-color: var(--accent); }

.btn-submit {
    border-radius: 14px;
    padding: 13px 36px;
    font-weight: 700;
    font-size: 15px;
    background: linear-gradient(135deg, #696cff, #8f91ff);
    border: none;
    box-shadow: 0 8px 20px rgba(105,108,255,.3);
    transition: .25s ease;
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(105,108,255,.4); }

.btn-cancel {
    border-radius: 14px;
    padding: 13px 28px;
    font-weight: 600;
    background: #f4f5fa;
    border: none;
    color: #5d6a82;
    transition: .2s ease;
}
.btn-cancel:hover { background: #e8eaf2; }

.error-msg { font-size: 12px; color: #ea5455; margin-top: 5px; display: block; }
</style>
@endpush

@section('content')

{{-- Back link --}}
<div class="mb-3">
    <a href="{{ route('adminsindex') }}" class="text-muted small d-inline-flex align-items-center gap-1"
       style="text-decoration:none;">
        <i class="ri ri-arrow-left-line"></i> Back to Admins
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="page-card">

            {{-- Header --}}
            <div class="page-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:14px;
                                display:flex;align-items:center;justify-content:center;">
                        <i class="ri ri-user-add-line" style="font-size:22px;color:#fff;"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-white">Create New Admin</h5>
                        <small style="color:rgba(255,255,255,.75);">Add a new admin and assign permissions</small>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="p-4 p-lg-5">
                <form action="{{ route('admins.store') }}" method="POST">
                    @csrf

                    {{-- Basic Info --}}
                    <div class="form-section-title">Basic Information</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="e.g. John Doe" required>
                            @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="e.g. admin@example.com" required>
                            @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min 8 characters" required>
                            @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control" placeholder="Repeat password" required>
                        </div>
                    </div>

                    {{-- Permissions --}}
                    <div class="form-section-title">Permissions</div>

                    @if($permissions->count())
                    <label class="select-all-box">
                        <input class="form-check-input" type="checkbox" id="selectAllCreate">
                        <span class="fw-bold" style="font-size:13px;">Select All Permissions</span>
                        <span class="ms-auto badge" style="background:#eef0ff;color:#696cff;border-radius:999px;">
                            {{ $permissions->count() }}
                        </span>
                    </label>

                    <div class="perm-grid">
                        @foreach($permissions as $permission)
                        <label class="perm-item" for="perm_create_{{ $permission->id }}">
                            <input class="form-check-input perm-create-cb"
                                   type="checkbox"
                                   name="permissions[]"
                                   value="{{ $permission->name }}"
                                   id="perm_create_{{ $permission->id }}"
                                   {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                            <span>{{ $permission->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4" style="background:#fafbff;border-radius:14px;">
                        <i class="ri ri-shield-line" style="font-size:36px;color:#c8ccda;"></i>
                        <p class="text-muted small mb-0 mt-2">No permissions available. Create permissions first.</p>
                    </div>
                    @endif

                    @error('permissions') <span class="error-msg">{{ $message }}</span> @enderror

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-3 mt-5 pt-3" style="border-top:1px solid #f1f3f9;">
                        <a href="{{ route('adminsindex') }}" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-submit text-white">
                            <i class="ri ri-user-add-line me-2"></i>Create Admin
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAllCreate');
    const checkboxes = document.querySelectorAll('.perm-create-cb');
    if (!selectAll) return;

    function syncState() {
        const checked = [...checkboxes].filter(c => c.checked).length;
        selectAll.checked       = checkboxes.length > 0 && checked === checkboxes.length;
        selectAll.indeterminate = checked > 0 && checked < checkboxes.length;
    }

    selectAll.addEventListener('change', () => checkboxes.forEach(c => c.checked = selectAll.checked));
    checkboxes.forEach(c => c.addEventListener('change', syncState));
    syncState();
});
</script>
@endpush

@endsection
