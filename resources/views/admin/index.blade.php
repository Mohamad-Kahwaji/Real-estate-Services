@extends('layouts/contentNavbarLayout')
{{-- Admin dashboard: welcome banner with permissions summary, platform stats, quick-action links, and pending business approval table. --}}

@section('title', 'Admin Dashboard')

@section('page-style')
<style>
:root {
    --accent:      #696cff;
    --accent-soft: #eef0ff;
    --green:       #28c76f;
    --green-soft:  #e8f8ef;
    --orange:      #ff9f43;
    --orange-soft: #fff4e5;
    --red:         #ea5455;
    --red-soft:    #fdeaea;
    --cyan:        #00cfe8;
    --cyan-soft:   #e3f8fc;
    --card-radius: 20px;
    --shadow:      0 8px 28px rgba(18,38,63,.08);
}

/* â”€â”€ Welcome Banner â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
.welcome-banner {
    background: linear-gradient(135deg, #696cff 0%, #8f91ff 60%, #9c9eff 100%);
    border-radius: var(--card-radius);
    padding: 28px 32px;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.welcome-banner::before {
    content: '';
    position: absolute;
    top: -50px; right: -50px;
    width: 180px; height: 180px;
    background: rgba(255,255,255,.08);
    border-radius: 50%;
}
.welcome-banner::after {
    content: '';
    position: absolute;
    bottom: -60px; right: 100px;
    width: 130px; height: 130px;
    background: rgba(255,255,255,.06);
    border-radius: 50%;
}
.welcome-avatar {
    width: 56px; height: 56px;
    border-radius: 16px;
    background: rgba(255,255,255,.2);
    border: 2px solid rgba(255,255,255,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; font-weight: 800; color: #fff;
    flex-shrink: 0;
}
.welcome-name  { font-size: 20px; font-weight: 800; margin-bottom: 3px; }
.welcome-sub   { font-size: 13px; color: rgba(255,255,255,.75); }
.welcome-date  { font-size: 11px; color: rgba(255,255,255,.55); margin-top: 2px; }
.perm-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 999px;
    padding: 5px 14px;
    font-size: 12px; font-weight: 700; color: #fff;
    margin-top: 10px;
}

/* â”€â”€ Stat Cards â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
.stat-card {
    background: #fff;
    border-radius: var(--card-radius);
    box-shadow: var(--shadow);
    padding: 20px 22px;
    transition: .25s ease;
    height: 100%;
}
.stat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 44px rgba(18,38,63,.13); }

.stat-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.stat-value  { font-size: 26px; font-weight: 800; color: #1e293b; line-height: 1.1; margin-top: 12px; }
.stat-label  { font-size: 11px; font-weight: 700; color: #8592a3; text-transform: uppercase; letter-spacing: .5px; margin-top: 2px; }
.stat-sub    { font-size: 11px; color: #8592a3; margin-top: 4px; }

.cnt-badge {
    display: inline-flex; align-items: center; gap: 4px;
    border-radius: 999px; padding: 3px 10px;
    font-size: 11px; font-weight: 700;
}
.cb-orange  { background: var(--orange-soft); color: var(--orange); }
.cb-green   { background: var(--green-soft);  color: var(--green);  }
.cb-purple  { background: var(--accent-soft); color: var(--accent); }
.cb-red     { background: var(--red-soft);    color: var(--red);    }

.icon-orange { background: var(--orange-soft); color: var(--orange); }
.icon-green  { background: var(--green-soft);  color: var(--green);  }
.icon-purple { background: var(--accent-soft); color: var(--accent); }
.icon-cyan   { background: var(--cyan-soft);   color: var(--cyan);   }
.icon-red    { background: var(--red-soft);    color: var(--red);    }
.icon-slate  { background: #f1f3f9; color: #475569; }

/* â”€â”€ Section Label â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
.section-title {
    font-size: 11px; font-weight: 700; letter-spacing: .8px;
    text-transform: uppercase; color: #8592a3;
    margin-bottom: 14px; padding-bottom: 8px;
    border-bottom: 2px solid #f1f3f9;
}

/* â”€â”€ Quick-Link Cards â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
.quick-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: var(--shadow);
    padding: 18px 14px;
    text-align: center;
    text-decoration: none;
    color: inherit;
    display: block;
    transition: .25s ease;
    border: 2px solid transparent;
    height: 100%;
}
.quick-card:hover { transform: translateY(-3px); border-color: var(--accent); color: inherit; }
.quick-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin: 0 auto 10px;
}
.quick-label { font-size: 13px; font-weight: 700; color: #1e293b; }
.quick-sub   { font-size: 11px; color: #8592a3; margin-top: 2px; }

/* â”€â”€ Pending Businesses Table â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
.main-card {
    background: #fff;
    border-radius: var(--card-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.main-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid #f1f3f9;
    display: flex; align-items: center; justify-content: space-between;
}
.main-card-title { font-size: 15px; font-weight: 700; color: #1e293b; }

.bt { margin-bottom: 0; }
.bt thead tr { background: #1e293b; }
.bt thead th {
    font-size: 11px; font-weight: 700; letter-spacing: .5px;
    text-transform: uppercase; color: #94a3b8;
    padding: 14px 18px; border: none;
}
.bt tbody td {
    padding: 14px 18px; vertical-align: middle;
    border-color: #f1f3f9; font-size: 13px;
}
.bt tbody tr:last-child td { border-bottom: none; }
.bt tbody tr { transition: .15s ease; }
.bt tbody tr:hover { background: #fafbff; }

.status-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 999px;
    font-size: 11px; font-weight: 700;
}
.pill-pending  { background: var(--orange-soft); color: var(--orange); }
.pill-approved { background: var(--green-soft);  color: var(--green);  }
.pill-rejected { background: var(--red-soft);    color: var(--red);    }

.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    gap: 5px;
    border-radius: 10px; padding: 7px 14px;
    font-size: 12px; font-weight: 700;
    border: none; transition: .2s ease; cursor: pointer;
}
.action-btn:hover { transform: translateY(-1px); }
.btn-approve { background: var(--green-soft);  color: var(--green);  }
.btn-approve:hover { background: var(--green); color: #fff; }
.btn-reject  { background: var(--red-soft);   color: var(--red);   }
.btn-reject:hover  { background: var(--red);  color: #fff; }

.biz-initials {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; color: #fff;
    flex-shrink: 0;
}
.bi-0 { background: linear-gradient(135deg,#696cff,#8f91ff); }
.bi-1 { background: linear-gradient(135deg,#03c3ec,#00a0cc); }
.bi-2 { background: linear-gradient(135deg,#28c76f,#1fa958); }
.bi-3 { background: linear-gradient(135deg,#ff9f43,#e08a2e); }
.bi-4 { background: linear-gradient(135deg,#ea5455,#c93c3c); }

.empty-state {
    padding: 50px 20px;
    text-align: center;
    color: #8592a3;
}
.empty-state i { font-size: 44px; color: #c8ccda; display: block; margin-bottom: 12px; }
</style>
@endsection

@section('content')

@php $initials = strtoupper(substr($admin->name ?? 'A', 0, 1)); @endphp

{{-- Flash Messages --}}
@if(session('success'))
<div class="alert border-0 shadow-sm mb-4"
     style="background:#e8f8ef;color:#1a7a45;border-radius:14px;" role="alert">
    <i class="ri ri-checkbox-circle-line me-2"></i>{{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert border-0 shadow-sm mb-4"
     style="background:#fdeaea;color:#b91c1c;border-radius:14px;" role="alert">
    <i class="ri ri-error-warning-line me-2"></i>{{ session('error') }}
</div>
@endif

{{-- â”€â”€ Welcome Banner â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
<div class="welcome-banner">
    <div class="d-flex align-items-center gap-3 position-relative" style="z-index:1;">
        <div class="welcome-avatar">{{ $initials }}</div>
        <div>
            <div class="welcome-name">Welcome, {{ $admin->name ?? 'Admin' }}</div>
            <div class="welcome-sub">{{ $admin->email ?? '' }}</div>
            <div class="welcome-date">{{ now()->format('l, F j, Y') }}</div>
            @if($admin)
            @php $perms = $admin->getAllPermissions(); @endphp
            @if($perms->count())
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach($perms->take(4) as $perm)
                <span class="perm-chip">
                    <i class="ri ri-shield-check-line" style="font-size:11px;"></i>
                    {{ $perm->name }}
                </span>
                @endforeach
                @if($perms->count() > 4)
                <span class="perm-chip">+{{ $perms->count() - 4 }} more</span>
                @endif
            </div>
            @endif
            @endif
        </div>
        <div class="ms-auto d-none d-md-flex align-items-center gap-2">
            <span class="perm-chip">
                <i class="ri ri-user-settings-line" style="font-size:11px;"></i>
                Administrator
            </span>
            <button type="button" class="perm-chip" style="border:none;cursor:pointer;background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);"
                    data-bs-toggle="modal" data-bs-target="#editAdminProfileModal">
                <i class="ri ri-edit-line" style="font-size:11px;"></i>
                Edit Profile
            </button>
        </div>
    </div>
</div>

{{-- â”€â”€ Stats Row â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div class="stat-icon icon-orange"><i class="ri ri-building-2-line"></i></div>
                @if($pendingBusinesses > 0)
                <span class="cnt-badge cb-orange">{{ $pendingBusinesses }} new</span>
                @endif
            </div>
            <div class="stat-value">{{ $totalBusinesses }}</div>
            <div class="stat-label">Businesses</div>
            <div class="stat-sub">{{ $pendingBusinesses }} pending</div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div class="stat-icon icon-green"><i class="ri ri-checkbox-circle-line"></i></div>
                <span class="cnt-badge cb-green">{{ $approvedBusinesses }}</span>
            </div>
            <div class="stat-value">{{ $approvedBusinesses }}</div>
            <div class="stat-label">Approved</div>
            <div class="stat-sub">Business accounts</div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div class="stat-icon icon-purple"><i class="ri ri-apps-2-line"></i></div>
            </div>
            <div class="stat-value">{{ $totalCategories }}</div>
            <div class="stat-label">Categories</div>
            <div class="stat-sub">{{ $totalSubcategories }} subcategories</div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div class="stat-icon icon-cyan"><i class="ri ri-map-pin-2-line"></i></div>
            </div>
            <div class="stat-value">{{ $totalCities }}</div>
            <div class="stat-label">Cities</div>
            <div class="stat-sub">Service areas</div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div class="stat-icon icon-slate"><i class="ri ri-tools-line"></i></div>
                @if($pendingServices > 0)
                <span class="cnt-badge cb-orange">{{ $pendingServices }}</span>
                @endif
            </div>
            <div class="stat-value">{{ $totalServices }}</div>
            <div class="stat-label">Services</div>
            <div class="stat-sub">{{ $pendingServices }} pending</div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div class="stat-icon icon-purple"><i class="ri ri-shield-check-line"></i></div>
            </div>
            <div class="stat-value">{{ $perms->count() }}</div>
            <div class="stat-label">Permissions</div>
            <div class="stat-sub">Assigned to you</div>
        </div>
    </div>

</div>

{{-- â”€â”€ Quick Actions â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
<div class="section-title">Quick Actions</div>
<div class="row g-3 mb-4">
    <div class="col-4 col-md-2">
        <a href="{{ route('business.index') }}" class="quick-card">
            <div class="quick-icon icon-orange"><i class="ri ri-building-2-line"></i></div>
            <div class="quick-label">Businesses</div>
            <div class="quick-sub">{{ $pendingBusinesses }} pending</div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="{{ route('categories.index') }}" class="quick-card">
            <div class="quick-icon icon-purple"><i class="ri ri-apps-2-line"></i></div>
            <div class="quick-label">Categories</div>
            <div class="quick-sub">Manage</div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="{{ route('subcategories.index') }}" class="quick-card">
            <div class="quick-icon icon-cyan"><i class="ri ri-list-check-2"></i></div>
            <div class="quick-label">Subcategories</div>
            <div class="quick-sub">Manage</div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="{{ route('cities.index') }}" class="quick-card">
            <div class="quick-icon" style="background:#f3e8ff;color:#9333ea;"><i class="ri ri-map-pin-2-line"></i></div>
            <div class="quick-label">Cities</div>
            <div class="quick-sub">Manage</div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="{{ route('allserviesad') }}" class="quick-card">
            <div class="quick-icon icon-green"><i class="ri ri-tools-line"></i></div>
            <div class="quick-label">Services</div>
            <div class="quick-sub">{{ $totalServices }} total</div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="{{ route('allindex.index') }}" class="quick-card">
            <div class="quick-icon icon-slate"><i class="ri ri-group-line"></i></div>
            <div class="quick-label">Users</div>
            <div class="quick-sub">All users</div>
        </a>
    </div>
</div>

{{-- ── Notifications ──────────────────────────────────────────── --}}
<div class="section-title mt-2">
    Notifications
    @if($unreadCount > 0)
    <span class="cnt-badge cb-orange ms-2">{{ $unreadCount }} unread</span>
    @endif
</div>
<div class="main-card mb-4">
    <div class="main-card-header">
        <span class="main-card-title">
            <i class="ri ri-notification-3-line me-2" style="color:var(--accent);"></i>
            Recent Notifications
        </span>
        @if($unreadCount > 0)
        <form action="{{ route('admin.notifications.read-all') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="border:none;background:none;font-size:12px;color:#696cff;font-weight:700;cursor:pointer;padding:0;">
                <i class="ri ri-check-double-line me-1"></i>Mark all as read
            </button>
        </form>
        @endif
    </div>

    @if($notifications->count())
    <div style="divide-y:1px solid #f1f3f9;">
        @foreach($notifications as $notif)
        @php $d = $notif->data; @endphp
        <div style="display:flex;align-items:flex-start;gap:14px;padding:14px 20px;border-bottom:1px solid #f8f9fc;">
            <div style="width:40px;height:40px;border-radius:12px;background:
                @if(($d['data']['type'] ?? '') === 'business_account_request') #fff4e5
                @elseif(($d['data']['type'] ?? '') === 'service_request') #eef0ff
                @else #fdeaea @endif
                ;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="ri
                    @if(($d['data']['type'] ?? '') === 'business_account_request') ri-building-2-line" style="color:#ff9f43;
                    @elseif(($d['data']['type'] ?? '') === 'service_request') ri-tools-line" style="color:#696cff;
                    @else ri-flag-line" style="color:#ea5455; @endif
                    font-size:18px;"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:14px;font-weight:700;color:#1e293b;">{{ $d['title'] ?? '' }}</div>
                <div style="font-size:13px;color:#64748b;margin-top:2px;">{{ $d['message'] ?? '' }}</div>
                <div style="font-size:11px;color:#b0aab8;margin-top:4px;">
                    <i class="ri ri-time-line me-1"></i>{{ $notif->created_at->diffForHumans() }}
                </div>
            </div>
            @if(! $notif->read_at)
            <div style="width:8px;height:8px;border-radius:50%;background:#696cff;margin-top:6px;flex-shrink:0;"></div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <i class="ri ri-notification-off-line"></i>
        <p class="fw-bold mb-1" style="color:#1e293b;">No Notifications</p>
        <p class="small mb-0">You're all caught up</p>
    </div>
    @endif
</div>

{{-- Edit Profile Modal --}}
<div class="modal fade" id="editAdminProfileModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
    <div class="modal-content" style="border:0;border-radius:20px;box-shadow:0 24px 70px rgba(0,0,0,.18);">
      <div class="modal-header" style="border-bottom:1px solid #f1f3f9;padding:20px 26px;">
        <h5 class="modal-title" style="font-weight:800;color:#1e293b;">
          <i class="ri ri-edit-line me-2" style="color:#696cff;"></i>Edit Profile
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.profile.update') }}" method="POST">
        @csrf
        <div class="modal-body" style="padding:26px;">

          @if($errors->any())
          <div class="alert alert-danger rounded-3 mb-4" style="font-size:13px;padding:12px 16px;">
            <ul class="mb-0 ps-3">
              @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
          </div>
          @endif

          <div class="mb-3">
            <label style="font-size:11px;font-weight:700;color:#8592a3;text-transform:uppercase;letter-spacing:.5px;">Name</label>
            <input type="text" name="name" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e2e8f0;"
                   value="{{ old('name', $admin->name) }}" required>
          </div>

          <div class="mb-3">
            <label style="font-size:11px;font-weight:700;color:#8592a3;text-transform:uppercase;letter-spacing:.5px;">Email</label>
            <input type="email" name="email" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e2e8f0;"
                   value="{{ old('email', $admin->email) }}" required>
          </div>

          <hr style="border-color:#f1f3f9;margin:18px 0 14px;">
          <p style="font-size:12px;color:#8592a3;margin-bottom:14px;">
            <i class="ri ri-lock-line me-1"></i>Leave blank to keep current password.
          </p>

          <div class="mb-3">
            <label style="font-size:11px;font-weight:700;color:#8592a3;text-transform:uppercase;letter-spacing:.5px;">Current Password</label>
            <input type="password" name="current_password" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e2e8f0;"
                   placeholder="Required only if changing password">
          </div>

          <div class="mb-3">
            <label style="font-size:11px;font-weight:700;color:#8592a3;text-transform:uppercase;letter-spacing:.5px;">New Password</label>
            <input type="password" name="new_password" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e2e8f0;"
                   placeholder="Min 8 characters">
          </div>

          <div class="mb-0">
            <label style="font-size:11px;font-weight:700;color:#8592a3;text-transform:uppercase;letter-spacing:.5px;">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e2e8f0;"
                   placeholder="Repeat new password">
          </div>

        </div>
        <div class="modal-footer" style="border-top:1px solid #f1f3f9;padding:18px 26px;gap:10px;">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                  style="border-radius:12px;font-weight:700;">Cancel</button>
          <button type="submit" class="btn btn-primary" style="border-radius:12px;font-weight:700;">
            <i class="ri ri-save-line me-1"></i>Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@if($errors->any())
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Modal(document.getElementById('editAdminProfileModal')).show();
});
</script>
@endpush
@endif

@endsection

