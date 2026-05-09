@extends('layouts/contentNavbarLayout')

@section('title', 'Admins Management')

@section('content')

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
.modal-header  { border-bottom: 1px solid #f1f3f9; padding: 22px 26px; }
.modal-body    { padding: 26px; }
.modal-footer  { border-top: 1px solid #f1f3f9; padding: 18px 26px; }

.perm-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 8px; }
.perm-item {
    background: #fafbff; border: 1.5px solid #e8eaf2;
    border-radius: 12px; padding: 10px 14px;
    display: flex; align-items: center; gap: 10px;
    cursor: pointer; transition: .2s ease;
}
.perm-item:has(input:checked) { background: var(--accent-soft); border-color: var(--accent); }
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
</style>
@endsection

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
                <span class="perm-badge">
                    <i class="ri ri-shield-check-line me-1"></i>
                    {{ $admin->permissions->count() }} permissions
                </span>
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
@foreach ($admins as $admin)
@php $avClass = 'av-' . ($admin->id % 5); @endphp

{{-- ── View Modal ── --}}
<div class="modal fade" id="viewAdminModal{{ $admin->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
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

            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div style="background:#fafbff;border-radius:14px;padding:16px;">
                            <div class="text-muted small mb-1">Status</div>
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
                                        <span class="status-badge status-active">Active</span>
                                    @else
                                        <span class="status-badge status-inactive">Inactive</span>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div style="background:#fafbff;border-radius:14px;padding:16px;">
                            <div class="text-muted small mb-1">Joined</div>
                            <div class="fw-bold">{{ $admin->created_at?->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Permissions --}}
                <div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <h6 class="mb-0 fw-bold">Permissions</h6>
                        <span class="perm-badge">{{ $admin->permissions->count() }}</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse ($admin->permissions as $perm)
                            <span class="view-perm-badge">
                                <i class="ri ri-shield-check-line me-1"></i>{{ $perm->name }}
                            </span>
                        @empty
                            <div style="background:#fafbff;border-radius:12px;padding:16px 20px;width:100%;text-align:center;color:#8592a3;">
                                <i class="ri ri-shield-line me-2"></i>No permissions assigned
                            </div>
                        @endforelse
                    </div>
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
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
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

                <div class="modal-body">

                    {{-- Basic Info --}}
                    <h6 class="fw-bold text-muted small text-uppercase mb-3" style="letter-spacing:.7px;">
                        Basic Information
                    </h6>
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

                    {{-- Permissions --}}
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-muted small text-uppercase mb-0" style="letter-spacing:.7px;">
                            Permissions
                            <span class="perm-badge ms-2">{{ $allPermissions->count() }}</span>
                        </h6>
                        <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
                            <input class="form-check-input select-all-perms" type="checkbox"
                                   id="selectAll_{{ $admin->id }}"
                                   data-admin="{{ $admin->id }}"
                                   style="width:2em;height:1em;cursor:pointer;">
                            <label class="form-check-label fw-semibold small" for="selectAll_{{ $admin->id }}">
                                Select All
                            </label>
                        </div>
                    </div>

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
                        <div class="col-12 text-muted fst-italic small">No permissions defined</div>
                        @endforelse
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
    document.querySelectorAll('.select-all-perms').forEach(function (selectAll) {
        const adminId = selectAll.dataset.admin;
        const checkboxes = document.querySelectorAll('.perm-cb-' + adminId);

        function syncState() {
            const total   = checkboxes.length;
            const checked = [...checkboxes].filter(c => c.checked).length;
            selectAll.checked       = total > 0 && checked === total;
            selectAll.indeterminate = checked > 0 && checked < total;
        }

        selectAll.addEventListener('change', () => checkboxes.forEach(c => c.checked = selectAll.checked));
        checkboxes.forEach(c => c.addEventListener('change', syncState));
        syncState();
    });
});
</script>
@endpush

@endsection
