@extends('layouts/contentNavbarLayout')

@section('title', 'Admins Management')

@section('page-style')
<style>
:root {
    --accent: #696cff;
    --accent-soft: #eef0ff;
    --success: #28c76f;
    --success-soft: #e8f8ef;
    --danger: #ea5455;
    --danger-soft: #fdeaea;
    --warning: #ff9f43;
    --warning-soft: #fff4e5;
    --info: #00cfe8;
    --info-soft: #e3f8fc;
    --card-radius: 20px;
    --shadow: 0 8px 28px rgba(18,38,63,.08);
    --shadow-hover: 0 16px 44px rgba(18,38,63,.14);
}

/* ── Stats ── */
.stat-card {
    border-radius: var(--card-radius);
    padding: 22px 24px;
    display: flex;
    align-items: center;
    gap: 18px;
    background: #fff;
    box-shadow: var(--shadow);
    border: none;
    transition: .25s ease;
}
.stat-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); }
.stat-icon {
    width: 54px; height: 54px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; flex-shrink: 0;
}
.stat-label { font-size: 13px; color: #8592a3; font-weight: 600; margin-bottom: 4px; }
.stat-value { font-size: 28px; font-weight: 800; line-height: 1; }

/* ── Admin cards ── */
.admin-card {
    border-radius: var(--card-radius);
    background: #fff;
    box-shadow: var(--shadow);
    border: none;
    transition: .3s ease;
    overflow: hidden;
}
.admin-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-4px); }

.admin-card-header {
    padding: 28px 20px 16px;
    display: flex; flex-direction: column; align-items: center;
    position: relative;
}

.avatar-circle {
    width: 72px; height: 72px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; font-weight: 800; color: #fff;
    margin-bottom: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,.15);
    flex-shrink: 0;
}

.admin-name { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 3px; }
.admin-email { font-size: 13px; color: #8592a3; margin-bottom: 10px; }

.admin-card-body {
    padding: 0 20px 20px;
}

.info-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f1f3f9;
    font-size: 13px;
}
.info-row:last-child { border-bottom: none; }
.info-row .label { color: #8592a3; font-weight: 600; }

/* Status badge */
.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; border-radius: 999px;
    font-size: 12px; font-weight: 700;
}
.status-active   { background: var(--success-soft); color: var(--success); }
.status-inactive { background: var(--danger-soft);  color: var(--danger);  }

/* Status toggle */
.status-toggle-wrap {
    padding: 14px 20px;
    background: #fafbff;
    border-top: 1px solid #f1f3f9;
    border-bottom: 1px solid #f1f3f9;
    display: flex; align-items: center; justify-content: space-between;
}
.status-toggle-wrap .form-check-input {
    width: 2.4em; height: 1.2em; cursor: pointer;
}
.status-toggle-wrap .form-check-input:checked { background-color: var(--success); border-color: var(--success); }

/* Action buttons */
.admin-actions {
    padding: 14px 20px;
    display: flex; gap: 8px;
}
.admin-actions .btn { flex: 1; border-radius: 12px; font-size: 13px; font-weight: 600; padding: 9px 0; }

/* Permissions badge */
.perm-badge {
    background: var(--accent-soft); color: var(--accent);
    padding: 4px 10px; border-radius: 999px;
    font-size: 12px; font-weight: 700;
}

