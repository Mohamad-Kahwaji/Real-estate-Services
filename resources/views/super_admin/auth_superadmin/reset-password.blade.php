@extends('layouts/blankLayout')
@section('title', 'Super Admin — Reset Password')

@section('page-style')
<style>
* { box-sizing: border-box; }
body { margin: 0; padding: 0; }

.al-wrapper { min-height: 100vh; display: flex; align-items: stretch; background: #f5f5f9; }

.al-panel {
    width: 42%;
    background: linear-gradient(145deg, var(--role-accent) 0%, var(--role-accent-light) 60%, #c3c5ff 100%);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 60px 48px; position: relative; overflow: hidden;
}
.al-panel::before { content:''; position:absolute; top:-80px; right:-80px; width:280px; height:280px; border-radius:50%; background:rgba(255,255,255,.08); }
.al-panel::after  { content:''; position:absolute; bottom:-60px; left:-60px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,.06); }

.al-panel-icon { width:90px; height:90px; border-radius:26px; background:rgba(255,255,255,.2); backdrop-filter:blur(10px); display:flex; align-items:center; justify-content:center; font-size:40px; color:#fff; margin-bottom:32px; position:relative; z-index:1; }
.al-panel-title { font-size:28px; font-weight:800; color:#fff; text-align:center; margin-bottom:14px; line-height:1.2; position:relative; z-index:1; }
.al-panel-sub { font-size:15px; color:rgba(255,255,255,.82); text-align:center; line-height:1.6; position:relative; z-index:1; max-width:280px; margin-bottom:32px; }

.al-step { margin-top:14px; background:rgba(255,255,255,.18); backdrop-filter:blur(8px); border-radius:16px; padding:16px 24px; display:flex; align-items:center; gap:14px; width:100%; max-width:290px; position:relative; z-index:1; }
.al-step-num { width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,.28); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:800; color:#fff; flex-shrink:0; }
.al-step-text { font-size:13px; font-weight:600; color:#fff; opacity:.9; }

.al-form-side { flex:1; display:flex; align-items:center; justify-content:center; padding:40px 24px; }
.al-form-box { width:100%; max-width:440px; }

.al-brand { display:flex; align-items:center; gap:12px; margin-bottom:28px; }
.al-brand-dot { width:42px; height:42px; background:linear-gradient(135deg,var(--role-accent),var(--role-accent-light)); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; color:#fff; }
.al-brand-name { font-size:20px; font-weight:800; color:#3b3551; }

.al-heading { font-size:22px; font-weight:800; color:#3b3551; margin-bottom:6px; }
.al-sub { font-size:14px; color:#8a859c; margin-bottom:16px; line-height:1.6; }

.al-email-chip { display:inline-flex; align-items:center; gap:8px; background:var(--role-accent-soft); border-radius:10px; padding:8px 14px; font-size:13px; font-weight:700; color:var(--role-accent); margin-bottom:22px; }

.otp-wrap { display:flex; gap:10px; margin-bottom:6px; }
.otp-wrap input { flex:1; text-align:center; font-size:22px; font-weight:800; padding:14px 4px; border:1.5px solid #e4e4eb; border-radius:12px; color:#3b3551; background:#faf9fc; outline:none; transition:.2s; }
.otp-wrap input:focus { border-color:var(--role-accent); background:#fff; box-shadow:0 0 0 3px rgba(var(--role-accent-rgb),.12); }

.al-field { margin-bottom:16px; }
.al-field label { display:block; font-size:13px; font-weight:600; color:#3b3551; margin-bottom:7px; }
.al-field .input-wrap { position:relative; }
.al-field .input-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#b0aab8; font-size:17px; pointer-events:none; }
.al-field .toggle-pass { position:absolute; right:14px; top:50%; transform:translateY(-50%); color:#b0aab8; cursor:pointer; font-size:17px; }
.al-field .form-control { width:100%; padding:13px 42px; border:1.5px solid #e4e4eb; border-radius:12px; font-size:14px; background:#fff; outline:none; transition:.2s; box-sizing:border-box; }
.al-field .form-control:focus { border-color:var(--role-accent); box-shadow:0 0 0 3px rgba(var(--role-accent-rgb),.12); }

.strength-bar { height:4px; border-radius:4px; background:#eee; margin-top:6px; overflow:hidden; }
.strength-fill { height:100%; border-radius:4px; transition:.3s; width:0; }

.btn-al-submit { width:100%; padding:14px; border-radius:12px; background:linear-gradient(135deg,var(--role-accent),var(--role-accent-light)); border:none; color:#fff; font-size:15px; font-weight:700; cursor:pointer; box-shadow:0 6px 20px rgba(var(--role-accent-rgb),.35); transition:.2s; display:flex; align-items:center; justify-content:center; gap:8px; margin-top:8px; }
.btn-al-submit:hover { opacity:.92; transform:translateY(-1px); }

.al-alert { border-radius:12px; padding:12px 16px; font-size:13px; margin-bottom:18px; display:flex; align-items:flex-start; gap:10px; }
.al-alert-danger { background:#fdeaea; color:#c0392b; border:1px solid #f5c6c6; }

.al-resend { text-align:center; margin-top:14px; font-size:13px; color:#8a859c; }
.al-resend a { color:var(--role-accent); font-weight:700; text-decoration:none; }
.al-resend a:hover { text-decoration:underline; }

@media (max-width:768px) { .al-panel { display:none; } .al-form-side { padding:32px 16px; } }
</style>
@endsection

@section('content')
<div class="al-wrapper">

    <div class="al-panel">
        <div class="al-panel-icon"><i class="ri ri-shield-check-line"></i></div>
        <div class="al-panel-title">Verify & Reset</div>
        <div class="al-panel-sub">Enter the code sent to your email and set a new password.</div>

        <div class="al-step" style="opacity:.5;">
            <div class="al-step-num">✓</div>
            <div class="al-step-text">Email submitted</div>
        </div>
        <div class="al-step">
            <div class="al-step-num">2</div>
            <div class="al-step-text">Enter the 6-digit code</div>
        </div>
        <div class="al-step" style="opacity:.4;">
            <div class="al-step-num">3</div>
            <div class="al-step-text">Set your new password</div>
        </div>
    </div>

    <div class="al-form-side">
        <div class="al-form-box">

            <div class="al-brand">
                <div class="al-brand-dot"><i class="ri ri-building-4-line"></i></div>
                <div class="al-brand-name">{{ config('variables.templateName') }}</div>
            </div>

            <h4 class="al-heading">Reset Password</h4>
            <p class="al-sub">Code sent to:</p>
            <div class="al-email-chip">
                <i class="ri ri-mail-line"></i>
                {{ $email }}
            </div>

            @if($errors->any())
            <div class="al-alert al-alert-danger">
                <i class="ri ri-error-warning-line" style="font-size:18px;flex-shrink:0;"></i>
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
            @endif

            <form action="{{ route('superadmin.reset-password.save') }}" method="POST" id="resetForm">
                @csrf

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#3b3551;margin-bottom:10px;">Verification Code</label>
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

                <div class="al-field">
                    <label>New Password</label>
                    <div class="input-wrap">
                        <i class="ri ri-lock-line input-icon"></i>
                        <input type="password" class="form-control" name="password" id="passInput"
                               placeholder="••••••••" autocomplete="new-password">
                        <i class="ri ri-eye-off-line toggle-pass" id="togglePass1"></i>
                    </div>
                    <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                </div>

                <div class="al-field">
                    <label>Confirm Password</label>
                    <div class="input-wrap">
                        <i class="ri ri-lock-check-line input-icon"></i>
                        <input type="password" class="form-control" name="password_confirmation" id="confirmInput"
                               placeholder="••••••••" autocomplete="new-password">
                        <i class="ri ri-eye-off-line toggle-pass" id="togglePass2"></i>
                    </div>
                </div>

                <button type="submit" class="btn-al-submit">
                    <i class="ri ri-shield-check-line"></i>
                    Reset Password
                </button>
            </form>

            <div class="al-resend">
                Didn't receive the code?
                <a href="{{ route('superadmin.forgot-password') }}">Request a new one</a>
            </div>

        </div>
    </div>

</div>

<script>
const digits    = document.querySelectorAll('.otp-digit');
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

document.getElementById('passInput').addEventListener('input', function () {
    const v = this.value;
    const s = [v.length >= 8, /[A-Z]/.test(v), /[0-9]/.test(v), /[^A-Za-z0-9]/.test(v)].filter(Boolean).length;
    const fill = document.getElementById('strengthFill');
    fill.style.width = (s * 25) + '%';
    fill.style.background = ['', '#ea5455', '#ff9f43', '#28c76f', '#00cfe8'][s] || '#eee';
});
</script>
@endsection
