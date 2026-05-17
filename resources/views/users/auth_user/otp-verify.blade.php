@extends('layouts/blankLayout')

@section('title', __('app.otp_title'))

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6 mx-4">
            <div class="card p-sm-7 p-2">

                {{-- Logo --}}
                <div class="app-brand justify-content-center mt-5">
                    <a href="{{ url('/') }}" class="app-brand-link gap-3">
                        <span class="app-brand-logo demo">@include('_partials.macros')</span>
                        <span class="app-brand-text demo text-heading fw-semibold">{{ config('variables.templateName') }}</span>
                    </a>
                </div>

                <div class="card-body mt-1">

                    {{-- WhatsApp icon --}}
                    <div class="text-center mb-4">
                        <div style="
                            width:64px;height:64px;border-radius:18px;
                            background:linear-gradient(135deg,#25D366,#128C7E);
                            display:inline-flex;align-items:center;justify-content:center;
                            margin-bottom:12px;
                        ">
                            <svg width="34" height="34" viewBox="0 0 24 24" fill="white">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <h4 class="mb-1">{{ __('app.otp_verify_title') }}</h4>
                        <p class="text-muted mb-0" style="font-size:14px;">
                            {{ __('app.otp_sent_to') }}
                        </p>
                        <p style="font-size:15px;font-weight:700;color:#696cff;direction:ltr;">
                            {{ $phone }}
                        </p>
                    </div>

                    {{-- Alerts --}}
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible mb-4" role="alert">
                        <i class="ri ri-checkbox-circle-line me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if($errors->has('code'))
                    <div class="alert alert-danger mb-4" role="alert">
                        <i class="ri ri-error-warning-line me-2"></i>{{ $errors->first('code') }}
                    </div>
                    @endif

                    {{-- OTP Form --}}
                    <form action="{{ route('otp.verify') }}" method="POST" class="mb-4">
                        @csrf

                        {{-- 6 digit boxes --}}
                        <div class="d-flex justify-content-center gap-2 mb-4" id="otpBoxes">
                            @for($i = 0; $i < 6; $i++)
                            <input type="text"
                                   maxlength="1"
                                   inputmode="numeric"
                                   pattern="[0-9]"
                                   class="otp-box form-control text-center fw-bold"
                                   style="
                                       width:48px;height:52px;font-size:20px;
                                       border:2px solid #e5e7ff;border-radius:10px;
                                       transition:.2s;
                                   "
                                   autocomplete="off">
                            @endfor
                        </div>

                        {{-- Hidden input that gets the full code --}}
                        <input type="hidden" name="code" id="codeInput">

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                <i class="ri ri-shield-check-line me-2"></i>{{ __('app.verify_code') }}
                            </button>
                        </div>
                    </form>

                    {{-- Resend --}}
                    <div class="text-center">
                        <p class="text-muted mb-2" style="font-size:13px;">{{ __('app.didnt_receive') }}</p>
                        <form action="{{ route('otp.resend') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit"
                                    class="btn btn-outline-secondary btn-sm"
                                    id="resendBtn">
                                <i class="ri ri-refresh-line me-1"></i>{{ __('app.resend') }}
                                <span id="resendTimer" class="ms-1"></span>
                            </button>
                        </form>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-muted" style="font-size:13px;">
                            <i class="ri ri-arrow-left-line me-1"></i>{{ __('app.back_to_login') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const boxes      = document.querySelectorAll('.otp-box');
    const codeInput  = document.getElementById('codeInput');
    const submitBtn  = document.getElementById('submitBtn');
    const resendBtn  = document.getElementById('resendBtn');
    const timerEl    = document.getElementById('resendTimer');

    // ── Box navigation ──
    boxes.forEach((box, i) => {
        box.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(-1);
            this.style.borderColor = this.value ? '#696cff' : '#e5e7ff';
            if (this.value && i < boxes.length - 1) boxes[i + 1].focus();
            syncCode();
        });

        box.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && i > 0) {
                boxes[i - 1].value = '';
                boxes[i - 1].style.borderColor = '#e5e7ff';
                boxes[i - 1].focus();
                syncCode();
            }
        });

        // Allow paste on first box
        if (i === 0) {
            box.addEventListener('paste', function (e) {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData)
                    .getData('text').replace(/\D/g, '').slice(0, 6);
                pasted.split('').forEach((ch, idx) => {
                    if (boxes[idx]) {
                        boxes[idx].value = ch;
                        boxes[idx].style.borderColor = '#696cff';
                    }
                });
                if (boxes[pasted.length - 1]) boxes[pasted.length - 1].focus();
                syncCode();
            });
        }
    });

    function syncCode() {
        const code = Array.from(boxes).map(b => b.value).join('');
        codeInput.value = code;
        submitBtn.disabled = code.length < 6;
    }

    boxes[0].focus();

    // ── Resend cooldown (60 seconds) ──
    let seconds = 60;
    resendBtn.disabled = true;

    const countdown = setInterval(() => {
        seconds--;
        timerEl.textContent = `(${seconds}s)`;
        if (seconds <= 0) {
            clearInterval(countdown);
            resendBtn.disabled = false;
            timerEl.textContent = '';
        }
    }, 1000);
})();
</script>
@endsection
