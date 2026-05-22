@extends('layouts/contentNavbarLayout')
@section('title', __('app.ads'))

@section('content')
<style>
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h4 { font-size:20px; font-weight:800; color:#312d4b; margin:0; }
.page-hdr p  { font-size:13px; color:#97939e; margin:4px 0 0; }
.btn-add {
    display:inline-flex; align-items:center; gap:8px;
    padding:11px 22px; border-radius:13px; border:none;
    background:linear-gradient(135deg,#696cff,#5f61e6);
    color:#fff; font-size:13px; font-weight:700; cursor:pointer;
    text-decoration:none; box-shadow:0 4px 14px rgba(105,108,255,.35); transition:.2s;
}
.btn-add:hover { transform:translateY(-1px); color:#fff; box-shadow:0 8px 22px rgba(105,108,255,.4); }

.ad-card {
    background:#fff; border-radius:20px;
    border:1px solid #f0eef8;
    box-shadow:0 4px 20px rgba(105,108,255,.07);
    overflow:hidden; transition:.25s;
}
.ad-card:hover { transform:translateY(-3px); box-shadow:0 12px 34px rgba(105,108,255,.14); }
.ad-img { width:100%; height:180px; object-fit:cover; display:block; }
.ad-img-ph {
    width:100%; height:180px; background:linear-gradient(135deg,#f4f5ff,#e8e9ff);
    display:flex; align-items:center; justify-content:center; font-size:48px;
}
.ad-body { padding:16px 18px; }
.ad-title { font-size:15px; font-weight:800; color:#312d4b; margin-bottom:4px; }
.ad-desc  { font-size:12px; color:#97939e; margin-bottom:12px;
            display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.ad-badge {
    display:inline-flex; align-items:center; gap:5px;
    border-radius:20px; padding:4px 12px; font-size:11px; font-weight:700; margin-bottom:12px;
}
.ad-actions { display:flex; gap:8px; }
.btn-act {
    flex:1; padding:9px; border-radius:11px; border:none; font-size:12px; font-weight:700;
    cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;
    text-decoration:none; transition:.15s;
}
.btn-edit   { background:#eef0ff; color:#696cff; }
.btn-edit:hover { background:#696cff; color:#fff; }
.btn-toggle-on  { background:#e8faf0; color:#28c76f; }
.btn-toggle-on:hover  { background:#28c76f; color:#fff; }
.btn-toggle-off { background:#fff4e5; color:#ff9f43; }
.btn-toggle-off:hover { background:#ff9f43; color:#fff; }
.btn-del    { background:#fdeaea; color:#ea5455; }
.btn-del:hover  { background:#ea5455; color:#fff; }
</style>

<div class="page-hdr">
    <div>
        <h4><i class="ri ri-advertisement-line" style="color:#696cff;margin-left:8px;"></i>{{ __('app.ads') }}</h4>
        <p>{{ __('app.ads_desc') }}</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <form method="GET" action="{{ route('ads.index') }}" class="d-flex align-items-center gap-2">
            <div class="input-group input-group-sm" style="width:200px;">
                <span class="input-group-text"><i class="ri ri-search-line"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search ads..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-select form-select-sm" style="width:120px;" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Go</button>
            @if(request('search') || request('status'))
                <a href="{{ route('ads.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            @endif
        </form>
        <a href="{{ route('ads.create') }}" class="btn-add">
            <i class="ri ri-add-line"></i> {{ __('app.add_ad') }}
        </a>
    </div>
</div>

@if(session('success'))
<div style="background:#e8faf0;border-radius:14px;padding:14px 18px;font-size:13px;color:#1f7a36;
            margin-bottom:20px;display:flex;align-items:center;gap:10px;">
    <i class="ri ri-checkbox-circle-line" style="font-size:20px;"></i>{{ session('success') }}
</div>
@endif

@if($ads->total() === 0)
<div style="text-align:center;padding:80px 20px;background:#fff;border-radius:20px;border:1.5px dashed #d4d5ff;">
    <i class="ri ri-advertisement-line" style="font-size:52px;color:#c5c7ff;display:block;margin-bottom:14px;"></i>
    <h5 style="font-size:16px;font-weight:800;color:#312d4b;margin-bottom:6px;">{{ __('app.no_ads_yet') }}</h5>
    <p style="font-size:13px;color:#97939e;margin-bottom:20px;">{{ __('app.ads_desc') }}</p>
    <a href="{{ route('ads.create') }}" class="btn-add" style="display:inline-flex;">
        <i class="ri ri-add-line"></i> {{ __('app.add_ad') }}
    </a>
</div>
@else
<div class="row g-4">
    @foreach($ads as $ad)
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="ad-card">
            <img class="ad-img" src="{{ $ad->image_url }}" alt="{{ $ad->title }}">

            <div class="ad-body">
                <div class="ad-title">{{ $ad->title }}</div>
                @if($ad->description)
                <div class="ad-desc">{{ $ad->description }}</div>
                @endif

                @if($ad->is_active)
                    <span class="ad-badge" style="background:#e8faf0;color:#28c76f;">
                        <i class="ri ri-checkbox-circle-line"></i> {{ __('app.active') }}
                    </span>
                @else
                    <span class="ad-badge" style="background:#fff4e5;color:#ff9f43;">
                        <i class="ri ri-pause-circle-line"></i> {{ __('app.inactive') }}
                    </span>
                @endif

                <div class="ad-actions">
                    <a href="{{ route('ads.edit', $ad->id) }}" class="btn-act btn-edit">
                        <i class="ri ri-edit-line"></i> {{ __('app.edit') }}
                    </a>

                    <form action="{{ route('ads.toggle', $ad->id) }}" method="POST" style="flex:1;">
                        @csrf
                        <button type="submit" class="btn-act w-100 {{ $ad->is_active ? 'btn-toggle-on' : 'btn-toggle-off' }}">
                            <i class="ri {{ $ad->is_active ? 'ri-eye-off-line' : 'ri-eye-line' }}"></i>
                        </button>
                    </form>

                    <form action="{{ route('ads.destroy', $ad->id) }}" method="POST"
                          onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-act btn-del">
                            <i class="ri ri-delete-bin-line"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@if($ads->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $ads->links() }}
</div>
@endif
@endif
@endsection
