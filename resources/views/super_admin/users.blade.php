@extends('layouts/contentNavbarLayout')
{{-- Users management page: displays all registered users with status stats and allows superadmin to suspend or activate accounts. --}}

@section('title', __('app.users'))

@section('page-style')
<style>
:root {
    --accent: var(--role-accent); --accent-soft: var(--role-accent-soft);
    --success: #28c76f; --success-soft: #e8f8ef;
    --warning: #ff9f43; --warning-soft: #fff4e5;
    --danger: #ea5455; --danger-soft: #fdeaea;
    --info: #00cfe8; --info-soft: #e3f8fc;
    --shadow: 0 4px 24px rgba(var(--role-accent-rgb),.10);
    --radius: 16px;
}
.pg-header h4 { font-size: 22px; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
.pg-header p  { font-size: 13px; color: #8592a3; margin: 0; }

.stat-card {
    background: #fff; border-radius: var(--radius);
    box-shadow: var(--shadow); padding: 20px 24px;
    display: flex; align-items: center; gap: 16px;
}
.stat-icon { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
.stat-label { font-size: 12px; color: #8592a3; font-weight: 600; }
.stat-value { font-size: 26px; font-weight: 800; line-height: 1; color: #1e293b; }

.table-card { background: #fff; border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
.table-card-header {
    padding: 18px 24px; border-bottom: 1px solid #f0eef8;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
}
.table-card-header h6 { font-weight: 700; color: #1e293b; margin: 0; }

.search-box { position: relative; max-width: 240px; }
.search-box input {
    border-radius: 10px; border: 1.5px solid #e4e4eb;
    padding: 8px 14px 8px 36px; font-size: 13px;
    background: #fafbff; width: 100%;
}
.search-box input:focus { border-color: var(--role-accent); outline: none; box-shadow: 0 0 0 3px rgba(var(--role-accent-rgb),.1); }
.search-box i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #8592a3; font-size: 16px; }

.table { margin: 0; }
.table thead th {
    background: #fafbff; font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .7px;
    color: #8592a3; border-bottom: 1px solid #f0eef8; padding: 14px 20px;
}
.table tbody td { padding: 14px 20px; vertical-align: middle; border-bottom: 1px solid #f7f7f9; font-size: 14px; color: #1e293b; }
.table tbody tr:last-child td { border-bottom: none; }
.table tbody tr:hover td { background: #fafbff; }

.user-ava {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, var(--role-accent), var(--role-accent-light));
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800; color: #fff; flex-shrink: 0;
}
.user-ava.suspended { background: linear-gradient(135deg, #b0aab8, #c8ccda); }

.badge-pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
.badge-accent   { background: var(--accent-soft); color: var(--accent); }
.badge-success  { background: var(--success-soft); color: var(--success); }
.badge-warning  { background: var(--warning-soft); color: var(--warning); }
.badge-danger   { background: var(--danger-soft); color: var(--danger); }
.badge-info     { background: var(--info-soft); color: var(--info); }
.badge-gray     { background: #f1f0f4; color: #585164; }

.btn-toggle-suspend {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 700;
    border: none; cursor: pointer; transition: background .18s, transform .1s;
}
.btn-toggle-suspend:active { transform: scale(.97); }
.btn-suspend  { background: var(--danger-soft); color: var(--danger); }
.btn-suspend:hover  { background: #f8c0c0; }
.btn-activate { background: var(--success-soft); color: var(--success); }
.btn-activate:hover { background: #b8f0d0; }

.empty-state { padding: 60px 20px; text-align: center; }
.empty-state i { font-size: 48px; color: #c8ccda; display: block; margin-bottom: 14px; }
.empty-state p { color: #8592a3; font-weight: 600; margin: 0; }

.al-alert { border-radius: 12px; padding: 12px 16px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.al-alert-success { background: #e8f8ef; color: #1a7a45; border: 1px solid #c3e9d4; }
.al-alert-error   { background: #fdeaea; color: #b91c1c; border: 1px solid #f5c6c6; }

tr.row-suspended td { opacity: .65; }
</style>
@endsection

@section('content')

{{-- Header --}}
<div class="pg-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>{{ __('app.users') }}</h4>
        <p>{{ __('app.users_desc') }}</p>
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="al-alert al-alert-success mb-4">
        <i class="ri ri-checkbox-circle-line"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="al-alert al-alert-error mb-4">
        <i class="ri ri-error-warning-line"></i> {{ session('error') }}
    </div>
@endif

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e3f8fc;">
                <i class="ri ri-group-line" style="color:#00cfe8;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.total_users') }}</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8ef;">
                <i class="ri ri-user-follow-line" style="color:#28c76f;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.active') }}</div>
                <div class="stat-value">{{ $stats['active'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdeaea;">
                <i class="ri ri-user-forbid-line" style="color:#ea5455;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.suspended') }}</div>
                <div class="stat-value">{{ $stats['suspended'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8ef;">
                <i class="ri ri-building-2-line" style="color:#28c76f;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.with_businesses') }}</div>
                <div class="stat-value">{{ $stats['with_biz'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="table-card">
    <div class="table-card-header">
        <h6><i class="ri ri-group-line me-2" style="color:var(--role-accent);"></i>{{ __('app.all_users') }}</h6>
        <form method="GET" action="{{ route('allindex.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
            <div class="search-box">
                <i class="ri ri-search-line"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_users') }}">
            </div>
            <select name="status" class="form-select form-select-sm" style="width:130px;border-radius:10px;">
                <option value="">{{ __('app.all_status') }}</option>
                <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>{{ __('app.active') }}</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>{{ __('app.suspended') }}</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">{{ __('app.search') }}</button>
            @if(request('search') || request('status'))
                <a href="{{ route('allindex.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('app.clear') }}</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table" id="usersTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('app.user') }}</th>
                    <th>{{ __('app.phone') }}</th>
                    <th>{{ __('app.businesses') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.joined') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr class="{{ ! $user->is_active ? 'row-suspended' : '' }}">
                    <td><span class="badge-pill badge-accent">{{ $user->id }}</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-ava {{ ! $user->is_active ? 'suspended' : '' }}">
                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;color:#1e293b;">{{ $user->name ?? '—' }}</div>
                                <div style="font-size:12px;color:#8592a3;">ID: {{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($user->phone)
                            <span class="badge-pill badge-info">
                                <i class="ri ri-phone-line"></i> {{ $user->phone }}
                            </span>
                        @else
                            <span style="color:#c8ccda;">—</span>
                        @endif
                    </td>
                    <td>
                        @php $bizCount = $user->businesses->count(); @endphp
                        @if($bizCount > 0)
                            <span class="badge-pill badge-success">
                                <i class="ri ri-building-2-line"></i> {{ $bizCount }} {{ $bizCount > 1 ? __('app.businesses') : __('app.business') }}
                            </span>
                        @else
                            <span class="badge-pill badge-gray">{{ __('app.no_data') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge-pill badge-success">
                                <i class="ri ri-checkbox-circle-line"></i> {{ __('app.active') }}
                            </span>
                        @else
                            <span class="badge-pill badge-danger">
                                <i class="ri ri-forbid-2-line"></i> {{ __('app.suspended') }}
                            </span>
                        @endif
                    </td>
                    <td style="color:#8592a3;font-size:13px;">
                        {{ $user->created_at?->format('M d, Y') ?? '—' }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('users.toggle-status', $user->id) }}"
                              onsubmit="return confirm('{{ $user->is_active ? __('app.suspend_confirm') : __('app.activate_confirm') }}')">
                            @csrf
                            @if($user->is_active)
                                <button type="submit" class="btn-toggle-suspend btn-suspend">
                                    <i class="ri ri-forbid-2-line"></i> {{ __('app.suspend') }}
                                </button>
                            @else
                                <button type="submit" class="btn-toggle-suspend btn-activate">
                                    <i class="ri ri-checkbox-circle-line"></i> {{ __('app.activate') }}
                                </button>
                            @endif
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="ri ri-group-line"></i>
                            <p>{{ __('app.no_users_found') }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="d-flex justify-content-center py-3">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
