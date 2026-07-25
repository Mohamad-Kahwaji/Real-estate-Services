@extends('layouts/blankLayout')

@section('title', 'Super Admin Registration')

@section('page-style')
<style>
* { box-sizing: border-box; }
body { margin: 0; padding: 0; }

.al-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: stretch;
    background: #f5f5f9;
}

/* Left panel */
.al-panel {
    width: 42%;
    background: linear-gradient(145deg, var(--role-accent) 0%, var(--role-accent-light) 60%, #c3c5ff 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 48px;
    position: relative;
    overflow: hidden;
}

.al-panel::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 280px; height: 280px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}

.al-panel::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -60px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}

.al-panel-icon {
    width: 90px; height: 90px;
    border-radius: 26px;
    background: rgba(255,255,255,.2);
    backdrop-filter: blur(10px);
    display: flex; align-items: center; justify-content: center;
    font-size: 40px; color: #fff;
    margin-bottom: 32px;
    position: relative; z-index: 1;
}

.al-panel-title {
    font-size: 30px; font-weight: 800;
    color: #fff; text-align: center;
    margin-bottom: 14px; line-height: 1.2;
    position: relative; z-index: 1;
}

.al-panel-sub {
    font-size: 15px; color: rgba(255,255,255,.82);
    text-align: center; line-height: 1.6;
    position: relative; z-index: 1;
}

.al-badge {
    margin-top: 40px;
    background: rgba(255,255,255,.18);
    backdrop-filter: blur(8px);
    border-radius: 16px;
    padding: 18px 28px;
    display: flex; align-items: center; gap: 14px;
    position: relative; z-index: 1;
}

.al-badge-icon {
    width: 44px; height: 44px;
    background: rgba(255,255,255,.25);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #fff; flex-shrink: 0;
}