/* Modal improvements */
.modal-content { border: none; border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,.2); }
.modal-header  { border-bottom: 1px solid #f1f3f9; padding: 22px 26px; flex-shrink: 0; }
.modal-body    { padding: 26px; overflow-y: auto; flex: 1 1 auto; }
.modal-footer  { border-top: 1px solid #f1f3f9; padding: 18px 26px; flex-shrink: 0; }
.modal-dialog-scrollable { max-height: calc(100vh - 56px); display: flex; }
.modal-dialog-scrollable .modal-content { max-height: calc(100vh - 56px); display: flex; flex-direction: column; overflow: hidden; }

.perm-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 8px; }
.perm-item {
    background: #fafbff; border: 1.5px solid #e8eaf2;
    border-radius: 12px; padding: 10px 14px;
    display: flex; align-items: center; gap: 10px;
    cursor: pointer; transition: .2s ease;
}
.perm-item:has(input[type="checkbox"]:checked) { background: var(--accent-soft); border-color: var(--accent); }
.role-item:has(input:checked) { background: #fff3e5 !important; border-color: var(--warning) !important; }
.perm-item label { font-size: 13px; font-weight: 600; cursor: pointer; margin: 0; }
.perm-item input { flex-shrink: 0; cursor: pointer; }

.view-perm-badge {
    display: inline-flex; align-items: center;
    background: var(--accent-soft); color: var(--accent);
    padding: 6px 14px; border-radius: 999px;
    font-size: 12px; font-weight: 700;
}

/* Avatar colors */
.av-0 { background: linear-gradient(135deg,#696cff,#8f91ff); }
.av-1 { background: linear-gradient(135deg,#03c3ec,#00a0cc); }
.av-2 { background: linear-gradient(135deg,#28c76f,#1fa958); }
.av-3 { background: linear-gradient(135deg,#ff9f43,#e08a2e); }
.av-4 { background: linear-gradient(135deg,#ea5455,#c93c3c); }

/* ── Section headers with accent bar ── */
.section-header {
    display:flex; align-items:center; gap:10px;
    margin-bottom:14px; padding:10px 14px;
    background:#f8f9ff; border-radius:0 10px 10px 0;
    border-left:4px solid var(--accent);
}
.section-header.orange { border-left-color:var(--warning); background:#fffbf5; }
.section-header.green  { border-left-color:var(--success); background:#f4fdf8; }
.section-header .sh-title {
    font-size:11px; font-weight:700; text-transform:uppercase;
    letter-spacing:.8px; color:#3d4a5c;
}

/* ── Role chip on admin cards ── */
.admin-role-chip {
    display:inline-flex; align-items:center; gap:4px;
    background:#eef0ff; color:var(--accent);
    padding:4px 11px; border-radius:999px;
    font-size:11px; font-weight:700; white-space:nowrap;
}
.admin-no-role-chip {
    display:inline-flex; align-items:center; gap:4px;
    background:#f4f5fa; color:#8592a3;
    padding:4px 11px; border-radius:999px;
    font-size:11px; font-weight:600; font-style:italic;
}

/* ── Role Selection Cards ── */
.role-card {
    position:relative;
    display:flex; align-items:flex-start; gap:14px;
    background:#fff; border:2px solid #e8eaf2; border-radius:14px;
    padding:14px 16px; cursor:pointer; width:100%;
    transition:border-color .2s, background .2s, box-shadow .2s;
    margin-bottom:8px;
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

@php
    $totalAdmins    = $admins->count();
    $activeAdmins   = $admins->where('is_active', true)->count();
    $inactiveAdmins = $admins->where('is_active', false)->count();
@endphp

{{-- ── Page Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Admins Management</h4>
        <p class="text-muted mb-0 small">Manage your system administrators and their permissions</p>
    </div>
    <a href="{{ route('admins.create') }}" class="btn btn-primary px-4" style="border-radius:12px;font-weight:700;">
        <i class="ri ri-user-add-line me-2"></i>Add Admin
    </a>
</div>

{{-- ── Stats ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eef0ff;">
                <i class="ri ri-group-line" style="color:#696cff;"></i>
            </div>
            <div>
                <div class="stat-label">Total Admins</div>
                <div class="stat-value" style="color:#1e293b;">{{ $totalAdmins }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8ef;">
                <i class="ri ri-checkbox-circle-line" style="color:#28c76f;"></i>
            </div>
            <div>
                <div class="stat-label">Active</div>
                <div class="stat-value" style="color:#28c76f;">{{ $activeAdmins }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdeaea;">
                <i class="ri ri-close-circle-line" style="color:#ea5455;"></i>
            </div>
            <div>
                <div class="stat-label">Inactive</div>
                <div class="stat-value" style="color:#ea5455;">{{ $inactiveAdmins }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Session success --}}
@if(session('success'))
<div class="alert border-0 shadow-sm mb-4"
     style="background:#e8f8ef;color:#1a7a45;border-radius:14px;" role="alert">
    <i class="ri ri-checkbox-circle-line me-2"></i>{{ session('success') }}
</div>
@endif

{{-- ── Admin Cards ── --}}
<div class="row g-4">
    @forelse ($admins as $admin)
    @php $avClass = 'av-' . ($admin->id % 5); @endphp

    <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="admin-card h-100 d-flex flex-column">

            {{-- Card Header --}}
            <div class="admin-card-header">
                <div class="avatar-circle {{ $avClass }}">
                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                </div>
                <div class="admin-name">{{ $admin->name }}</div>
                <div class="admin-email">{{ $admin->email }}</div>
                <div class="d-flex flex-wrap gap-2 justify-content-center mt-1">
                    @forelse($admin->roles as $cardRole)
                        <span class="admin-role-chip">
                            <i class="ri ri-shield-star-line"></i>{{ $cardRole->name }}
                        </span>
                    @empty
                        <span class="admin-no-role-chip">
                            <i class="ri ri-shield-line"></i>No Role
                        </span>
                    @endforelse
                    @if($admin->permissions->count())
                        <span class="perm-badge">
                            <i class="ri ri-key-line me-1"></i>+{{ $admin->permissions->count() }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Info rows --}}
            <div class="admin-card-body flex-grow-1">
                <div class="info-row">
                    <span class="label">Status</span>
                    @if($admin->is_active)
                        <span class="status-badge status-active">
                            <i class="ri ri-checkbox-blank-circle-fill" style="font-size:8px;"></i>Active
                        </span>
                    @else
                        <span class="status-badge status-inactive">
                            <i class="ri ri-checkbox-blank-circle-fill" style="font-size:8px;"></i>Inactive
                        </span>
                    @endif
                </div>
                <div class="info-row">
                    <span class="label">Last Seen</span>
                    @if($admin->last_seen_at)
                        @php $diffHours = now()->diffInHours($admin->last_seen_at); @endphp
                        @if($diffHours < 1)
                            <span style="color:#28c76f;font-weight:700;font-size:12px;">
                                <i class="ri ri-circle-fill me-1" style="font-size:7px;"></i>Online
                            </span>
                        @elseif($diffHours < 24)
                            <span class="fw-semibold" style="font-size:12px;">{{ $diffHours }}h ago</span>
                        @else
                            <span class="fw-semibold" style="font-size:12px;">{{ now()->diffInDays($admin->last_seen_at) }}d ago</span>
                        @endif
                    @else
                        <span style="color:#a0aab4;font-size:12px;">Never</span>
                    @endif
                </div>
                <div class="info-row">
                    <span class="label">Joined</span>
                    <span class="fw-semibold">{{ $admin->created_at?->format('M d, Y') }}</span>
                </div>
            </div>

            {{-- Status Toggle --}}
            <div class="status-toggle-wrap">
                <span class="fw-semibold" style="font-size:13px;">
                    {{ $admin->is_active ? 'Admin is Active' : 'Admin is Inactive' }}
                </span>
                <form action="{{ route('adminstatus', $admin->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" role="switch"
                               {{ $admin->is_active ? 'checked' : '' }}
                               onchange="this.form.submit()">
                    </div>
                </form>
            </div>

            {{-- Actions --}}
            <div class="admin-actions">
                <button class="btn btn-light"
                        data-bs-toggle="modal"
                        data-bs-target="#viewAdminModal{{ $admin->id }}"
                        title="View Details">
                    <i class="ri ri-eye-line me-1"></i>View
                </button>
                <button class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#editAdminModal{{ $admin->id }}"
                        title="Edit Admin">
                    <i class="ri ri-pencil-line me-1"></i>Edit
                </button>
                <form action="{{ route('admins.destroy', $admin->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Delete {{ $admin->name }}?')"
                            title="Delete Admin">
                        <i class="ri ri-delete-bin-line"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5" style="background:#fff;border-radius:20px;box-shadow:var(--shadow);">
            <i class="ri ri-group-line" style="font-size:52px;color:#c8ccda;"></i>
            <p class="text-muted mt-3 mb-0 fw-semibold">No admins found</p>
        </div>
    </div>
    @endforelse
</div>

{{-- ══════════════ MODALS ══════════════ --}}
@php $rolePermissionsMap = $roles->mapWithKeys(fn($r) => [$r->name => $r->permissions->pluck('name')]); @endphp
<div id="adminRolePermMap" data-map="{{ json_encode($rolePermissionsMap) }}" style="display:none;"></div>

@foreach ($admins as $admin)
@php $avClass = 'av-' . ($admin->id % 5); @endphp

{{-- ── View Modal ── --}}
<div class="modal fade" id="viewAdminModal{{ $admin->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" style="margin:1.75rem auto;">
        <div class="modal-content">

            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-circle {{ $avClass }}" style="width:48px;height:48px;font-size:20px;">
                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="modal-title mb-0">{{ $admin->name }}</h5>
                        <small class="text-muted">{{ $admin->email }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="overflow-y:auto;max-height:calc(100vh - 200px);">

                {{-- Status + Joined --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div style="background:#fafbff;border-radius:14px;padding:16px;border:1.5px solid #f1f3f9;">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#8592a3;margin-bottom:10px;">Status</div>
                            <form action="{{ route('adminstatus', $admin->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                               style="width:2.4em;height:1.2em;cursor:pointer;"
                                               {{ $admin->is_active ? 'checked' : '' }}
                                               onchange="this.form.submit()">
                                    </div>
                                    @if($admin->is_active)
                                        <span class="status-badge status-active"><i class="ri ri-checkbox-blank-circle-fill me-1" style="font-size:8px;"></i>Active</span>
                                    @else
                                        <span class="status-badge status-inactive"><i class="ri ri-checkbox-blank-circle-fill me-1" style="font-size:8px;"></i>Inactive</span>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div style="background:#fafbff;border-radius:14px;padding:16px;border:1.5px solid #f1f3f9;">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#8592a3;margin-bottom:10px;">Member Since</div>
                            <div style="font-size:15px;font-weight:700;color:#1e293b;">{{ $admin->created_at?->format('M d, Y') }}</div>
                            <div style="font-size:12px;color:#a0aab4;margin-top:2px;">{{ $admin->created_at?->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div style="background:#fafbff;border-radius:14px;padding:16px;border:1.5px solid #f1f3f9;">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#8592a3;margin-bottom:10px;">Last Seen</div>
                            @if($admin->last_seen_at)
                                @php $diffHours = now()->diffInHours($admin->last_seen_at); @endphp
                                @if($diffHours < 1)
                                    <div style="font-size:15px;font-weight:700;color:#28c76f;">
                                        <i class="ri ri-circle-fill me-1" style="font-size:9px;"></i>Online Now
                                    </div>
                                    <div style="font-size:12px;color:#a0aab4;margin-top:2px;">Active recently</div>
                                @elseif($diffHours < 24)
                                    <div style="font-size:15px;font-weight:700;color:#1e293b;">{{ $diffHours }} hours ago</div>
                                    <div style="font-size:12px;color:#a0aab4;margin-top:2px;">{{ $admin->last_seen_at->format('h:i A') }}</div>
                                @else
                                    @php $diffDays = now()->diffInDays($admin->last_seen_at); @endphp
                                    <div style="font-size:15px;font-weight:700;color:#1e293b;">{{ $diffDays }} {{ $diffDays == 1 ? 'day' : 'days' }} ago</div>
                                    <div style="font-size:12px;color:#a0aab4;margin-top:2px;">{{ $admin->last_seen_at->format('M d, Y') }}</div>
                                @endif
                            @else
                                <div style="font-size:15px;font-weight:700;color:#a0aab4;">Never</div>
                                <div style="font-size:12px;color:#c8ccda;margin-top:2px;">No activity recorded</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Role --}}
                @php $adminRole = $admin->roles->first(); @endphp
                <div class="mb-4">
                    <div class="section-header orange mb-3"><span class="sh-title">Assigned Role</span></div>
                    @if($adminRole)
                    <div style="background:#fffbf5;border-radius:14px;padding:18px;border:1.5px solid #ffe5c0;">
                        <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
                            <div style="width:48px;height:48px;background:linear-gradient(135deg,#ff9f43,#ffb976);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 14px rgba(255,159,67,.3);">
                                <i class="ri ri-shield-star-line" style="font-size:22px;color:#fff;"></i>
                            </div>
                            <div>
                                <div style="font-size:16px;font-weight:800;color:#1e293b;line-height:1.2;">{{ $adminRole->name }}</div>
                                <div style="font-size:12px;color:#8592a3;margin-top:3px;">{{ $adminRole->permissions->count() }} permissions included</div>
                            </div>
                        </div>
                        @if($adminRole->permissions->count())
                        <div style="border-top:1px solid #ffd59a;padding-top:14px;">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#ff9f43;margin-bottom:8px;">Included Permissions</div>
                            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                                @foreach($adminRole->permissions as $rp)
                                <span style="display:inline-flex;align-items:center;gap:5px;background:#fff;border:1px solid #ffd59a;color:#d07c1a;padding:4px 11px;border-radius:8px;font-size:11px;font-weight:600;">
                                    <i class="ri ri-check-line" style="color:#ff9f43;font-size:11px;"></i>{{ $rp->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <div style="background:#fafbff;border-radius:14px;padding:28px 16px;text-align:center;border:1.5px dashed #e0e3ef;">
                        <div style="width:52px;height:52px;background:#f1f3f9;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i class="ri ri-shield-off-line" style="font-size:24px;color:#c8ccda;"></i>
                        </div>
                        <div style="font-size:13px;font-weight:700;color:#5d6a82;margin-bottom:4px;">No role assigned</div>
                        <div style="font-size:12px;color:#a0aab4;">Permissions managed through direct assignment</div>
                    </div>
                    @endif
                </div>

                {{-- Direct Permissions --}}
                <div>
                    <div class="section-header green mb-3">
                        <span class="sh-title">Direct Permissions</span>
                        <span style="background:#e8f8ef;color:#28c76f;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;margin-left:auto;">{{ $admin->permissions->count() }}</span>
                    </div>
                    @if($admin->permissions->count())
                    <div style="background:#f4fdf8;border-radius:14px;padding:16px;border:1.5px solid #c3e9d4;">
                        <div class="row g-2">
                            @foreach ($admin->permissions as $perm)
                            <div class="col-sm-6">
                                <div style="display:flex;align-items:center;gap:9px;background:#fff;border-radius:10px;padding:9px 12px;border:1px solid #d4f0e0;">
                                    <div style="width:24px;height:24px;background:#e8f8ef;border-radius:7px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ri ri-check-line" style="font-size:13px;color:#28c76f;"></i>
                                    </div>
                                    <span style="font-size:12px;font-weight:600;color:#1e293b;">{{ $perm->name }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div style="background:#fafbff;border-radius:14px;padding:28px 16px;text-align:center;border:1.5px dashed #e0e3ef;">
                        <div style="width:52px;height:52px;background:#f1f3f9;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i class="ri ri-key-line" style="font-size:24px;color:#c8ccda;"></i>
                        </div>
                        <div style="font-size:13px;font-weight:700;color:#5d6a82;margin-bottom:4px;">No direct permissions</div>
                        <div style="font-size:12px;color:#a0aab4;">All permissions come from the assigned role</div>
                    </div>
                    @endif
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        style="border-radius:12px;font-weight:600;">Close</button>
                <button type="button" class="btn btn-primary"
                        style="border-radius:12px;font-weight:600;"
                        data-bs-dismiss="modal"
                        data-bs-toggle="modal"
                        data-bs-target="#editAdminModal{{ $admin->id }}">
                    <i class="ri ri-pencil-line me-1"></i>Edit Admin
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ── Edit Modal ── --}}
<div class="modal fade" id="editAdminModal{{ $admin->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" style="margin:1.75rem auto;">
        <div class="modal-content">

            <form action="{{ route('admins.update', $admin->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle {{ $avClass }}" style="width:44px;height:44px;font-size:18px;">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="modal-title mb-0">Edit Admin</h5>
                            <small class="text-muted">{{ $admin->name }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="overflow-y:auto;max-height:calc(100vh - 200px);">

                    {{-- Basic Info --}}
                    <div class="section-header mb-4"><span class="sh-title">Basic Information</span></div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" style="border-radius:12px;"
                                       id="name_{{ $admin->id }}" name="name"
                                       value="{{ old('name', $admin->name) }}" placeholder="Name" required>
                                <label for="name_{{ $admin->id }}">Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="email" class="form-control" style="border-radius:12px;"
                                       id="email_{{ $admin->id }}" name="email"
                                       value="{{ old('email', $admin->email) }}" placeholder="Email" required>
                                <label for="email_{{ $admin->id }}">Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="password" class="form-control" style="border-radius:12px;"
                                       id="password_{{ $admin->id }}" name="password"
                                       placeholder="New Password (optional)">
                                <label for="password_{{ $admin->id }}">New Password (optional)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="password" class="form-control" style="border-radius:12px;"
                                       id="password_confirmation_{{ $admin->id }}"
                                       name="password_confirmation" placeholder="Confirm Password">
                                <label for="password_confirmation_{{ $admin->id }}">Confirm Password</label>
                            </div>
                        </div>
                    </div>

                    {{-- Roles (multi-select) --}}
                    <div class="section-header orange mb-3">
                        <span class="sh-title">Assign Roles</span>
                        <small style="font-weight:400;text-transform:none;letter-spacing:0;color:#8592a3;font-size:11px;margin-left:6px;">— يمكن اختيار أكثر من رول</small>
                    </div>
                    <div class="mb-4">
                        @php $adminRoleNames = $admin->roles->pluck('name')->toArray(); @endphp
                        @foreach($roles as $role)
                        <label class="role-card" for="modal_role_{{ $admin->id }}_{{ $role->id }}">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                   id="modal_role_{{ $admin->id }}_{{ $role->id }}"
                                   class="modal-role-cb" data-admin="{{ $admin->id }}"
                                   {{ in_array($role->name, $adminRoleNames) ? 'checked' : '' }}>
                            <div class="role-card-icon"><i class="ri ri-shield-star-line"></i></div>
                            <div class="role-card-body">
                                <div class="role-card-name">{{ $role->name }}</div>
                                <div class="role-card-perms">
                                    @forelse($role->permissions as $rp)
                                        <span class="role-perm-tag">{{ $rp->name }}</span>
                                    @empty
                                        <span style="font-size:11px;color:#8592a3;font-style:italic;">No permissions</span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="role-check"><i class="ri ri-check-line"></i></div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Extra Permissions --}}
                    <div class="section-header green mb-3">
                        <span class="sh-title">Extra Permissions</span>
                        <small style="font-weight:400;text-transform:none;letter-spacing:0;color:#8592a3;font-size:11px;">— on top of role</small>
                        <span class="perm-badge ms-1">{{ $allPermissions->count() }}</span>
                        <div class="ms-auto form-check form-switch mb-0 d-flex align-items-center gap-2">
                            <input class="form-check-input select-all-perms" type="checkbox"
                                   id="selectAll_{{ $admin->id }}"
                                   data-admin="{{ $admin->id }}"
                                   style="width:2em;height:1em;cursor:pointer;">
                            <label class="form-check-label fw-semibold small" for="selectAll_{{ $admin->id }}">
                                Select All
                            </label>
                        </div>
                    </div>

                    <div style="background:#fafbff;border-radius:14px;padding:14px;border:1.5px solid #e8eaf2;">
                        <div class="perm-grid">
                            @forelse ($allPermissions as $permission)
                            <label class="perm-item" for="perm_{{ $admin->id }}_{{ $permission->id }}">
                                <input class="form-check-input perm-cb-{{ $admin->id }}"
                                       type="checkbox" name="permissions[]"
                                       value="{{ $permission->name }}"
                                       id="perm_{{ $admin->id }}_{{ $permission->id }}"
                                       {{ $admin->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                <span>{{ $permission->name }}</span>
                            </label>
                            @empty
                            <div class="text-muted fst-italic small text-center py-3">No permissions defined</div>
                            @endforelse
                        </div>
                    </div>

                    @error('permissions')
                        <small class="text-danger d-block mt-2">{{ $message }}</small>
                    @enderror

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="border-radius:12px;font-weight:600;">Cancel</button>
                    <button type="submit" class="btn btn-primary"
                            style="border-radius:12px;font-weight:700;padding:10px 28px;">
                        <i class="ri ri-save-line me-1"></i>Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleMap = JSON.parse(document.getElementById('adminRolePermMap')?.dataset.map || '{}');

    function syncSelectAll(adminId) {
        const selectAll  = document.getElementById('selectAll_' + adminId);
        if (!selectAll) return;
        const checkboxes = document.querySelectorAll('.perm-cb-' + adminId);
        const total      = checkboxes.length;
        const checked    = [...checkboxes].filter(c => c.checked).length;
        selectAll.checked       = total > 0 && checked === total;
        selectAll.indeterminate = checked > 0 && checked < total;
    }

    document.querySelectorAll('.select-all-perms').forEach(function (selectAll) {
        const adminId    = selectAll.dataset.admin;
        const checkboxes = document.querySelectorAll('.perm-cb-' + adminId);
        selectAll.addEventListener('change', () => checkboxes.forEach(c => c.checked = selectAll.checked));
        checkboxes.forEach(c => c.addEventListener('change', () => syncSelectAll(adminId)));
        syncSelectAll(adminId);
    });

    // Multi-role: merge permissions from all checked roles
    document.querySelectorAll('.modal-role-cb').forEach(function (cb) {
        cb.addEventListener('change', function () {
            const adminId = this.dataset.admin;
            const selectedRoles = [...document.querySelectorAll(`.modal-role-cb[data-admin="${adminId}"]:checked`)]
                .map(c => c.value);
            const mergedPerms = selectedRoles.reduce((acc, roleName) => {
                return [...new Set([...acc, ...(roleMap[roleName] || [])])];
            }, []);
            document.querySelectorAll('.perm-cb-' + adminId).forEach(permCb => {
                permCb.checked = mergedPerms.includes(permCb.value);
            });
            syncSelectAll(adminId);
        });
    });
});
</script>
@endpush

@endsection
