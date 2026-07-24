@extends('layouts/contentNavbarLayout')
@section('title', __('app.favorites'))

@section('content')
<style>
.page-hdr { margin-bottom:24px; }
.page-hdr h4 { font-size:20px; font-weight:800; color:#312d4b; margin:0 0 4px; }
.page-hdr p  { font-size:13px; color:#97939e; margin:0; }

.fav-card {
    background:#fff; border-radius:20px;
    border:1px solid #f0eef8;
    box-shadow:0 4px 20px rgba(var(--role-accent-rgb),.07);
    overflow:hidden; height:100%;
    display:flex; flex-direction:column; transition:.25s;
}
.fav-card:hover { transform:translateY(-4px); box-shadow:0 12px 34px rgba(var(--role-accent-rgb),.15); }
.fav-img { height:190px; object-fit:cover; width:100%; display:block; }
.fav-body { padding:18px 20px; flex:1; display:flex; flex-direction:column; }

.fav-biz   { font-size:11px; font-weight:700; color:#97939e; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; }
.fav-title { font-size:15px; font-weight:800; color:#312d4b; margin-bottom:6px; line-height:1.3; }
.fav-cat   { font-size:12px; color:#b0aab8; margin-bottom:12px; }

.fav-prices { display:flex; gap:8px; margin-bottom:14px; }
.fav-price-pill {
    flex:1; padding:8px 10px; border-radius:10px; text-align:center;
}
.fav-price-pill .cur { font-size:10px; font-weight:700; opacity:.7; }
.fav-price-pill .amt { font-size:14px; font-weight:800; }

.fav-actions { display:flex; gap:8px; margin-top:auto; }
.btn-request {
    flex:1; padding:10px; border-radius:12px; border:none;
    background:linear-gradient(135deg,var(--role-accent),var(--role-accent-light));
    color:#fff; font-size:13px; font-weight:700; cursor:pointer;
    display:flex; align-items:center; justify-content:center; gap:6px;
    text-decoration:none; transition:.15s;
    box-shadow:0 4px 12px rgba(var(--role-accent-rgb),.3);
}
.btn-request:hover { color:#fff; transform:translateY(-1px); }
.btn-unfav {
    width:42px; height:42px; border-radius:12px; border:none;
    background:#fff4e5; color:#ff9f43; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    font-size:18px; transition:.15s; flex-shrink:0;
}
.btn-unfav:hover { background:#ff9f43; color:#fff; }
</style>

<div class="page-hdr">
    <h4><i class="ri ri-star-line" style="color:#f4b400;margin-left:8px;"></i>{{ __('app.favorites') }}</h4>
    <p>{{ __('app.favorites_desc') }}</p>
</div>

@if(session('success'))
<div style="background:#e8faf0;border-radius:14px;padding:14px 18px;font-size:13px;color:#1f7a36;
            margin-bottom:20px;display:flex;align-items:center;gap:10px;">
    <i class="ri ri-checkbox-circle-line" style="font-size:20px;"></i>{{ session('success') }}
</div>
@endif

{{-- $favorites passed from FavoriteController@index --}}

@if($favorites->isEmpty())
<div style="text-align:center;padding:80px 20px;background:#fff;border-radius:20px;border:1.5px dashed #ffe0a0;">
    <i class="ri ri-star-line" style="font-size:52px;color:#ffd166;display:block;margin-bottom:14px;"></i>
    <h5 style="font-size:16px;font-weight:800;color:#312d4b;margin-bottom:6px;">{{ __('app.no_favorites') }}</h5>
    <p style="font-size:13px;color:#97939e;margin-bottom:20px;">{{ __('app.no_favorites_desc') }}</p>
    <a href="{{ route('allservices.user') }}"
       style="display:inline-flex;align-items:center;gap:8px;padding:11px 24px;border-radius:13px;
              background:linear-gradient(135deg,var(--role-accent),var(--role-accent-light));color:#fff;font-weight:700;
              font-size:13px;text-decoration:none;box-shadow:0 4px 14px rgba(var(--role-accent-rgb),.35);">
        <i class="ri ri-search-line"></i> {{ __('app.browse_services') }}
    </a>
</div>
@else
<div class="row g-4">
    @foreach($favorites as $service)
    @php
        $st = $service->status ?? 'pending';
        $sc = ['approved'=>['#28c76f','#e8faf0'],'rejected'=>['#ea5455','#fdeaea'],'pending'=>['#ff9f43','#fff4e5']];
        [$stColor,$stBg] = $sc[$st] ?? ['#97939e','#f0f0f8'];
    @endphp
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="fav-card">
            <div class="position-relative">
                <img class="fav-img" src="{{ $service->image_url }}" alt="{{ $service->title }}">
                <span style="position:absolute;top:12px;right:12px;
                             background:{{ $stBg }};color:{{ $stColor }};
                             border-radius:20px;padding:4px 12px;font-size:11px;font-weight:700;">
                    {{ $st }}
                </span>
            </div>

            <div class="fav-body">
                <div class="fav-biz">{{ $service->business->job_name_en ?? '—' }}</div>
                <div class="fav-title">{{ $service->title }}</div>
                <div class="fav-cat">
                    <i class="ri ri-price-tag-3-line me-1"></i>
                    {{ $service->category->name_en ?? '—' }}
                    · {{ $service->location_name ?? '—' }}
                </div>

                <div class="fav-prices">
                    <div class="fav-price-pill" style="background:var(--role-accent-soft);">
                        <div class="cur">USD</div>
                        <div class="amt" style="color:var(--role-accent);">${{ number_format($service->price_usd ?? 0) }}</div>
                    </div>
                    <div class="fav-price-pill" style="background:#f4f5ff;">
                        <div class="cur">SYP</div>
                        <div class="amt" style="color:var(--role-accent-light);">{{ number_format($service->price_syp ?? 0) }}</div>
                    </div>
                </div>

                <div class="fav-actions">
                    <a href="#" class="btn-request"
                       data-bs-toggle="modal"
                       data-bs-target="#reqModal{{ $service->id }}">
                        <i class="ri ri-send-plane-line"></i> {{ __('app.request_service') }}
                    </a>
                    <form action="{{ route('favorite.toggle', $service->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-unfav" title="{{ __('app.remove_from_favorites') }}">
                            <i class="ri ri-star-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Request Modal --}}
    <div class="modal fade" id="reqModal{{ $service->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:20px;overflow:hidden;">
                <div class="modal-header border-0 pb-0" style="padding:24px 24px 0;">
                    <h5 style="font-size:16px;font-weight:800;color:#312d4b;">
                        {{ __('app.request_service') }}: {{ $service->title }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('servicerequest.store', $service->id) }}" method="POST">
                    @csrf
                    <div class="modal-body" style="padding:20px 24px;">
                        <div style="margin-bottom:14px;">
                            <label style="font-size:12px;font-weight:700;color:#585164;margin-bottom:8px;display:block;">{{ __('app.quantity') }}</label>
                            <input type="number" name="quantity" class="form-control" min="1" value="1"
                                   style="border-radius:12px;border:1.5px solid #e5e2ec;padding:11px 14px;">
                        </div>
                        <div style="margin-bottom:14px;">
                            <label style="font-size:12px;font-weight:700;color:#585164;margin-bottom:8px;display:block;">{{ __('app.needed_on') }}</label>
                            <input type="date" name="needed_at" class="form-control"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   style="border-radius:12px;border:1.5px solid #e5e2ec;padding:11px 14px;">
                        </div>
                        <div>
                            <label style="font-size:12px;font-weight:700;color:#585164;margin-bottom:8px;display:block;">{{ __('app.notes') }} ({{ __('app.optional') }})</label>
                            <textarea name="details" class="form-control" rows="3"
                                      style="border-radius:12px;border:1.5px solid #e5e2ec;padding:11px 14px;"
                                      placeholder="{{ __('app.write_comment') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0" style="padding:0 24px 24px;">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                                style="border-radius:12px;">{{ __('app.cancel') }}</button>
                        <button type="submit"
                                style="padding:11px 24px;border-radius:12px;border:none;
                                       background:linear-gradient(135deg,var(--role-accent),var(--role-accent-light));
                                       color:#fff;font-weight:700;font-size:14px;cursor:pointer;">
                            <i class="ri ri-send-plane-line me-1"></i>{{ __('app.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