.al-badge-text { color: #fff; }
.al-badge-text strong { display: block; font-size: 14px; font-weight: 700; }
.al-badge-text span   { font-size: 12px; opacity: .8; }

/* Right form side */
.al-form-side {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 24px;
}

.al-form-box {
    width: 100%;
    max-width: 420px;
}

.al-brand {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 40px;
}

.al-brand-dot {
    width: 42px; height: 42px;
    background: linear-gradient(135deg,var(--role-accent),var(--role-accent-light));
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #fff;
}

.al-brand-name { font-size: 20px; font-weight: 800; color: #3b3551; }

.al-heading { font-size: 24px; font-weight: 800; color: #3b3551; margin-bottom: 6px; }
.al-sub     { font-size: 14px; color: #8a859c; margin-bottom: 32px; }

/* Inputs */
.al-field { margin-bottom: 20px; }

.al-field label {
    display: block;
    font-size: 13px; font-weight: 600;
    color: #3b3551; margin-bottom: 7px;
}

.al-field .form-control {
    border-radius: 12px;
    border: 1.5px solid #e4e4eb;
    padding: 13px 16px;
    font-size: 14px;
    background: #fff;
    transition: border-color .2s, box-shadow .2s;
    width: 100%;
}

.al-field .form-control:focus {
    border-color: var(--role-accent);
    box-shadow: 0 0 0 3px rgba(var(--role-accent-rgb),.12);
    outline: none;
}

.al-field .input-group {
    display: flex;
    align-items: stretch;
}

.al-field .input-group .form-control {
    flex: 1;
    min-width: 0;
    border-right: none;
    border-radius: 12px 0 0 12px;
}

.al-field .input-group-text {
    background: #fff;
    border: 1.5px solid #e4e4eb;
    border-left: none;
    border-radius: 0 12px 12px 0;
    cursor: pointer;
    color: #8a859c;
    padding: 0 14px;
    transition: color .2s;
}

.al-field .input-group-text:hover { color: var(--role-accent); }
.al-field .input-group:focus-within .form-control {
    border-color: var(--role-accent);
    box-shadow: 0 0 0 3px rgba(var(--role-accent-rgb),.12);
}
.al-field .input-group:focus-within .input-group-text { border-color: var(--role-accent); }

.btn-al-login {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    background: linear-gradient(135deg,var(--role-accent),var(--role-accent-light));
    border: none;
    color: #fff;
    font-size: 15px; font-weight: 700;
    letter-spacing: .3px;
    cursor: pointer;
    transition: opacity .2s, transform .15s;
    box-shadow: 0 6px 20px rgba(var(--role-accent-rgb),.35);
}

.btn-al-login:hover { opacity: .92; transform: translateY(-1px); }
.btn-al-login:active { transform: translateY(0); }

.al-back {
    display: flex; justify-content: center;
    margin-top: 22px;
}

.al-back a {
    font-size: 13px; font-weight: 600; color: var(--role-accent);
    text-decoration: none;
}
.al-back a:hover { text-decoration: underline; }

/* Alert */
.al-alert {
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 13px;
    margin-bottom: 20px;
    display: flex; align-items: flex-start; gap: 10px;
}

.al-alert-danger  { background: #fdeaea; color: #c0392b; border: 1px solid #f5c6c6; }
.al-alert-success { background: #e8f8ef; color: #1a7a45; border: 1px solid #c3e9d4; }

@media (max-width: 768px) {
    .al-panel { display: none; }
    .al-form-side { padding: 32px 16px; }
}
</style>
@endsection

@section('content')
<div class="al-wrapper">

    {{-- Left Gradient Panel --}}
    <div class="al-panel">
        <div class="al-panel-icon">
            <i class="ri ri-vip-crown-2-line"></i>
        </div>
        <div class="al-panel-title">Super Admin Portal</div>
        <div class="al-panel-sub">
            One-time setup — create the platform's first and only super administrator account.
        </div>

        <div class="al-badge">
            <div class="al-badge-icon"><i class="ri ri-shield-star-line"></i></div>
            <div class="al-badge-text">
                <strong>Highest Access Level</strong>
                <span>Complete platform authority</span>
            </div>
        </div>

        <div class="al-badge mt-3">
            <div class="al-badge-icon"><i class="ri ri-lock-2-line"></i></div>
            <div class="al-badge-text">
                <strong>Bootstrap Only</strong>
                <span>Closes automatically after first use</span>
            </div>
        </div>
    </div>

    {{-- Right Form Side --}}
    <div class="al-form-side">
        <div class="al-form-box">

            {{-- Brand --}}
            <div class="al-brand">
                <div class="al-brand-dot"><i class="ri ri-building-4-line"></i></div>
                <div class="al-brand-name">{{ config('variables.templateName') }}</div>
            </div>

            <h4 class="al-heading">Create Super Admin</h4>
            <p class="al-sub">This form only works once, before any super admin exists</p>

            {{-- Alerts --}}
            @if($errors->any())
                <div class="al-alert al-alert-danger">
                    <i class="ri ri-error-warning-line mt-1"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('registersa.store') }}" method="POST">
                @csrf

                <div class="al-field">
                    <label for="name">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                           placeholder="Your full name"
                           value="{{ old('name') }}" autofocus required>
                </div>

                <div class="al-field">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="superadmin@example.com"
                           value="{{ old('email') }}" required>
                </div>

                <div class="al-field">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Create a password" required>
                        <span class="input-group-text" onclick="togglePass('password','passIcon1')">
                            <i class="ri ri-eye-off-line" id="passIcon1"></i>
                        </span>
                    </div>
                </div>

                <div class="al-field">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                               placeholder="Repeat the password" required>
                        <span class="input-group-text" onclick="togglePass('password_confirmation','passIcon2')">
                            <i class="ri ri-eye-off-line" id="passIcon2"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-al-login">
                    <i class="ri ri-user-add-line me-2"></i>Create Super Admin Account
                </button>

            </form>

            <div class="al-back">
                <a href="{{ route('loginsa.create') }}">Back to login</a>
            </div>

        </div>
    </div>

</div>

<script>
function togglePass(inputId, iconId) {
    var inp  = document.getElementById(inputId);
    var icon = document.getElementById(iconId);
    if (inp.type === 'password') {
        inp.type       = 'text';
        icon.className = 'ri ri-eye-line';
    } else {
        inp.type       = 'password';
        icon.className = 'ri ri-eye-off-line';
    }
}
</script>
@endsection
