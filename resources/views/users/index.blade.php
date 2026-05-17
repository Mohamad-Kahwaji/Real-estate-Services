@extends('layouts/contentNavbarLayout')
@section('title', __('app.dashboard'))

@section('content')
<style>
/* ── Welcome Hero ── */
.dash-hero {
    background: linear-gradient(135deg, #696cff 0%, #5f61e6 50%, #9c6bff 100%);
    border-radius: 20px;
    padding: 36px 40px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
}
.dash-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}
.dash-hero::after {
    content: '';
    position: absolute;
    bottom: -80px; left: 40%;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.dash-hero-text h3 {
    font-size: 24px; font-weight: 800;
    margin: 0 0 6px; color: #fff;
}
.dash-hero-text p {
    font-size: 14px; opacity: .85;
    margin: 0 0 20px;
}
.dash-hero-avatar {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,.2);
    border: 3px solid rgba(255,255,255,.4);
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; font-weight: 800; color: #fff;
    flex-shrink: 0;
    position: relative; z-index: 1;
}
.hero-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 12px;
    background: rgba(255,255,255,.2);
    color: #fff; font-size: 13px; font-weight: 700;
    text-decoration: none; border: 1.5px solid rgba(255,255,255,.35);
    transition: .2s;
}
.hero-btn:hover {
    background: rgba(255,255,255,.3); color: #fff;
}

