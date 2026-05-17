@extends('layouts/blankLayout')
@section('title', __('app.register_title'))

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
.auth-brand::before{
    content:'';position:absolute;top:-80px;right:-80px;
    width:320px;height:320px;border-radius:50%;background:rgba(255,255,255,.07);
}
.auth-brand::after{
    content:'';position:absolute;bottom:-100px;left:-60px;
    width:280px;height:280px;border-radius:50%;background:rgba(255,255,255,.06);
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
.auth-step{
    display:flex;align-items:center;gap:14px;margin-bottom:20px;
    width:100%;max-width:290px;
}
.auth-step-num{
    width:36px;height:36px;border-radius:50%;
    background:rgba(255,255,255,.2);
    display:flex;align-items:center;justify-content:center;
    font-size:14px;font-weight:800;flex-shrink:0;
}
.auth-step-text{font-size:13px;font-weight:600;opacity:.9;line-height:1.4;}

.auth-form-panel{
    flex:1;display:flex;align-items:center;justify-content:center;
    background:#f8f9fc;padding:40px 24px;
}
.auth-form-box{
    background:#fff;border-radius:24px;padding:44px 44px;
    width:100%;max-width:440px;
    box-shadow:0 8px 40px rgba(105,108,255,.1);
}
.logo-wrap{display:flex;align-items:center;gap:12px;margin-bottom:28px;}
.logo-icon{
    width:42px;height:42px;border-radius:12px;
    background:linear-gradient(135deg,#696cff,#9c9eff);
    display:flex;align-items:center;justify-content:center;font-size:20px;
}
.logo-name{font-size:18px;font-weight:800;color:#312d4b;}
.auth-form-box h2{font-size:22px;font-weight:800;color:#312d4b;margin:0 0 6px;}
.auth-form-box .sub{font-size:14px;color:#97939e;margin:0 0 24px;}

.form-field{margin-bottom:16px;}
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

.strength-bar{height:4px;border-radius:4px;margin-top:8px;background:#ece9f1;overflow:hidden;}
.strength-bar-fill{height:100%;border-radius:4px;width:0;transition:.3s;}

.btn-register{
    width:100%;padding:14px;border-radius:13px;border:none;
    background:linear-gradient(135deg,#696cff,#5f61e6);
    color:#fff;font-size:15px;font-weight:700;cursor:pointer;
    box-shadow:0 6px 20px rgba(105,108,255,.4);
    transition:.2s;display:flex;align-items:center;justify-content:center;gap:8px;
    margin-top:6px;
}
.btn-register:hover{transform:translateY(-1px);box-shadow:0 10px 28px rgba(105,108,255,.45);}

.auth-switch{text-align:center;font-size:13px;color:#97939e;margin-top:18px;}
.auth-switch a{color:#696cff;font-weight:700;text-decoration:none;}
.auth-switch a:hover{text-decoration:underline;}

.alert-err{
    background:#fdeaea;border:1px solid rgba(234,84,85,.2);border-radius:12px;
    padding:12px 16px;font-size:13px;color:#a53030;margin-bottom:18px;
    display:flex;align-items:flex-start;gap:10px;
}
.form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:12px;}

@media(max-width:768px){
    .auth-brand{display:none;}
    .auth-form-box{padding:32px 24px;}
    .form-row-2{grid-template-columns:1fr;}
}
</style>

<div class="auth-wrap">

    {{-- ─── Left Brand Panel ─── --}}
    <div class="auth-brand">
        <div class="auth-brand-logo">🏠</div>
        <h1>{{ __('app.join_today') }}</h1>
        <p>{{ __('app.register_subtitle') }}</p>

        <div class="auth-step">
            <div class="auth-step-num">1</div>
            <div class="auth-step-text">{{ __('app.step_create') }}</div>
        </div>
        <div class="auth-step">
            <div class="auth-step-num">2</div>
            <div class="auth-step-text">{{ __('app.step_verify') }}</div>
        </div>
        <div class="auth-step">
            <div class="auth-step-num">3</div>
            <div class="auth-step-text">{{ __('app.step_browse') }}</div>
        </div>
        <div class="auth-step">
            <div class="auth-step-num">4</div>
            <div class="auth-step-text">{{ __('app.step_add') }}</div>
        </div>
    </div>

    {{-- ─── Right Form Panel ─── --}}
    <div class="auth-form-panel">
        <div class="auth-form-box">

            <div class="logo-wrap">
                <div class="logo-icon">🏠</div>
                <div class="logo-name">{{ config('variables.templateName', 'RealEstate') }}</div>
            </div>

            <h2>{{ __('app.create_new_account') }}</h2>
            <p class="sub">{{ __('app.enter_data') }}</p>

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

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                {{-- Name --}}
                <div class="form-field">
                    <label>{{ __('app.full_name') }}</label>
                    <div class="input-wrap">
                        <i class="ri ri-user-3-line input-icon"></i>
                        <input type="text" name="name" placeholder="{{ __('app.full_name') }}" value="{{ old('name') }}" autofocus>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="form-field">
                    <label>{{ __('app.whatsapp_phone') }}</label>
                    <div class="input-wrap">
                        <i class="ri ri-phone-line input-icon"></i>
                        <input type="text" name="phone" placeholder="09XXXXXXXX" value="{{ old('phone') }}">
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-field">
                    <label>{{ __('app.password') }}</label>
                    <div class="input-wrap">
                        <i class="ri ri-lock-line input-icon"></i>
                        <input type="password" name="password" id="passInput"
                               placeholder="{{ __('app.password_hint') }}"
                               oninput="checkStrength(this.value)">
                        <i class="ri ri-eye-off-line toggle-pass" id="togglePass"></i>
                    </div>
                    <div class="strength-bar"><div class="strength-bar-fill" id="strengthFill"></div></div>
                </div>

                {{-- Confirm Password --}}
                <div class="form-field">
                    <label>{{ __('app.confirm_password') }}</label>
                    <div class="input-wrap">
                        <i class="ri ri-lock-2-line input-icon"></i>
                        <input type="password" name="password_confirmation"
                               placeholder="{{ __('app.confirm_password_ph') }}">
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="ri ri-user-add-line"></i>
                    {{ __('app.create_account_btn') }}
                </button>
            </form>

            <p class="auth-switch">
                {{ __('app.already_have_account') }} <a href="{{ route('login') }}">{{ __('app.sign_in_link') }}</a>
            </p>

        </div>
    </div>
</div>

<script>
// Password toggle
document.getElementById('togglePass').addEventListener('click', function () {
    const inp = document.getElementById('passInput');
    const isPass = inp.type === 'password';
    inp.type = isPass ? 'text' : 'password';
    this.className = isPass ? 'ri ri-eye-line toggle-pass' : 'ri ri-eye-off-line toggle-pass';
});

// Password strength
function checkStrength(val) {
    const fill = document.getElementById('strengthFill');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['#ea5455','#ff9f43','#00cfe8','#28c76f'];
    const widths  = ['25%','50%','75%','100%'];
    fill.style.width  = score ? widths[score - 1] : '0';
    fill.style.background = score ? colors[score - 1] : 'transparent';
}
</script>
@endsection
