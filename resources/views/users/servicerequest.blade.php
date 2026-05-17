@extends('layouts/contentNavbarLayout')

@section('title', 'Incoming Service Requests')

@section('page-style')
<style>
  .page-hdr h4 { font-size: 20px; font-weight: 800; color: #312d4b; margin: 0 0 4px; }
  .page-hdr p  { font-size: 13px; color: #97939e; margin: 0; }

  /* ── Request card ── */
  .req-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 22px rgba(105,108,255,.1);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    height: 100%; display: flex; flex-direction: column;
  }
  .req-card:hover { transform: translateY(-4px); box-shadow: 0 12px 34px rgba(105,108,255,.16); }

  .req-img { height: 170px; object-fit: cover; width: 100%; display: block; }

  /* Payment proof overlay */
  .proof-overlay {
    position: relative;
  }
  .proof-badge {
    position: absolute; top: 10px; left: 10px;
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(40,199,111,.9); color: #fff;
    border-radius: 20px; padding: 4px 12px;
    font-size: 11px; font-weight: 700;
    backdrop-filter: blur(4px);
  }
  .proof-badge i { font-size: 13px; }

  .req-body { padding: 16px 18px; flex: 1; display: flex; flex-direction: column; }

  .req-business { font-size: 11px; font-weight: 700; color: #97939e; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
  .req-title    { font-size: 15px; font-weight: 800; color: #312d4b; margin-bottom: 4px; line-height: 1.3; }
  .req-cat      { font-size: 12px; color: #b0aab8; margin-bottom: 10px; }

  /* Requester info */
  .requester-row {
    display: flex; align-items: center; gap: 8px;
    background: #f5f4ff; border-radius: 10px; padding: 8px 12px;
    margin-bottom: 10px;
  }
  .requester-ava {
    width: 30px; height: 30px; border-radius: 50%;
    background: linear-gradient(135deg, #696cff, #9c6bff);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800; color: #fff; flex-shrink: 0;
  }
  .requester-name { font-size: 12px; font-weight: 700; color: #312d4b; }
  .requester-sub  { font-size: 11px; color: #97939e; }

  /* Details */
  .req-details { background: #faf9fc; border-radius: 12px; padding: 10px 14px; margin-bottom: 12px; }
  .req-row { display: flex; justify-content: space-between; align-items: center; font-size: 12px; padding: 4px 0; border-bottom: 1px solid #f0eef4; }
  .req-row:last-child { border-bottom: none; }
  .req-row .lbl { color: #97939e; font-weight: 600; }
  .req-row .val { font-weight: 700; color: #585164; }

  /* Payment method chip */
  .method-chip {
    display: inline-flex; align-items: center; gap: 4px;
    background: #eef0ff; color: #696cff;
    border-radius: 20px; padding: 3px 10px;
    font-size: 11px; font-weight: 700;
  }

  /* Proof image link */
  .proof-link {
    display: flex; align-items: center; gap: 6px;
    background: #f5f4ff; border: 1px solid #d5d8ff;
    border-radius: 10px; padding: 8px 12px;
    font-size: 12px; font-weight: 700; color: #696cff;
    text-decoration: none; margin-bottom: 12px;
    transition: .2s;
  }
  .proof-link:hover { background: #eef0ff; color: #696cff; text-decoration: none; }
  .proof-link i { font-size: 16px; }

  /* Action buttons */
  .action-row { display: flex; gap: 8px; margin-top: auto; }
  .btn-approve {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 10px; border-radius: 12px;
    background: linear-gradient(135deg, #28c76f, #48da89);
    color: #fff; font-size: 13px; font-weight: 700;
    border: none; cursor: pointer; transition: .2s;
    box-shadow: 0 4px 14px rgba(40,199,111,.3);
  }
  .btn-approve:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(40,199,111,.4); }
  .btn-reject {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 10px; border-radius: 12px;
    background: #fdeaea; color: #ea5455;
    font-size: 13px; font-weight: 700;
    border: 1.5px solid #ffd0d0; cursor: pointer; transition: .2s;
  }
  .btn-reject:hover { background: #ea5455; color: #fff; transform: translateY(-1px); }

  /* Empty state */
  .empty-state { text-align: center; padding: 60px 20px; }
  .empty-state i { font-size: 48px; color: #d5d8ff; display: block; margin-bottom: 14px; }
  .empty-state h5 { font-size: 16px; font-weight: 700; color: #312d4b; margin-bottom: 6px; }
  .empty-state p  { font-size: 13px; color: #97939e; }
</style>
@endsection

@section('content')

<div class="page-hdr d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
  <div>
    <h4>Incoming Service Requests</h4>
    <p>All paid requests waiting for your approval</p>
  </div>
  <span style="background:#e8faf0;color:#28c76f;border-radius:20px;padding:6px 16px;font-size:12px;font-weight:700;">
    <i class="ri ri-shield-check-line me-1"></i>Only paid requests are shown
  </span>
</div>

@if(session('success'))
  <div class="alert alert-success rounded-3 mb-4">{{ session('success') }}</div>
@endif

<div class="row g-4">
  @forelse($requests as $req)
  <div class="col-sm-6 col-lg-4 col-xl-3">
    <div class="req-card">

      {{-- Image + payment badge --}}
      <div class="proof-overlay">
        <img class="req-img"
             src="{{ $req->service->image_url }}"
             alt="{{ $req->service->title }}">
        <span class="proof-badge">
          <i class="ri ri-secure-payment-line"></i>Payment Verified
        </span>
      </div>

      <div class="req-body">
        <p class="req-business">{{ $req->service->business->job_name_en ?? '—' }}</p>
        <h6 class="req-title">{{ $req->service->title ?? '—' }}</h6>
        <p class="req-cat"><i class="ri ri-price-tag-3-line me-1"></i>{{ $req->service->category->name_en ?? 'Uncategorized' }}</p>

        {{-- Requester --}}
        @php $requesterName = $req->user->name ?? 'Unknown'; $initials = strtoupper(substr($requesterName, 0, 1)); @endphp
        <div class="requester-row">
          <div class="requester-ava">{{ $initials }}</div>
          <div>
            <div class="requester-name">{{ $requesterName }}</div>
            <div class="requester-sub">Requested {{ $req->created_at->diffForHumans() }}</div>
          </div>
        </div>

        {{-- Details --}}
        <div class="req-details">
          <div class="req-row">
            <span class="lbl">Qty</span>
            <span class="val">{{ $req->quantity }}</span>
          </div>
          <div class="req-row">
            <span class="lbl">Total paid</span>
            <span class="val" style="color:#28c76f;">${{ number_format(($req->payment->amount ?? 0), 2) }}</span>
          </div>
          <div class="req-row">
            <span class="lbl">Needed on</span>
            <span class="val">{{ \Carbon\Carbon::parse($req->needed_at)->format('M d, Y') }}</span>
          </div>
          @if($req->details)
          <div class="req-row">
            <span class="lbl">Notes</span>
            <span class="val" style="max-width:140px;text-align:right;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $req->details }}">{{ $req->details }}</span>
          </div>
          @endif
        </div>

        {{-- Payment method + proof --}}
        @if($req->payment)
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="method-chip">
              <i class="ri {{ $req->payment->payment_method === 'stripe' ? 'ri-bank-card-line' : ($req->payment->payment_method === 'paypal' ? 'ri-paypal-line' : 'ri-building-4-line') }}"></i>
              {{ str_replace('_', ' ', ucfirst($req->payment->payment_method)) }}
            </span>
            @if($req->payment->transaction_id)
              <span style="font-size:10px;color:#b0aab8;" title="{{ $req->payment->transaction_id }}">
                #{{ substr($req->payment->transaction_id, -8) }}
              </span>
            @endif
          </div>
          @if($req->payment->proof_image)
          <a href="{{ asset('storage/' . $req->payment->proof_image) }}" target="_blank" class="proof-link">
            <i class="ri ri-image-line"></i>View Bank Transfer Receipt
            <i class="ri ri-external-link-line ms-auto"></i>
          </a>
          @endif
        @endif

        {{-- Approve / Reject --}}
        <div class="action-row">
          <form action="{{ route('servicerequest.approve', $req->id) }}" method="POST" style="flex:1;">
            @csrf
            <button type="submit" class="btn-approve w-100">
              <i class="ri ri-check-line"></i>Approve
            </button>
          </form>
          <form action="{{ route('servicerequest.reject', $req->id) }}" method="POST" style="flex:1;">
            @csrf
            <button type="submit" class="btn-reject w-100">
              <i class="ri ri-close-line"></i>Reject
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="empty-state">
      <i class="ri ri-inbox-line"></i>
      <h5>No incoming requests</h5>
      <p>Requests will appear here once customers complete their payment.</p>
    </div>
  </div>
  @endforelse
</div>

@endsection