/* ── Stat Cards ── */
.stat-card {
    background: #fff;
    border-radius: 18px;
    padding: 24px 20px;
    border: 1px solid #f0eef8;
    box-shadow: 0 4px 20px rgba(105,108,255,.07);
    display: flex; align-items: center; gap: 18px;
    transition: .25s;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(105,108,255,.13);
}
.stat-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.stat-val { font-size: 28px; font-weight: 800; color: #312d4b; line-height: 1; margin-bottom: 4px; }
.stat-label { font-size: 12px; color: #97939e; font-weight: 600; }

/* ── Quick Actions ── */
.qa-card {
    background: #fff;
    border-radius: 18px;
    padding: 22px 20px;
    border: 1px solid #f0eef8;
    box-shadow: 0 4px 20px rgba(105,108,255,.06);
    text-align: center;
    text-decoration: none;
    display: flex; flex-direction: column; align-items: center; gap: 12px;
    transition: .25s; color: #312d4b;
}
.qa-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(105,108,255,.15);
    border-color: #d4d5ff; color: #312d4b;
}
.qa-icon {
    width: 54px; height: 54px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
}
.qa-label { font-size: 13px; font-weight: 700; }

/* ── Section title ── */
.sec-title {
    font-size: 16px; font-weight: 800; color: #312d4b;
    margin: 0 0 16px;
    display: flex; align-items: center; gap: 10px;
}
.sec-title i { color: #696cff; font-size: 18px; }

/* ── Services mini cards ── */
.mini-service {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px;
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0eef8;
    margin-bottom: 10px;
    transition: .2s;
}
.mini-service:hover { border-color: #d4d5ff; box-shadow: 0 4px 16px rgba(105,108,255,.1); }
.mini-service img {
    width: 52px; height: 52px;
    border-radius: 12px; object-fit: cover; flex-shrink: 0;
}
.mini-service-body { flex: 1; min-width: 0; }
.mini-service-title { font-size: 14px; font-weight: 700; color: #312d4b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.mini-service-sub { font-size: 12px; color: #97939e; }
</style>

{{-- ─── Welcome Hero ─── --}}
<div class="dash-hero">
    <div class="dash-hero-text" style="position:relative;z-index:1;">
        <h3>
            @php
                $hour = now()->hour;
                if ($hour < 12)      $greeting = __('app.greeting_morning');
                elseif ($hour < 18)  $greeting = __('app.greeting_afternoon');
                else                 $greeting = __('app.greeting_evening');
            @endphp
            {{ $greeting }}, {{ $user->name }} 👋
        </h3>
        <p>{{ __('app.welcome_platform') }}</p>
        <a href="{{ route('allservices.user') }}" class="hero-btn">
            <i class="ri ri-search-line"></i>
            {{ __('app.browse_services') }}
        </a>
    </div>
    <div class="dash-hero-avatar">
        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
    </div>
</div>

{{-- ─── Stats Row ─── --}}
@php
    $totalServices = $services->count();
    $approvedSvc   = $services->where('status','approved')->count();
    $userRequests  = \App\Models\ServiceRequest::where('user_id', $user->id)->count();
    $hasBusiness   = $user->businesses()->exists();
@endphp

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eef0ff;">
                <i class="ri ri-store-3-line" style="color:#696cff;"></i>
            </div>
            <div>
                <div class="stat-val">{{ $totalServices }}</div>
                <div class="stat-label">{{ __('app.total_services') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8faf0;">
                <i class="ri ri-checkbox-circle-line" style="color:#28c76f;"></i>
            </div>
            <div>
                <div class="stat-val">{{ $approvedSvc }}</div>
                <div class="stat-label">{{ __('app.active_services') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff4e5;">
                <i class="ri ri-file-list-3-line" style="color:#ff9f43;"></i>
            </div>
            <div>
                <div class="stat-val">{{ $userRequests }}</div>
                <div class="stat-label">{{ __('app.my_requests') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdeaea;">
                <i class="ri ri-building-4-line" style="color:#ea5455;"></i>
            </div>
            <div>
                <div class="stat-val">{{ $hasBusiness ? '✓' : '—' }}</div>
                <div class="stat-label">{{ __('app.business_account') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ─── Quick Actions ─── --}}
<div class="sec-title"><i class="ri ri-flashlight-line"></i> {{ __('app.quick_actions') }}</div>
<div class="row g-3 mb-4">
    <div class="col-4 col-lg-2">
        <a href="{{ route('allservices.user') }}" class="qa-card">
            <div class="qa-icon" style="background:#eef0ff;">🔍</div>
            <div class="qa-label">{{ __('app.browse') }}</div>
        </a>
    </div>
    <div class="col-4 col-lg-2">
        <a href="{{ route('sentservice.user') }}" class="qa-card">
            <div class="qa-icon" style="background:#fff4e5;">📋</div>
            <div class="qa-label">{{ __('app.sent_requests') }}</div>
        </a>
    </div>
    <div class="col-4 col-lg-2">
        <a href="{{ route('myservices.user') }}" class="qa-card">
            <div class="qa-icon" style="background:#e8faf0;">🏠</div>
            <div class="qa-label">{{ __('app.my_services') }}</div>
        </a>
    </div>
    <div class="col-4 col-lg-2">
        <a href="{{ route('favorite') }}" class="qa-card">
            <div class="qa-icon" style="background:#fdeaea;">⭐</div>
            <div class="qa-label">{{ __('app.favorites') }}</div>
        </a>
    </div>
    <div class="col-4 col-lg-2">
        <a href="{{ route('profile.edit') }}" class="qa-card">
            <div class="qa-icon" style="background:#f3e8ff;">👤</div>
            <div class="qa-label">{{ __('app.profile') }}</div>
        </a>
    </div>
    <div class="col-4 col-lg-2">
        <a href="{{ route('chat.index') }}" class="qa-card">
            <div class="qa-icon" style="background:#e0f7ff;">💬</div>
            <div class="qa-label">{{ __('app.conversations') }}</div>
        </a>
    </div>
</div>

{{-- ─── Latest Services ─── --}}
<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="sec-title"><i class="ri ri-time-line"></i> {{ __('app.latest_services') }}</div>
        @forelse($services->take(6) as $service)
        <div class="mini-service">
            <img src="{{ $service->image_url ?? asset('assets/img/elements/5.png') }}"
                 alt="{{ $service->title }}">
            <div class="mini-service-body">
                <div class="mini-service-title">{{ $service->title }}</div>
                <div class="mini-service-sub">
                    {{ $service->category->name_en ?? '—' }} •
                    {{ $service->location_name ?? '—' }}
                </div>
            </div>
            @php
                $sc = ['approved'=>'#28c76f','rejected'=>'#ea5455','pending'=>'#ff9f43'];
                $sb = ['approved'=>'#e8faf0','rejected'=>'#fdeaea','pending'=>'#fff4e5'];
                $ss = $service->status ?? 'pending';
            @endphp
            <span style="
                background:{{ $sb[$ss] ?? '#f0f0f8' }};
                color:{{ $sc[$ss] ?? '#97939e' }};
                border-radius:20px;padding:4px 12px;font-size:11px;font-weight:700;
                white-space:nowrap;flex-shrink:0;
            ">{{ __('app.'.$ss) }}</span>
        </div>
        @empty
        <div style="text-align:center;padding:40px;color:#97939e;">
            <i class="ri ri-inbox-line" style="font-size:40px;display:block;margin-bottom:10px;"></i>
            {{ __('app.no_services_yet') }}
        </div>
        @endforelse
        @if($services->count() > 6)
        <a href="{{ route('allservices.user') }}"
           style="display:block;text-align:center;padding:12px;border-radius:12px;
                  background:#f4f5ff;color:#696cff;font-weight:700;font-size:13px;
                  text-decoration:none;margin-top:8px;">
            {{ __('app.view_all_services') }} ({{ $services->count() }})
            <i class="ri ri-arrow-left-line ms-1"></i>
        </a>
        @endif
    </div>

    <div class="col-12 col-lg-5">
        <div class="sec-title"><i class="ri ri-user-3-line"></i> {{ __('app.my_info') }}</div>
        <div style="background:#fff;border-radius:18px;padding:24px;border:1px solid #f0eef8;
                    box-shadow:0 4px 20px rgba(105,108,255,.07);">
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
                <div style="
                    width:60px;height:60px;border-radius:50%;
                    background:linear-gradient(135deg,#696cff,#9c9eff);
                    color:#fff;font-size:24px;font-weight:800;
                    display:flex;align-items:center;justify-content:center;
                ">{{ strtoupper(mb_substr($user->name,0,1)) }}</div>
                <div>
                    <div style="font-size:16px;font-weight:800;color:#312d4b;">{{ $user->name }}</div>
                    <div style="font-size:13px;color:#97939e;">{{ $user->phone }}</div>
                </div>
            </div>

            @foreach([
                ['ri-phone-line',    __('app.phone'),    $user->phone],
                ['ri-mail-line',     __('app.email'),    $user->email ?? __('app.no_data')],
                ['ri-calendar-line', __('app.join_date'),$user->created_at?->format('Y-m-d')],
            ] as [$icon,$label,$val])
            <div style="display:flex;justify-content:space-between;align-items:center;
                        padding:10px 0;border-bottom:1px solid #f5f4f8;font-size:13px;">
                <span style="color:#97939e;display:flex;align-items:center;gap:8px;">
                    <i class="ri {{ $icon }}" style="color:#696cff;"></i>{{ $label }}
                </span>
                <span style="font-weight:700;color:#585164;">{{ $val }}</span>
            </div>
            @endforeach

            <a href="{{ route('profile.edit') }}"
               style="display:flex;align-items:center;justify-content:center;gap:8px;
                      margin-top:18px;padding:11px;border-radius:12px;
                      background:linear-gradient(135deg,#696cff,#5f61e6);
                      color:#fff;font-size:13px;font-weight:700;text-decoration:none;
                      box-shadow:0 4px 14px rgba(105,108,255,.3);">
                <i class="ri ri-edit-line"></i> {{ __('app.edit_profile') }}
            </a>
        </div>
    </div>
</div>
@endsection
