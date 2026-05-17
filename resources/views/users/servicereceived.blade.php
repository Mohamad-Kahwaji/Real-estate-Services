@extends('layouts/contentNavbarLayout')
@section('title', __('app.incoming_requests'))

@section('content')
<style>
.page-hdr { margin-bottom:24px; }
.page-hdr h4 { font-size:20px; font-weight:800; color:#312d4b; margin:0 0 4px; }
.page-hdr p  { font-size:13px; color:#97939e; margin:0; }

.req-card {
    background:#fff; border-radius:20px;
    border:1px solid #f0eef8;
    box-shadow:0 4px 20px rgba(105,108,255,.07);
    overflow:hidden; height:100%;
    display:flex; flex-direction:column; transition:.25s;
}
.req-card:hover { transform:translateY(-4px); box-shadow:0 12px 34px rgba(105,108,255,.15); }
.req-img { height:180px; object-fit:cover; width:100%; display:block; }
.req-body { padding:18px 20px; flex:1; display:flex; flex-direction:column; }

.req-user { font-size:11px; font-weight:700; color:#97939e; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:flex; align-items:center; gap:6px; }
.req-user i { color:#696cff; }
.req-title { font-size:15px; font-weight:800; color:#312d4b; margin-bottom:4px; line-height:1.3; }
.req-cat   { font-size:12px; color:#b0aab8; margin-bottom:14px; }

.req-info { background:#faf9fc; border-radius:12px; padding:10px 14px; margin-bottom:14px; }
.req-row  { display:flex; justify-content:space-between; font-size:12px; padding:5px 0; border-bottom:1px solid #f0eef4; }
.req-row:last-child { border-bottom:none; }
.req-row .lbl { color:#97939e; font-weight:600; }
.req-row .val { font-weight:700; color:#585164; }

.status-pill {
    display:inline-flex; align-items:center; gap:5px;
    border-radius:20px; padding:5px 14px;
    font-size:11px; font-weight:700; margin-bottom:14px;
}
.status-pill::before { content:''; width:6px; height:6px; border-radius:50%; background:currentColor; }

.action-row { display:flex; gap:8px; margin-top:auto; }
.btn-approve, .btn-reject {
    flex:1; padding:10px; border-radius:12px; border:none;
    font-size:13px; font-weight:700; cursor:pointer;
    display:flex; align-items:center; justify-content:center; gap:6px; transition:.15s;
}
.btn-approve { background:#e8faf0; color:#28c76f; }
.btn-approve:hover { background:#28c76f; color:#fff; }
.btn-reject  { background:#fdeaea; color:#ea5455; }
.btn-reject:hover  { background:#ea5455; color:#fff; }

/* Star rating */
.star-rating { display:flex; gap:4px; margin-bottom:8px; }
.star { font-size:20px; cursor:pointer; color:#e0e0e0; transition:.15s; }
.star.active, .star:hover { color:#f4b400; }

.review-section {
    background:#faf9fc; border-radius:14px;
    padding:14px 16px; margin-top:10px; border:1px solid #f0eef4;
}
.review-section label { font-size:12px; font-weight:700; color:#585164; margin-bottom:6px; display:block; }
.review-section select,
.review-section textarea {
    width:100%; padding:10px 14px; border-radius:10px;
    border:1.5px solid #e5e2ec; background:#fff;
    font-size:13px; color:#312d4b; outline:none; transition:.2s;
}
.review-section select:focus,
.review-section textarea:focus { border-color:#696cff; }
.btn-review {
    width:100%; margin-top:10px; padding:10px; border-radius:12px; border:none;
    background:linear-gradient(135deg,#696cff,#5f61e6);
    color:#fff; font-size:13px; font-weight:700; cursor:pointer;
}
</style>

<div class="page-hdr">
    <h4><i class="ri ri-file-list-3-line" style="color:#696cff;margin-left:8px;"></i>{{ __('app.incoming_requests') }}</h4>
    <p>{{ __('app.user_requests_label') }}</p>
</div>

@if(session('success'))
<div style="background:#e8faf0;border-radius:14px;padding:14px 18px;font-size:13px;color:#1f7a36;
            margin-bottom:20px;display:flex;align-items:center;gap:10px;">
    <i class="ri ri-checkbox-circle-line" style="font-size:20px;"></i>{{ session('success') }}
</div>
@endif

@if($serviceRequests->isEmpty())
<div style="text-align:center;padding:80px 20px;background:#fff;border-radius:20px;border:1.5px dashed #d4d5ff;">
    <i class="ri ri-inbox-line" style="font-size:52px;color:#c5c7ff;display:block;margin-bottom:14px;"></i>
    <h5 style="font-size:16px;font-weight:800;color:#312d4b;margin-bottom:6px;">{{ __('app.no_incoming_requests') }}</h5>
    <p style="font-size:13px;color:#97939e;">{{ __('app.no_incoming_desc') }}</p>
</div>
@else
<div class="row g-4">
    @foreach($serviceRequests as $request)
    @php
        $st = $request->status ?? 'pending';
        $sc = ['approved'=>['#28c76f','#e8faf0'],'rejected'=>['#ea5455','#fdeaea'],'pending'=>['#ff9f43','#fff4e5']];
        [$stColor,$stBg] = $sc[$st] ?? ['#97939e','#f0f0f8'];
    @endphp
    <div class="col-sm-6 col-lg-4">
        <div class="req-card">
            @if($request->service?->image)
            <img class="req-img" src="{{ asset('storage/'.$request->service->image) }}" alt="">
            @else
            <div style="height:180px;background:linear-gradient(135deg,#f4f5ff,#e8e9ff);display:flex;align-items:center;justify-content:center;font-size:52px;">🏠</div>
            @endif

            <div class="req-body">
                <div class="req-user">
                    <i class="ri ri-user-3-line"></i>
                    {{ $request->user->name ?? '—' }}
                </div>
                <div class="req-title">{{ $request->service->title ?? '—' }}</div>
                <div class="req-cat">{{ $request->service->category->name_en ?? '—' }}</div>

                <span class="status-pill" style="background:{{ $stBg }};color:{{ $stColor }};">
                    {{ __('app.'.$st) }}
                </span>

                <div class="req-info">
                    <div class="req-row">
                        <span class="lbl">{{ __('app.quantity') }}</span>
                        <span class="val">{{ $request->quantity ?? 1 }}</span>
                    </div>
                    <div class="req-row">
                        <span class="lbl">{{ __('app.needed_on') }}</span>
                        <span class="val">{{ $request->needed_at?->format('Y-m-d') ?? '—' }}</span>
                    </div>
                    <div class="req-row">
                        <span class="lbl">{{ __('app.price_usd') }}</span>
                        <span class="val" style="color:#696cff;">${{ number_format(($request->service->price_usd ?? 0) * ($request->quantity ?? 1)) }}</span>
                    </div>
                    @if($request->details)
                    <div class="req-row">
                        <span class="lbl">{{ __('app.notes') }}</span>
                        <span class="val" style="max-width:160px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;" title="{{ $request->details }}">{{ $request->details }}</span>
                    </div>
                    @endif
                </div>

                @if($st === 'pending')
                <div class="action-row">
                    <form action="{{ route('servicerequest.approve', $request->id) }}" method="POST">
                        @csrf
                        <button class="btn-approve" type="submit">
                            <i class="ri ri-check-line"></i> {{ __('app.approve') }}
                        </button>
                    </form>
                    <form action="{{ route('servicerequest.reject', $request->id) }}" method="POST">
                        @csrf
                        <button class="btn-reject" type="submit">
                            <i class="ri ri-close-line"></i> {{ __('app.reject') }}
                        </button>
                    </form>
                </div>
                @endif

                {{-- Review form for approved --}}
                @if($st === 'approved')
                <div class="review-section">
                    <label><i class="ri ri-star-line me-1" style="color:#f4b400;"></i> {{ __('app.add_review') }}</label>
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $request->service_id }}">
                        <select name="rating" style="margin-bottom:8px;">
                            @for($i=5;$i>=1;$i--)
                            <option value="{{ $i }}">{{ str_repeat('⭐',$i) }} ({{ $i }})</option>
                            @endfor
                        </select>
                        <textarea name="comment" rows="2" placeholder="{{ __('app.write_comment') }}"></textarea>
                        <button type="submit" class="btn-review">{{ __('app.send_review') }}</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
