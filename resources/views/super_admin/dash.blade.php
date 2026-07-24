@extends('layouts/contentNavbarLayout')
{{-- Super admin overview dashboard: profile hero, platform-wide stats, notifications, and recent activity tables. --}}

@section('title', __('app.dashboard'))

@section('page-style')
<style>
  /* ── Profile Hero ── */
  .hero-card {
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 8px 40px rgba(var(--role-accent-rgb),.16);
    overflow: hidden;
  }
  .hero-cover {
    background: linear-gradient(135deg, var(--role-accent) 0%, var(--role-accent) 45%, var(--role-accent-light) 75%, var(--role-accent-light) 100%);
    height: 160px;
    position: relative;
    overflow: hidden;
  }
  /* large circle top-right */
  .hero-cover::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 240px; height: 240px;
    background: rgba(255,255,255,.07);
    border-radius: 50%;
  }
  /* medium circle bottom-left */
  .hero-cover::after {
    content: '';
    position: absolute;
    bottom: -50px; left: -30px;
    width: 170px; height: 170px;
    background: rgba(255,255,255,.05);
    border-radius: 50%;
  }
  .hero-cover-dot1 {
    position: absolute; top: 20px; left: 42%;
    width: 80px; height: 80px;
    background: rgba(255,255,255,.06);
    border-radius: 50%;
  }
  .hero-cover-dot2 {
    position: absolute; bottom: 10px; right: 180px;
    width: 50px; height: 50px;
    background: rgba(255,255,255,.08);
    border-radius: 50%;
  }
  .hero-cover-label {
    position: absolute;
    top: 18px; right: 22px;
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.18);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 30px;
    padding: 5px 14px;
    font-size: 12px; font-weight: 700; color: #fff;
  }
  .hero-body {
    padding: 0 28px 24px;
    position: relative;
  }
  .hero-ava-wrap {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 14px;
  }
  .hero-ava {
    width: 100px; height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--role-accent), var(--role-accent-light));
    border: 4px solid #fff;
    box-shadow: 0 8px 28px rgba(var(--role-accent-rgb),.38);
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; font-weight: 800; color: #fff;
    margin-top: -50px;
    flex-shrink: 0;
    position: relative;
    z-index: 2;
  }
  .hero-ava-online {
    width: 16px; height: 16px;
    background: #28c76f;
    border: 3px solid #fff;
    border-radius: 50%;
    position: absolute;
    bottom: 4px; right: 4px;
  }
  .edit-profile-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 12px;
    font-size: 12px; font-weight: 700;
    background: var(--role-accent-soft); color: var(--role-accent);
    border: 1.5px solid var(--role-accent-light); cursor: pointer;
    transition: .2s;
  }
  .edit-profile-btn:hover { background: var(--role-accent); color: #fff; border-color: var(--role-accent); }
  .hero-name {
    font-size: 22px; font-weight: 800; color: #312d4b;
    margin: 0 0 3px;
  }
  .hero-email {
    font-size: 13px; color: #97939e; margin: 0 0 10px;
  }
  .hero-role {
    display: inline-flex; align-items: center; gap: 6px;
    background: linear-gradient(90deg,var(--role-accent),var(--role-accent-light));
    color: #fff;
    border-radius: 30px; padding: 5px 16px;
    font-size: 12px; font-weight: 700;
    box-shadow: 0 4px 12px rgba(var(--role-accent-rgb),.3);
  }
  .hero-meta {
    display: flex; flex-wrap: wrap; gap: 10px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #f0eef4;
  }
  .hero-meta-chip {
    display: inline-flex; align-items: center; gap: 6px;
    background: #faf9fc;
    border: 1px solid #eeecf4;
    border-radius: 10px;
    padding: 6px 14px;
    font-size: 12px; color: #585164; font-weight: 600;
  }
  .hero-meta-chip i { font-size: 15px; }
  .hero-actions {
    display: flex; gap: 8px; flex-wrap: wrap;
    padding: 14px 28px;
    border-top: 1px solid #f0eef4;
    background: #faf9fc;
  }
  .hero-action-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px; border-radius: 12px;
    font-size: 12px; font-weight: 700;
    text-decoration: none; transition: .2s;
    background: #fff; border: 1.5px solid #e8e6ef;
    color: #585164;
  }
  .hero-action-btn:hover {
    border-color: var(--role-accent); color: var(--role-accent);
    background: var(--role-accent-soft); text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(var(--role-accent-rgb),.15);
  }
  .hero-action-btn i { font-size: 16px; }

  /* ── Stat Cards ── */
  .s-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(var(--role-accent-rgb),.1);
    padding: 21px;
    height: 100%;
    transition: transform .2s, box-shadow .2s;
    border: 1px solid rgba(var(--role-accent-rgb),.06);
  }
  .s-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 36px rgba(var(--role-accent-rgb),.18);
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
  .bp-purple { background: var(--role-accent-soft); color: var(--role-accent); }

  /* ── Recent Table Cards ── */
  .rc {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(var(--role-accent-rgb),.09);
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
  .rc-link:hover { color: var(--role-accent); }
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
    box-shadow: 0 4px 24px rgba(var(--role-accent-rgb),.09);
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
  .notif-dot-purple { background: var(--role-accent-soft); color: var(--role-accent); }
  .notif-title { font-size: 13px; font-weight: 700; color: #312d4b; margin-bottom: 2px; }
  .notif-msg   { font-size: 12px; color: #7c748a; line-height: 1.4; }
  .notif-time  { font-size: 11px; color: #b0aab8; margin-top: 3px; }
</style>
@endsection

@section('content')
@php
    $nameParts = array_filter(explode(' ', $superadmin->name ?? 'S'));
    $initials  = strtoupper(substr($nameParts[0] ?? 'S', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
@endphp

{{-- ═══════════════════════════════════════════════
     1. PROFILE HERO
═══════════════════════════════════════════════ --}}
<div class="row g-4 mb-4">
  <div class="col-12">
    <div class="hero-card">

      {{-- Cover --}}
      <div class="hero-cover">
        <div class="hero-cover-dot1"></div>
        <div class="hero-cover-dot2"></div>
        <span class="hero-cover-label">
          <i class="ri ri-shield-star-fill"></i>{{ __('app.super_administrator') }}
        </span>
      </div>

      {{-- Body --}}
      <div class="hero-body">

        {{-- Avatar row + edit button --}}
        <div class="hero-ava-wrap">
          <div class="hero-ava" style="position:relative;">
            {{ $initials }}
            <span class="hero-ava-online"></span>
          </div>
          <button type="button" class="edit-profile-btn"
                  data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="ri ri-edit-line"></i>{{ __('app.edit_profile') }}
          </button>
        </div>

        {{-- Name / Email / Role --}}
        <h4 class="hero-name">{{ $superadmin->name ?? 'Super Admin' }}</h4>
        <p class="hero-email">{{ $superadmin->email }}</p>
        <span class="hero-role">
          <i class="ri ri-shield-star-fill"></i>{{ __('app.super_administrator') }}
        </span>

        {{-- Meta chips --}}
        <div class="hero-meta">
          <span class="hero-meta-chip">
            <i class="ri ri-calendar-check-line" style="color:#28c76f;"></i>
            {{ __('app.joined') }} {{ $superadmin->created_at?->format('M d, Y') ?? '-' }}
          </span>
          <span class="hero-meta-chip">
            <i class="ri ri-time-line" style="color:var(--role-accent);"></i>
            {{ now()->format('l, F j, Y') }}
          </span>
          <span class="hero-meta-chip">
            <i class="ri ri-user-settings-line" style="color:#ff9f43;"></i>
            {{ __('app.x_active_admins', ['count' => $activeAdmins]) }}
            @if($inactiveAdmins > 0)
              &middot; {{ __('app.x_inactive', ['count' => $inactiveAdmins]) }}
            @endif
          </span>
          @if($pendingBusinesses > 0)
          <span class="hero-meta-chip" style="background:#fff8f0;border-color:#ffe4bc;color:#ff9f43;">
            <i class="ri ri-building-2-line"></i>
            {{ __('app.x_businesses_awaiting', ['count' => $pendingBusinesses]) }}
          </span>
          @endif
          @if($pendingReports > 0)
          <span class="hero-meta-chip" style="background:#fff5f5;border-color:#ffd5d5;color:#ea5455;">
            <i class="ri ri-flag-2-line"></i>
            {{ __('app.x_open_reports', ['count' => $pendingReports]) }}
          </span>
          @endif
        </div>

      </div>

      {{-- Quick Actions Bar --}}
      <div class="hero-actions">
        <a href="{{ route('adminsindex') }}" class="hero-action-btn">
          <i class="ri ri-admin-line"></i>{{ __('app.manage_admins') }}
        </a>
        <a href="{{ route('business.index') }}" class="hero-action-btn">
          <i class="ri ri-building-2-line"></i>{{ __('app.businesses') }}
          @if($pendingBusinesses > 0)
          <span class="bp bp-orange" style="font-size:10px;padding:2px 7px;">{{ $pendingBusinesses }}</span>
          @endif
        </a>
        <a href="{{ route('allserviesad') }}" class="hero-action-btn">
          <i class="ri ri-service-line"></i>{{ __('app.services') }}
        </a>
        <a href="{{ route('reports.index') }}" class="hero-action-btn">
          <i class="ri ri-flag-2-line"></i>{{ __('app.reports') }}
          @if($pendingReports > 0)
          <span class="bp bp-red" style="font-size:10px;padding:2px 7px;">{{ $pendingReports }}</span>
          @endif
        </a>
        <a href="{{ route('allindex.index') }}" class="hero-action-btn">
          <i class="ri ri-group-line"></i>{{ __('app.users') }}
        </a>
        <a href="{{ route('categories.index') }}" class="hero-action-btn">
          <i class="ri ri-layout-grid-line"></i>{{ __('app.categories') }}
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
          @if($pendingBusinesses > 0)<span class="bp bp-orange">{{ $pendingBusinesses }} {{ __('app.new_badge') }}</span>@endif
        </div>
        <div class="s-num">{{ $totalBusinesses }}</div>
        <div class="s-lbl">{{ __('app.businesses') }}</div>
        <div class="s-note">{{ __('app.x_pending_approval', ['count' => $pendingBusinesses]) }}</div>
      </div>
    </a>
  </div>

  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('allserviesad') }}" style="text-decoration:none;">
      <div class="s-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="s-icon" style="background:var(--role-accent-soft);color:var(--role-accent);">
            <i class="ri ri-tools-line"></i>
          </div>
          @if($pendingServices > 0)<span class="bp bp-purple">{{ $pendingServices }} {{ __('app.new_badge') }}</span>@endif
        </div>
        <div class="s-num">{{ $totalServices }}</div>
        <div class="s-lbl">{{ __('app.services') }}</div>
        <div class="s-note">{{ __('app.x_pending_approval', ['count' => $pendingServices]) }}</div>
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
          <span class="bp bp-green">{{ $activeAdmins }} {{ __('app.active') }}</span>
        </div>
        <div class="s-num">{{ $totalAdmins }}</div>
        <div class="s-lbl">{{ __('app.admins') }}</div>
        <div class="s-note">{{ __('app.x_inactive', ['count' => $inactiveAdmins]) }}</div>
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
          @if($pendingReports > 0)<span class="bp bp-red">{{ $pendingReports }} {{ __('app.open_badge') }}</span>@endif
        </div>
        <div class="s-num">{{ $totalReports }}</div>
        <div class="s-lbl">{{ __('app.reports') }}</div>
        <div class="s-note">{{ __('app.x_need_review', ['count' => $pendingReports]) }}</div>
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
        <div class="s-lbl">{{ __('app.users') }}</div>
        <div class="s-note">{{ __('app.registered_accounts') }}</div>
      </div>
    </a>
  </div>

  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('superadmin.service-requests') }}" style="text-decoration:none;">
      <div class="s-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="s-icon" style="background:var(--role-accent-soft);color:var(--role-accent-light);">
            <i class="ri ri-file-list-3-line"></i>
          </div>
          @if($pendingRequests > 0)<span class="bp bp-purple">{{ $pendingRequests }}</span>@endif
        </div>
        <div class="s-num">{{ $totalRequests }}</div>
        <div class="s-lbl">{{ __('app.requests') }}</div>
        <div class="s-note">{{ __('app.x_pending', ['count' => $pendingRequests]) }}</div>
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
        <div class="s-icon" style="background:var(--role-accent-soft);color:var(--role-accent);">
          <i class="ri ri-layout-grid-line"></i>
        </div>
        <a href="{{ route('categories.index') }}" class="bp bp-purple" style="text-decoration:none;">{{ __('app.manage') }}</a>
      </div>
      <div class="s-num">{{ $totalCategories }}</div>
      <div class="s-lbl">{{ __('app.categories') }}</div>
      <div class="s-note">{{ __('app.service_classification') }}</div>
    </div>
  </div>

  <div class="col-6 col-md-4 col-xl-4">
    <div class="s-card">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="s-icon" style="background:#fff4e5;color:#ff9f43;">
          <i class="ri ri-list-check-2"></i>
        </div>
        <a href="{{ route('subcategories.index') }}" class="bp bp-orange" style="text-decoration:none;">{{ __('app.manage') }}</a>
      </div>
      <div class="s-num">{{ $totalSubcategories }}</div>
      <div class="s-lbl">{{ __('app.subcategories') }}</div>
      <div class="s-note">{{ __('app.under_x_categories', ['count' => $totalCategories]) }}</div>
    </div>
  </div>

  <div class="col-6 col-md-4 col-xl-4">
    <div class="s-card">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="s-icon" style="background:#e3f8fc;color:#00cfe8;">
          <i class="ri ri-map-pin-2-line"></i>
        </div>
        <a href="{{ route('cities.index') }}" class="bp bp-gray" style="text-decoration:none;background:#e3f8fc;color:#00cfe8;">{{ __('app.manage') }}</a>
      </div>
      <div class="s-num">{{ $totalCities }}</div>
      <div class="s-lbl">{{ __('app.cities') }}</div>
      <div class="s-note">{{ __('app.available_locations') }}</div>
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
          <i class="ri ri-notification-3-line me-2" style="color:var(--role-accent);"></i>
          {{ __('app.notifications') }}
          @if($unreadCount > 0)
            <span class="bp bp-purple ms-2">{{ $unreadCount }} {{ __('app.new_badge') }}</span>
          @endif
        </span>
        @if($unreadCount > 0)
        <form action="{{ route('notifications.readAll') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-sm" style="background:var(--role-accent-soft);color:var(--role-accent);font-size:12px;font-weight:700;border-radius:10px;padding:5px 14px;">
            <i class="ri ri-check-double-line me-1"></i>{{ __('app.mark_all_as_read') }}
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
          {{ __('app.no_notifications_yet') }}
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
          <i class="ri ri-building-2-line me-2" style="color:#ff9f43;"></i>{{ __('app.recent_businesses') }}
        </span>
        <a href="{{ route('business.index') }}" class="rc-link">{{ __('app.view_all') }}</a>
      </div>
      <div class="table-responsive">
        <table class="table rt mb-0">
          <thead>
            <tr>
              <th>{{ __('app.business') }}</th>
              <th>{{ __('app.owner') }}</th>
              <th>{{ __('app.status') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentBusinesses as $biz)
            <tr>
              <td>
                <div style="font-weight:700;color:#312d4b;">{{ app()->getLocale() === 'ar' ? ($biz->job_name_ar ?? $biz->job_name_en ?? '-') : ($biz->job_name_en ?? $biz->job_name_ar ?? '-') }}</div>
                <div style="font-size:11px;color:#97939e;">{{ app()->getLocale() === 'ar' ? ($biz->city?->name_ar ?? '') : ($biz->city?->name_en ?? '') }}</div>
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
                {{ __('app.no_businesses_yet') }}
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
          <i class="ri ri-time-line me-2" style="color:#ff9f43;"></i>{{ __('app.pending_services') }}
        </span>
        <a href="{{ route('allserviesad') }}?status=pending" class="rc-link">{{ __('app.view_all') }}</a>
      </div>
      <div class="table-responsive">
        <table class="table rt mb-0">
          <thead>
            <tr>
              <th>{{ __('app.service') }}</th>
              <th>{{ __('app.business') }}</th>
              <th>{{ __('app.status') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pendingServicesList as $svc)
            <tr>
              <td>
                <div style="font-weight:700;color:#312d4b;">{{ $svc->title }}</div>
                <div style="font-size:11px;color:#97939e;">{{ $svc->category?->name ?? '' }}</div>
              </td>
              <td style="color:#585164;">{{ app()->getLocale() === 'ar' ? ($svc->business?->job_name_ar ?? $svc->business?->job_name_en ?? '-') : ($svc->business?->job_name_en ?? $svc->business?->job_name_ar ?? '-') }}</td>
              <td>
                <span class="sp sp-pending">{{ __('app.pending') }}</span>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="3" style="text-align:center;padding:40px;color:#b0aab8;">
                <i class="ri ri-checkbox-circle-line" style="font-size:30px;display:block;margin-bottom:8px;opacity:.4;color:#28c76f;"></i>
                {{ __('app.no_pending_services') }}
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
          <i class="ri ri-flag-2-line me-2" style="color:#ea5455;"></i>{{ __('app.recent_reports') }}
        </span>
        <a href="{{ route('reports.index') }}" class="rc-link">{{ __('app.view_all') }}</a>
      </div>
      <div class="table-responsive">
        <table class="table rt mb-0">
          <thead>
            <tr>
              <th>{{ __('app.service') }}</th>
              <th>{{ __('app.status') }}</th>
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
                {{ __('app.no_reports_yet') }}
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
          <i class="ri ri-edit-line me-2" style="color:var(--role-accent);"></i>{{ __('app.edit_profile') }}
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
            <label style="font-size:11px;font-weight:700;color:#7c748a;text-transform:uppercase;letter-spacing:.5px;">{{ __('app.name') }}</label>
            <input type="text" name="name" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e8e6ef;"
                   value="{{ old('name', $superadmin->name) }}" required>
          </div>

          <div class="mb-3">
            <label style="font-size:11px;font-weight:700;color:#7c748a;text-transform:uppercase;letter-spacing:.5px;">{{ __('app.email') }}</label>
            <input type="email" name="email" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e8e6ef;"
                   value="{{ old('email', $superadmin->email) }}" required>
          </div>

          <hr style="border-color:#f0eef4;margin:18px 0 14px;">
          <p style="font-size:12px;color:#97939e;margin-bottom:14px;">
            <i class="ri ri-lock-line me-1"></i>{{ __('app.leave_blank_password') }}
          </p>

          <div class="mb-3">
            <label style="font-size:11px;font-weight:700;color:#7c748a;text-transform:uppercase;letter-spacing:.5px;">{{ __('app.current_password') }}</label>
            <input type="password" name="current_password" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e8e6ef;"
                   placeholder="{{ __('app.required_if_changing_password') }}">
          </div>

          <div class="mb-3">
            <label style="font-size:11px;font-weight:700;color:#7c748a;text-transform:uppercase;letter-spacing:.5px;">{{ __('app.new_password') }}</label>
            <input type="password" name="new_password" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e8e6ef;"
                   placeholder="{{ __('app.min_8_characters') }}">
          </div>

          <div class="mb-0">
            <label style="font-size:11px;font-weight:700;color:#7c748a;text-transform:uppercase;letter-spacing:.5px;">{{ __('app.confirm_new_password') }}</label>
            <input type="password" name="new_password_confirmation" class="form-control mt-1"
                   style="border-radius:10px;border:1.5px solid #e8e6ef;"
                   placeholder="{{ __('app.repeat_new_password') }}">
          </div>

        </div>
        <div class="modal-footer" style="border-top:1px solid #f0eef4;padding:18px 26px;gap:10px;">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                  style="border-radius:12px;font-weight:700;">{{ __('app.cancel') }}</button>
          <button type="submit" class="btn btn-primary" style="border-radius:12px;font-weight:700;">
            <i class="ri ri-save-line me-1"></i>{{ __('app.save_changes') }}
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
