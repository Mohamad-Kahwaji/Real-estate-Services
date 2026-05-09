@extends('layouts/contentNavbarLayout')

@section('title', 'Super Admin Dashboard')

@section('page-style')
<style>
  /* Welcome Banner */
  .sa-welcome {
    background: linear-gradient(135deg, #696cff 0%, #9c9eff 100%);
    border-radius: 18px;
    padding: 28px 32px;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 28px;
  }
  .sa-welcome::before {
    content: '';
    position: absolute;
    top: -50px; right: -50px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,.07);
    border-radius: 50%;
  }
  .sa-welcome::after {
    content: '';
    position: absolute;
    bottom: -70px; right: 80px;
    width: 150px; height: 150px;
    background: rgba(255,255,255,.05);
    border-radius: 50%;
  }
  .sa-avatar {
    width: 60px; height: 60px;
    border-radius: 16px;
    background: rgba(255,255,255,.2);
    border: 2px solid rgba(255,255,255,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; font-weight: 800; color: #fff;
    flex-shrink: 0;
  }
  .sa-badge {
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 30px;
    padding: 6px 16px;
    font-size: 12px; font-weight: 700; color: #fff;
  }

  /* Stat Cards */
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

  /* Badge pills */
  .bp { display: inline-flex; align-items: center; gap: 3px; padding: 3px 9px; border-radius: 30px; font-size: 11px; font-weight: 700; }
  .bp-orange { background: #fff4e5; color: #ff9f43; }
  .bp-green  { background: #e8faf0; color: #28c76f; }
  .bp-red    { background: #fdeaea; color: #ea5455; }
  .bp-gray   { background: #f1f0f4; color: #97939e; }
  .bp-purple { background: #eef0ff; color: #696cff; }

  /* Section Header */
  .sec-hdr {
    font-size: 11px; font-weight: 700; letter-spacing: .8px;
    text-transform: uppercase; color: #97939e;
    padding-bottom: 10px; margin-bottom: 20px;
    border-bottom: 2px solid #f0eef4;
  }

  /* Quick Links */
  .ql {
    display: flex; flex-direction: column; align-items: center;
    gap: 8px; padding: 18px 10px;
    border-radius: 14px; text-decoration: none; color: inherit;
    background: #fff;
    box-shadow: 0 4px 20px rgba(105,108,255,.09);
    border: 2px solid transparent;
    transition: .2s ease;
  }
  .ql:hover { border-color: #696cff; transform: translateY(-3px); color: inherit; }
  .ql-icon {
    width: 50px; height: 50px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
  }
  .ql-lbl { font-size: 13px; font-weight: 700; color: #312d4b; text-align: center; line-height: 1.3; }
  .ql-sub { font-size: 11px; color: #97939e; }

  /* Recent Table Cards */
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

  /* Status pills */
  .sp { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 30px; font-size: 11px; font-weight: 700; }
  .sp::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
  .sp-pending  { background: #fff4e5; color: #ff9f43; }
  .sp-approved { background: #e8faf0; color: #28c76f; }
  .sp-rejected { background: #fdeaea; color: #ea5455; }
  .sp-reviewed { background: #e3f8fc; color: #00cfe8; }
  .sp-resolved { background: #e8faf0; color: #28c76f; }

  /* Account Profile Card */
  .acc-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(105,108,255,.12);
    overflow: hidden;
    height: 100%;
  }
  .acc-cover {
    background: linear-gradient(135deg, #696cff 0%, #9c9eff 100%);
    height: 90px;
    position: relative;
  }
  .acc-cover::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 120px; height: 120px;
    background: rgba(255,255,255,.08);
    border-radius: 50%;
  }
  .acc-cover::after {
    content: '';
    position: absolute;
    bottom: -20px; left: 20px;
    width: 80px; height: 80px;
    background: rgba(255,255,255,.05);
    border-radius: 50%;
  }
  .acc-ava {
    width: 86px; height: 86px;
    border-radius: 50%;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    border: 4px solid #fff;
    box-shadow: 0 6px 20px rgba(105,108,255,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; font-weight: 800; color: #fff;
    margin: -43px auto 0;
    position: relative;
    z-index: 2;
  }
  .acc-name { font-size: 18px; font-weight: 800; color: #312d4b; margin-top: 12px; margin-bottom: 6px; text-align: center; }
  .acc-role {
    display: inline-flex; align-items: center; gap: 6px;
    background: #eef0ff; color: #696cff;
    border-radius: 30px; padding: 5px 16px;
    font-size: 12px; font-weight: 700;
  }
  .acc-stat-row {
    display: flex; gap: 10px; padding: 16px 18px;
    border-top: 1px solid #f0eef4;
  }
  .acc-stat-box {
    flex: 1; background: #faf9fc; border-radius: 12px;
    padding: 12px 8px; text-align: center;
  }
  .acc-stat-num { font-size: 20px; font-weight: 800; line-height: 1; }
  .acc-stat-lbl { font-size: 10px; font-weight: 700; color: #97939e; text-transform: uppercase; letter-spacing: .4px; margin-top: 4px; }
  .acc-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 11px 20px;
    border-top: 1px solid #f0eef4;
  }
  .acc-key {
    display: flex; align-items: center; gap: 9px;
    font-size: 12px; font-weight: 600; color: #97939e;
  }
  .acc-key i { font-size: 17px; }
  .acc-val { font-size: 13px; font-weight: 700; color: #312d4b; }
  .acc-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    margin: 14px 18px 18px;
    background: linear-gradient(135deg, #696cff 0%, #9c9eff 100%);
    color: #fff; border: none; border-radius: 12px;
    padding: 12px; font-size: 13px; font-weight: 700;
    text-decoration: none;
    transition: opacity .2s;
  }
  .acc-btn:hover { opacity: .88; color: #fff; }
</style>
@endsection

@section('content')
@php $initials = strtoupper(substr($superadmin->name ?? 'S', 0, 1)); @endphp

{{-- Welcome Banner --}}
<div class="sa-welcome">
  <div class="d-flex align-items-center gap-3" style="position:relative;z-index:1;">
    <div class="sa-avatar">{{ $initials }}</div>
    <div>
      <div style="font-size:21px;font-weight:800;margin-bottom:3px;">Welcome back, {{ $superadmin->name ?? 'Super Admin' }} 👋</div>
      <div style="font-size:13px;color:rgba(255,255,255,.75);">{{ $superadmin->email }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.55);margin-top:2px;">{{ now()->format('l, F j, Y') }}</div>
    </div>
    <div class="ms-auto d-none d-md-block">
      <span class="sa-badge"><i class="ri ri-shield-star-fill me-1"></i>Super Admin</span>
    </div>
  </div>
</div>

{{-- Stat Cards Row 1 --}}
<div class="row g-4 mb-3">

  <div class="col-6 col-md-4 col-xl-2">
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
  </div>

  <div class="col-6 col-md-4 col-xl-2">
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
  </div>

  <div class="col-6 col-md-4 col-xl-2">
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
  </div>

  <div class="col-6 col-md-4 col-xl-2">
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
  </div>

  <div class="col-6 col-md-4 col-xl-2">
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
  </div>


</div>

{{-- Stat Cards Row 2: Content --}}
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

{{-- Bottom Row --}}
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
          <thead><tr><th>Business</th><th>Owner</th><th>Status</th></tr></thead>
          <tbody>
            @forelse($recentBusinesses as $biz)
            <tr>
              <td>
                <div style="font-weight:700;color:#312d4b;">{{ $biz->job_name_en ?? '-' }}</div>
                <div style="font-size:11px;color:#97939e;">{{ $biz->job_name_ar ?? '' }}</div>
              </td>
              <td style="color:#585164;">{{ $biz->user->name ?? '-' }}</td>
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

  {{-- Recent Reports --}}
  <div class="col-12 col-xl-4">
    <div class="rc">
      <div class="rc-hdr">
        <span class="rc-title">
          <i class="ri ri-flag-2-line me-2" style="color:#ea5455;"></i>Recent Reports
        </span>
        <a href="{{ route('reports.index') }}" class="rc-link">View all &rarr;</a>
      </div>
      <div class="table-responsive">
        <table class="table rt mb-0">
          <thead><tr><th>Service</th><th>By</th><th>Status</th></tr></thead>
          <tbody>
            @forelse($recentReports as $rep)
            <tr>
              <td style="font-weight:600;color:#312d4b;max-width:110px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $rep->service->title ?? 'Deleted' }}
              </td>
              <td style="color:#585164;">{{ $rep->user->name ?? '-' }}</td>
              <td>
                @php $rs = $rep->status ?? 'pending'; @endphp
                <span class="sp sp-{{ $rs }}">{{ ucfirst($rs) }}</span>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="3" style="text-align:center;padding:40px;color:#b0aab8;">
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

  {{-- Account Profile Card --}}
  <div class="col-12 col-xl-3">
    <div class="acc-card">
      <div class="acc-cover"></div>
      <div style="text-align:center;padding:0 20px 4px;">
        <div class="acc-ava">{{ $initials }}</div>
        <div class="acc-name">{{ $superadmin->name ?? 'Super Admin' }}</div>
        <span class="acc-role">
          <i class="ri ri-shield-star-fill"></i>Super Administrator
        </span>
      </div>

      {{-- Quick Stats --}}
      <div class="acc-stat-row">
        <div class="acc-stat-box">
          <div class="acc-stat-num" style="color:#696cff;">{{ $totalAdmins }}</div>
          <div class="acc-stat-lbl">Admins</div>
        </div>
        <div class="acc-stat-box">
          <div class="acc-stat-num" style="color:#28c76f;">{{ $totalBusinesses }}</div>
          <div class="acc-stat-lbl">Businesses</div>
        </div>
        <div class="acc-stat-box">
          <div class="acc-stat-num" style="color:#ff9f43;">{{ $totalServices }}</div>
          <div class="acc-stat-lbl">Services</div>
        </div>
      </div>

      {{-- Detail Rows --}}
      <div class="acc-row">
        <span class="acc-key">
          <i class="ri ri-mail-line" style="color:#696cff;"></i>Email
        </span>
        <span class="acc-val" style="font-size:12px;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
          {{ $superadmin->email }}
        </span>
      </div>
      <div class="acc-row">
        <span class="acc-key">
          <i class="ri ri-calendar-check-line" style="color:#28c76f;"></i>Joined
        </span>
        <span class="acc-val">{{ $superadmin->created_at?->format('M d, Y') ?? '-' }}</span>
      </div>
      <div class="acc-row">
        <span class="acc-key">
          <i class="ri ri-user-settings-line" style="color:#ff9f43;"></i>Admins
        </span>
        <span class="acc-val">
          <span class="bp bp-green me-1">{{ $activeAdmins }} on</span>
          <span class="bp bp-gray">{{ $inactiveAdmins }} off</span>
        </span>
      </div>
      <div class="acc-row">
        <span class="acc-key">
          <i class="ri ri-flag-2-line" style="color:#ea5455;"></i>Reports
        </span>
        <span class="acc-val">
          @if($pendingReports > 0)
            <span class="bp bp-red">{{ $pendingReports }} open</span>
          @else
            <span class="bp bp-green">All clear</span>
          @endif
        </span>
      </div>
      <div class="acc-row">
        <span class="acc-key">
          <i class="ri ri-building-2-line" style="color:#ff9f43;"></i>Pending
        </span>
        <span class="acc-val">
          @if($pendingBusinesses > 0)
            <span class="bp bp-orange">{{ $pendingBusinesses }} biz</span>
          @else
            <span class="bp bp-green">All clear</span>
          @endif
        </span>
      </div>

      <a href="{{ route('adminsindex') }}" class="acc-btn">
        <i class="ri ri-user-settings-line"></i>Manage Admins
      </a>
    </div>
  </div>

</div>

@endsection
