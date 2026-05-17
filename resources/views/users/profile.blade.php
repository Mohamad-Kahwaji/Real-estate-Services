@extends('layouts/contentNavbarLayout')
@section('title', __('app.profile_title'))

@section('content')
<style>
.profile-hero {
    background: linear-gradient(135deg,#696cff,#5f61e6);
    border-radius: 20px; padding: 32px 36px;
    color: #fff; display: flex; align-items: center; gap: 24px;
    margin-bottom: 28px; position: relative; overflow: hidden;
}
.profile-hero::before {
    content:''; position:absolute; top:-60px; right:-60px;
    width:200px; height:200px; border-radius:50%;
    background:rgba(255,255,255,.08);
}
.profile-avatar {
    width:80px; height:80px; border-radius:50%;
    background:rgba(255,255,255,.25); border:3px solid rgba(255,255,255,.4);
    display:flex; align-items:center; justify-content:center;
    font-size:32px; font-weight:800; flex-shrink:0; position:relative; z-index:1;
}
.profile-hero-info h3 { font-size:20px; font-weight:800; margin:0 0 4px; }
.profile-hero-info p  { font-size:13px; opacity:.85; margin:0; }

.pf-card {
    background:#fff; border-radius:20px;
    border:1px solid #f0eef8;
    box-shadow:0 4px 24px rgba(105,108,255,.08);
    padding:28px 32px; margin-bottom:24px;
}
.pf-card-title {
    font-size:15px; font-weight:800; color:#312d4b;
    display:flex; align-items:center; gap:10px;
    margin-bottom:22px; padding-bottom:14px;
    border-bottom:1px solid #f5f4f8;
}
.pf-card-title i { color:#696cff; font-size:18px; }

.pf-field { margin-bottom:18px; }
.pf-field label {
    display:block; font-size:12px; font-weight:700;
    color:#97939e; text-transform:uppercase; letter-spacing:.06em;
    margin-bottom:8px;
}
.pf-field input {
    width:100%; padding:13px 16px;
    border:1.5px solid #e5e2ec; border-radius:12px;
    font-size:14px; color:#312d4b; background:#faf9fc;
    outline:none; transition:.2s;
}
.pf-field input:focus {
    border-color:#696cff; background:#fff;
    box-shadow:0 0 0 4px rgba(105,108,255,.1);
}
.pf-field .hint { font-size:11px; color:#b0aab8; margin-top:5px; }

.btn-save {
    padding:12px 28px; border-radius:12px; border:none;
    background:linear-gradient(135deg,#696cff,#5f61e6);
    color:#fff; font-size:14px; font-weight:700; cursor:pointer;
    box-shadow:0 4px 16px rgba(105,108,255,.35);
    transition:.2s; display:inline-flex; align-items:center; gap:8px;
}
.btn-save:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(105,108,255,.4); }

.pf-nav { display:flex; gap:4px; margin-bottom:24px; }
.pf-nav-item {
    padding:9px 18px; border-radius:12px; font-size:13px; font-weight:700;
    text-decoration:none; color:#97939e;
    transition:.15s; display:flex; align-items:center; gap:7px;
}
.pf-nav-item.active, .pf-nav-item:hover { background:#eef0ff; color:#696cff; }

.biz-card {
    background:#fff; border-radius:16px;
    border:1.5px solid #f0eef8;
    padding:18px 20px;
    display:flex; align-items:center; gap:16px;
    margin-bottom:12px; transition:.2s;
}
.biz-card:hover { border-color:#d4d5ff; box-shadow:0 6px 20px rgba(105,108,255,.1); }
.biz-icon {
    width:48px; height:48px; border-radius:14px;
    background:#eef0ff; display:flex; align-items:center; justify-content:center;
    font-size:22px; flex-shrink:0;
}
.biz-name { font-size:14px; font-weight:800; color:#312d4b; }
.biz-sub  { font-size:12px; color:#97939e; }
</style>

{{-- Hero --}}
<div class="profile-hero">
    <div class="profile-avatar">
        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
    </div>
    <div class="profile-hero-info" style="position:relative;z-index:1;">
        <h3>{{ $user->name }}</h3>
        <p>{{ $user->phone }} • {{ __('app.member_since') }} {{ $user->created_at?->format('Y') }}</p>
    </div>
</div>

{{-- Nav --}}
<div class="pf-nav">
    <a href="javascript:void(0);" class="pf-nav-item active">
        <i class="ri ri-user-3-line"></i> {{ __('app.profile') }}
    </a>
    <a href="{{ route('user.business.create') }}" class="pf-nav-item">
        <i class="ri ri-building-4-line"></i> {{ __('app.add_new_service') }}
    </a>
    <a href="{{ route('user.service.create') }}" class="pf-nav-item">
        <i class="ri ri-add-circle-line"></i> {{ __('app.add_service') }}
    </a>
</div>

<div class="row g-4">
    {{-- Left: form --}}
    <div class="col-12 col-lg-7">
        <div class="pf-card">
            <div class="pf-card-title">
                <i class="ri ri-edit-line"></i> {{ __('app.edit_personal_info') }}
            </div>

            @if(session('success'))
            <div style="background:#e8faf0;border-radius:12px;padding:12px 16px;
                        font-size:13px;color:#1f7a36;margin-bottom:18px;
                        display:flex;align-items:center;gap:10px;">
                <i class="ri ri-checkbox-circle-line" style="font-size:18px;"></i>
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div style="background:#fdeaea;border-radius:12px;padding:12px 16px;
                        font-size:13px;color:#a53030;margin-bottom:18px;">
                @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="pf-field">
                            <label>{{ __('app.full_name_label') }}</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="{{ __('app.full_name') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="pf-field">
                            <label>{{ __('app.phone_label') }}</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="09XXXXXXXX">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="pf-field">
                            <label>{{ __('app.email_optional') }}</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="example@mail.com">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="pf-field">
                            <label>{{ __('app.new_password_label') }}</label>
                            <input type="password" name="password" placeholder="{{ __('app.leave_blank_password') }}">
                            <div class="hint">{{ __('app.password_hint') }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-2">
                    <button type="submit" class="btn-save">
                        <i class="ri ri-save-line"></i> {{ __('app.save_changes') }}
                    </button>
                    <button type="reset" style="
                        padding:12px 20px;border-radius:12px;border:1.5px solid #e5e2ec;
                        background:#fff;color:#97939e;font-size:14px;font-weight:700;cursor:pointer;
                    ">{{ __('app.reset') }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Right: account info + businesses --}}
    <div class="col-12 col-lg-5">
        {{-- Info card --}}
        <div class="pf-card" style="margin-bottom:20px;">
            <div class="pf-card-title"><i class="ri ri-information-line"></i> {{ __('app.account_info') }}</div>
            @foreach([
                ['ri-phone-line', __('app.phone'), $user->phone],
                ['ri-mail-line',  __('app.email'), $user->email ?? __('app.no_data')],
                ['ri-calendar-line', __('app.join_date'), $user->created_at?->format('d M Y')],
            ] as [$icon,$label,$val])
            <div style="display:flex;justify-content:space-between;align-items:center;
                        padding:10px 0;border-bottom:1px solid #f5f4f8;font-size:13px;">
                <span style="color:#97939e;display:flex;align-items:center;gap:8px;">
                    <i class="ri {{ $icon }}" style="color:#696cff;"></i>{{ $label }}
                </span>
                <span style="font-weight:700;color:#585164;">{{ $val }}</span>
            </div>
            @endforeach
        </div>

        {{-- Business accounts --}}
        <div class="pf-card">
            <div class="pf-card-title"><i class="ri ri-building-4-line"></i> {{ __('app.my_businesses') }}</div>
            @forelse($accounts as $account)
            <div class="biz-card">
                <div class="biz-icon">🏢</div>
                <div style="flex:1;min-width:0;">
                    <div class="biz-name">{{ $account->job_name_en ?? __('app.my_business') }}</div>
                    <div class="biz-sub">
                        {{ $account->city->name_en ?? '—' }} •
                        {{ $account->license_number ?? '—' }}
                    </div>
                </div>
                <div style="display:flex;gap:6px;">
                    <a href="{{ route('user.business.edit', $account->id) }}"
                       style="width:34px;height:34px;border-radius:10px;background:#eef0ff;
                              color:#696cff;display:flex;align-items:center;justify-content:center;
                              text-decoration:none;">
                        <i class="ri ri-edit-line"></i>
                    </a>
                    <form action="{{ route('user.business.destroy', $account->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit"
                                style="width:34px;height:34px;border-radius:10px;background:#fdeaea;
                                       color:#ea5455;border:none;cursor:pointer;
                                       display:flex;align-items:center;justify-content:center;">
                            <i class="ri ri-delete-bin-line"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:30px;color:#97939e;">
                <i class="ri ri-building-4-line" style="font-size:36px;display:block;margin-bottom:8px;"></i>
                <div style="font-size:13px;">{{ __('app.no_business_yet') }}</div>
                <a href="{{ route('user.business.create') }}"
                   style="display:inline-flex;align-items:center;gap:6px;margin-top:12px;
                          padding:9px 18px;border-radius:10px;
                          background:linear-gradient(135deg,#696cff,#5f61e6);
                          color:#fff;font-size:13px;font-weight:700;text-decoration:none;">
                    <i class="ri ri-add-line"></i> {{ __('app.create_business') }}
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
