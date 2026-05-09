@extends('layouts/contentNavbarLayout')

@section('title', 'Roles & Permissions')

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
        <h4><i class="ri ri-shield-star-line me-2" style="color:#696cff;"></i>Roles & Permissions</h4>
        <p>Define roles and assign permissions to control admin access</p>
    </div>
    <a href="{{ route('permissions.create') }}" class="btn-create">
        <i class="ri ri-add-line"></i> Create Permission
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eef0ff;">
                <i class="ri ri-shield-star-line" style="color:#696cff;"></i>
            </div>
            <div>
                <div class="stat-label">Total Roles</div>
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
                <div class="stat-label">Total Permissions</div>
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
                <div class="stat-label">Roles with Permissions</div>
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
                <div class="stat-label">Empty Roles</div>
                <div class="stat-value">{{ $roles->filter(fn($r) => $r->permissions->count() === 0)->count() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Roles Table --}}
<div class="table-card">
    <div class="table-card-header">
        <h6><i class="ri ri-shield-star-line me-2" style="color:#696cff;"></i>All Roles</h6>
        <span style="font-size:12px;color:#8592a3;font-weight:600;">{{ $roles->count() }} role{{ $roles->count() !== 1 ? 's' : '' }} defined</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Guard</th>
                    <th>Permissions</th>
                    <th style="width:120px;">Count</th>
                    <th class="text-center" style="width:100px;">Actions</th>
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
                                <span style="color:#c8ccda;font-size:13px;font-style:italic;">No permissions assigned</span>
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
                               class="action-btn action-btn-edit" title="Edit Permissions">
                                <i class="ri ri-pencil-line"></i>
                            </a>
                            <form action="{{ route('permissions.destroy', $role->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete" title="Delete Role"
                                        onclick="return confirm('Delete role: {{ addslashes($role->name) }}?')">
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
                            <p class="fw-bold mb-1">No roles found</p>
                            <p class="small" style="color:#aab0be;">Start by creating your first role</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
