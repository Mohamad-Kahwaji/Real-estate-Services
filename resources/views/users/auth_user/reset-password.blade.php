@extends('layouts/blankLayout')
@section('title', __('app.reset_password'))

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<style>
html,body{height:100%;margin:0;padding:0;}
.auth-wrap{min-height:100vh;display:flex;font-family:'Inter',system-ui,sans-serif;}

.auth-brand{
    flex:0 0 42%;
    background:linear-gradient(145deg,#5f61e6 0%,#696cff 45%,#9c9eff 100%);
    display:flex;flex-direction:column;justify-content:center;align-items:center;
    padding:60px 52px;color:#fff;position:relative;overflow:hidden;
}
.auth-brand::before{content:'';position:absolute;top:-80px;right:-80px;width:320px;height:320px;border-radius:50%;background:rgba(255,255,255,.07);}
.auth-brand::after{content:'';position:absolute;bottom:-100px;left:-60px;width:280px;height:280px;border-radius:50%;background:rgba(255,255,255,.06);}
.auth-brand-logo{width:64px;height:64px;border-radius:18px;background:rgba(255,255,255,.2);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;font-size:30px;margin-bottom:28px;box-shadow:0 8px 32px rgba(0,0,0,.15);}
.auth-brand h1{font-size:28px;font-weight:800;margin:0 0 10px;text-align:center;}
.auth-brand p{font-size:15px;opacity:.85;text-align:center;line-height:1.7;max-width:280px;margin:0 0 32px;}

.step-badge{
    display:flex;align-items:center;gap:10px;
    background:rgba(255,255,255,.15);border-radius:16px;
    padding:12px 20px;margin-bottom:14px;width:100%;max-width:290px;
}
.step-num{width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;flex-shrink:0;}
.step-text{font-size:13px;font-weight:600;opacity:.9;}
.step-done{opacity:.5;}

.auth-form-panel{flex:1;display:flex;align-items:center;justify-content:center;background:#f8f9fc;padding:40px 24px;}
.auth-form-box{background:#fff;border-radius:24px;padding:48px 44px;width:100%;max-width:440px;box-shadow:0 8px 40px rgba(105,108,255,.1);}
.auth-form-box .logo-wrap{display:flex;align-items:center;gap:12px;margin-bottom:28px;}
.auth-form-box .logo-icon{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#696cff,#9c9eff);display:flex;align-items:center;justify-content:center;font-size:20px;}
.auth-form-box .logo-name{font-size:18px;font-weight:800;color:#312d4b;}
.auth-form-box h2{font-size:22px;font-weight:800;color:#312d4b;margin:0 0 6px;}
.auth-form-box .sub{font-size:14px;color:#97939e;margin:0 0 24px;line-height:1.6;}

.phone-chip{
    display:inline-flex;align-items:center;gap:8px;
    background:#eef0ff;border-radius:10px;padding:8px 14px;
    font-size:13px;font-weight:700;color:#696cff;margin-bottom:22px;
}

.otp-wrap{display:flex;gap:10px;margin-bottom:6px;}
.otp-wrap input{
    flex:1;text-align:center;font-size:22px;font-weight:800;
    padding:14px 4px;border:1.5px solid #e5e2ec;border-radius:12px;
    color:#312d4b;background:#faf9fc;outline:none;transition:.2s;
}
.otp-wrap input:focus{border-color:#696cff;background:#fff;box-shadow:0 0 0 4px rgba(105,108,255,.1);}

.form-field{margin-bottom:16px;}
.form-field label{display:block;font-size:13px;font-weight:700;color:#585164;margin-bottom:8px;}
.form-field .input-wrap{position:relative;}
.form-field .input-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#b0aab8;font-size:17px;pointer-events:none;}
.form-field .toggle-pass{position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#b0aab8;cursor:pointer;font-size:17px;}
.form-field input{width:100%;padding:13px 42px 13px 42px;border:1.5px solid #e5e2ec;border-radius:12px;font-size:14px;color:#312d4b;background:#faf9fc;outline:none;transition:.2s;box-sizing:border-box;}
.form-field input:focus{border-color:#696cff;background:#fff;box-shadow:0 0 0 4px rgba(105,108,255,.1);}

.strength-bar{height:4px;border-radius:4px;background:#eee;margin-top:6px;overflow:hidden;}
.strength-fill{height:100%;border-radius:4px;transition:.3s;width:0;}

.btn-primary-auth{width:100%;padding:14px;border-radius:13px;border:none;background:linear-gradient(135deg,#696cff,#5f61e6);color:#fff;font-size:15px;font-weight:700;cursor:pointer;box-shadow:0 6px 20px rgba(105,108,255,.4);transition:.2s;display:flex;align-items:center;justify-content:center;gap:8px;margin-top:8px;}
.btn-primary-auth:hover{transform:translateY(-1px);box-shadow:0 10px 28px rgba(105,108,255,.45);}

.resend-row{text-align:center;margin-top:14px;font-size:13px;color:#97939e;}
.resend-row a{color:#696cff;font-weight:700;text-decoration:none;cursor:pointer;}

.alert-err{background:#fdeaea;border:1px solid rgba(234,84,85,.2);border-radius:12px;padding:12px 16px;font-size:13px;color:#a53030;margin-bottom:18px;display:flex;align-items:flex-start;gap:10px;}

@media(max-width:768px){.auth-brand{display:none;}.auth-form-box{padding:36px 24px;}}
</style>

<div class="auth-wrap">

    {{-- Left Brand Panel --}}
    <div class="auth-brand">
        <div class="auth-brand-logo">🏠</div>
        <h1>Reset<br>Password</h1>
        <p>{{ __('app.reset_password_brand') }}</p>

        <div class="step-badge step-done">
            <div class="step-num">✓</div>
            <div class="step-text">{{ __('app.step_phone_sent') }}</div>
        </div>
        <div class="step-badge">
            <div class="step-num">2</div>
            <div class="step-text">{{ __('app.step_verify_otp') }}</div>
        </div>
        <div class="step-badge" style="opacity:.4;">
            <div class="step-num">3</div>
            <div class="step-text">{{ __('app.step_new_password') }}</div>
        </div>
    </div>

    {{-- Right Form Panel --}}
    <div class="auth-form-panel">
        <div class="auth-form-box">

            <div class="logo-wrap">
                <div class="logo-icon">🏠</div>
                <div class="logo-name">{{ config('variables.templateName', 'RealEstate') }}</div>
            </div>

            <h2>{{ __('app.reset_password') }}</h2>
            <p class="sub">{{ __('app.otp_sent_to') }}</p>

            <div class="phone-chip">
                <i class="ri ri-whatsapp-line"></i>
                {{ $phone }}
            </div>

            @if($errors->any())
            <div class="alert-err">
                <i class="ri ri-error-warning-line" style="font-size:18px;flex-shrink:0;"></i>
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
            @endif

            <form action="{{ route('reset-password.save') }}" method="POST" id="resetForm">
                @csrf

                {{-- OTP digits --}}
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:700;color:#585164;margin-bottom:10px;">
                        {{ __('app.verify_code') }}
                    </label>
                    <div class="otp-wrap" id="otpBoxes">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit" autofocus>
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit">
                    </div>
                    <input type="hidden" name="code" id="codeInput">
                </div>

                {{-- New Password --}}
                <div class="form-field">
                    <label>{{ __('app.new_password_label') }}</label>
                    <div class="input-wrap">
                        <i class="ri ri-lock-line input-icon"></i>
                        <input type="password" name="password" id="passInput"
                               placeholder="••••••••" autocomplete="new-password">
                        <i class="ri ri-eye-off-line toggle-pass" id="togglePass1"></i>
                    </div>
                    <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                </div>

                {{-- Confirm Password --}}
                <div class="form-field">
                    <label>{{ __('app.confirm_password') }}</label>
                    <div class="input-wrap">
                        <i class="ri ri-lock-check-line input-icon"></i>
                        <input type="password" name="password_confirmation" id="confirmInput"
                               placeholder="••••••••" autocomplete="new-password">
                        <i class="ri ri-eye-off-line toggle-pass" id="togglePass2"></i>
                    </div>
                </div>

                <button type="submit" class="btn-primary-auth">
                    <i class="ri ri-shield-check-line"></i>
                    {{ __('app.reset_password_btn') }}
                </button>
            </form>

            <div class="resend-row">
                {{ __('app.didnt_receive') }}
                <form action="{{ route('otp.resend') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="border:none;background:none;padding:0;color:#696cff;font-weight:700;font-size:13px;cursor:pointer;">
                        {{ __('app.resend') }}
                    </button>
                </form>
                &nbsp;·&nbsp;
                <a href="{{ route('forgot-password') }}">{{ __('app.back_to_login') }}</a>
            </div>

        </div>
    </div>

</div>

<script>
// OTP digit boxes auto-advance
const digits = document.querySelectorAll('.otp-digit');
const codeInput = document.getElementById('codeInput');

digits.forEach((box, i) => {
    box.addEventListener('input', () => {
        box.value = box.value.replace(/\D/, '');
        if (box.value && i < digits.length - 1) digits[i + 1].focus();
        codeInput.value = [...digits].map(d => d.value).join('');
    });
    box.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !box.value && i > 0) digits[i - 1].focus();
    });
    box.addEventListener('paste', e => {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
        paste.split('').forEach((ch, j) => { if (digits[j]) digits[j].value = ch; });
        codeInput.value = paste;
        if (digits[paste.length - 1]) digits[paste.length - 1].focus();
    });
});

// Password toggle
function toggleVisibility(toggleId, inputId) {
    const btn = document.getElementById(toggleId);
    const inp = document.getElementById(inputId);
    if (!btn || !inp) return;
    btn.addEventListener('click', () => {
        inp.type = inp.type === 'password' ? 'text' : 'password';
        btn.className = inp.type === 'password' ? 'ri ri-eye-off-line toggle-pass' : 'ri ri-eye-line toggle-pass';
    });
}
toggleVisibility('togglePass1', 'passInput');
toggleVisibility('togglePass2', 'confirmInput');

// Strength bar
document.getElementById('passInput').addEventListener('input', function () {
    const val = this.value;
    const strength = [val.length >= 8, /[A-Z]/.test(val), /[0-9]/.test(val), /[^A-Za-z0-9]/.test(val)].filter(Boolean).length;
    const fill = document.getElementById('strengthFill');
    const colors = ['', '#ea5455', '#ff9f43', '#28c76f', '#00cfe8'];
    fill.style.width = (strength * 25) + '%';
    fill.style.background = colors[strength] || '#eee';
});
</script>
@endsection
