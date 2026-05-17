@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Admin')

@section('page-style')
<style>
:root {
    --accent: #696cff;
    --accent-soft: #eef0ff;
    --card-radius: 20px;
    --shadow: 0 8px 28px rgba(18,38,63,.08);
}
.page-card { background:#fff; border-radius:var(--card-radius); box-shadow:var(--shadow); border:none; overflow:hidden; }
.page-card-header { padding:28px 32px; border-bottom:1px solid #f1f3f9; background:linear-gradient(135deg,#ff9f43,#ffb976); color:#fff; }
.form-section-title { font-size:11px; font-weight:700; letter-spacing:.8px; text-transform:uppercase; color:#3d4a5c; margin-bottom:16px; padding:10px 14px; border-left:4px solid #ff9f43; background:#fffbf5; border-radius:0 10px 10px 0; }
.form-control, .form-select { border-radius:12px; border-color:#e2e6ef; padding:12px 14px; font-size:14px; transition:.2s ease; }
.form-control:focus, .form-select:focus { border-color:#ff9f43; box-shadow:0 0 0 3px rgba(255,159,67,.12); }
.perm-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(180px,1fr)); gap:8px; }
.perm-item { background:#fafbff; border:1.5px solid #e8eaf2; border-radius:12px; padding:11px 14px; display:flex; align-items:center; gap:10px; cursor:pointer; transition:.2s ease; }
.perm-item:has(input:checked) { background:#fff3e5; border-color:#ff9f43; }
.perm-item input { flex-shrink:0; cursor:pointer; }
.perm-item span { font-size:13px; font-weight:600; cursor:pointer; }
.select-all-box { background:#f8f9fc; border:1.5px dashed #d0d4e8; border-radius:12px; padding:12px 16px; display:flex; align-items:center; gap:10px; cursor:pointer; margin-bottom:12px; transition:.2s ease; }
.select-all-box:hover { background:#fff3e5; border-color:#ff9f43; }
.btn-submit { border-radius:14px; padding:13px 36px; font-weight:700; font-size:15px; background:linear-gradient(135deg,#ff9f43,#ffb976); border:none; box-shadow:0 8px 20px rgba(255,159,67,.3); transition:.25s ease; }
.btn-submit:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(255,159,67,.4); }
.btn-cancel { border-radius:14px; padding:13px 28px; font-weight:600; background:#f4f5fa; border:none; color:#5d6a82; transition:.2s ease; }
.btn-cancel:hover { background:#e8eaf2; }
.error-msg { font-size:12px; color:#ea5455; margin-top:5px; display:block; }

/* ── Role Selection Cards ── */
.role-card {
    position:relative; display:flex; align-items:flex-start; gap:14px;
    background:#fff; border:2px solid #e8eaf2; border-radius:14px;
    padding:14px 16px; cursor:pointer; width:100%;
    transition:border-color .2s, background .2s, box-shadow .2s; margin-bottom:8px;
}
.role-card:last-child { margin-bottom:0; }
.role-card:hover { border-color:#ffc080; background:#fffdf9; box-shadow:0 3px 12px rgba(255,159,67,.08); }
.role-card:has(input:checked) { border-color:#ff9f43; background:#fffbf5; box-shadow:0 0 0 4px rgba(255,159,67,.12); }
.role-card input[type="radio"] { position:absolute; opacity:0; width:0; height:0; }
.role-card-icon {
    width:42px; height:42px; background:#f4f5fa; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    font-size:20px; color:#8592a3; flex-shrink:0; transition:all .2s;
}
.role-card:has(input:checked) .role-card-icon { background:#fff3e5; color:#ff9f43; }
.role-card-body { flex:1; min-width:0; }
.role-card-name { font-size:13px; font-weight:700; color:#1e293b; margin-bottom:7px; }
.role-card-perms { display:flex; flex-wrap:wrap; gap:5px; }
.role-perm-tag { background:#f4f5fa; color:#5d6a82; padding:3px 9px; border-radius:6px; font-size:11px; font-weight:600; display:inline-block; transition:all .2s; }
.role-card:has(input:checked) .role-perm-tag { background:#fff3e5; color:#ff9f43; }
.role-check {
    width:24px; height:24px; border-radius:50%; border:2px solid #d5d9e3;
    display:flex; align-items:center; justify-content:center;
    font-size:12px; color:transparent; flex-shrink:0; margin-top:2px; transition:all .25s;
}
.role-card:has(input:checked) .role-check { background:#ff9f43; border-color:#ff9f43; color:#fff; }
</style>
@endsection

@section('content')

<div class="mb-3">
    <a href="{{ route('adminsindex') }}" class="text-muted small d-inline-flex align-items-center gap-1" style="text-decoration:none;">
        <i class="ri ri-arrow-left-line"></i> Back to Admins
    </a>
</div>

@if(session('success'))
<div style="background:#e8f8ef;color:#1a7a45;border:1px solid #c3e9d4;border-radius:12px;padding:12px 16px;font-size:13px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
    <i class="ri ri-checkbox-circle-line"></i> {{ session('success') }}
</div>
@endif

<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="page-card">

            <div class="page-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;">
                        <i class="ri ri-user-settings-line" style="font-size:22px;color:#fff;"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-white">Edit Admin — {{ $admin->name }}</h5>
                        <small style="color:rgba(255,255,255,.75);">Update role and permissions</small>
                    </div>
                </div>
            </div>

            <div class="p-4 p-lg-5">
                <form action="{{ route('admins.update', $admin->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Basic Info --}}
                    <div class="form-section-title">Basic Information</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $admin->name) }}" required>
                            @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $admin->email) }}" required>
                            @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">New Password <span style="color:#8592a3;font-weight:400;">(leave blank to keep current)</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min 8 characters">
                            @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                        </div>
                    </div>

                    {{-- Roles (multi-select) --}}
                    <div class="form-section-title">
                        Assign Roles
                        <span style="font-size:11px;font-weight:400;color:#8592a3;text-transform:none;letter-spacing:0;">— يمكن اختيار أكثر من رول</span>
                    </div>

                    @php
                        $currentRoleNames   = $admin->roles->pluck('name')->toArray();
                        $currentPermNames   = $admin->permissions->pluck('name')->toArray();
                        $rolePermissionsMap = $roles->mapWithKeys(fn($r) => [$r->name => $r->permissions->pluck('name')]);
                    @endphp
                    <div id="rolePermissionsData" data-map="{{ json_encode($rolePermissionsMap) }}" style="display:none;"></div>

                    <div class="mb-1">
                        @foreach($roles as $role)
                        <label class="role-card" for="role_{{ $role->id }}">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                   id="role_{{ $role->id }}" class="role-cb"
                                   {{ in_array($role->name, old('roles', $currentRoleNames)) ? 'checked' : '' }}>
                            <div class="role-card-icon"><i class="ri ri-shield-star-line"></i></div>
                            <div class="role-card-body">
                                <div class="role-card-name">{{ $role->name }}</div>
                                <div class="role-card-perms">
                                    @forelse($role->permissions as $rp)
                                        <span class="role-perm-tag">{{ $rp->name }}</span>
                                    @empty
                                        <span style="font-size:11px;color:#8592a3;font-style:italic;">No permissions assigned yet</span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="role-check"><i class="ri ri-check-line"></i></div>
                        </label>
                        @endforeach
                    </div>
                    @error('roles') <span class="error-msg d-block mt-2">{{ $message }}</span> @enderror

                    {{-- Extra Permissions --}}
                    <div class="form-section-title" style="margin-top:24px;">
                        Extra Permissions
                        <span style="font-size:11px;font-weight:400;color:#8592a3;text-transform:none;letter-spacing:0;">
                            — optional, added on top of the role
                        </span>
                    </div>

                    <div style="background:#fafbff;border-radius:14px;padding:16px;border:1.5px solid #e8eaf2;">
                    @if($permissions->count())
                    <label class="select-all-box">
                        <input class="form-check-input" type="checkbox" id="selectAllEdit">
                        <span class="fw-bold" style="font-size:13px;">Select All Permissions</span>
                        <span class="ms-auto badge" style="background:#fff3e5;color:#ff9f43;border-radius:999px;">{{ $permissions->count() }}</span>
                    </label>
                    <div class="perm-grid">
                        @foreach($permissions as $permission)
                        <label class="perm-item" for="perm_edit_{{ $permission->id }}">
                            <input class="form-check-input perm-edit-cb"
                                   type="checkbox" name="permissions[]"
                                   value="{{ $permission->name }}"
                                   id="perm_edit_{{ $permission->id }}"
                                   {{ in_array($permission->name, old('permissions', $currentPermNames)) ? 'checked' : '' }}>
                            <span>{{ $permission->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="ri ri-shield-line" style="font-size:36px;color:#c8ccda;"></i>
                        <p class="text-muted small mb-0 mt-2">No permissions available.</p>
                    </div>
                    @endif
                    </div>
                    @error('permissions') <span class="error-msg d-block mt-2">{{ $message }}</span> @enderror

                    <div class="d-flex justify-content-end gap-3 mt-5 pt-3" style="border-top:1px solid #f1f3f9;">
                        <a href="{{ route('adminsindex') }}" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-submit text-white">
                            <i class="ri ri-save-line me-2"></i>Save Changes
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
    const selectAll  = document.getElementById('selectAllEdit');
    const checkboxes = document.querySelectorAll('.perm-edit-cb');
    const roleMap    = JSON.parse(document.getElementById('rolePermissionsData').dataset.map || '{}');

    function syncSelectAll() {
        if (!selectAll) return;
        const checked = [...checkboxes].filter(c => c.checked).length;
        selectAll.checked       = checkboxes.length > 0 && checked === checkboxes.length;
        selectAll.indeterminate = checked > 0 && checked < checkboxes.length;
    }

    // Multi-role: merge permissions from all checked roles
    document.querySelectorAll('.role-cb').forEach(cb => {
        cb.addEventListener('change', function () {
            const selectedRoles = [...document.querySelectorAll('.role-cb:checked')].map(c => c.value);
            const merged = selectedRoles.reduce((acc, name) => {
                return [...new Set([...acc, ...(roleMap[name] || [])])];
            }, []);
            checkboxes.forEach(c => { c.checked = merged.includes(c.value); });
            syncSelectAll();
        });
    });

    if (selectAll) {
        selectAll.addEventListener('change', () => checkboxes.forEach(c => c.checked = selectAll.checked));
    }
    checkboxes.forEach(c => c.addEventListener('change', syncSelectAll));
    syncSelectAll();
});
</script>
@endpush

@endsection
