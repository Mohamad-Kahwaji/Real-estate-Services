@extends('layouts/contentNavbarLayout')

@section('title', 'Complete Payment')

@section('page-style')
<style>
  .pay-wrap { max-width: 780px; margin: 0 auto; }

  /* ── Test Mode Banner ── */
  .test-banner {
    display: flex; align-items: center; gap: 12px;
    background: linear-gradient(90deg, #fff8e1, #fffde7);
    border: 1.5px solid #ffe082; border-radius: 16px;
    padding: 14px 20px; margin-bottom: 20px;
  }
  .test-banner i { font-size: 22px; color: #f59e0b; flex-shrink: 0; }
  .test-banner-text { flex: 1; }
  .test-banner-title { font-size: 13px; font-weight: 800; color: #92400e; margin-bottom: 2px; }
  .test-banner-sub   { font-size: 12px; color: #b45309; }
  .test-pay-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 12px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff; border: none; cursor: pointer;
    font-size: 13px; font-weight: 700;
    box-shadow: 0 4px 14px rgba(245,158,11,.4);
    transition: .2s; white-space: nowrap;
  }
  .test-pay-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(245,158,11,.5); }

  /* ── Order Card ── */
  .order-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 28px rgba(105,108,255,.12);
    overflow: hidden; margin-bottom: 20px;
  }
  .order-hdr {
    background: linear-gradient(135deg, #696cff 0%, #9c6bff 100%);
    padding: 18px 22px;
    display: flex; align-items: center; justify-content: space-between;
  }
  .order-hdr-left { display: flex; align-items: center; gap: 10px; }
  .order-hdr i { font-size: 20px; color: rgba(255,255,255,.8); }
  .order-hdr-title { font-size: 15px; font-weight: 700; color: #fff; margin: 0; }
  .order-badge {
    background: rgba(255,255,255,.2); color: #fff;
    border-radius: 20px; padding: 4px 12px;
    font-size: 12px; font-weight: 700;
  }
  .order-body { padding: 18px 22px; }
  .order-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 8px 0; border-bottom: 1px solid #f5f3fa;
    font-size: 13px; color: #585164;
  }
  .order-row:last-child { border-bottom: none; }
  .order-row .lbl { font-weight: 600; color: #97939e; }
  .order-total {
    display: flex; justify-content: space-between; align-items: center;
    background: #f5f4ff; border-radius: 14px; padding: 14px 16px;
    margin-top: 12px;
  }
  .order-total-label  { font-size: 13px; font-weight: 700; color: #585164; }
  .order-total-amount { font-size: 24px; font-weight: 800; color: #696cff; }

  /* ── Payment Card ── */
  .pay-card { background: #fff; border-radius: 20px; box-shadow: 0 4px 28px rgba(105,108,255,.12); overflow: hidden; }
  .pay-tabs  { display: flex; border-bottom: 1px solid #f0eef4; background: #faf9fc; }
  .pay-tab {
    flex: 1; padding: 15px 6px;
    display: flex; flex-direction: column; align-items: center; gap: 5px;
    cursor: pointer; border: none; background: none;
    font-size: 11px; font-weight: 700; color: #97939e;
    transition: .2s; border-bottom: 3px solid transparent; margin-bottom: -1px;
  }
  .pay-tab i { font-size: 20px; }
  .pay-tab.active  { color: #696cff; border-bottom-color: #696cff; background: #fff; }
  .pay-tab:hover:not(.active) { color: #585164; background: #f5f4ff; }

  .pay-panel          { display: none; padding: 26px 22px; }
  .pay-panel.active   { display: block; }
  .pay-panel-title    { font-size: 14px; font-weight: 700; color: #312d4b; margin-bottom: 6px; }
  .pay-panel-sub      { font-size: 12px; color: #97939e; margin-bottom: 20px; }

  /* Stripe */
  .stripe-btn {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; padding: 14px;
    background: linear-gradient(135deg, #635bff, #796eff);
    color: #fff; border: none; border-radius: 14px;
    font-size: 15px; font-weight: 700; cursor: pointer;
    transition: .2s; box-shadow: 0 6px 18px rgba(99,91,255,.35);
  }
  .stripe-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(99,91,255,.45); }

  /* PayPal */
  #paypal-button-container { min-height: 50px; }
  .info-box {
    display: flex; align-items: center; gap: 10px;
    border-radius: 12px; padding: 12px 16px; margin-bottom: 18px;
    font-size: 12px; font-weight: 600;
  }
  .info-box-yellow { background: #fffbf0; border: 1px solid #ffe9a0; color: #856404; }
  .info-box-blue   { background: #f0f4ff; border: 1px solid #c7d6ff; color: #3451b2; }

  /* Bank transfer */
  .bank-details {
    background: #f5f4ff; border: 1px solid #d5d8ff;
    border-radius: 14px; padding: 16px 18px; margin-bottom: 18px;
  }
  .bank-details-title { font-size: 11px; font-weight: 700; color: #696cff; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 10px; }
  .bank-row { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; font-size: 13px; }
  .bank-row .bk-lbl { color: #97939e; font-weight: 600; }
  .bank-row .bk-val { font-weight: 700; color: #312d4b; }
  .upload-zone {
    border: 2px dashed #d5d8ff; border-radius: 14px;
    padding: 30px 20px; text-align: center; cursor: pointer;
    transition: .2s; background: #fafbff; position: relative;
    overflow: hidden;
  }
  .upload-zone:hover { border-color: #696cff; background: #f0f0ff; }
  .upload-zone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
  .upload-zone i { font-size: 30px; color: #c5c8ff; display: block; margin-bottom: 8px; }
  .upload-zone p { font-size: 13px; color: #97939e; margin: 0; }
  .upload-zone p.small { font-size: 11px; margin-top: 4px; }
  #file-preview { display: none; margin-top: 14px; }
  #preview-img  { max-height: 120px; border-radius: 8px; border: 2px solid #696cff; }
  #file-name    { font-size: 11px; color: #696cff; margin-top: 6px; font-weight: 600; }
  .submit-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 13px;
    background: #696cff; color: #fff; border: none; border-radius: 14px;
    font-size: 14px; font-weight: 700; cursor: pointer;
    transition: .2s; margin-top: 16px;
    box-shadow: 0 6px 18px rgba(105,108,255,.3);
  }
  .submit-btn:hover { background: #5a5de8; transform: translateY(-1px); }

  /* secure note */
  .secure-note { display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 11px; color: #b0aab8; margin-top: 18px; }
  .secure-note i { font-size: 14px; color: #28c76f; }

  /* Currency selector buttons */
  .curr-btn {
    flex: 1; padding: 11px 14px; border-radius: 12px;
    border: 2px solid #e0dced; background: #faf9fc;
    font-size: 13px; font-weight: 700; color: #97939e;
    cursor: pointer; transition: .2s;
    display: flex; align-items: center; justify-content: center; gap: 4px;
  }
  .curr-btn:hover { border-color: #696cff; color: #696cff; background: #f0f0ff; }
  .curr-btn.active { border-color: #696cff; background: #696cff; color: #fff; box-shadow: 0 4px 14px rgba(105,108,255,.3); }
</style>
@endsection

@section('content')
@php
  $service   = $serviceRequest->service;
  $qty       = $serviceRequest->quantity ?? 1;
  $totalUsd  = $service->price_usd * $qty;
  $totalSyp  = $service->price_syp * $qty;
  $testMode  = config('payment.test_mode');
@endphp

<div class="pay-wrap">

  {{-- Back --}}
  <div class="mb-3">
    <a href="{{ route('sentservice.user') }}" style="font-size:13px;color:#696cff;text-decoration:none;font-weight:600;">
      <i class="ri ri-arrow-left-line me-1"></i>Back to my requests
    </a>
  </div>

  {{-- ── Test Mode Banner ── --}}
  @if($testMode)
  <div class="test-banner">
    <i class="ri ri-flask-line"></i>
    <div class="test-banner-text">
      <div class="test-banner-title">Test Mode Active</div>
      <div class="test-banner-sub">Payment will be marked as paid immediately without a real gateway.</div>
    </div>
    <form action="{{ route('payment.test', $serviceRequest->id) }}" method="POST" class="d-flex align-items-center gap-2">
      @csrf
      <select name="currency" class="form-select form-select-sm rounded-3 fw-bold"
              style="width:90px;border:1.5px solid #ffe082;font-size:12px;">
        <option value="USD">USD</option>
        <option value="SYP">SYP</option>
      </select>
      <button type="submit" class="test-pay-btn">
        <i class="ri ri-shield-check-line"></i>Pay Now (Test)
      </button>
    </form>
  </div>
  @endif

  {{-- ── Order Summary ── --}}
  <div class="order-card">
    <div class="order-hdr">
      <div class="order-hdr-left">
        <i class="ri ri-shopping-cart-2-line"></i>
        <p class="order-hdr-title">Order Summary</p>
      </div>
      <span class="order-badge">Qty: {{ $qty }}</span>
    </div>
    <div class="order-body">
      <div class="order-row">
        <span class="lbl">Service</span>
        <span style="font-weight:700;color:#312d4b;">{{ $service->title }}</span>
      </div>
      <div class="order-row">
        <span class="lbl">Business</span>
        <span>{{ $service->business->job_name_en ?? '-' }}</span>
      </div>
      <div class="order-row">
        <span class="lbl">Needed on</span>
        <span>{{ \Carbon\Carbon::parse($serviceRequest->needed_at)->format('M d, Y') }}</span>
      </div>
      <div class="order-row">
        <span class="lbl">Unit price (USD)</span>
        <span style="font-weight:700;color:#16a34a;">${{ number_format($service->price_usd, 2) }}</span>
      </div>
      <div class="order-row">
        <span class="lbl">Unit price (SYP)</span>
        <span style="font-weight:700;color:#0284c7;">{{ number_format($service->price_syp) }} SYP</span>
      </div>
      <div class="order-total" style="flex-direction:column;align-items:stretch;gap:6px;">
        <div class="d-flex justify-content-between align-items-center">
          <span class="order-total-label">Total (USD)</span>
          <span class="order-total-amount" style="color:#16a34a;">${{ number_format($totalUsd, 2) }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center" style="border-top:1px dashed #d5d8ff;padding-top:6px;">
          <span class="order-total-label">Total (SYP)</span>
          <span class="order-total-amount" style="font-size:18px;color:#0284c7;">{{ number_format($totalSyp) }} <small style="font-size:12px;color:#97939e;">SYP</small></span>
        </div>
      </div>
    </div>
  </div>

  {{-- ── Payment Methods ── --}}
  <div class="pay-card">
    <div class="pay-tabs">
      <button class="pay-tab active" onclick="switchTab('stripe', this)">
        <i class="ri ri-bank-card-2-line"></i>Card (Stripe)
      </button>
      <button class="pay-tab" onclick="switchTab('paypal', this)">
        <i class="ri ri-paypal-line"></i>PayPal
      </button>
      <button class="pay-tab" onclick="switchTab('bank', this)">
        <i class="ri ri-building-4-line"></i>Bank Transfer
      </button>
    </div>

    {{-- Stripe panel --}}
    <div id="panel-stripe" class="pay-panel active">
      <p class="pay-panel-title"><i class="ri ri-bank-card-2-line me-1" style="color:#635bff;"></i>Pay with Card</p>
      <p class="pay-panel-sub">You will be redirected to Stripe's secure checkout page.</p>
      <div class="info-box info-box-yellow mb-3">
        <i class="ri ri-exchange-dollar-line"></i>
        Stripe only supports <strong>USD</strong>. You will be charged ${{ number_format($totalUsd, 2) }}.
      </div>
      <form action="{{ route('payment.stripe', $serviceRequest->id) }}" method="POST">
        @csrf
        <button type="submit" class="stripe-btn">
          <i class="ri ri-lock-2-line"></i>
          Pay ${{ number_format($totalUsd, 2) }} USD with Stripe
        </button>
      </form>
      <div class="info-box info-box-blue mt-3">
        <i class="ri ri-information-line"></i>
        Your card details are handled securely by Stripe — we never store them.
      </div>
    </div>

    {{-- PayPal panel --}}
    <div id="panel-paypal" class="pay-panel">
      <p class="pay-panel-title"><i class="ri ri-paypal-line me-1" style="color:#003087;"></i>Pay with PayPal</p>
      <p class="pay-panel-sub">Click the button to open PayPal and complete your payment.</p>
      <div class="info-box info-box-yellow mb-3">
        <i class="ri ri-exchange-dollar-line"></i>
        PayPal only supports <strong>USD</strong>. You will be charged ${{ number_format($totalUsd, 2) }}.
      </div>
      <div class="info-box info-box-blue mb-3">
        <i class="ri ri-information-line"></i>
        A PayPal account or credit card is accepted.
      </div>
      <div id="paypal-button-container"></div>
    </div>

    {{-- Bank Transfer panel --}}
    <div id="panel-bank" class="pay-panel">
      <p class="pay-panel-title"><i class="ri ri-bank-line me-1" style="color:#696cff;"></i>Bank Transfer</p>
      <p class="pay-panel-sub">Choose your currency, transfer the exact amount, then upload your receipt.</p>

      {{-- Currency selector --}}
      <div class="d-flex gap-2 mb-4">
        <button type="button" class="curr-btn active" id="btn-usd" onclick="selectCurrency('USD')">
          <i class="ri ri-money-dollar-circle-line me-1"></i>USD — ${{ number_format($totalUsd, 2) }}
        </button>
        <button type="button" class="curr-btn" id="btn-syp" onclick="selectCurrency('SYP')">
          <i class="ri ri-coins-line me-1"></i>SYP — {{ number_format($totalSyp) }} SYP
        </button>
      </div>

      <div class="bank-details">
        <div class="bank-details-title"><i class="ri ri-information-line me-1"></i>Transfer Details</div>
        <div class="bank-row">
          <span class="bk-lbl">Bank</span>
          <span class="bk-val">{{ config('payment.bank_name') }}</span>
        </div>
        <div class="bank-row">
          <span class="bk-lbl">Account Name</span>
          <span class="bk-val">{{ config('payment.bank_account_name') }}</span>
        </div>
        <div class="bank-row">
          <span class="bk-lbl">Account Number</span>
          <span class="bk-val" style="letter-spacing:.5px;">{{ config('payment.bank_account_number') }}</span>
        </div>
        <div class="bank-row" style="margin-top:8px;padding-top:8px;border-top:1px solid #d5d8ff;">
          <span class="bk-lbl">Amount to Transfer</span>
          <span class="bk-val" id="bank-amount-display" style="color:#696cff;font-size:17px;">
            ${{ number_format($totalUsd, 2) }} USD
          </span>
        </div>
      </div>

      <form action="{{ route('payment.bank-transfer', $serviceRequest->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="currency" id="bank-currency-input" value="USD">

        @error('proof_image')
          <div class="alert alert-danger rounded-3 mb-3" style="font-size:13px;">{{ $message }}</div>
        @enderror
        @error('currency')
          <div class="alert alert-danger rounded-3 mb-3" style="font-size:13px;">{{ $message }}</div>
        @enderror

        <p style="font-size:13px;font-weight:700;color:#312d4b;margin-bottom:10px;">
          <i class="ri ri-image-line me-1" style="color:#696cff;"></i>Upload Transfer Receipt
        </p>
        <div class="upload-zone" id="uploadZone">
          <input type="file" name="proof_image" accept="image/*" onchange="previewFile(this)">
          <i class="ri ri-cloud-upload-line"></i>
          <p>Click to upload or drag & drop</p>
          <p class="small">JPG, PNG or WEBP · Max 5 MB</p>
          <div id="file-preview">
            <img id="preview-img" alt="">
            <p id="file-name"></p>
          </div>
        </div>
        <button type="submit" class="submit-btn">
          <i class="ri ri-check-double-line"></i>Confirm & Submit
        </button>
      </form>
    </div>
  </div>

  <div class="secure-note">
    <i class="ri ri-shield-check-line"></i>
    All payments are encrypted and processed securely.
  </div>

</div>
@endsection

@push('scripts')
<script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id', 'sb') }}&currency=USD"></script>
<script>
const totalUsd = {{ $totalUsd }};
const totalSyp = {{ $totalSyp }};

function switchTab(name, btn) {
  document.querySelectorAll('.pay-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.pay-tab').forEach(b => b.classList.remove('active'));
  document.getElementById('panel-' + name).classList.add('active');
  btn.classList.add('active');
}

function selectCurrency(currency) {
  document.getElementById('bank-currency-input').value = currency;
  document.getElementById('btn-usd').classList.toggle('active', currency === 'USD');
  document.getElementById('btn-syp').classList.toggle('active', currency === 'SYP');
  const display = document.getElementById('bank-amount-display');
  if (currency === 'SYP') {
    display.textContent = new Intl.NumberFormat().format(totalSyp) + ' SYP';
    display.style.color = '#0284c7';
  } else {
    display.textContent = '$' + totalUsd.toFixed(2) + ' USD';
    display.style.color = '#696cff';
  }
}

function previewFile(input) {
  if (!input.files.length) return;
  const file   = input.files[0];
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('preview-img').src = e.target.result;
    document.getElementById('file-name').textContent  = file.name;
    document.getElementById('file-preview').style.display = 'block';
  };
  reader.readAsDataURL(file);
}

var paypalCreateUrl  = '{{ route('payment.paypal.create',  $serviceRequest->id) }}';
var paypalCaptureUrl = '{{ route('payment.paypal.capture', $serviceRequest->id) }}';
var csrfToken        = '{{ csrf_token() }}';

paypal.Buttons({
  createOrder: function() {
    return fetch(paypalCreateUrl, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
    })
    .then(function(r) { return r.json(); })
    .then(function(d) { return d.id; });
  },

  onApprove: function(data) {
    return fetch(paypalCaptureUrl, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ orderID: data.orderID }),
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      if (res.success) { window.location.href = res.redirect; }
      else { alert('Payment could not be captured. Please try again.'); }
    });
  },

  onError: function(err) {
    console.error(err);
    alert('An error occurred with PayPal. Please try another payment method.');
  }
}).render('#paypal-button-container');
</script>
@endpush
