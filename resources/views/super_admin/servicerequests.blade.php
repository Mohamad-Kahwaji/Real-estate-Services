@extends('layouts/contentNavbarLayout')

@section('title', 'Service Requests')

@section('page-style')
<style>
  .req-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(105,108,255,.1);
    overflow: hidden;
  }
  .req-hdr {
    padding: 20px 24px;
    border-bottom: 1px solid #f0eef4;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px;
  }
  .req-title { font-size: 16px; font-weight: 800; color: #312d4b; }
  .req-table thead { background: #1f2937; color: #fff; }
  .req-table thead th {
    font-size: 11px; text-transform: uppercase; letter-spacing: .45px;
    padding: 14px 18px; border-bottom: 0; white-space: nowrap; font-weight: 700;
  }
  .req-table tbody td { padding: 15px 18px; vertical-align: middle; border-color: #f0eef4; font-size: 13px; }
  .req-table tbody tr:hover { background: #fafbff; }
  .req-table tbody tr:last-child td { border-bottom: none; }
  .sp { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 30px; font-size: 11px; font-weight: 700; }
  .sp::before { content:''; width:6px; height:6px; border-radius:50%; background:currentColor; }
  .sp-pending  { background:#fff4e5; color:#ff9f43; }
  .sp-approved { background:#e8faf0; color:#28c76f; }
  .sp-rejected { background:#fdeaea; color:#ea5455; }
  .filter-btn { padding: 7px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; border: 2px solid transparent; text-decoration: none; transition: .2s; }
  .filter-btn.active, .filter-btn:hover { border-color: #696cff; color: #696cff; background: #eef0ff; }
  .filter-btn:not(.active) { color: #97939e; background: #f4f5fa; }
</style>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
  <i class="ri ri-checkbox-circle-line me-2"></i>{{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="req-card">
  <div class="req-hdr">
    <span class="req-title">
      <i class="ri ri-file-list-3-line me-2" style="color:#696cff;"></i>Service Requests
    </span>

    <div class="d-flex gap-2 flex-wrap">
      <a href="{{ route('superadmin.service-requests') }}"
         class="filter-btn {{ !request('status') ? 'active' : '' }}">
        All ({{ $requests->count() }})
      </a>
      <a href="{{ route('superadmin.service-requests') }}?status=pending"
         class="filter-btn {{ request('status') === 'pending' ? 'active' : '' }}">
        Pending ({{ $requests->where('status','pending')->count() }})
      </a>
      <a href="{{ route('superadmin.service-requests') }}?status=approved"
         class="filter-btn {{ request('status') === 'approved' ? 'active' : '' }}">
        Approved ({{ $requests->where('status','approved')->count() }})
      </a>
      <a href="{{ route('superadmin.service-requests') }}?status=rejected"
         class="filter-btn {{ request('status') === 'rejected' ? 'active' : '' }}">
        Rejected ({{ $requests->where('status','rejected')->count() }})
      </a>
    </div>
  </div>

  @php
    $filtered = request('status') ? $requests->where('status', request('status')) : $requests;
  @endphp

  <div class="table-responsive">
    <table class="table req-table mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Requester</th>
          <th>Service</th>
          <th>Business</th>
          <th>Qty</th>
          <th>Needed At</th>
          <th>Status</th>
          <th>Date</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($filtered as $req)
        <tr>
          <td style="color:#b0aab8;font-weight:700;">#{{ $req->id }}</td>
          <td>
            <div style="font-weight:700;color:#312d4b;">{{ $req->user?->name ?? '-' }}</div>
            <div style="font-size:11px;color:#97939e;">{{ $req->user?->email ?? '' }}</div>
          </td>
          <td>
            <div style="font-weight:700;color:#312d4b;">{{ $req->service?->name ?? $req->service?->name_ar ?? '-' }}</div>
            <div style="font-size:11px;color:#97939e;">{{ $req->service?->category?->name ?? '' }}</div>
          </td>
          <td style="color:#585164;">{{ $req->service?->business?->job_name_en ?? $req->business?->job_name_en ?? '-' }}</td>
          <td style="color:#585164;">{{ $req->quantity ?? '-' }}</td>
          <td style="color:#585164;font-size:12px;">
            {{ $req->needed_at ? \Carbon\Carbon::parse($req->needed_at)->format('M d, Y') : '-' }}
          </td>
          <td><span class="sp sp-{{ $req->status ?? 'pending' }}">{{ ucfirst($req->status ?? 'pending') }}</span></td>
          <td style="color:#b0aab8;font-size:12px;">{{ $req->created_at?->format('M d, Y') }}</td>
          <td class="text-center">
            <div class="dropdown">
              <button class="btn btn-sm btn-light dropdown-toggle hide-arrow"
                      style="border-radius:10px;"
                      data-bs-toggle="dropdown">
                <i class="ri ri-more-2-line"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end"
                   style="border:0;border-radius:14px;box-shadow:0 12px 36px rgba(0,0,0,.13);padding:8px;min-width:160px;">
                <button class="dropdown-item" type="button"
                        style="border-radius:10px;font-weight:600;padding:9px 14px;"
                        data-bs-toggle="modal"
                        data-bs-target="#reqModal{{ $req->id }}">
                  <i class="ri ri-eye-line me-1" style="color:#696cff;"></i>View Details
                </button>

                @if($req->status !== 'approved')
                <form action="{{ route('superadmin.service-requests.approve', $req->id) }}" method="POST">
                  @csrf
                  <button class="dropdown-item text-success" type="submit"
                          style="border-radius:10px;font-weight:600;padding:9px 14px;">
                    <i class="ri ri-check-line me-1"></i>Approve
                  </button>
                </form>
                @endif

                @if($req->status !== 'rejected')
                <form action="{{ route('superadmin.service-requests.reject', $req->id) }}" method="POST">
                  @csrf
                  <button class="dropdown-item text-danger" type="submit"
                          style="border-radius:10px;font-weight:600;padding:9px 14px;">
                    <i class="ri ri-close-line me-1"></i>Reject
                  </button>
                </form>
                @endif
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" style="text-align:center;padding:50px;color:#b0aab8;">
            <i class="ri ri-file-list-3-line" style="font-size:36px;display:block;margin-bottom:10px;opacity:.35;"></i>
            No service requests found.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Detail Modals outside the table --}}
@foreach($filtered as $req)
<div class="modal fade" id="reqModal{{ $req->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border:0;border-radius:20px;box-shadow:0 24px 70px rgba(0,0,0,.18);">
      <div class="modal-header" style="border-bottom:1px solid #f0eef4;padding:20px 26px;">
        <h5 class="modal-title" style="font-weight:800;color:#312d4b;">
          <i class="ri ri-file-list-3-line me-2" style="color:#696cff;"></i>
          Request #{{ $req->id }} Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:26px;">
        <div class="row g-4">
          <div class="col-md-6">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Requester</div>
            <div style="font-weight:700;color:#312d4b;">{{ $req->user?->name ?? '-' }}</div>
            <div style="font-size:12px;color:#97939e;">{{ $req->user?->email ?? '' }}</div>
          </div>
          <div class="col-md-6">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Service</div>
            <div style="font-weight:700;color:#312d4b;">{{ $req->service?->name ?? $req->service?->name_ar ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Business</div>
            <div style="font-weight:700;color:#312d4b;">{{ $req->service?->business?->job_name_en ?? $req->business?->job_name_en ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Category</div>
            <div style="font-weight:700;color:#312d4b;">{{ $req->service?->category?->name ?? '-' }}</div>
          </div>
          <div class="col-md-4">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Quantity</div>
            <div style="font-weight:700;color:#312d4b;">{{ $req->quantity ?? '-' }}</div>
          </div>
          <div class="col-md-4">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Needed At</div>
            <div style="font-weight:700;color:#312d4b;">
              {{ $req->needed_at ? \Carbon\Carbon::parse($req->needed_at)->format('M d, Y') : '-' }}
            </div>
          </div>
          <div class="col-md-4">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Status</div>
            <span class="sp sp-{{ $req->status ?? 'pending' }}">{{ ucfirst($req->status ?? 'pending') }}</span>
          </div>
          @if($req->details)
          <div class="col-12">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Details</div>
            <div style="color:#585164;line-height:1.6;">{{ $req->details }}</div>
          </div>
          @endif
        </div>
      </div>
      <div class="modal-footer" style="border-top:1px solid #f0eef4;padding:18px 26px;gap:10px;">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                style="border-radius:12px;font-weight:700;">Close</button>
        @if($req->status !== 'approved')
        <form action="{{ route('superadmin.service-requests.approve', $req->id) }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-success" style="border-radius:12px;font-weight:700;">
            <i class="ri ri-check-line me-1"></i>Approve
          </button>
        </form>
        @endif
        @if($req->status !== 'rejected')
        <form action="{{ route('superadmin.service-requests.reject', $req->id) }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-danger" style="border-radius:12px;font-weight:700;">
            <i class="ri ri-close-line me-1"></i>Reject
          </button>
        </form>
        @endif
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection
