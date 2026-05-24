@extends('layouts/contentNavbarLayout')

@section('title', __('app.roles_permissions'))

@section('page-style')
<style>
:root {
    --accent: #696cff;
    --accent-soft: #eef0ff;
    --success: #28c76f;
    --danger: #ea5455;
    --warning: #ff9f43;
    --shadow: 0 4px 24px rgba(105,108,255,.10);
    --radius: 16px;
}

/* Header */
.pg-header h4 { font-size: 22px; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
.pg-header p  { font-size: 13px; color: #8592a3; margin: 0; }

/* Stats */
.stat-card {
    background: #fff; border-radius: var(--radius);
    box-shadow: var(--shadow); padding: 20px 24px;
    display: flex; align-items: center; gap: 16px;
}
.stat-icon {
    width: 50px; height: 50px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.stat-label { font-size: 12px; color: #8592a3; font-weight: 600; }
.stat-value { font-size: 26px; font-weight: 800; line-height: 1; color: #1e293b; }

/* Table card */
.table-card { background: #fff; border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
.table-card-header {
    padding: 18px 24px; border-bottom: 1px solid #f0eef8;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
}
.table-card-header h6 { font-weight: 700; color: #1e293b; margin: 0; font-size: 15px; }

/* Table */
.table { margin: 0; }
.table thead th {
    background: #fafbff; font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .7px;
    color: #8592a3; border-bottom: 1px solid #f0eef8; padding: 14px 20px;
}
.table tbody td {
    padding: 14px 20px; vertical-align: middle;
    border-bottom: 1px solid #f7f7f9; font-size: 14px; color: #1e293b;
}
.table tbody tr:last-child td { border-bottom: none; }
.table tbody tr:hover td { background: #fafbff; }

/* Role chip */
.role-chip {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--accent-soft); color: var(--accent);
    border-radius: 10px; padding: 8px 14px;
    font-weight: 700; font-size: 13px;
}
.role-chip i { font-size: 16px; }

/* Permission tags */
.perm-tag {
    display: inline-flex; align-items: center; gap: 4px;
    background: #f4f5fa; color: #5d6a82;
    border-radius: 8px; padding: 4px 10px;
    font-size: 12px; font-weight: 600; margin: 2px;
    border: 1px solid #e8eaf2;
}
.perm-tag i { font-size: 11px; }

/* Count badge */
.count-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--accent-soft); color: var(--accent);
    border-radius: 999px; padding: 4px 12px;
    font-size: 12px; font-weight: 700;
}

/* Action buttons */
.action-btn {
    width: 34px; height: 34px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 10px; font-size: 16px; border: none;
    transition: .2s ease; text-decoration: none; cursor: pointer;
}
.action-btn-edit   { background: #eef0ff; color: #696cff; }
.action-btn-delete { background: #fdeaea; color: #ea5455; }
.action-btn-edit:hover   { background: #696cff; color: #fff; }
.action-btn-delete:hover { background: #ea5455; color: #fff; }

/* Create button */
.btn-create {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    color: #fff; border: none; border-radius: 12px;
    padding: 10px 20px; font-weight: 700; font-size: 14px;
    text-decoration: none; transition: .2s ease;
}
.btn-create:hover { opacity: .9; color: #fff; transform: translateY(-1px); }

/* Empty state */
.empty-state { padding: 60px 20px; text-align: center; }
.empty-state i { font-size: 48px; color: #c8ccda; display: block; margin-bottom: 14px; }
.empty-state p { color: #8592a3; font-weight: 600; margin: 0; }

/* Alert */
.al-alert { border-radius: 12px; padding: 12px 16px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.al-alert-success { background: #e8f8ef; color: #1a7a45; border: 1px solid #c3e9d4; }

/* Guard badges */
.guard-web   { background: #e3f8fc; color: #00cfe8; border-radius: 8px; padding: 3px 10px; font-size: 11px; font-weight: 700; }
.guard-admin { background: #eef0ff; color: #696cff; border-radius: 8px; padding: 3px 10px; font-size: 11px; font-weight: 700; }
</style>
@endsection

@section('content')

@if(session('success'))
<div class="al-alert al-alert-success">
    <i class="ri ri-checkbox-circle-line"></i> {{ session('success') }}
</div>
@endif

{{-- Page Header --}}
<div class="pg-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4><i class="ri ri-shield-star-line me-2" style="color:#696cff;"></i>{{ __('app.roles_permissions') }}</h4>
        <p>{{ __('app.roles_permissions_desc') }}</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn-create" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            <i class="ri ri-shield-star-line"></i> {{ __('app.create_role') }}
        </button>
        <a href="{{ route('permissions.create') }}" class="btn-create" style="background:linear-gradient(135deg,#28c76f,#48da89);">
            <i class="ri ri-add-line"></i> {{ __('app.create_permission') }}
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eef0ff;">
                <i class="ri ri-shield-star-line" style="color:#696cff;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.total_roles') }}</div>
                <div class="stat-value">{{ $roles->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8ef;">
                <i class="ri ri-key-2-line" style="color:#28c76f;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.permissions') }}</div>
                <div class="stat-value">{{ $roles->sum(fn($r) => $r->permissions->count()) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff4e5;">
                <i class="ri ri-shield-check-line" style="color:#ff9f43;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.roles_with_permissions') }}</div>
                <div class="stat-value">{{ $roles->filter(fn($r) => $r->permissions->count() > 0)->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdeaea;">
                <i class="ri ri-error-warning-line" style="color:#ea5455;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.empty_roles') }}</div>
                <div class="stat-value">{{ $roles->filter(fn($r) => $r->permissions->count() === 0)->count() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Roles Table --}}
<div class="table-card">
    <div class="table-card-header">
        <h6><i class="ri ri-shield-star-line me-2" style="color:#696cff;"></i>{{ __('app.all_roles') }}</h6>
        <span style="font-size:12px;color:#8592a3;font-weight:600;">{{ $roles->count() }} role{{ $roles->count() !== 1 ? 's' : '' }} defined</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('app.role_name') }}</th>
                    <th>{{ __('app.guard') }}</th>
                    <th>{{ __('app.permissions') }}</th>
                    <th style="width:120px;">{{ __('app.count') }}</th>
                    <th class="text-center" style="width:100px;">{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>
                        <span style="background:#f4f5fa;color:#8592a3;border-radius:8px;padding:3px 10px;font-size:12px;font-weight:700;">
                            #{{ $role->id }}
                        </span>
                    </td>

                    <td>
                        <div class="role-chip">
                            <i class="ri ri-shield-star-line"></i>
                            {{ $role->name }}
                        </div>
                    </td>

                    <td>
                        <span class="guard-{{ $role->guard_name === 'admins' ? 'admin' : 'web' }}">
                            <i class="ri ri-{{ $role->guard_name === 'admins' ? 'user-settings' : 'user' }}-line"></i>
                            {{ $role->guard_name }}
                        </span>
                    </td>

                    <td>
                        <div style="max-width:380px;display:flex;flex-wrap:wrap;gap:2px;">
                            @forelse($role->permissions as $permission)
                                <span class="perm-tag">
                                    <i class="ri ri-key-2-line"></i>{{ $permission->name }}
                                </span>
                            @empty
                                <span style="color:#c8ccda;font-size:13px;font-style:italic;">{{ __('app.no_permissions_assigned') }}</span>
                            @endforelse
                        </div>
                    </td>

                    <td>
                        <span class="count-badge">
                            <i class="ri ri-key-2-line"></i>{{ $role->permissions->count() }}
                        </span>
                    </td>

                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <a href="{{ route('permissions.edit', $role->id) }}"
                               class="action-btn action-btn-edit" title="{{ __('app.edit_permissions') }}">
                                <i class="ri ri-pencil-line"></i>
                            </a>
                            <form action="{{ route('permissions.destroy', $role->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete" title="{{ __('app.delete_role') }}"
                                        onclick="return confirm('{{ __('app.confirm_delete') }}')">
                                    <i class="ri ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="ri ri-shield-line"></i>
                            <p class="fw-bold mb-1">{{ __('app.no_roles_found') }}</p>
                            <p class="small" style="color:#aab0be;">{{ __('app.create_first_role') }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── Create Role Modal ────────────────────────────────── --}}
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:20px;border:0;box-shadow:0 20px 60px rgba(105,108,255,.18);">

            <div class="modal-header" style="background:linear-gradient(135deg,#696cff,#9c9eff);border-radius:20px 20px 0 0;border:0;padding:20px 28px;">
                <h5 class="modal-title" id="createRoleModalLabel" style="color:#fff;font-weight:800;font-size:17px;">
                    <i class="ri ri-shield-star-line me-2"></i>{{ __('app.create_new_role') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="padding:28px;">

                    {{-- Role Name --}}
                    <div class="mb-4">
                        <label class="form-label" style="font-weight:700;color:#1e293b;font-size:13px;">
                            <i class="ri ri-shield-star-line me-1" style="color:#696cff;"></i>Role Name <span style="color:#ea5455;">*</span>
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               placeholder="e.g. content-manager"
                               value="{{ old('name') }}"
                               style="border-radius:12px;border:1.5px solid #e8eaf2;padding:12px 16px;font-size:14px;">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Permissions --}}
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <label class="form-label mb-0" style="font-weight:700;color:#1e293b;font-size:13px;">
                                <i class="ri ri-key-2-line me-1" style="color:#28c76f;"></i>Permissions
                            </label>
                            <div class="d-flex gap-2">
                                <button type="button" onclick="toggleAll(true)"
                                        style="font-size:11px;font-weight:700;color:#696cff;background:#eef0ff;border:0;border-radius:8px;padding:4px 12px;cursor:pointer;">
                                    {{ __('app.select_all') }}
                                </button>
                                <button type="button" onclick="toggleAll(false)"
                                        style="font-size:11px;font-weight:700;color:#ea5455;background:#fdeaea;border:0;border-radius:8px;padding:4px 12px;cursor:pointer;">
                                    {{ __('app.clear_all') }}
                                </button>
                            </div>
                        </div>

                        <div style="background:#fafbff;border-radius:14px;border:1.5px solid #e8eaf2;padding:16px;max-height:260px;overflow-y:auto;">
                            @if($permissions->isEmpty())
                                <p style="color:#8592a3;font-size:13px;text-align:center;margin:0;">{{ __('app.no_permissions_found_msg') }}</p>
                            @else
                                <div class="row g-2" id="permissionsContainer">
                                    @foreach($permissions as $perm)
                                    <div class="col-6 col-md-4">
                                        <label style="display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:10px;cursor:pointer;border:1.5px solid #e8eaf2;background:#fff;transition:.15s;width:100%;"
                                               onmouseover="this.style.borderColor='#696cff';this.style.background='#f2f0ff'"
                                               onmouseout="if(!this.querySelector('input').checked){this.style.borderColor='#e8eaf2';this.style.background='#fff';}">
                                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                                   style="accent-color:#696cff;width:16px;height:16px;flex-shrink:0;"
                                                   onchange="updateLabel(this)"
                                                   {{ is_array(old('permissions')) && in_array($perm->name, old('permissions')) ? 'checked' : '' }}>
                                            <span style="font-size:12px;font-weight:600;color:#1e293b;line-height:1.3;">{{ $perm->name }}</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div style="font-size:11px;color:#8592a3;margin-top:8px;">
                            <span id="selectedCount">0</span> {{ __('app.permission_selected') }}
                        </div>
                    </div>

                </div>

                <div class="modal-footer" style="border-top:1px solid #f0eef8;padding:16px 28px;border-radius:0 0 20px 20px;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="border-radius:10px;font-weight:700;padding:10px 20px;">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn-create" style="padding:10px 24px;">
                        <i class="ri ri-save-line"></i> {{ __('app.create_role') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleAll(check) {
    document.querySelectorAll('#permissionsContainer input[type=checkbox]').forEach(cb => {
        cb.checked = check;
        updateLabel(cb);
    });
}

function updateLabel(cb) {
    const label = cb.closest('label');
    if (cb.checked) {
        label.style.borderColor = '#696cff';
        label.style.background  = '#f2f0ff';
    } else {
        label.style.borderColor = '#e8eaf2';
        label.style.background  = '#fff';
    }
    document.getElementById('selectedCount').textContent =
        document.querySelectorAll('#permissionsContainer input:checked').length;
}

@if($errors->any())
    document.addEventListener('DOMContentLoaded', () => {
        new bootstrap.Modal(document.getElementById('createRoleModal')).show();
    });
@endif

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('#permissionsContainer input:checked').forEach(updateLabel);
});
</script>
@endpush

@endsection
