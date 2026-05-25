@extends('layouts/contentNavbarLayout')
@section('title', __('app.dashboard'))

@section('page-style')
<style>
.stat-card {
    border-radius: 18px;
    border: 0;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 18px;
    background: #fff;
    box-shadow: 0 4px 20px rgba(15,23,42,.07);
    transition: .25s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(15,23,42,.11);
}
.stat-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}
.stat-label { font-size: 12px; color: #8592a3; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
.stat-value { font-size: 28px; font-weight: 800; color: #1e293b; line-height: 1; }
.stat-sub   { font-size: 12px; color: #8592a3; margin-top: 4px; }

.section-title {
    font-size: 16px; font-weight: 800; color: #1e293b;
    margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
}
.section-title i { color: #696cff; font-size: 18px; }

.svc-row {
    display: flex; align-items: center; gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid #f8f9fb;
}
.svc-row:last-child { border-bottom: 0; }
.svc-thumb {
    width: 44px; height: 44px; border-radius: 10px;
    object-fit: cover; flex-shrink: 0;
    background: #f4f5ff;
}
.svc-title-sm { font-size: 13px; font-weight: 700; color: #1e293b; }
.svc-meta     { font-size: 11px; color: #8592a3; margin-top: 2px; }

.user-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f8f9fb;
}
.user-row:last-child { border-bottom: 0; }
.user-ava {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    color: #fff; font-size: 14px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
</style>
@endsection

@section('content')

{{-- ── Welcome banner ──────────────────────────────────── --}}
<div style="background:linear-gradient(135deg,#696cff,#9c9eff);border-radius:20px;
            padding:28px 32px;color:#fff;margin-bottom:28px;
            display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
    <div>
        <h4 style="font-size:22px;font-weight:800;margin:0 0 6px;">
            {{ __('app.welcome_back') }}, {{ auth('superadmins')->user()->name ?? 'Admin' }} 👋
        </h4>
        <p style="opacity:.85;margin:0;font-size:14px;">
            {{ __('app.platform_today') }}
        </p>
    </div>
    <a href="{{ route('allserviesad') }}"
       style="background:rgba(255,255,255,.2);border:1.5px solid rgba(255,255,255,.4);
              color:#fff;padding:10px 22px;border-radius:12px;font-weight:700;
              font-size:13px;text-decoration:none;transition:.2s;"
       onmouseover="this.style.background='rgba(255,255,255,.3)'"
       onmouseout="this.style.background='rgba(255,255,255,.2)'">
        <i class="ri ri-service-line me-2"></i>{{ __('app.manage_services') }}
    </a>
</div>

{{-- ── Stat cards ───────────────────────────────────────── --}}
<div class="row g-4 mb-4">

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#696cff18;color:#696cff;">
                <i class="ri ri-service-line"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.total_services') }}</div>
                <div class="stat-value">{{ $stats['services_total'] }}</div>
                <div class="stat-sub">
                    <span style="color:#ff9f43;">{{ __('app.x_pending', ['count' => $stats['services_pending']]) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#28c76f18;color:#28c76f;">
                <i class="ri ri-checkbox-circle-line"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.approved') }}</div>
                <div class="stat-value">{{ $stats['services_approved'] }}</div>
                <div class="stat-sub">
                    <span style="color:#ea5455;">{{ __('app.x_rejected', ['count' => $stats['services_rejected']]) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#00cfe818;color:#00cfe8;">
                <i class="ri ri-user-3-line"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.users') }}</div>
                <div class="stat-value">{{ $stats['users_total'] }}</div>
                <div class="stat-sub">{{ __('app.registered_accounts') }}</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#ff9f4318;color:#ff9f43;">
                <i class="ri ri-building-2-line"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.businesses') }}</div>
                <div class="stat-value">{{ $stats['businesses_total'] }}</div>
                <div class="stat-sub">
                    <span style="color:#ff9f43;">{{ __('app.x_pending', ['count' => $stats['businesses_pending']]) }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Second row: service requests + quick actions ────── --}}
<div class="row g-4 mb-4">

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#7367f018;color:#7367f0;">
                <i class="ri ri-file-list-3-line"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.service_requests') }}</div>
                <div class="stat-value">{{ $stats['requests_total'] }}</div>
                <div class="stat-sub">
                    <span style="color:#ff9f43;">{{ __('app.x_pending', ['count' => $stats['requests_pending']]) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#fff9ee,#fff3d8);border:1px solid #ffe8a0;">
            <div class="stat-icon" style="background:#ff9f4322;color:#ff9f43;">
                <i class="ri ri-time-line"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.needs_review') }}</div>
                <div class="stat-value" style="color:#ff9f43;">{{ $stats['services_pending'] + $stats['businesses_pending'] }}</div>
                <div class="stat-sub">{{ __('app.services_and_businesses') }}</div>
            </div>
        </div>
    </div>

    {{-- Quick links --}}
    <div class="col-xl-6">
        <div class="card border-0 h-100" style="border-radius:18px;box-shadow:0 4px 20px rgba(15,23,42,.07);">
            <div class="card-body">
                <div class="section-title"><i class="ri ri-flashlight-line"></i> {{ __('app.quick_actions') }}</div>
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('allserviesad') }}?status=pending"
                           class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none"
                           style="background:#fff9f0;color:#ff9f43;font-size:13px;font-weight:700;transition:.2s;"
                           onmouseover="this.style.background='#fff0d8'" onmouseout="this.style.background='#fff9f0'">
                            <i class="ri ri-time-line" style="font-size:18px;"></i> {{ __('app.pending_services') }}
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('business.index') }}?status=pending"
                           class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none"
                           style="background:#fff0ee;color:#ea5455;font-size:13px;font-weight:700;transition:.2s;"
                           onmouseover="this.style.background='#ffe4e2'" onmouseout="this.style.background='#fff0ee'">
                            <i class="ri ri-building-2-line" style="font-size:18px;"></i> {{ __('app.pending_businesses') }}
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('allindex.index') }}"
                           class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none"
                           style="background:#eefbff;color:#00cfe8;font-size:13px;font-weight:700;transition:.2s;"
                           onmouseover="this.style.background='#ddf7ff'" onmouseout="this.style.background='#eefbff'">
                            <i class="ri ri-user-3-line" style="font-size:18px;"></i> {{ __('app.all_users') }}
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('superadmin.service-requests') }}"
                           class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none"
                           style="background:#f2f0ff;color:#696cff;font-size:13px;font-weight:700;transition:.2s;"
                           onmouseover="this.style.background='#e8e5ff'" onmouseout="this.style.background='#f2f0ff'">
                            <i class="ri ri-file-list-3-line" style="font-size:18px;"></i> {{ __('app.service_requests') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Recent services + Recent users ─────────────────── --}}
<div class="row g-4">

    {{-- Recent Services --}}
    <div class="col-lg-7">
        <div class="card border-0 h-100" style="border-radius:18px;box-shadow:0 4px 20px rgba(15,23,42,.07);">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="section-title mb-0"><i class="ri ri-service-line"></i> {{ __('app.recent_services') }}</div>
                    <a href="{{ route('allserviesad') }}" style="font-size:12px;color:#696cff;font-weight:700;text-decoration:none;">
                        {{ __('app.view_all') }}
                    </a>
                </div>

                @forelse($recentServices as $svc)
                @php
                    $statusColor = match($svc->status){
                        'approved' => '#28c76f',
                        'rejected' => '#ea5455',
                        default    => '#ff9f43',
                    };
                @endphp
                <div class="svc-row">
                    <img class="svc-thumb"
                         src="{{ $svc->image_url }}"
                         alt="{{ $svc->title }}">
                    <div style="flex:1;min-width:0;">
                        <div class="svc-title-sm text-truncate">{{ $svc->title }}</div>
                        <div class="svc-meta">{{ $svc->business->job_name_en ?? '—' }} · {{ $svc->category->name_en ?? '—' }}</div>
                    </div>
                    <span style="font-size:10px;padding:3px 10px;border-radius:999px;
                                 background:{{ $statusColor }}18;color:{{ $statusColor }};
                                 font-weight:700;flex-shrink:0;">
                        {{ ucfirst($svc->status) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-muted">{{ __('app.no_services_yet') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="col-lg-5">
        <div class="card border-0 h-100" style="border-radius:18px;box-shadow:0 4px 20px rgba(15,23,42,.07);">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="section-title mb-0"><i class="ri ri-user-3-line"></i> {{ __('app.recent_users') }}</div>
                    <a href="{{ route('allindex.index') }}" style="font-size:12px;color:#696cff;font-weight:700;text-decoration:none;">
                        {{ __('app.view_all') }}
                    </a>
                </div>

                @forelse($recentUsers as $user)
                <div class="user-row">
                    <div class="user-ava">{{ strtoupper(substr($user->name ?? '?', 0, 1)) }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:700;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $user->name ?? 'Unknown' }}
                        </div>
                        <div style="font-size:11px;color:#8592a3;">{{ $user->phone ?? $user->email ?? '—' }}</div>
                    </div>
                    <div style="font-size:11px;color:#b0b8c4;flex-shrink:0;">
                        {{ $user->created_at?->diffForHumans() }}
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">{{ __('app.no_users_yet') }}</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection
