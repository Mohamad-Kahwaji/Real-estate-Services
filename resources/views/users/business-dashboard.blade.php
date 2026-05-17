@extends('layouts/contentNavbarLayout')

@section('title', 'Business Dashboard')

@section('page-style')
<style>
/* ── Header ── */
.biz-header {
  background: linear-gradient(135deg, #696cff 0%, #9c6bff 100%);
  border-radius: 20px;
  padding: 28px 32px;
  color: #fff;
  margin-bottom: 28px;
  position: relative;
  overflow: hidden;
}
.biz-header::before {
  content: '';
  position: absolute;
  top: -40px; right: -40px;
  width: 200px; height: 200px;
  border-radius: 50%;
  background: rgba(255,255,255,0.07);
}
.biz-header h3 { font-size: 22px; font-weight: 800; margin: 0 0 4px; }
.biz-header p  { font-size: 13px; opacity: .8; margin: 0; }
.biz-badge {
  display: inline-flex; align-items: center; gap: 5px;
  background: rgba(255,255,255,.2); border-radius: 20px;
  padding: 4px 12px; font-size: 11px; font-weight: 700;
  margin-top: 10px;
}

/* ── Stat cards ── */
.stat-card {
  background: #fff;
  border-radius: 18px;
  padding: 22px 20px;
  box-shadow: 0 4px 20px rgba(105,108,255,.08);
  transition: .2s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(105,108,255,.14); }
.stat-icon {
  width: 50px; height: 50px; border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  font-size: 22px; margin-bottom: 14px;
}
.stat-value { font-size: 28px; font-weight: 800; color: #312d4b; line-height: 1; margin-bottom: 4px; }
.stat-label { font-size: 12px; color: #97939e; font-weight: 600; }
.stat-sub   { font-size: 11px; color: #b0aab8; margin-top: 6px; }

/* ── Section titles ── */
.section-title {
  font-size: 15px; font-weight: 800; color: #312d4b;
  margin-bottom: 16px;
  display: flex; align-items: center; gap: 8px;
}
.section-title i { color: #696cff; font-size: 18px; }

/* ── Requests table ── */
.req-table-wrap {
  background: #fff; border-radius: 18px;
  box-shadow: 0 4px 20px rgba(105,108,255,.08);
  overflow: hidden;
}
.req-table { margin: 0; }
.req-table thead th {
  background: #f8f7ff; color: #97939e;
  font-size: 11px; font-weight: 700; text-transform: uppercase;
  letter-spacing: .5px; border: none; padding: 14px 16px;
}
.req-table tbody td { font-size: 13px; padding: 13px 16px; vertical-align: middle; border-color: #f0eef6; }
.req-table tbody tr:hover { background: #faf9ff; }

.status-pill {
  display: inline-flex; align-items: center; gap: 4px;
  border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 700;
}
.pill-pending  { background: #fff4e5; color: #ff9f43; }
.pill-approved { background: #e8faf0; color: #28c76f; }
.pill-rejected { background: #fdeaea; color: #ea5455; }
.pill-paid     { background: #e8faf0; color: #28c76f; }
.pill-unpaid   { background: #fdeaea; color: #ea5455; }

/* ── Services list ── */
.svc-card {
  background: #fff; border-radius: 16px;
  box-shadow: 0 4px 20px rgba(105,108,255,.08);
  overflow: hidden; transition: .2s;
}
.svc-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(105,108,255,.14); }
.svc-img { height: 140px; object-fit: cover; width: 100%; }
.svc-body { padding: 14px 16px; }
.svc-title { font-size: 13px; font-weight: 700; color: #312d4b; margin-bottom: 6px; line-height: 1.3;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.svc-meta  { font-size: 11px; color: #97939e; }

/* ── Stars ── */
.stars { color: #ff9f43; font-size: 14px; }
.stars .empty { color: #e0dced; }

/* ── Rating avg ── */
.rating-big { font-size: 36px; font-weight: 900; color: #312d4b; line-height: 1; }
</style>
@endsection

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
    <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

{{-- ── Business Header ── --}}
<div class="biz-header">
  <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
      <h3>{{ $business->job_name_en }}</h3>
      <p>{{ $business->job_name_ar }} &nbsp;·&nbsp; {{ $business->activeType->name ?? '—' }} &nbsp;·&nbsp; {{ $business->city->name_en ?? '—' }}</p>
      <div class="d-flex gap-2 flex-wrap mt-2">
        <span class="biz-badge"><i class="ri-shield-check-line"></i> Approved</span>
        <span class="biz-badge"><i class="ri-file-list-3-line"></i> License #{{ $business->license_number }}</span>
      </div>
    </div>
    <a href="{{ route('myservices.user') }}" class="btn btn-sm rounded-3 fw-bold"
       style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);">
      <i class="ri-add-line me-1"></i>Manage Services
    </a>
  </div>
</div>

{{-- ── Stats ── --}}
<div class="row g-3 mb-4">

  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#eeeeff;">
        <i class="ri-store-3-line" style="color:#696cff;"></i>
      </div>
      <div class="stat-value">{{ $stats['services'] }}</div>
      <div class="stat-label">Total Services</div>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#fff4e5;">
        <i class="ri-file-list-3-line" style="color:#ff9f43;"></i>
      </div>
      <div class="stat-value">{{ $stats['requests_total'] }}</div>
      <div class="stat-label">Total Requests</div>
      <div class="stat-sub">
        <span style="color:#28c76f;">{{ $stats['requests_approved'] }} approved</span>
        &nbsp;·&nbsp;
        <span style="color:#ff9f43;">{{ $stats['requests_pending'] }} pending</span>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#fff8e1;">
        <i class="ri-star-fill" style="color:#f4b400;"></i>
      </div>
      <div class="stat-value">{{ $stats['favorites'] }}</div>
      <div class="stat-label">Saved to Favourites</div>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#fdeaea;">
        <i class="ri-heart-line" style="color:#ea5455;"></i>
      </div>
      @if($stats['reviews_count'] > 0)
        <div class="d-flex align-items-end gap-2">
          <div class="stat-value">{{ $stats['reviews_avg'] }}</div>
          <div class="stars mb-1">
            @for($i = 1; $i <= 5; $i++)
              <i class="ri-star-{{ $i <= round($stats['reviews_avg']) ? 'fill' : 'line' }}"></i>
            @endfor
          </div>
        </div>
        <div class="stat-label">Avg Rating</div>
        <div class="stat-sub">{{ $stats['reviews_count'] }} review{{ $stats['reviews_count'] != 1 ? 's' : '' }}</div>
      @else
        <div class="stat-value text-muted" style="font-size:18px;">—</div>
        <div class="stat-label">No Reviews Yet</div>
      @endif
    </div>
  </div>

</div>

<div class="row g-4">

  {{-- ── Recent Requests ── --}}
  <div class="col-12 col-xl-7">
    <div class="section-title">
      <i class="ri-file-list-3-line"></i> Recent Requests
    </div>
    <div class="req-table-wrap">
      @if($recentRequests->isEmpty())
        <div class="text-center py-5 text-muted">
          <i class="ri-inbox-line d-block mb-2" style="font-size:36px;color:#d5d8ff;"></i>
          No requests received yet.
        </div>
      @else
        <table class="table req-table">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Service</th>
              <th>Status</th>
              <th>Payment</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentRequests as $req)
            <tr>
              <td>
                <div class="fw-semibold" style="color:#312d4b;">{{ $req->user->name ?? '—' }}</div>
                <div class="text-muted" style="font-size:11px;">{{ $req->user->phone ?? '' }}</div>
              </td>
              <td>
                <div style="max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                  {{ $req->service->title ?? '—' }}
                </div>
                <div class="text-muted" style="font-size:11px;">Qty: {{ $req->quantity }}</div>
              </td>
              <td>
                <span class="status-pill pill-{{ $req->status }}">
                  {{ ucfirst($req->status) }}
                </span>
              </td>
              <td>
                <span class="status-pill {{ $req->payment_status === 'paid' ? 'pill-paid' : 'pill-unpaid' }}">
                  {{ $req->payment_status === 'paid' ? 'Paid' : 'Unpaid' }}
                </span>
              </td>
              <td class="text-muted" style="font-size:12px; white-space:nowrap;">
                {{ $req->created_at->format('M d, Y') }}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @if($recentRequests->count() === 8)
          <div class="px-4 py-3 border-top text-center">
            <a href="{{ route('incoming.user') }}" class="text-primary fw-semibold" style="font-size:13px;">
              View all requests <i class="ri-arrow-right-line"></i>
            </a>
          </div>
        @endif
      @endif
    </div>
  </div>

  {{-- ── My Services ── --}}
  <div class="col-12 col-xl-5">
    <div class="section-title">
      <i class="ri-store-3-line"></i> My Services ({{ $services->count() }})
    </div>

    @if($services->isEmpty())
      <div class="text-center py-5 text-muted">
        <i class="ri-store-3-line d-block mb-2" style="font-size:36px;color:#d5d8ff;"></i>
        No services yet.
        <a href="{{ route('user.service.create') }}" class="d-block mt-2 text-primary fw-semibold">Add your first service</a>
      </div>
    @else
      <div class="row g-3">
        @foreach($services as $svc)
        <div class="col-6">
          <div class="svc-card">
            <img src="{{ $svc->image_url }}" class="svc-img" alt="{{ $svc->title }}">
            <div class="svc-body">
              <div class="svc-title">{{ $svc->title }}</div>
              <div class="svc-meta mb-2">
                <i class="ri-price-tag-3-line me-1"></i>{{ $svc->category->name_en ?? '—' }}
              </div>
              <div class="d-flex align-items-center justify-content-between">
                <span class="status-pill pill-{{ $svc->status }}" style="font-size:10px;">
                  {{ ucfirst($svc->status) }}
                </span>
                @if($svc->reviews_count > 0)
                  <span class="text-muted" style="font-size:11px;">
                    <i class="ri-star-fill" style="color:#ff9f43;"></i>
                    {{ $svc->reviews_count }}
                  </span>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    @endif
  </div>

</div>

@endsection
