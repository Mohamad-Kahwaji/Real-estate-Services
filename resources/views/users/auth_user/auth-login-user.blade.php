@extends('layouts/blankLayout')
@section('title', __('app.login_title'))

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<style>
html,body{height:100%;margin:0;padding:0;}
.auth-wrap{min-height:100vh;display:flex;font-family:'Inter',system-ui,sans-serif;}

/* ─── Left brand panel ─── */
.auth-brand{
    flex:0 0 42%;
    background:linear-gradient(145deg,#5f61e6 0%,#696cff 45%,#9c9eff 100%);
    display:flex;flex-direction:column;justify-content:center;align-items:center;
    padding:60px 52px;color:#fff;position:relative;overflow:hidden;
}
.auth-brand::before{
    content:'';position:absolute;top:-80px;right:-80px;
    width:320px;height:320px;border-radius:50%;
    background:rgba(255,255,255,.07);
}
.auth-brand::after{
    content:'';position:absolute;bottom:-100px;left:-60px;
    width:280px;height:280px;border-radius:50%;
    background:rgba(255,255,255,.06);
}
.auth-brand-logo{
    width:64px;height:64px;border-radius:18px;
    background:rgba(255,255,255,.2);backdrop-filter:blur(10px);
    display:flex;align-items:center;justify-content:center;
    font-size:30px;margin-bottom:28px;
    box-shadow:0 8px 32px rgba(0,0,0,.15);
}
.auth-brand h1{font-size:28px;font-weight:800;margin:0 0 10px;text-align:center;}
.auth-brand p{font-size:15px;opacity:.85;text-align:center;line-height:1.7;max-width:280px;margin:0 0 40px;}
.auth-feature{display:flex;align-items:center;gap:14px;margin-bottom:18px;width:100%;max-width:290px;}
.auth-feature-icon{
    width:40px;height:40px;border-radius:12px;
    background:rgba(255,255,255,.18);
    display:flex;align-items:center;justify-content:center;
    font-size:18px;flex-shrink:0;
}
.auth-feature-text{font-size:13px;font-weight:600;opacity:.9;}

/* ─── Right form panel ─── */
.auth-form-panel{
    flex:1;display:flex;align-items:center;justify-content:center;
    background:#f8f9fc;padding:40px 24px;
}
.auth-form-box{
    background:#fff;border-radius:24px;padding:48px 44px;
    width:100%;max-width:420px;
    box-shadow:0 8px 40px rgba(105,108,255,.1);
}
.auth-form-box .logo-wrap{display:flex;align-items:center;gap:12px;margin-bottom:32px;}
.auth-form-box .logo-icon{
    width:42px;height:42px;border-radius:12px;
    background:linear-gradient(135deg,#696cff,#9c9eff);
    display:flex;align-items:center;justify-content:center;font-size:20px;
}
.auth-form-box .logo-name{font-size:18px;font-weight:800;color:#312d4b;}
.auth-form-box h2{font-size:22px;font-weight:800;color:#312d4b;margin:0 0 6px;}
.auth-form-box .sub{font-size:14px;color:#97939e;margin:0 0 28px;}

.form-field{margin-bottom:18px;}
.form-field label{display:block;font-size:13px;font-weight:700;color:#585164;margin-bottom:8px;}
.form-field .input-wrap{position:relative;}
.form-field .input-icon{
    position:absolute;left:14px;top:50%;transform:translateY(-50%);
    color:#b0aab8;font-size:17px;pointer-events:none;
}
.form-field input{
    width:100%;padding:13px 14px 13px 42px;
    border:1.5px solid #e5e2ec;border-radius:12px;
    font-size:14px;color:#312d4b;background:#faf9fc;
    outline:none;transition:.2s;
}
.form-field input:focus{border-color:#696cff;background:#fff;box-shadow:0 0 0 4px rgba(105,108,255,.1);}
.toggle-pass{
    position:absolute;right:14px;top:50%;transform:translateY(-50%);
    color:#b0aab8;cursor:pointer;font-size:17px;
}

.form-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;}
.form-check-label{font-size:13px;color:#585164;}
.forgot-link{font-size:13px;color:#696cff;font-weight:600;text-decoration:none;}
.forgot-link:hover{text-decoration:underline;}

.btn-login{
    width:100%;padding:14px;border-radius:13px;border:none;
    background:linear-gradient(135deg,#696cff,#5f61e6);
    color:#fff;font-size:15px;font-weight:700;cursor:pointer;
    box-shadow:0 6px 20px rgba(105,108,255,.4);
    transition:.2s;display:flex;align-items:center;justify-content:center;gap:8px;
}
.btn-login:hover{transform:translateY(-1px);box-shadow:0 10px 28px rgba(105,108,255,.45);}
.btn-login:active{transform:translateY(0);}

.divider{display:flex;align-items:center;gap:12px;margin:22px 0;color:#c0baca;font-size:12px;}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:#ece9f1;}

.auth-switch{text-align:center;font-size:13px;color:#97939e;margin-top:20px;}
.auth-switch a{color:#696cff;font-weight:700;text-decoration:none;}
.auth-switch a:hover{text-decoration:underline;}

.alert-err{
    background:#fdeaea;border:1px solid rgba(234,84,85,.2);border-radius:12px;
    padding:12px 16px;font-size:13px;color:#a53030;margin-bottom:18px;
    display:flex;align-items:flex-start;gap:10px;
}

@media(max-width:768px){
    .auth-brand{display:none;}
    .auth-form-box{padding:36px 28px;}
}
</style>

<div class="auth-wrap">

    {{-- ─── Left Brand Panel ─── --}}
    <div class="auth-brand">
        <div class="auth-brand-logo">🏠</div>
        <h1>Real Estate<br>Services</h1>
        <p>{{ __('app.platform_tagline') }}</p>

        <div class="auth-feature">
            <div class="auth-feature-icon">🔍</div>
            <div class="auth-feature-text">{{ __('app.feature_browse') }}</div>
        </div>
        <div class="auth-feature">
            <div class="auth-feature-icon">📍</div>
            <div class="auth-feature-text">{{ __('app.feature_location') }}</div>
        </div>
        <div class="auth-feature">
            <div class="auth-feature-icon">💬</div>
            <div class="auth-feature-text">{{ __('app.feature_contact') }}</div>
        </div>
        <div class="auth-feature">
            <div class="auth-feature-icon">🔐</div>
            <div class="auth-feature-text">{{ __('app.feature_secure') }}</div>
        </div>
    </div>

    {{-- ─── Right Form Panel ─── --}}
    <div class="auth-form-panel">
        <div class="auth-form-box">

            <div class="logo-wrap">
                <div class="logo-icon">🏠</div>
                <div class="logo-name">{{ config('variables.templateName', 'RealEstate') }}</div>
            </div>

            <h2>{{ __('app.welcome_back') }}</h2>
            <p class="sub">{{ __('app.login_subtitle') }}</p>

            {{-- Error --}}
            @if($errors->any())
            <div class="alert-err">
                <i class="ri ri-error-warning-line" style="font-size:18px;flex-shrink:0;margin-top:1px;"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST">
                @csrf

                {{-- Phone --}}
                <div class="form-field">
                    <label>{{ __('app.phone_number') }}</label>
                    <div class="input-wrap">
                        <i class="ri ri-phone-line input-icon"></i>
                        <input type="text" name="phone"
                               placeholder="09XXXXXXXX"
                               value="{{ old('phone') }}"
                               autocomplete="username" autofocus>
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-field">
                    <label>{{ __('app.password') }}</label>
                    <div class="input-wrap">
                        <i class="ri ri-lock-line input-icon"></i>
                        <input type="password" name="password" id="passInput"
                               placeholder="••••••••" autocomplete="current-password">
                        <i class="ri ri-eye-off-line toggle-pass" id="togglePass"></i>
                    </div>
                </div>

                <div class="form-row">
                    <label class="form-check-label d-flex align-items-center gap-2">
                        <input type="checkbox" name="remember" class="form-check-input m-0">
                        {{ __('app.remember_me') }}
                    </label>
                    <a href="{{ route('forgot-password') }}" class="forgot-link">{{ __('app.forgot_password') }}</a>
                </div>

                <button type="submit" class="btn-login">
                    <i class="ri ri-login-circle-line"></i>
                    {{ __('app.sign_in') }}
                </button>
            </form>

            <p class="auth-switch">
                {{ __('app.new_user') }} <a href="{{ route('register.create') }}">{{ __('app.create_account_link') }}</a>
            </p>

        </div>
    </div>
</div>

<script>
document.getElementById('togglePass').addEventListener('click', function () {
    const inp = document.getElementById('passInput');
    const isPass = inp.type === 'password';
    inp.type = isPass ? 'text' : 'password';
    this.className = isPass ? 'ri ri-eye-line toggle-pass' : 'ri ri-eye-off-line toggle-pass';
});
</script>
@endsection
