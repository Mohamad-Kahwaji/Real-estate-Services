@extends('layouts/contentNavbarLayout')
@section('title', __('app.ads'))

@section('content')
<style>
.page-hdr { margin-bottom:24px; }
.page-hdr h4 { font-size:20px; font-weight:800; color:#312d4b; margin:0 0 4px; }
.page-hdr p  { font-size:13px; color:#97939e; margin:0; }

.ad-card {
    background:#fff; border-radius:20px;
    border:1px solid #f0eef8;
    box-shadow:0 4px 20px rgba(105,108,255,.07);
    overflow:hidden; height:100%;
    display:flex; flex-direction:column; transition:.25s;
}
.ad-card:hover { transform:translateY(-4px); box-shadow:0 12px 34px rgba(105,108,255,.15); }

.ad-img { height:200px; object-fit:cover; width:100%; display:block; }
.ad-img-placeholder {
    height:200px; width:100%; display:flex; align-items:center; justify-content:center;
    background:linear-gradient(135deg,#f5f4ff,#ece9ff);
    color:#b0aacc; font-size:48px;
}

.ad-body { padding:18px 20px; flex:1; display:flex; flex-direction:column; }
.ad-title { font-size:15px; font-weight:800; color:#312d4b; margin-bottom:8px; line-height:1.3; }
.ad-desc  { font-size:13px; color:#97939e; flex:1; margin-bottom:14px; }

.btn-ad-link {
    display:inline-flex; align-items:center; gap:6px;
    padding:10px 20px; border-radius:12px;
    background:linear-gradient(135deg,#696cff,#5f61e6);
    color:#fff; font-size:13px; font-weight:700; text-decoration:none;
    transition:.15s; box-shadow:0 4px 12px rgba(105,108,255,.3);
}
.btn-ad-link:hover { color:#fff; transform:translateY(-1px); }

.empty-state {
    text-align:center; padding:80px 20px; color:#97939e;
}
.empty-state i { font-size:64px; margin-bottom:16px; display:block; color:#d0cde8; }
.empty-state h5 { font-size:18px; font-weight:700; color:#312d4b; margin-bottom:8px; }
</style>

<div class="page-hdr">
    <h4><i class="ri ri-advertisement-line me-2" style="color:#696cff;"></i>{{ __('app.ads') }}</h4>
    <p>{{ __('app.ads_desc') }}</p>
</div>

@if($ads->isEmpty())
<div class="empty-state">
    <i class="ri ri-advertisement-line"></i>
    <h5>{{ __('app.no_ads_yet') }}</h5>
</div>
@else
<div class="row g-4">
    @foreach($ads as $ad)
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="ad-card">
            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="ad-img">
            <div class="ad-body">
                <div class="ad-title">{{ $ad->title }}</div>
                @if($ad->description)
                    <div class="ad-desc">{{ $ad->description }}</div>
                @endif
                @if($ad->link)
                    <a href="{{ $ad->link }}" target="_blank" rel="noopener noreferrer" class="btn-ad-link">
                        <i class="ri ri-external-link-line"></i>
                        {{ __('app.view') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
