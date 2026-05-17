@extends('layouts/contentNavbarLayout')

@section('title', 'Payment Failed')

@section('content')
<div style="max-width:500px;margin:40px auto;text-align:center;">
  <div style="background:#fff;border-radius:24px;box-shadow:0 8px 40px rgba(234,84,85,.12);padding:44px 36px;">

    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#ea5455,#ff6b6b);
                display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
      <i class="ri ri-close-line" style="font-size:38px;color:#fff;"></i>
    </div>

    <h4 style="font-size:22px;font-weight:800;color:#312d4b;margin-bottom:6px;">Payment Failed</h4>
    <p style="font-size:14px;color:#97939e;margin-bottom:28px;">
      Something went wrong with your payment for
      <strong>{{ $serviceRequest->service->title }}</strong>.
      Please try again.
    </p>

    <div style="display:flex;gap:12px;justify-content:center;">
      <a href="{{ route('payment.checkout', $serviceRequest->id) }}"
         style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;
                background:#696cff;color:#fff;border-radius:14px;font-weight:700;
                font-size:14px;text-decoration:none;">
        <i class="ri ri-refresh-line"></i>Try Again
      </a>
      <a href="{{ route('sentservice.user') }}"
         style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;
                background:#f5f4ff;color:#696cff;border:1.5px solid #d5d8ff;
                border-radius:14px;font-weight:700;font-size:14px;text-decoration:none;">
        My Requests
      </a>
    </div>

  </div>
</div>
@endsection
