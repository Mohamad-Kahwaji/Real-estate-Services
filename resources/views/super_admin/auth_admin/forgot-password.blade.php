@extends('layouts/blankLayout')
@section('title', 'Admin — Forgot Password')

@section('page-style')
<style>
* { box-sizing: border-box; }
body { margin: 0; padding: 0; }

.al-wrapper { min-height: 100vh; display: flex; align-items: stretch; background: #f5f5f9; }

.al-panel {
    width: 42%;
    background: linear-gradient(145deg, #696cff 0%, #9c9eff 60%, #c3c5ff 100%);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 60px 48px; position: relative; overflow: hidden;
}
.al-panel::before { content:''; position:absolute; top:-80px; right:-80px; width:280px; height:280px; border-radius:50%; background:rgba(255,255,255,.08); }
.al-panel::after  { content:''; position:absolute; bottom:-60px; left:-60px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,.06); }

.al-panel-icon { width:90px; height:90px; border-radius:26px; background:rgba(255,255,255,.2); backdrop-filter:blur(10px); display:flex; align-items:center; justify-content:center; font-size:40px; color:#fff; margin-bottom:32px; position:relative; z-index:1; }
.al-panel-title { font-size:28px; font-weight:800; color:#fff; text-align:center; margin-bottom:14px; line-height:1.2; position:relative; z-index:1; }
.al-panel-sub { font-size:15px; color:rgba(255,255,255,.82); text-align:center; line-height:1.6; position:relative; z-index:1; max-width:280px; }

.al-step { margin-top:32px; background:rgba(255,255,255,.18); backdrop-filter:blur(8px); border-radius:16px; padding:16px 24px; display:flex; align-items:center; gap:14px; width:100%; max-width:290px; position:relative; z-index:1; }
.al-step-num { width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,.28); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:800; color:#fff; flex-shrink:0; }
.al-step-text { font-size:13px; font-weight:600; color:#fff; opacity:.9; }

.al-form-side { flex:1; display:flex; align-items:center; justify-content:center; padding:40px 24px; }
.al-form-box { width:100%; max-width:420px; }

.al-brand { display:flex; align-items:center; gap:12px; margin-bottom:36px; }
.al-brand-dot { width:42px; height:42px; background:linear-gradient(135deg,#696cff,#9c9eff); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; color:#fff; }
.al-brand-name { font-size:20px; font-weight:800; color:#3b3551; }

.al-icon-box { width:70px; height:70px; border-radius:20px; background:linear-gradient(135deg,#eef0ff,#f0f0ff); display:flex; align-items:center; justify-content:center; font-size:30px; margin:0 auto 24px; }

.al-heading { font-size:22px; font-weight:800; color:#3b3551; margin-bottom:6px; text-align:center; }
.al-sub { font-size:14px; color:#8a859c; margin-bottom:28px; text-align:center; line-height:1.6; }

.al-field { margin-bottom:20px; }
.al-field label { display:block; font-size:13px; font-weight:600; color:#3b3551; margin-bottom:7px; }
.al-field .input-wrap { position:relative; }
.al-field .input-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#b0aab8; font-size:17px; pointer-events:none; }
.al-field .form-control { width:100%; padding:13px 14px 13px 42px; border:1.5px solid #e4e4eb; border-radius:12px; font-size:14px; background:#fff; outline:none; transition:.2s; box-sizing:border-box; }
.al-field .form-control:focus { border-color:#696cff; box-shadow:0 0 0 3px rgba(105,108,255,.12); }

.btn-al-submit { width:100%; padding:14px; border-radius:12px; background:linear-gradient(135deg,#696cff,#9c9eff); border:none; color:#fff; font-size:15px; font-weight:700; cursor:pointer; box-shadow:0 6px 20px rgba(105,108,255,.35); transition:.2s; display:flex; align-items:center; justify-content:center; gap:8px; }
.btn-al-submit:hover { opacity:.92; transform:translateY(-1px); }

.al-alert { border-radius:12px; padding:12px 16px; font-size:13px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px; }
.al-alert-danger { background:#fdeaea; color:#c0392b; border:1px solid #f5c6c6; }
.al-alert-success { background:#e8f8ef; color:#1a7a45; border:1px solid #c3e9d4; }

.al-switch { text-align:center; font-size:13px; color:#8a859c; margin-top:20px; }
.al-switch a { color:#696cff; font-weight:700; text-decoration:none; }
.al-switch a:hover { text-decoration:underline; }

@media (max-width:768px) { .al-panel { display:none; } .al-form-side { padding:32px 16px; } }
</style>
@endsection

@section('content')
<div class="al-wrapper">

    <div class="al-panel">
        <div class="al-panel-icon"><i class="ri ri-shield-keyhole-line"></i></div>
        <div class="al-panel-title">Admin Password Reset</div>
        <div class="al-panel-sub">Verify your identity and set a new secure password in minutes.</div>

        <div class="al-step">
            <div class="al-step-num">1</div>
            <div class="al-step-text">Enter your admin email address</div>
        </div>
        <div class="al-step" style="opacity:.5;">
            <div class="al-step-num">2</div>
            <div class="al-step-text">Enter the 6-digit code</div>
        </div>
        <div class="al-step" style="opacity:.3;">
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

            <div class="al-icon-box"><i class="ri ri-lock-unlock-line" style="color:#696cff;"></i></div>

            <h4 class="al-heading">Forgot Password?</h4>
            <p class="al-sub">Enter your admin email and we'll send you a verification code.</p>

            @if($errors->any())
            <div class="al-alert al-alert-danger">
                <i class="ri ri-error-warning-line mt-1"></i>
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
            @endif

            @if(session('status'))
            <div class="al-alert al-alert-success">
                <i class="ri ri-checkbox-circle-line mt-1"></i>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            <form action="{{ route('admin.forgot-password.send') }}" method="POST">
                @csrf
                <div class="al-field">
                    <label>Email Address</label>
                    <div class="input-wrap">
                        <i class="ri ri-mail-line input-icon"></i>
                        <input type="email" class="form-control" name="email"
                               placeholder="admin@example.com"
                               value="{{ old('email') }}" autofocus>
                    </div>
                </div>

                <button type="submit" class="btn-al-submit">
                    <i class="ri ri-send-plane-line"></i>
                    Send Verification Code
                </button>
            </form>

            <div class="al-switch">
                Remembered your password?
                <a href="{{ route('logina.create') }}">Back to Sign In</a>
            </div>

        </div>
    </div>

</div>
@endsection
