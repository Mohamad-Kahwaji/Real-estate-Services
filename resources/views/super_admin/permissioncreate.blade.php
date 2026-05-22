@extends('layouts/contentNavbarLayout')

@section('title', 'Create Permission')

@section('page-style')
<style>
:root {
    --accent: #696cff;
    --accent-soft: #eef0ff;
    --shadow: 0 4px 24px rgba(105,108,255,.10);
    --radius: 16px;
}

/* Back link */
.back-link {
    display: inline-flex; align-items: center; gap: 6px;
    color: #8592a3; font-size: 13px; font-weight: 600;
    text-decoration: none; margin-bottom: 20px;
    transition: color .2s;
}
.back-link:hover { color: #696cff; }

/* Form card */
.form-card {
    background: #fff; border-radius: var(--radius);
    box-shadow: var(--shadow); overflow: hidden;
}

.form-card-banner {
    background: linear-gradient(135deg, #696cff 0%, #9c9eff 100%);
    padding: 28px 32px; position: relative; overflow: hidden;
}
.form-card-banner::before {
    content: ''; position: absolute;
    top: -40px; right: -40px;
    width: 160px; height: 160px;
    background: rgba(255,255,255,.08); border-radius: 50%;
}
.form-card-banner::after {
    content: ''; position: absolute;
    bottom: -50px; right: 100px;
    width: 120px; height: 120px;
    background: rgba(255,255,255,.05); border-radius: 50%;
}
.banner-icon {
    width: 52px; height: 52px; border-radius: 16px;
    background: rgba(255,255,255,.18); border: 2px solid rgba(255,255,255,.25);
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: #fff; flex-shrink: 0;
}

.form-body { padding: 32px; }

/* Inputs */
.form-label { font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
.form-control, .form-select {
    border-radius: 12px; border: 1.5px solid #e4e4eb;
    padding: 12px 16px; font-size: 14px; background: #fafbff;
    transition: .2s ease; color: #1e293b;
}
.form-control:focus, .form-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(105,108,255,.12);
    background: #fff; outline: none;
}
.form-control.is-invalid { border-color: #ea5455; }
.invalid-feedback { font-size: 12px; color: #ea5455; }
.form-text { font-size: 12px; color: #8592a3; margin-top: 6px; }

/* Section label */
.section-label {
    font-size: 11px; font-weight: 700; letter-spacing: .8px;
    text-transform: uppercase; color: #8592a3;
    padding-bottom: 10px; margin-bottom: 18px;
    border-bottom: 2px solid #f0eef8;
}

/* Guard cards */
.guard-card {
    border: 2px solid #e8eaf2; border-radius: 14px;
    padding: 16px 18px; cursor: pointer; transition: .2s ease;
    display: flex; align-items: center; gap: 14px;
    background: #fafbff;
}
.guard-card:has(input:checked) {
    border-color: var(--accent); background: var(--accent-soft);
}
.guard-card:hover { border-color: #c5c7ff; }
.guard-card input { display: none; }
.guard-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.guard-name { font-size: 14px; font-weight: 700; color: #1e293b; }
.guard-desc { font-size: 12px; color: #8592a3; }
.guard-check {
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid #c8ccda; margin-left: auto; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: .2s ease;
}
.guard-card:has(input:checked) .guard-check {
    background: var(--accent); border-color: var(--accent); color: #fff;
}
.guard-check::after {
    content: '✓'; font-size: 11px; font-weight: 800; color: transparent;
}
.guard-card:has(input:checked) .guard-check::after { color: #fff; }

/* Buttons */
.btn-submit {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    color: #fff; border: none; border-radius: 12px;
    padding: 12px 28px; font-weight: 700; font-size: 14px;
    transition: .2s ease; cursor: pointer;
}
.btn-submit:hover { opacity: .9; transform: translateY(-1px); color: #fff; }
.btn-cancel {
    display: inline-flex; align-items: center; gap: 8px;
    background: #f4f5fa; color: #5d6a82; border: none;
    border-radius: 12px; padding: 12px 22px;
    font-weight: 600; font-size: 14px; text-decoration: none;
    transition: .2s ease;
}
.btn-cancel:hover { background: #e8eaf2; color: #5d6a82; }

/* Permissions list card */
.list-card {
    background: #fff; border-radius: var(--radius);
    box-shadow: var(--shadow); overflow: hidden; margin-top: 28px;
}
.list-card-header {
    padding: 18px 24px; border-bottom: 1px solid #f0eef8;
    display: flex; align-items: center; justify-content: space-between;
}
.list-card-header h6 { font-weight: 700; color: #1e293b; margin: 0; }

/* Permissions grid */
.perm-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 12px; padding: 20px 24px;
}
.perm-item {
    display: flex; align-items: center; gap: 10px;
    background: #fafbff; border: 1px solid #f0eef8;
    border-radius: 12px; padding: 12px 14px;
    transition: .15s; cursor: default;
}
.perm-item.role-match {
    background: #eef0ff; border-color: #696cff; cursor: pointer;
}
.perm-item.role-match .perm-dot { background: #696cff; }
.perm-item.manually-excluded {
    opacity: .45; background: #fff5f5; border-color: #fca5a5; cursor: pointer;
}
.perm-item.manually-excluded .perm-name { text-decoration: line-through; color: #ea5455; }
.perm-item.manually-excluded .perm-dot  { background: #ea5455; }
.perm-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--accent); flex-shrink: 0;
}
.perm-name { font-size: 13px; font-weight: 600; color: #1e293b; }
.perm-guard { font-size: 11px; color: #8592a3; }

/* Alert */
.al-alert { border-radius: 12px; padding: 12px 16px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.al-alert-success { background: #e8f8ef; color: #1a7a45; border: 1px solid #c3e9d4; }
.al-alert-danger  { background: #fdeaea; color: #7a1a1a; border: 1px solid #f4c2c2; }

.empty-perm { padding: 40px 20px; text-align: center; color: #8592a3; }
.empty-perm i { font-size: 40px; color: #c8ccda; display: block; margin-bottom: 10px; }
</style>
@endsection

@section('content')

@if(session('success'))
<div class="al-alert al-alert-success">
    <i class="ri ri-checkbox-circle-line"></i> {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="al-alert al-alert-danger">
    <i class="ri ri-error-warning-line"></i> Please fix the errors below.
</div>
@endif

{{-- Back --}}
<a href="{{ route('roleindex') }}" class="back-link">
    <i class="ri ri-arrow-left-s-line" style="font-size:18px;"></i> Back to Roles
</a>

<div class="row justify-content-center">
    <div class="col-12 col-lg-7">

        {{-- Form Card --}}
        <div class="form-card">

            {{-- Banner --}}
            <div class="form-card-banner">
                <div class="d-flex align-items-center gap-3" style="position:relative;z-index:1;">
                    <div class="banner-icon">
                        <i class="ri ri-key-2-line"></i>
                    </div>
                    <div>
                        <div style="font-size:19px;font-weight:800;color:#fff;margin-bottom:3px;">Create New Permission</div>
                        <div style="font-size:13px;color:rgba(255,255,255,.7);">Add a permission and assign it to a guard</div>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="form-body">
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf

                    {{-- Permission Name --}}
                    <div class="section-label">Permission Details</div>

                    <div class="mb-4">
                        <label class="form-label">Permission Name</label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g. manage services, edit category, view reports">
                        <div class="form-text">Use clear, descriptive names like <code>manage services</code> or <code>delete user</code></div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Guard Type --}}
                    <div class="section-label">Guard Type</div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="guard-card w-100" for="guard_admin">
                                <input type="radio" id="guard_admin" name="guard_name" value="admins"
                                       {{ old('guard_name', 'admins') === 'admins' ? 'checked' : '' }}>
                                <div class="guard-icon" style="background:#eef0ff;">
                                    <i class="ri ri-user-settings-line" style="color:#696cff;"></i>
                                </div>
                                <div style="flex:1;">
                                    <div class="guard-name">Admin</div>
                                    <div class="guard-desc">For admin panel users</div>
                                </div>
                                <div class="guard-check"></div>
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="guard-card w-100" for="guard_web">
                                <input type="radio" id="guard_web" name="guard_name" value="web"
                                       {{ old('guard_name') === 'web' ? 'checked' : '' }}>
                                <div class="guard-icon" style="background:#e3f8fc;">
                                    <i class="ri ri-user-line" style="color:#00cfe8;"></i>
                                </div>
                                <div style="flex:1;">
                                    <div class="guard-name">Web User</div>
                                    <div class="guard-desc">For regular app users</div>
                                </div>
                                <div class="guard-check"></div>
                            </label>
                        </div>
                    </div>

                    @error('guard_name')
                        <div class="invalid-feedback d-block mb-3">{{ $message }}</div>
                    @enderror

                    {{-- Assign to Roles --}}
                    <div class="section-label" style="margin-top:24px;">Assign to Roles <span style="font-weight:400;text-transform:none;letter-spacing:0;">(optional)</span></div>

                    @if($roles->count())
                    <div class="row g-2 mb-4" id="rolesContainer">
                        @foreach($roles as $role)
                        <div class="col-6 col-md-4">
                            <label style="display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:12px;
                                          cursor:pointer;border:1.5px solid #e8eaf2;background:#fafbff;transition:.15s;width:100%;"
                                   onmouseover="this.style.borderColor='#696cff';this.style.background='#f2f0ff'"
                                   onmouseout="if(!this.querySelector('input').checked){this.style.borderColor='#e8eaf2';this.style.background='#fafbff';}">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                       data-permissions="{{ json_encode($role->permissions->pluck('name')) }}"
                                       style="accent-color:#696cff;width:16px;height:16px;flex-shrink:0;"
                                       onchange="highlightRole(this); filterPermsByRoles();"
                                       {{ is_array(old('roles')) && in_array($role->name, old('roles')) ? 'checked' : '' }}>
                                <div>
                                    <div style="font-size:13px;font-weight:700;color:#1e293b;">{{ $role->name }}</div>
                                    <div style="font-size:11px;color:#8592a3;">{{ $role->permissions->count() }} perms</div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div style="background:#fafbff;border-radius:12px;padding:16px;color:#8592a3;font-size:13px;margin-bottom:16px;">
                        No admin roles found. <a href="{{ route('roleindex') }}" style="color:#696cff;">Create roles first.</a>
                    </div>
                    @endif

                    {{-- Actions --}}
                    <div class="d-flex align-items-center gap-3 pt-3" style="border-top:1px solid #f0eef8;">
                        <a href="{{ route('roleindex') }}" class="btn-cancel">
                            <i class="ri ri-arrow-left-line"></i> Cancel
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="ri ri-key-2-line"></i> Create Permission
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- Existing Permissions --}}
        @if(isset($permissions) && $permissions->count() > 0)
        <div class="list-card">
            <div class="list-card-header">
                <h6><i class="ri ri-key-2-line me-2" style="color:#696cff;"></i>Existing Permissions</h6>
                <span id="permCount" style="font-size:12px;color:#8592a3;font-weight:600;">{{ $permissions->count() }} total</span>
            </div>
            <div class="perm-grid">
                @foreach($permissions as $perm)
                <div class="perm-item" data-perm="{{ $perm->name }}">
                    <div class="perm-dot"
                         style="background:{{ $perm->guard_name === 'admins' ? '#696cff' : '#00cfe8' }};"></div>
                    <div>
                        <div class="perm-name">{{ $perm->name }}</div>
                        <div class="perm-guard">{{ $perm->guard_name }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @elseif(isset($permissions))
        <div class="list-card">
            <div class="list-card-header">
                <h6><i class="ri ri-key-2-line me-2" style="color:#696cff;"></i>Existing Permissions</h6>
            </div>
            <div class="empty-perm">
                <i class="ri ri-key-2-line"></i>
                <p style="font-weight:600;margin:0;">No permissions created yet</p>
            </div>
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
function highlightRole(cb) {
    const label = cb.closest('label');
    if (cb.checked) {
        label.style.borderColor = '#696cff';
        label.style.background  = '#f2f0ff';
    } else {
        label.style.borderColor = '#e8eaf2';
        label.style.background  = '#fafbff';
    }
}

function updateCounter() {
    const counter = document.getElementById('permCount');
    if (!counter) return;
    const items   = [...document.querySelectorAll('.perm-item')];
    const anyRole = document.querySelectorAll('#rolesContainer input:checked').length > 0;
    if (!anyRole) {
        counter.textContent = items.length + ' total';
    } else {
        const shown = items.filter(el => el.style.display !== 'none').length;
        counter.textContent = shown + ' / ' + items.length;
    }
}

function filterPermsByRoles() {
    const checked = [...document.querySelectorAll('#rolesContainer input:checked')];
    const items   = [...document.querySelectorAll('.perm-item')];

    // Reset all state classes and visibility
    items.forEach(el => {
        el.classList.remove('role-match', 'manually-excluded');
        el.style.display = '';
    });

    if (checked.length === 0) {
        updateCounter();
        return;
    }

    const allowed = new Set();
    checked.forEach(cb => {
        JSON.parse(cb.dataset.permissions || '[]').forEach(p => allowed.add(p));
    });

    items.forEach(el => {
        if (allowed.has(el.dataset.perm)) {
            el.classList.add('role-match');
        } else {
            el.style.display = 'none';
        }
    });

    updateCounter();
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('#rolesContainer input:checked').forEach(highlightRole);
    filterPermsByRoles();

    // Toggle individual permission cards
    document.querySelectorAll('.perm-item').forEach(item => {
        item.addEventListener('click', () => {
            const anyRole = document.querySelectorAll('#rolesContainer input:checked').length > 0;
            if (!anyRole) return;

            if (item.classList.contains('role-match')) {
                item.classList.replace('role-match', 'manually-excluded');
            } else if (item.classList.contains('manually-excluded')) {
                item.classList.replace('manually-excluded', 'role-match');
            }

            // Auto-uncheck any role whose ALL permissions are now manually excluded
            let anyAutoUnchecked = false;
            document.querySelectorAll('#rolesContainer input[type="checkbox"]:checked').forEach(cb => {
                const perms = JSON.parse(cb.dataset.permissions || '[]');
                if (!perms.length) return;
                const allExcluded = perms.every(p => {
                    const el = document.querySelector('.perm-item[data-perm="' + p.replace(/\\/g,'\\\\').replace(/"/g,'\\"') + '"]');
                    return el && el.classList.contains('manually-excluded');
                });
                if (allExcluded) {
                    cb.checked = false;
                    highlightRole(cb);
                    anyAutoUnchecked = true;
                }
            });

            // If a role was auto-unchecked, recalculate the whole filter
            if (anyAutoUnchecked) {
                filterPermsByRoles();
            } else {
                updateCounter();
            }
        });
    });
});
</script>
@endpush

@endsection
