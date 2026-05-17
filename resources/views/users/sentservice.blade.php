@extends('layouts/contentNavbarLayout')

@section('title', __('app.my_requested_services'))

@section('page-style')
<style>
  /* ── Page header ── */
  .page-hdr { margin-bottom: 24px; }
  .page-hdr h4 { font-size: 20px; font-weight: 800; color: #312d4b; margin: 0 0 4px; }
  .page-hdr p  { font-size: 13px; color: #97939e; margin: 0; }

  /* ── Request card ── */
  .req-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 22px rgba(105,108,255,.1);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    height: 100%;
    display: flex; flex-direction: column;
  }
  .req-card:hover { transform: translateY(-4px); box-shadow: 0 12px 34px rgba(105,108,255,.18); }

  .req-img { height: 180px; object-fit: cover; width: 100%; display: block; }

  .req-body { padding: 16px 18px; flex: 1; display: flex; flex-direction: column; }

  .req-business { font-size: 11px; font-weight: 700; color: #97939e; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
  .req-title    { font-size: 15px; font-weight: 800; color: #312d4b; margin-bottom: 4px; line-height: 1.3; }
  .req-cat      { font-size: 12px; color: #b0aab8; margin-bottom: 12px; }

  /* Status badge */
  .req-status { display: inline-flex; align-items: center; gap: 5px; border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 700; }
  .req-status::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
  .status-pending  { background: #fff4e5; color: #ff9f43; }
  .status-approved { background: #e8faf0; color: #28c76f; }
  .status-rejected { background: #fdeaea; color: #ea5455; }

  /* Payment badge */
  .pay-badge { display: inline-flex; align-items: center; gap: 5px; border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 700; }
  .pay-unpaid { background: #fdeaea; color: #ea5455; }
  .pay-paid   { background: #e8faf0; color: #28c76f; }

  /* Details row */
  .req-details { background: #faf9fc; border-radius: 12px; padding: 10px 14px; margin: 12px 0; }
  .req-row { display: flex; justify-content: space-between; align-items: center; font-size: 12px; padding: 4px 0; border-bottom: 1px solid #f0eef4; }
  .req-row:last-child { border-bottom: none; }
  .req-row .lbl { color: #97939e; font-weight: 600; }
  .req-row .val { font-weight: 700; color: #585164; }

  /* Pay now button */
  .pay-now-btn {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    width: 100%; padding: 10px; border-radius: 12px;
    background: linear-gradient(135deg, #696cff, #9c6bff);
    color: #fff; font-size: 13px; font-weight: 700;
    text-decoration: none; border: none; cursor: pointer;
    transition: .2s; box-shadow: 0 4px 14px rgba(105,108,255,.3);
    margin-top: auto;
  }
  .pay-now-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(105,108,255,.4); color: #fff; text-decoration: none; }

  /* Empty state */
  .empty-state { text-align: center; padding: 60px 20px; }
  .empty-state i { font-size: 48px; color: #d5d8ff; display: block; margin-bottom: 14px; }
  .empty-state h5 { font-size: 16px; font-weight: 700; color: #312d4b; margin-bottom: 6px; }
  .empty-state p  { font-size: 13px; color: #97939e; }
</style>
@endsection

@section('content')

<div class="page-hdr d-flex align-items-center justify-content-between flex-wrap gap-3">
  <div>
    <h4>{{ __('app.my_requested_services') }}</h4>
    <p>{{ __('app.my_requests_desc') }}</p>
  </div>
  <div class="d-flex gap-2">
    <span class="pay-badge pay-unpaid"><i class="ri ri-error-warning-line"></i>{{ __('app.unpaid') }}</span>
    <span class="pay-badge pay-paid"><i class="ri ri-check-line"></i>{{ __('app.paid') }}</span>
  </div>
</div>

<div class="row g-4">
  @forelse($requests as $req)
  <div class="col-sm-6 col-lg-4 col-xl-3">
    <div class="req-card">

      {{-- Service image --}}
      <img class="req-img"
           src="{{ $req->service->image_url }}"
           alt="{{ $req->service->title }}">

      <div class="req-body">
        <p class="req-business">{{ $req->service->business->job_name_en ?? '—' }}</p>
        <h6 class="req-title">{{ $req->service->title ?? '—' }}</h6>
        <p class="req-cat"><i class="ri ri-price-tag-3-line me-1"></i>{{ $req->service->category->name_en ?? 'Uncategorized' }}</p>

        {{-- Status + Payment badges --}}
        <div class="d-flex gap-2 flex-wrap mb-2">
          <span class="req-status status-{{ $req->status }}">{{ ucfirst($req->status) }}</span>
          <span class="pay-badge {{ $req->payment_status === 'paid' ? 'pay-paid' : 'pay-unpaid' }}">
            <i class="ri {{ $req->payment_status === 'paid' ? 'ri-shield-check-line' : 'ri-error-warning-line' }}"></i>
            {{ $req->payment_status === 'paid' ? __('app.paid') : __('app.unpaid') }}
          </span>
        </div>

        {{-- Details --}}
        @php $qty = $req->quantity ?? 1; @endphp
        <div class="req-details">
          <div class="req-row">
            <span class="lbl">{{ __('app.price_usd') }}</span>
            <span class="val" style="color:#16a34a;">${{ number_format($req->service->price_usd ?? 0, 2) }}</span>
          </div>
          <div class="req-row">
            <span class="lbl">{{ __('app.price_syp') }}</span>
            <span class="val" style="color:#0284c7;">{{ number_format($req->service->price_syp ?? 0) }} SYP</span>
          </div>
          <div class="req-row">
            <span class="lbl">{{ __('app.quantity') }}</span>
            <span class="val">{{ $qty }}</span>
          </div>
          <div class="req-row">
            <span class="lbl">{{ __('app.total_usd') }}</span>
            <span class="val" style="color:#696cff;">${{ number_format(($req->service->price_usd ?? 0) * $qty, 2) }}</span>
          </div>
          <div class="req-row">
            <span class="lbl">{{ __('app.total_syp') }}</span>
            <span class="val" style="color:#0284c7;">{{ number_format(($req->service->price_syp ?? 0) * $qty) }} SYP</span>
          </div>
          <div class="req-row">
            <span class="lbl">{{ __('app.needed_on') }}</span>
            <span class="val">{{ \Carbon\Carbon::parse($req->needed_at)->format('M d, Y') }}</span>
          </div>
          @if($req->payment && $req->payment->payment_method)
          <div class="req-row">
            <span class="lbl">Paid via</span>
            <span class="val" style="text-transform:capitalize;">
              {{ str_replace('_', ' ', $req->payment->payment_method) }}
              @if($req->payment->currency)
                <span style="font-size:10px;background:#f0f0ff;color:#696cff;border-radius:4px;padding:1px 6px;margin-left:4px;">{{ $req->payment->currency }}</span>
              @endif
            </span>
          </div>
          @endif
        </div>

        {{-- Action --}}
        @if($req->payment_status === 'unpaid')
          <a href="{{ route('payment.checkout', $req->id) }}" class="pay-now-btn">
            <i class="ri ri-secure-payment-line"></i>{{ __('app.pay_now') }}
          </a>
        @else
          <div style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;background:#e8faf0;border-radius:12px;margin-top:auto;">
            <i class="ri ri-check-double-line" style="color:#28c76f;font-size:16px;"></i>
            <span style="font-size:12px;font-weight:700;color:#28c76f;">{{ __('app.paid') }}</span>
          </div>

          {{-- Review button (approved + paid only) --}}
          @if($req->status === 'approved')
            <button class="btn btn-outline-warning btn-sm w-100 rounded-3 mt-2"
                    data-bs-toggle="modal"
                    data-bs-target="#reviewModal{{ $req->id }}"
                    style="font-size:12px;font-weight:700;">
              <i class="ri ri-star-line me-1"></i>{{ __('app.rate_service') }}
            </button>
          @endif
        @endif

      </div>
    </div>
  </div>

  {{-- Review Modal --}}
  @if($req->status === 'approved' && $req->payment_status === 'paid')
  <div class="modal fade" id="reviewModal{{ $req->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 rounded-4 shadow-lg">
        <div class="modal-header border-0 pb-0">
          <div>
            <h6 class="fw-bold mb-0">{{ __('app.rate_service') }}</h6>
            <small class="text-muted">{{ $req->service->title ?? '' }}</small>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('review.store', $req->id) }}" method="POST">
          @csrf
          <div class="modal-body">

            {{-- Star rating --}}
            <div class="mb-4 text-center">
              <label class="form-label fw-semibold d-block mb-2">{{ __('app.rating') }}</label>
              <div class="star-rating d-flex justify-content-center gap-2" id="stars{{ $req->id }}">
                @for($i = 1; $i <= 5; $i++)
                  <i class="ri ri-star-line"
                     style="font-size:32px;cursor:pointer;color:#e0dced;transition:.15s;"
                     data-val="{{ $i }}"
                     onmouseover="hoverStars({{ $req->id }}, {{ $i }})"
                     onmouseout="resetStars({{ $req->id }})"
                     onclick="selectStar({{ $req->id }}, {{ $i }})"></i>
                @endfor
              </div>
              <input type="hidden" name="rating" id="ratingInput{{ $req->id }}" value="" required>
              <small class="text-muted mt-1 d-block" id="ratingLabel{{ $req->id }}">Click to rate</small>
            </div>

            {{-- Comment --}}
            <div class="mb-0">
              <label class="form-label fw-semibold">{{ __('app.comment') }} <span class="text-muted fw-normal">({{ __('app.optional') }})</span></label>
              <textarea name="comment" class="form-control rounded-3" rows="3"
                        placeholder="{{ __('app.write_comment') }}"></textarea>
            </div>
          </div>
          <div class="modal-footer border-0 pt-0">
            <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
            <button type="submit" class="btn btn-warning rounded-3 px-4 fw-bold">
              <i class="ri ri-send-plane-line me-1"></i>{{ __('app.submit_review') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif

  @empty
  <div class="col-12">
    <div class="empty-state">
      <i class="ri ri-file-list-3-line"></i>
      <h5>{{ __('app.no_services_yet') }}</h5>
      <p>{{ __('app.no_favorites_desc') }}</p>
    </div>
  </div>
  @endforelse
</div>

@endsection

@push('scripts')
<script>
const labels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
const selected = {};

function hoverStars(id, val) {
  if (selected[id]) return;
  document.querySelectorAll(`#stars${id} i`).forEach((el, i) => {
    el.className = i < val ? 'ri ri-star-fill' : 'ri ri-star-line';
    el.style.color = i < val ? '#ff9f43' : '#e0dced';
  });
}

function resetStars(id) {
  if (selected[id]) return;
  document.querySelectorAll(`#stars${id} i`).forEach(el => {
    el.className = 'ri ri-star-line';
    el.style.color = '#e0dced';
  });
}

function selectStar(id, val) {
  selected[id] = val;
  document.getElementById(`ratingInput${id}`).value = val;
  document.getElementById(`ratingLabel${id}`).textContent = labels[val];
  document.querySelectorAll(`#stars${id} i`).forEach((el, i) => {
    el.className = i < val ? 'ri ri-star-fill' : 'ri ri-star-line';
    el.style.color = i < val ? '#ff9f43' : '#e0dced';
  });
}
</script>
@endpush
