@extends('layouts/contentNavbarLayout')

@section('title', 'Service Reports')

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
.status-default  { background: #f1f3f9; color: #8592a3; }

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
.btn-reviewed { background: #eef0ff; color: #696cff; }
.btn-reviewed:hover { background: #696cff; color: #fff; }
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
</style>
@endsection

@section('content')

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
        <h4 class="fw-bold mb-1">Service Reports</h4>
        <p class="text-muted mb-0 small">Review and manage user complaints about services</p>
    </div>
    @if(isset($reports))
    <span class="badge px-4 py-2" style="background:#fdeaea;color:#ea5455;border-radius:999px;font-size:13px;font-weight:700;">
        {{ $reports->count() }} Reports
    </span>
    @endif
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
                        {{ $report->service->title ?? 'Unknown Service' }}
                    </div>
                    <div class="business-name">
                        <i class="ri ri-store-2-line me-1"></i>
                        {{ $report->service->business->job_name_en ?? 'No business name' }}
                    </div>
                </div>
                @php
                    $statusClass = match($report->status ?? 'pending') {
                        'reviewed' => 'status-reviewed',
                        'resolved' => 'status-resolved',
                        default    => 'status-pending',
                    };
                @endphp
                <span class="status-pill {{ $statusClass }}">
                    <i class="ri ri-checkbox-blank-circle-fill" style="font-size:8px;"></i>
                    {{ ucfirst($report->status ?? 'pending') }}
                </span>
            </div>

            {{-- Details --}}
            <div class="report-details flex-grow-1">
                <div class="detail-row">
                    <span class="detail-label">Reported By</span>
                    <span class="detail-value">{{ $report->user->name ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Reason</span>
                    <span class="detail-value">{{ $report->reason ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value">{{ $report->created_at?->format('M d, Y') ?? '-' }}</span>
                </div>
                <div class="detail-row" style="flex-direction:column;gap:8px;">
                    <span class="detail-label">Message</span>
                    <div class="report-message-box w-100">
                        {{ $report->message ?? 'No additional details provided.' }}
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="report-actions">
                <form action="{{ route('report.updateStatus', $report->id) }}" method="POST" class="d-contents flex-grow-1" style="flex:1;">
                    @csrf
                    <input type="hidden" name="status" value="reviewed">
                    <button type="submit" class="action-btn btn-reviewed w-100">
                        <i class="ri ri-eye-line"></i>Mark Reviewed
                    </button>
                </form>
                <form action="{{ route('report.updateStatus', $report->id) }}" method="POST" style="flex:1;">
                    @csrf
                    <input type="hidden" name="status" value="resolved">
                    <button type="submit" class="action-btn btn-resolved w-100">
                        <i class="ri ri-checkbox-circle-line"></i>Resolve
                    </button>
                </form>
                <form action="{{ route('report.destroy', $report->id) }}" method="POST" style="flex:0 0 auto;">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn btn-del"
                            onclick="return confirm('Delete this report?')">
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
            <p class="fw-bold mb-1">No Reports Found</p>
            <p class="small mb-0">No service reports have been submitted yet</p>
        </div>
    </div>
    @endforelse
</div>

@endsection
