@extends('layouts/contentNavbarLayout')

@section('title', 'Payment Successful')

@section('content')
@php $payment = $serviceRequest->payment; @endphp

<div style="max-width:500px;margin:40px auto;text-align:center;">

  <div style="background:#fff;border-radius:24px;box-shadow:0 8px 40px rgba(105,108,255,.14);padding:44px 36px;">

    {{-- Success icon --}}
    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#28c76f,#48da89);
                display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
      <i class="ri ri-check-line" style="font-size:38px;color:#fff;"></i>
    </div>

    <h4 style="font-size:22px;font-weight:800;color:#312d4b;margin-bottom:6px;">Payment Successful!</h4>
    <p style="font-size:14px;color:#97939e;margin-bottom:28px;">
      Your payment has been confirmed. The service owner will now review your request.
    </p>

    {{-- Details --}}
    <div style="background:#f9f8fc;border-radius:14px;padding:18px 20px;text-align:left;margin-bottom:24px;">
      <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:13px;border-bottom:1px solid #f0eef4;">
        <span style="color:#97939e;font-weight:600;">Service</span>
        <span style="font-weight:700;color:#312d4b;">{{ $serviceRequest->service->title }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:13px;border-bottom:1px solid #f0eef4;">
        <span style="color:#97939e;font-weight:600;">Method</span>
        <span style="font-weight:700;color:#312d4b;text-transform:capitalize;">
          {{ str_replace('_', ' ', $payment?->payment_method ?? '-') }}
        </span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:13px;border-bottom:1px solid #f0eef4;">
        <span style="color:#97939e;font-weight:600;">Amount Paid</span>
        <span style="font-weight:800;color:#696cff;">${{ number_format($payment?->amount ?? 0, 2) }}</span>
      </div>
      @if($payment?->transaction_id)
      <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:13px;">
        <span style="color:#97939e;font-weight:600;">Transaction ID</span>
        <span style="font-size:11px;font-weight:600;color:#585164;word-break:break-all;">{{ $payment->transaction_id }}</span>
      </div>
      @endif
    </div>

    <a href="{{ route('sentservice.user') }}"
       style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;
              background:linear-gradient(135deg,#696cff,#9c6bff);color:#fff;
              border-radius:14px;font-weight:700;font-size:14px;text-decoration:none;
              box-shadow:0 6px 18px rgba(105,108,255,.35);">
      <i class="ri ri-file-list-3-line"></i>View My Requests
    </a>

  </div>

</div>
@endsection
