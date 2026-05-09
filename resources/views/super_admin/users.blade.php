@extends('layouts/contentNavbarLayout')

@section('title', 'Users')

@section('page-style')
<style>
:root {
    --accent: #696cff; --accent-soft: #eef0ff;
    --success: #28c76f; --success-soft: #e8f8ef;
    --warning: #ff9f43; --warning-soft: #fff4e5;
    --danger: #ea5455; --danger-soft: #fdeaea;
    --info: #00cfe8; --info-soft: #e3f8fc;
    --shadow: 0 4px 24px rgba(105,108,255,.10);
    --radius: 16px;
}
.pg-header h4 { font-size: 22px; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
.pg-header p  { font-size: 13px; color: #8592a3; margin: 0; }

.stat-card {
    background: #fff; border-radius: var(--radius);
    box-shadow: var(--shadow); padding: 20px 24px;
    display: flex; align-items: center; gap: 16px;
}
.stat-icon {
    width: 50px; height: 50px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.stat-label { font-size: 12px; color: #8592a3; font-weight: 600; }
.stat-value { font-size: 26px; font-weight: 800; line-height: 1; color: #1e293b; }

.table-card { background: #fff; border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
.table-card-header {
    padding: 18px 24px; border-bottom: 1px solid #f0eef8;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
}
.table-card-header h6 { font-weight: 700; color: #1e293b; margin: 0; }

.search-box {
    position: relative; max-width: 240px;
}
.search-box input {
    border-radius: 10px; border: 1.5px solid #e4e4eb;
    padding: 8px 14px 8px 36px; font-size: 13px;
    background: #fafbff; width: 100%;
}
.search-box input:focus { border-color: #696cff; outline: none; box-shadow: 0 0 0 3px rgba(105,108,255,.1); }
.search-box i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #8592a3; font-size: 16px; }

.table { margin: 0; }
.table thead th {
    background: #fafbff; font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .7px;
    color: #8592a3; border-bottom: 1px solid #f0eef8; padding: 14px 20px;
}
.table tbody td {
    padding: 14px 20px; vertical-align: middle;
    border-bottom: 1px solid #f7f7f9; font-size: 14px; color: #1e293b;
}
.table tbody tr:last-child td { border-bottom: none; }
.table tbody tr:hover td { background: #fafbff; }

.user-ava {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800; color: #fff; flex-shrink: 0;
}

.badge-pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
.badge-accent   { background: var(--accent-soft); color: var(--accent); }
.badge-success  { background: var(--success-soft); color: var(--success); }
.badge-warning  { background: var(--warning-soft); color: var(--warning); }
.badge-info     { background: var(--info-soft); color: var(--info); }
.badge-gray     { background: #f1f0f4; color: #585164; }

.empty-state { padding: 60px 20px; text-align: center; }
.empty-state i { font-size: 48px; color: #c8ccda; display: block; margin-bottom: 14px; }
.empty-state p { color: #8592a3; font-weight: 600; margin: 0; }

.al-alert { border-radius: 12px; padding: 12px 16px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.al-alert-success { background: #e8f8ef; color: #1a7a45; border: 1px solid #c3e9d4; }
</style>
@endsection

@section('content')

{{-- Header --}}
<div class="pg-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>Users</h4>
        <p>View and manage all registered users</p>
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="al-alert al-alert-success mb-4">
        <i class="ri ri-checkbox-circle-line"></i> {{ session('success') }}
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
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ $users->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8ef;">
                <i class="ri ri-building-2-line" style="color:#28c76f;"></i>
            </div>
            <div>
                <div class="stat-label">With Businesses</div>
                <div class="stat-value">{{ $users->filter(fn($u) => $u->businesses->count() > 0)->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff4e5;">
                <i class="ri ri-user-line" style="color:#ff9f43;"></i>
            </div>
            <div>
                <div class="stat-label">No Business</div>
                <div class="stat-value">{{ $users->filter(fn($u) => $u->businesses->count() === 0)->count() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="table-card">
    <div class="table-card-header">
        <h6><i class="ri ri-group-line me-2" style="color:#696cff;"></i>All Users</h6>
        <div class="search-box">
            <i class="ri ri-search-line"></i>
            <input type="text" id="userSearch" placeholder="Search users..." oninput="filterUsers()">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table" id="usersTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Phone</th>
                    <th>Businesses</th>
                    <th>Joined</th>
                    <th>FCM Token</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <td><span class="badge-pill badge-accent">{{ $user->id }}</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-ava">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</div>
                            <div>
                                <div style="font-weight:700;color:#1e293b;">{{ $user->name ?? '—' }}</div>
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
                                <i class="ri ri-building-2-line"></i> {{ $bizCount }} business{{ $bizCount > 1 ? 'es' : '' }}
                            </span>
                        @else
                            <span class="badge-pill badge-gray">None</span>
                        @endif
                    </td>
                    <td style="color:#8592a3;font-size:13px;">
                        {{ $user->created_at?->format('M d, Y') ?? '—' }}
                    </td>
                    <td>
                        @if($user->fcm_token)
                            <span class="badge-pill badge-accent" title="{{ $user->fcm_token }}">
                                <i class="ri ri-notification-3-line"></i> Active
                            </span>
                        @else
                            <span style="color:#c8ccda;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="ri ri-group-line"></i>
                            <p>No users found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
function filterUsers() {
    const q = document.getElementById('userSearch').value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>
@endpush
