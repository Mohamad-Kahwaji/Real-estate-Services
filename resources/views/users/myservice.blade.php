@extends('layouts/contentNavbarLayout')
@section('title', __('app.my_services'))

@section('content')
<style>
.page-hdr { margin-bottom:24px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
.page-hdr h4 { font-size:20px; font-weight:800; color:#312d4b; margin:0; }
.page-hdr p  { font-size:13px; color:#97939e; margin:4px 0 0; }
.btn-add {
    display:inline-flex; align-items:center; gap:8px;
    padding:11px 22px; border-radius:13px; border:none;
    background:linear-gradient(135deg,var(--role-accent),var(--role-accent-light));
    color:#fff; font-size:13px; font-weight:700; cursor:pointer;
    text-decoration:none;
    box-shadow:0 4px 14px rgba(var(--role-accent-rgb),.35); transition:.2s;
}
.btn-add:hover { transform:translateY(-1px); color:#fff; box-shadow:0 8px 22px rgba(var(--role-accent-rgb),.4); }

.svc-card {
    background:#fff; border-radius:20px;
    border:1px solid #f0eef8;
    box-shadow:0 4px 20px rgba(var(--role-accent-rgb),.07);
    overflow:hidden; height:100%;
    display:flex; flex-direction:column; transition:.25s;
}
.svc-card:hover { transform:translateY(-4px); box-shadow:0 12px 34px rgba(var(--role-accent-rgb),.15); }

.svc-img { height:200px; object-fit:cover; width:100%; display:block; }
.svc-img-placeholder {
    height:200px; background:linear-gradient(135deg,#f4f5ff,#e8e9ff);
    display:flex; align-items:center; justify-content:center;
    font-size:52px;
}
.svc-body { padding:18px 20px; flex:1; display:flex; flex-direction:column; }

.svc-status {
    position:absolute; top:14px; right:14px;
    border-radius:20px; padding:5px 14px;
    font-size:11px; font-weight:700;
}
.svc-type {
    position:absolute; top:14px; left:14px;
    border-radius:20px; padding:5px 12px;
    font-size:11px; font-weight:700;
    background:rgba(0,0,0,.5); color:#fff;
    backdrop-filter:blur(4px);
}
.svc-biz { font-size:11px; font-weight:700; color:#97939e; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; }
.svc-title { font-size:16px; font-weight:800; color:#312d4b; margin-bottom:6px; line-height:1.3; }
.svc-cat { font-size:12px; color:#b0aab8; margin-bottom:14px; }

.svc-price-row {
    display:flex; gap:8px; margin-bottom:14px;
}
.price-pill {
    flex:1; padding:9px 12px; border-radius:12px; text-align:center;
}
.price-pill .cur { font-size:10px; font-weight:700; opacity:.7; }
.price-pill .amt { font-size:15px; font-weight:800; }

.svc-actions { display:flex; gap:8px; margin-top:auto; }
.btn-action {
    flex:1; padding:10px 0; border-radius:12px; border:none;
    font-size:13px; font-weight:700; cursor:pointer;
    display:flex; align-items:center; justify-content:center; gap:6px;
    text-decoration:none; transition:.15s;
}
.btn-edit { background:var(--role-accent-soft); color:var(--role-accent); }
.btn-edit:hover { background:var(--role-accent); color:#fff; }
.btn-del  { background:#fdeaea; color:#ea5455; }
.btn-del:hover { background:#ea5455; color:#fff; }

.empty-state {
    text-align:center; padding:80px 20px;
    background:#fff; border-radius:20px;
    border:1.5px dashed #d4d5ff;
}
.empty-state i { font-size:52px; color:var(--role-accent-light); display:block; margin-bottom:14px; }
.empty-state h5 { font-size:16px; font-weight:800; color:#312d4b; margin-bottom:6px; }
.empty-state p  { font-size:13px; color:#97939e; margin-bottom:20px; }
</style>

<div class="page-hdr">
    <div>
        <h4><i class="ri ri-store-3-line" style="color:var(--role-accent);margin-left:8px;"></i> {{ __('app.my_services') }}</h4>
        <p>{{ __('app.no_services_desc') }}</p>
    </div>
    <a href="{{ route('user.service.create') }}" class="btn-add">
        <i class="ri ri-add-line"></i> {{ __('app.add_new_service') }}
    </a>
</div>

@if(session('success'))
<div style="background:#e8faf0;border-radius:14px;padding:14px 18px;font-size:13px;color:#1f7a36;
            margin-bottom:20px;display:flex;align-items:center;gap:10px;">
    <i class="ri ri-checkbox-circle-line" style="font-size:20px;"></i>{{ session('success') }}
</div>
@endif

@if($services->isEmpty())
<div class="empty-state">
    <i class="ri ri-store-3-line"></i>
    <h5>{{ __('app.no_services_yet') }}</h5>
    <p>{{ __('app.no_services_desc') }}</p>
    <a href="{{ route('user.service.create') }}" class="btn-add" style="display:inline-flex;">
        <i class="ri ri-add-line"></i> {{ __('app.add_service') }}
    </a>
</div>
@else
<div class="row g-4">
    @foreach($services as $service)
    @php
        $sc = ['approved'=>['#28c76f','#e8faf0'],'rejected'=>['#ea5455','#fdeaea'],'pending'=>['#ff9f43','#fff4e5']];
        $st = $service->status ?? 'pending';
        [$stColor,$stBg] = $sc[$st] ?? ['#97939e','#f0f0f8'];
    @endphp
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="svc-card">
            <div class="position-relative">
                @if($service->image)
                <img class="svc-img"
                     src="{{ asset('storage/'.$service->image) }}"
                     alt="{{ $service->title }}">
                @else
                <div class="svc-img-placeholder">🏠</div>
                @endif

                <span class="svc-status" style="background:{{ $stBg }};color:{{ $stColor }};">
                    {{ __('app.'.$st) }}
                </span>
                @if($service->services_type)
                <span class="svc-type">{{ ucfirst($service->services_type) }}</span>
                @endif
            </div>

            <div class="svc-body">
                <div class="svc-biz">{{ $service->business->job_name_en ?? '—' }}</div>
                <div class="svc-title">{{ $service->title }}</div>
                <div class="svc-cat">
                    <i class="ri ri-price-tag-3-line me-1"></i>
                    {{ $service->category->name_en ?? '—' }}
                    @if($service->subcategory)
                    · {{ $service->subcategory->name_en }}
                    @endif
                </div>

                <div class="svc-price-row">
                    <div class="price-pill" style="background:var(--role-accent-soft);">
                        <div class="cur">USD</div>
                        <div class="amt" style="color:var(--role-accent);">${{ number_format($service->price_usd ?? 0) }}</div>
                    </div>
                    <div class="price-pill" style="background:#f4f5ff;">
                        <div class="cur">SYP</div>
                        <div class="amt" style="color:var(--role-accent-light);">{{ number_format($service->price_syp ?? 0) }}</div>
                    </div>
                </div>

                <div style="display:flex;justify-content:space-between;font-size:12px;color:#97939e;margin-bottom:14px;">
                    <span><i class="ri ri-stack-line me-1"></i>{{ __('app.quantity') }}: {{ $service->quantity ?? '—' }}</span>
                    <span><i class="ri ri-map-pin-line me-1"></i>{{ $service->location_name ?? '—' }}</span>
                </div>

                <div class="svc-actions">
                    <a href="{{ route('user.service.edit', $service->id) }}" class="btn-action btn-edit">
                        <i class="ri ri-edit-line"></i> {{ __('app.edit') }}
                    </a>
                    <form action="{{ route('user.service.destroy', $service->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-action btn-del"
                                onclick="return confirm('{{ __('app.confirm_delete') }}')">
                            <i class="ri ri-delete-bin-line"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
