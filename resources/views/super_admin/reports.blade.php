@extends('layouts/contentNavbarLayout')

@section('title', __('app.service_reports'))

@section('page-style')
<style>
:root {
    --card-radius: 20px;
    --shadow: 0 8px 28px rgba(18,38,63,.08);
}

.report-card {
    background: #fff;
    border-radius: var(--card-radius);
    box-shadow: var(--shadow);
    border: none;
    transition: .25s ease;
    overflow: hidden;
}
.report-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 44px rgba(18,38,63,.13);
}

.report-card-header {
    padding: 20px 22px 16px;
    border-bottom: 1px solid #f1f3f9;
}

.service-title { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 3px; }
.business-name { font-size: 13px; color: #8592a3; }

.status-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: 999px;
    font-size: 12px; font-weight: 700;
}
.status-pending  { background: #fff4e5; color: #ff9f43; }
.status-reviewed { background: #e3f8fc; color: #00cfe8; }
.status-resolved { background: #e8f8ef; color: #28c76f; }

.report-details {
    padding: 16px 22px;
}

.detail-row {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 9px 0;
    border-bottom: 1px solid #f1f3f9;
    gap: 12px;
}
.detail-row:last-child { border-bottom: none; }
.detail-label { font-size: 12px; font-weight: 700; color: #8592a3; flex-shrink: 0; }
.detail-value { font-size: 13px; font-weight: 600; color: #1e293b; text-align: right; }

.report-message-box {
    background: #fafbff;
    border: 1px solid #e8eaf2;
    border-radius: 12px;
    padding: 12px 14px;
    font-size: 13px;
    color: #5d6a82;
    line-height: 1.6;
    min-height: 60px;
}

.report-actions {
    padding: 14px 22px;
    background: #fafbff;
    border-top: 1px solid #f1f3f9;
    display: flex; gap: 8px; flex-wrap: wrap;
}

.action-btn {
    flex: 1;
    border-radius: 10px;
    padding: 9px 12px;
    font-size: 12px;
    font-weight: 700;
    border: none;
    transition: .2s ease;
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    min-width: 0;
}
.action-btn:hover { transform: translateY(-1px); }
.btn-reviewed { background: var(--role-accent-soft); color: var(--role-accent); }
.btn-reviewed:hover { background: var(--role-accent); color: #fff; }
.btn-resolved { background: #e8f8ef; color: #28c76f; }
.btn-resolved:hover { background: #28c76f; color: #fff; }
.btn-del { background: #fdeaea; color: #ea5455; }
.btn-del:hover { background: #ea5455; color: #fff; }

.empty-state {
    background: #fff;
    border-radius: var(--card-radius);
    box-shadow: var(--shadow);
    padding: 60px 20px;
    text-align: center;
    color: #8592a3;
}
.empty-state i { font-size: 52px; color: #c8ccda; display: block; margin-bottom: 16px; }

.filter-bar {
    background: #fff;
    border-radius: 16px;
    box-shadow: var(--shadow);
    padding: 18px 22px;
    margin-bottom: 24px;
}

.stat-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: var(--shadow);
    padding: 18px 22px;
    display: flex;
    align-items: center;
    gap: 16px;
}
.stat-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.stat-label { font-size: 12px; color: #8592a3; font-weight: 600; }
.stat-value { font-size: 22px; font-weight: 800; color: #1e293b; line-height: 1; }
</style>
@endsection

@section('content')

@php
    $isSuperAdmin = auth('superadmins')->check();
    $updateRoute  = $isSuperAdmin ? 'superadmin.report.updateStatus' : 'report.updateStatus';
    $destroyRoute = $isSuperAdmin ? 'superadmin.report.destroy'       : 'report.destroy';
    $indexRoute   = $isSuperAdmin ? 'superadmin.reports.index'        : 'reports.index';

    $total    = $reports->count();
    $pending  = $reports->where('status', 'pending')->count();
    $reviewed = $reports->where('status', 'reviewed')->count();
    $resolved = $reports->where('status', 'resolved')->count();
@endphp

{{-- Alerts --}}
@if(session('success'))
<div class="alert border-0 shadow-sm mb-4"
     style="background:#e8f8ef;color:#1a7a45;border-radius:14px;" role="alert">
    <i class="ri ri-checkbox-circle-line me-2"></i>{{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert border-0 shadow-sm mb-4"
     style="background:#fdeaea;color:#b91c1c;border-radius:14px;" role="alert">
    <i class="ri ri-error-warning-line me-2"></i>{{ session('error') }}
</div>
@endif

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('app.reports') }}</h4>
        <p class="text-muted mb-0 small">{{ __('app.reports_desc_admin') }}</p>
    </div>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f1f3f9;">
                <i class="ri ri-flag-2-line" style="color:#8592a3;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.total') }}</div>
                <div class="stat-value">{{ $total }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff4e5;">
                <i class="ri ri-time-line" style="color:#ff9f43;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.pending') }}</div>
                <div class="stat-value" style="color:#ff9f43;">{{ $pending }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e3f8fc;">
                <i class="ri ri-eye-line" style="color:#00cfe8;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.reviewed') }}</div>
                <div class="stat-value" style="color:#00cfe8;">{{ $reviewed }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8ef;">
                <i class="ri ri-checkbox-circle-line" style="color:#28c76f;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.resolved') }}</div>
                <div class="stat-value" style="color:#28c76f;">{{ $resolved }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <form method="GET" action="{{ route($indexRoute) }}" class="row g-2 align-items-end">
        <div class="col-12 col-md-6">
            <label class="form-label fw-semibold small text-muted mb-1">{{ __('app.search') }}</label>
            <div class="input-group">
                <span class="input-group-text" style="background:#f8f9fc;border-color:#e8eaf2;">
                    <i class="ri ri-search-line text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control"
                       style="border-color:#e8eaf2;"
                       placeholder="{{ __('app.search_by_reason') }}"
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-12 col-md-4">
            <label class="form-label fw-semibold small text-muted mb-1">{{ __('app.status') }}</label>
            <select name="status" class="form-select" style="border-color:#e8eaf2;">
                <option value="">{{ __('app.all_statuses') }}</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>{{ __('app.pending') }}</option>
                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>{{ __('app.reviewed') }}</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>{{ __('app.resolved') }}</option>
            </select>
        </div>
        <div class="col-12 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;">
                <i class="ri ri-filter-3-line me-1"></i>{{ __('app.filter') }}
            </button>
            @if(request('search') || request('status'))
            <a href="{{ route($indexRoute) }}" class="btn btn-outline-secondary" style="border-radius:10px;" title="Clear">
                <i class="ri ri-close-line"></i>
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Reports Grid --}}
<div class="row g-4">
    @forelse($reports as $report)
    <div class="col-12 col-lg-6">
        <div class="report-card h-100 d-flex flex-column">

            {{-- Card Header --}}
            <div class="report-card-header d-flex justify-content-between align-items-start">
                <div>
                    <div class="service-title">
                        <i class="ri ri-tools-line me-1" style="color:#8592a3;font-size:14px;"></i>
                        {{ auto_translate($report->service->title ?? '') ?: __('app.unknown_service') }}
                    </div>
                    <div class="business-name">
                        <i class="ri ri-store-2-line me-1"></i>
                        @php
                            $biz     = $report->service->business ?? null;
                            $locale  = app()->getLocale();
                            $bizName = $biz
                                ? ($locale === 'ar'
                                    ? ($biz->job_name_ar ?: $biz->job_name_en)
                                    : ($biz->job_name_en ?: $biz->job_name_ar))
                                : null;
                        @endphp
                        {{ $bizName ?? __('app.no_business_name') }}
                    </div>
                </div>
                @php
                    $st = $report->status ?? 'pending';
                    $statusClass = match($st) {
                        'reviewed' => 'status-reviewed',
                        'resolved' => 'status-resolved',
                        default    => 'status-pending',
                    };
                    $statusLabel = match($st) {
                        'reviewed' => __('app.reviewed'),
                        'resolved' => __('app.resolved'),
                        default    => __('app.pending'),
                    };
                @endphp
                <span class="status-pill {{ $statusClass }}">
                    <i class="ri ri-checkbox-blank-circle-fill" style="font-size:8px;"></i>
                    {{ $statusLabel }}
                </span>
            </div>

            {{-- Details --}}
            <div class="report-details flex-grow-1">
                <div class="detail-row">
                    <span class="detail-label">{{ __('app.reported_by') }}</span>
                    <span class="detail-value">{{ $report->user->name ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ __('app.reason') }}</span>
                    <span class="detail-value">{{ auto_translate($report->reason ?? '') ?: '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ __('app.date') }}</span>
                    <span class="detail-value">{{ $report->created_at?->format('M d, Y') ?? '-' }}</span>
                </div>
                <div class="detail-row" style="flex-direction:column;gap:8px;">
                    <span class="detail-label">{{ __('app.message_label') }}</span>
                    <div class="report-message-box w-100">
                        {{ auto_translate($report->message ?? '') ?: __('app.no_details_provided') }}
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="report-actions">
                @if(($report->status ?? 'pending') !== 'reviewed')
                <form action="{{ route($updateRoute, $report->id) }}" method="POST" style="flex:1;">
                    @csrf
                    <input type="hidden" name="status" value="reviewed">
                    <button type="submit" class="action-btn btn-reviewed w-100">
                        <i class="ri ri-eye-line"></i>{{ __('app.mark_reviewed') }}
                    </button>
                </form>
                @endif
                @if(($report->status ?? 'pending') !== 'resolved')
                <form action="{{ route($updateRoute, $report->id) }}" method="POST" style="flex:1;">
                    @csrf
                    <input type="hidden" name="status" value="resolved">
                    <button type="submit" class="action-btn btn-resolved w-100">
                        <i class="ri ri-checkbox-circle-line"></i>{{ __('app.resolve') }}
                    </button>
                </form>
                @endif
                <form action="{{ route($destroyRoute, $report->id) }}" method="POST" style="flex:0 0 auto;">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn btn-del"
                            onclick="return confirm('{{ __("app.confirm_delete") }}')">
                        <i class="ri ri-delete-bin-line"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <i class="ri ri-file-shield-2-line"></i>
            <p class="fw-bold mb-1">{{ __('app.no_reports_found') }}</p>
            <p class="small mb-0">{{ __('app.no_reports_match') }}</p>
        </div>
    </div>
    @endforelse
</div>

@endsection
