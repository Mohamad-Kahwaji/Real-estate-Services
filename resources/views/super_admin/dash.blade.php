@extends('layouts/contentNavbarLayout')

@section('title', 'Super Admin Dashboard')

@section('page-style')
<style>
  /* ── Profile Hero ── */
  .hero-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 28px rgba(105,108,255,.13);
    overflow: hidden;
  }
  .hero-cover {
    background: linear-gradient(135deg, #696cff 0%, #9c9eff 100%);
    height: 80px;
    position: relative;
  }
  .hero-cover::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 160px; height: 160px;
    background: rgba(255,255,255,.08);
    border-radius: 50%;
  }
  .hero-cover::after {
    content: '';
    position: absolute;
    bottom: -30px; right: 120px;
    width: 100px; height: 100px;
    background: rgba(255,255,255,.05);
    border-radius: 50%;
  }
  .hero-body {
    padding: 0 28px 24px;
  }
  .hero-ava {
    width: 88px; height: 88px;
    border-radius: 50%;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    border: 4px solid #fff;
    box-shadow: 0 6px 22px rgba(105,108,255,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 34px; font-weight: 800; color: #fff;
    margin-top: -44px;
    flex-shrink: 0;
  }
  .hero-name {
    font-size: 20px; font-weight: 800; color: #312d4b;
    margin: 0 0 4px;
  }
  .hero-email {
    font-size: 13px; color: #97939e; margin: 0 0 10px;
  }
  .hero-role {
    display: inline-flex; align-items: center; gap: 6px;
    background: #eef0ff; color: #696cff;
    border-radius: 30px; padding: 5px 14px;
    font-size: 12px; font-weight: 700;
  }
  .hero-top-row {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    margin-bottom: 10px;
  }
  .hero-meta {
    display: flex; flex-wrap: wrap; gap: 16px;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid #f0eef4;
  }
  .hero-meta-item {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: #7c748a; font-weight: 600;
  }
  .hero-meta-item i { font-size: 15px; }
  .hero-divider {
    width: 1px; background: #f0eef4; align-self: stretch;
  }
  .edit-profile-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: 10px;
    font-size: 12px; font-weight: 700;
    background: #eef0ff; color: #696cff;
    border: 1.5px solid #d0d3ff; cursor: pointer;
    transition: .2s; margin-top: 12px;
  }
  .edit-profile-btn:hover { background: #696cff; color: #fff; border-color: #696cff; }
  .hero-actions {
    display: flex; gap: 8px; flex-wrap: wrap;
    padding: 14px 28px;
    border-top: 1px solid #f0eef4;
    background: #faf9fc;
  }
  .hero-action-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: 10px;
    font-size: 12px; font-weight: 700;
    text-decoration: none; transition: .2s;
    background: #fff; border: 1.5px solid #e8e6ef;
    color: #585164;
  }
  .hero-action-btn:hover {
    border-color: #696cff; color: #696cff;
    background: #eef0ff; text-decoration: none;
  }
  .hero-action-btn i { font-size: 15px; }

  /* ── Stat Cards ── */
  .s-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(105,108,255,.1);
    padding: 20px;
    height: 100%;
    transition: transform .2s, box-shadow .2s;
    border: 1px solid rgba(105,108,255,.06);
  }
  .s-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 36px rgba(105,108,255,.18);
  }
  .s-icon {
    width: 50px; height: 50px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
  }
  .s-num  { font-size: 28px; font-weight: 800; color: #2e263d; line-height: 1; }
  .s-lbl  { font-size: 11px; font-weight: 700; color: #97939e; text-transform: uppercase; letter-spacing: .6px; margin-top: 4px; }
  .s-note { font-size: 11px; color: #b0aab8; margin-top: 2px; }

  /* ── Badge Pills ── */
  .bp { display: inline-flex; align-items: center; gap: 3px; padding: 3px 9px; border-radius: 30px; font-size: 11px; font-weight: 700; }
  .bp-orange { background: #fff4e5; color: #ff9f43; }
  .bp-green  { background: #e8faf0; color: #28c76f; }
  .bp-red    { background: #fdeaea; color: #ea5455; }
  .bp-gray   { background: #f1f0f4; color: #97939e; }
  .bp-purple { background: #eef0ff; color: #696cff; }

  /* ── Recent Table Cards ── */
  .rc {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(105,108,255,.09);
    overflow: hidden;
    height: 100%;
  }
  .rc-hdr {
    padding: 16px 20px;
    border-bottom: 1px solid #f0eef4;
    display: flex; align-items: center; justify-content: space-between;
    background: #fff;
  }
  .rc-title { font-size: 15px; font-weight: 700; color: #312d4b; }
  .rc-link  { font-size: 12px; color: #97939e; text-decoration: none; }
  .rc-link:hover { color: #696cff; }
  .rt th {
    font-size: 10px; font-weight: 700; letter-spacing: .6px;
    text-transform: uppercase; color: #97939e;
    background: #faf9fc; border: none; padding: 11px 16px;
  }
  .rt td { padding: 11px 16px; vertical-align: middle; border-color: #f0eef4; font-size: 13px; }
  .rt tbody tr:last-child td { border-bottom: none; }
  .rt tbody tr:hover { background: #fafbff; }

  /* ── Status Pills ── */
  .sp { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 30px; font-size: 11px; font-weight: 700; }
  .sp::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
  .sp-pending  { background: #fff4e5; color: #ff9f43; }
  .sp-approved { background: #e8faf0; color: #28c76f; }
  .sp-rejected { background: #fdeaea; color: #ea5455; }
  .sp-reviewed { background: #e3f8fc; color: #00cfe8; }
  .sp-resolved { background: #e8faf0; color: #28c76f; }

  /* ── Notifications ── */
  .notif-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(105,108,255,.09);
    overflow: hidden;
  }
  .notif-hdr {
    padding: 16px 20px;
    border-bottom: 1px solid #f0eef4;
    display: flex; align-items: center; justify-content: space-between;
  }
  .notif-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 13px 20px;
    border-bottom: 1px solid #f5f4f8;
    transition: background .15s;
  }
  .notif-item:last-child { border-bottom: none; }
  .notif-item:hover { background: #fafbff; }
  .notif-item.unread { background: #f5f5ff; }
  .notif-dot {
    width: 38px; height: 38px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 18px;
  }
  .notif-dot-orange { background: #fff4e5; color: #ff9f43; }
  .notif-dot-green  { background: #e8faf0; color: #28c76f; }
  .notif-dot-purple { background: #eef0ff; color: #696cff; }
  .notif-title { font-size: 13px; font-weight: 700; color: #312d4b; margin-bottom: 2px; }
  .notif-msg   { font-size: 12px; color: #7c748a; line-height: 1.4; }
  .notif-time  { font-size: 11px; color: #b0aab8; margin-top: 3px; }
</style>
@endsection

@section('content')
@php $initials = strtoupper(substr($superadmin->name ?? 'S', 0, 1)); @endphp

{{-- ═══════════════════════════════════════════════
     1. PROFILE HERO
═══════════════════════════════════════════════ --}}
<div class="row g-4 mb-4">
  <div class="col-12">
    <div class="hero-card">
      <div class="hero-cover"></div>

      <div class="hero-body">
        <div class="d-flex flex-wrap gap-4 align-items-start">

          {{-- Avatar --}}
          <div class="hero-ava">{{ $initials }}</div>

          {{-- Name / Email / Role / Meta --}}
          <div class="flex-grow-1" style="min-width:200px;">
            <h4 class="hero-name">{{ $superadmin->name ?? 'Super Admin' }}</h4>
            <p class="hero-email">{{ $superadmin->email }}</p>
            <div class="hero-top-row">
              <span class="hero-role">
                <i class="ri ri-shield-star-fill"></i>Super Administrator
              </span>
              <button type="button" class="edit-profile-btn"
                      data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="ri ri-edit-line"></i>Edit Profile
              </button>
            </div>

            <div class="hero-meta">
              <span class="hero-meta-item">
                <i class="ri ri-calendar-check-line" style="color:#28c76f;"></i>
                Joined {{ $superadmin->created_at?->format('M d, Y') ?? '-' }}
              </span>
              <span class="hero-meta-item">
                <i class="ri ri-time-line" style="color:#696cff;"></i>
                {{ now()->format('l, F j, Y') }}
              </span>
              <span class="hero-meta-item">
                <i class="ri ri-user-settings-line" style="color:#ff9f43;"></i>
                {{ $activeAdmins }} active admin{{ $activeAdmins !== 1 ? 's' : '' }}
                @if($inactiveAdmins > 0)
                  &middot; {{ $inactiveAdmins }} inactive
                @endif
              </span>
              @if($pendingBusinesses > 0)
              <span class="hero-meta-item" style="color:#ff9f43;">
                <i class="ri ri-building-2-line"></i>
                {{ $pendingBusinesses }} business{{ $pendingBusinesses !== 1 ? 'es' : '' }} awaiting approval
              </span>
              @endif
              @if($pendingReports > 0)
              <span class="hero-meta-item" style="color:#ea5455;">
                <i class="ri ri-flag-2-line"></i>
                {{ $pendingReports }} open report{{ $pendingReports !== 1 ? 's' : '' }}
              </span>
              @endif
            </div>
          </div>

        </div>
      </div>

      {{-- Quick Actions Bar --}}
      <div class="hero-actions">
        <a href="{{ route('adminsindex') }}" class="hero-action-btn">
          <i class="ri ri-admin-line"></i>Manage Admins
        </a>
        <a href="{{ route('business.index') }}" class="hero-action-btn">
          <i class="ri ri-building-2-line"></i>Businesses
          @if($pendingBusinesses > 0)
          <span class="bp bp-orange" style="font-size:10px;padding:2px 7px;">{{ $pendingBusinesses }}</span>
          @endif
        </a>
        <a href="{{ route('allserviesad') }}" class="hero-action-btn">
          <i class="ri ri-service-line"></i>Services
        </a>
        <a href="{{ route('reports.index') }}" class="hero-action-btn">
          <i class="ri ri-flag-2-line"></i>Reports
          @if($pendingReports > 0)
          <span class="bp bp-red" style="font-size:10px;padding:2px 7px;">{{ $pendingReports }}</span>
          @endif
        </a>
        <a href="{{ route('allindex.index') }}" class="hero-action-btn">
          <i class="ri ri-group-line"></i>Users
        </a>
        <a href="{{ route('categories.index') }}" class="hero-action-btn">
          <i class="ri ri-layout-grid-line"></i>Categories
        </a>
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════
     2. STAT CARDS — Main Entities
═══════════════════════════════════════════════ --}}
<div class="row g-4 mb-3">

  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('business.index') }}" style="text-decoration:none;">
      <div class="s-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="s-icon" style="background:#fff4e5;color:#ff9f43;">
            <i class="ri ri-building-2-line"></i>
          </div>
          @if($pendingBusinesses > 0)<span class="bp bp-orange">{{ $pendingBusinesses }} new</span>@endif
        </div>
        <div class="s-num">{{ $totalBusinesses }}</div>
        <div class="s-lbl">Businesses</div>
        <div class="s-note">{{ $pendingBusinesses }} pending approval</div>
      </div>
    </a>
  </div>

  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('allserviesad') }}" style="text-decoration:none;">
      <div class="s-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="s-icon" style="background:#eef0ff;color:#696cff;">
            <i class="ri ri-tools-line"></i>
          </div>
          @if($pendingServices > 0)<span class="bp bp-purple">{{ $pendingServices }} new</span>@endif
        </div>
        <div class="s-num">{{ $totalServices }}</div>
        <div class="s-lbl">Services</div>
        <div class="s-note">{{ $pendingServices }} pending approval</div>
      </div>
    </a>
  </div>

  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('adminsindex') }}" style="text-decoration:none;">
      <div class="s-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="s-icon" style="background:#e8faf0;color:#28c76f;">
            <i class="ri ri-user-settings-line"></i>
          </div>
          <span class="bp bp-green">{{ $activeAdmins }} active</span>
        </div>
        <div class="s-num">{{ $totalAdmins }}</div>
        <div class="s-lbl">Admins</div>
        <div class="s-note">{{ $inactiveAdmins }} inactive</div>
      </div>
    </a>
  </div>

  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('reports.index') }}" style="text-decoration:none;">
      <div class="s-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="s-icon" style="background:#fdeaea;color:#ea5455;">
            <i class="ri ri-flag-2-line"></i>
          </div>
          @if($pendingReports > 0)<span class="bp bp-red">{{ $pendingReports }} open</span>@endif
        </div>
        <div class="s-num">{{ $totalReports }}</div>
        <div class="s-lbl">Reports</div>
        <div class="s-note">{{ $pendingReports }} need review</div>
      </div>
    </a>
  </div>

  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('allindex.index') }}" style="text-decoration:none;">
      <div class="s-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="s-icon" style="background:#e3f8fc;color:#00cfe8;">
            <i class="ri ri-group-line"></i>
          </div>
        </div>
        <div class="s-num">{{ $totalUsers }}</div>
        <div class="s-lbl">Users</div>
        <div class="s-note">Registered users</div>
      </div>
    </a>
  </div>

  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('superadmin.service-requests') }}" style="text-decoration:none;">
      <div class="s-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="s-icon" style="background:#f0f0ff;color:#8a8fff;">
            <i class="ri ri-file-list-3-line"></i>
          </div>
          @if($pendingRequests > 0)<span class="bp bp-purple">{{ $pendingRequests }}</span>@endif
        </div>
        <div class="s-num">{{ $totalRequests }}</div>
        <div class="s-lbl">Requests</div>
        <div class="s-note">{{ $pendingRequests }} pending</div>
      </div>
    </a>
  </div>

</div>

{{-- ═══════════════════════════════════════════════
     3. STAT CARDS — Content Management
═══════════════════════════════════════════════ --}}
<div class="row g-4 mb-4">

  <div class="col-6 col-md-4 col-xl-4">
    <div class="s-card">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="s-icon" style="background:#eef0ff;color:#696cff;">
          <i class="ri ri-layout-grid-line"></i>
        </div>
        <a href="{{ route('categories.index') }}" class="bp bp-purple" style="text-decoration:none;">Manage</a>
      </div>
      <div class="s-num">{{ $totalCategories }}</div>
      <div class="s-lbl">Categories</div>
      <div class="s-note">Service classification</div>
    </div>
  </div>

  <div class="col-6 col-md-4 col-xl-4">
    <div class="s-card">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="s-icon" style="background:#fff4e5;color:#ff9f43;">
          <i class="ri ri-list-check-2"></i>
        </div>
        <a href="{{ route('subcategories.index') }}" class="bp bp-orange" style="text-decoration:none;">Manage</a>
      </div>
      <div class="s-num">{{ $totalSubcategories }}</div>
      <div class="s-lbl">Subcategories</div>
      <div class="s-note">Under {{ $totalCategories }} categories</div>
    </div>
  </div>

  <div class="col-6 col-md-4 col-xl-4">
    <div class="s-card">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="s-icon" style="background:#e3f8fc;color:#00cfe8;">
          <i class="ri ri-map-pin-2-line"></i>
        </div>
        <a href="{{ route('cities.index') }}" class="bp bp-gray" style="text-decoration:none;background:#e3f8fc;color:#00cfe8;">Manage</a>
      </div>
      <div class="s-num">{{ $totalCities }}</div>
      <div class="s-lbl">Cities</div>
      <div class="s-note">Available locations</div>
    </div>
  </div>

</div>

{{-- ═══════════════════════════════════════════════
     4. NOTIFICATIONS
═══════════════════════════════════════════════ --}}
<div class="row g-4 mb-4">
  <div class="col-12">
    <div class="notif-card">
      <div class="notif-hdr">
        <span class="rc-title">
          <i class="ri ri-notification-3-line me-2" style="color:#696cff;"></i>
          Notifications
          @if($unreadCount > 0)
            <span class="bp bp-purple ms-2">{{ $unreadCount }} new</span>
          @endif
        </span>
        @if($unreadCount > 0)
        <form action="{{ route('notifications.readAll') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-sm" style="background:#eef0ff;color:#696cff;font-size:12px;font-weight:700;border-radius:10px;padding:5px 14px;">
            <i class="ri ri-check-double-line me-1"></i>Mark all as read
          </button>
        </form>
        @endif
      </div>

      @forelse($notifications as $notif)
        @php
          $nd       = $notif->data;
          $type     = $nd['data']['type'] ?? ($nd['type'] ?? 'general');
          $dotClass = match($type) {
            'business_account_request' => 'notif-dot-orange',
            'business_account'         => 'notif-dot-green',
            default                    => 'notif-dot-purple',
          };
          $iconClass = match($type) {
            'business_account_request' => 'ri-building-2-line',
            'business_account'         => 'ri-building-2-fill',
            default                    => 'ri-bell-line',
          };
        @endphp
        <div class="notif-item {{ $notif->read_at ? '' : 'unread' }}">
          <div class="notif-dot {{ $dotClass }}">
            <i class="ri {{ $iconClass }}"></i>
          </div>
          <div style="flex:1;min-width:0;">
            <div class="notif-title">{{ $nd['title'] ?? 'Notification' }}</div>
            <div class="notif-msg">{{ $nd['message'] ?? '' }}</div>
            <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
          </div>
          @if(!$notif->read_at)
          <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="flex-shrink-0">
            @csrf
            <button type="submit" title="Mark as read"
              style="background:none;border:none;color:#b0aab8;padding:4px;cursor:pointer;font-size:16px;">
              <i class="ri ri-check-line"></i>
            </button>
          </form>
          @endif
        </div>
      @empty
        <div style="text-align:center;padding:40px;color:#b0aab8;">
          <i class="ri ri-notification-off-line" style="font-size:32px;display:block;margin-bottom:8px;opacity:.4;"></i>
          No notifications yet
        </div>
      @endforelse
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════
     5. RECENT TABLES
═══════════════════════════════════════════════ --}}
<div class="row g-4">

  {{-- Recent Businesses --}}
  <div class="col-12 col-xl-5">
    <div class="rc">
      <div class="rc-hdr">
        <span class="rc-title">
          <i class="ri ri-building-2-line me-2" style="color:#ff9f43;"></i>Recent Businesses
        </span>
        <a href="{{ route('business.index') }}" class="rc-link">View all &rarr;</a>
      </div>
      <div class="table-responsive">
        <table class="table rt mb-0">
          <thead>
            <tr>
              <th>Business</th>
              <th>Owner</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentBusinesses as $biz)
            <tr>
              <td>
                <div style="font-weight:700;color:#312d4b;">{{ $biz->job_name_en ?? '-' }}</div>
                <div style="font-size:11px;color:#97939e;">{{ $biz->city?->name_en ?? '' }}</div>
              </td>
              <td style="color:#585164;">{{ $biz->user?->name ?? '-' }}</td>
              <td>
                @php $s = $biz->status ?? 'pending'; @endphp
                <span class="sp sp-{{ $s }}">{{ ucfirst($s) }}</span>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="3" style="text-align:center;padding:40px;color:#b0aab8;">
                <i class="ri ri-building-2-line" style="font-size:30px;display:block;margin-bottom:8px;opacity:.4;"></i>
                No businesses yet
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Pending Services --}}
  <div class="col-12 col-xl-4">
    <div class="rc">
      <div class="rc-hdr">
        <span class="rc-title">
          <i class="ri ri-time-line me-2" style="color:#ff9f43;"></i>Pending Services
        </span>
        <a href="{{ route('allserviesad') }}?status=pending" class="rc-link">View all &rarr;</a>
      </div>
      <div class="table-responsive">
        <table class="table rt mb-0">
          <thead>
            <tr>
              <th>Service</th>
              <th>Business</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pendingServicesList as $svc)
            <tr>
              <td>
                <div style="font-weight:700;color:#312d4b;">{{ $svc->title }}</div>
                <div style="font-size:11px;color:#97939e;">{{ $svc->category?->name ?? '' }}</div>
              </td>
              <td style="color:#585164;">{{ $svc->business?->job_name_en ?? '-' }}</td>
              <td>
                <span class="sp sp-pending">Pending</span>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="3" style="text-align:center;padding:40px;color:#b0aab8;">
                <i class="ri ri-checkbox-circle-line" style="font-size:30px;display:block;margin-bottom:8px;opacity:.4;color:#28c76f;"></i>
                No pending services
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Recent Reports --}}
  <div class="col-12 col-xl-3">
    <div class="rc">
      <div class="rc-hdr">
        <span class="rc-title">
          <i class="ri ri-flag-2-line me-2" style="color:#ea5455;"></i>Recent Reports
        </span>
        <a href="{{ route('reports.index') }}" class="rc-link">View all &rarr;</a>
      </div>
      <div class="table-responsive">
        <table class="table rt mb-0">
          <thead>
            <tr>
              <th>Service</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentReports as $rep)
            <tr>
              <td style="font-weight:600;color:#312d4b;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $rep->service?->title ?? 'Deleted' }}
                <div style="font-size:11px;color:#97939e;">{{ $rep->user?->name ?? '-' }}</div>
              </td>
              <td>
                @php $rs = $rep->status ?? 'pending'; @endphp
                <span class="sp sp-{{ $rs }}">{{ ucfirst($rs) }}</span>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="2" style="text-align:center;padding:40px;color:#b0aab8;">
                <i class="ri ri-flag-2-line" style="font-size:30px;display:block;margin-bottom:8px;opacity:.4;"></i>
                No reports yet
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

{{-- ═══════════════════════════════════════════════
     Edit Profile Modal
═══════════════════════════════════════════════ --}}
<div class="modal fade" id="editProfileModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
    <div class="modal-content" style="border:0;border-radius:20px;box-shadow:0 24px 70px rgba(0,0,0,.18);">
      <div class="modal-header" style="border-bottom:1px solid #f0eef4;padding:20px 26px;">
        <h5 class="modal-title" style="font-weight:800;color:#312d4b;">
          <i class="ri ri-edit-line me-2" style="color:#696cff;"></i>Edit Profile
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('superadmin.profile.update') }}" method="POST">
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
            <label style="font-size:11px;font-weight:700;color:#7c748a;text-transform:uppercase;letter-spacing:.5px;">Name</label>
            <input type="text" name="name" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e8e6ef;"
                   value="{{ old('name', $superadmin->name) }}" required>
          </div>

          <div class="mb-3">
            <label style="font-size:11px;font-weight:700;color:#7c748a;text-transform:uppercase;letter-spacing:.5px;">Email</label>
            <input type="email" name="email" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e8e6ef;"
                   value="{{ old('email', $superadmin->email) }}" required>
          </div>

          <hr style="border-color:#f0eef4;margin:18px 0 14px;">
          <p style="font-size:12px;color:#97939e;margin-bottom:14px;">
            <i class="ri ri-lock-line me-1"></i>Leave blank to keep current password.
          </p>

          <div class="mb-3">
            <label style="font-size:11px;font-weight:700;color:#7c748a;text-transform:uppercase;letter-spacing:.5px;">Current Password</label>
            <input type="password" name="current_password" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e8e6ef;"
                   placeholder="Required only if changing password">
          </div>

          <div class="mb-3">
            <label style="font-size:11px;font-weight:700;color:#7c748a;text-transform:uppercase;letter-spacing:.5px;">New Password</label>
            <input type="password" name="new_password" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e8e6ef;"
                   placeholder="Min 8 characters">
          </div>

          <div class="mb-0">
            <label style="font-size:11px;font-weight:700;color:#7c748a;text-transform:uppercase;letter-spacing:.5px;">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e8e6ef;"
                   placeholder="Repeat new password">
          </div>

        </div>
        <div class="modal-footer" style="border-top:1px solid #f0eef4;padding:18px 26px;gap:10px;">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
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
    new bootstrap.Modal(document.getElementById('editProfileModal')).show();
});
</script>
@endpush
@endif

@endsection
