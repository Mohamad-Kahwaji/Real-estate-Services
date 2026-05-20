@extends('layouts/contentNavbarLayout')
{{-- Business accounts page: lists all business registrations filterable by status, with approve/reject actions and a detail modal per business. --}}

@section('title', 'Business Accounts')

@section('page-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
  .biz-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(105,108,255,.1);
    overflow: hidden;
  }
  .biz-hdr {
    padding: 20px 24px;
    border-bottom: 1px solid #f0eef4;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px;
  }
  .biz-title { font-size: 16px; font-weight: 800; color: #312d4b; }
  .biz-table thead { background: #1f2937; color: #fff; }
  .biz-table thead th {
    font-size: 11px; text-transform: uppercase; letter-spacing: .45px;
    padding: 14px 18px; border-bottom: 0; white-space: nowrap; font-weight: 700;
  }
  .biz-table tbody td { padding: 15px 18px; vertical-align: middle; border-color: #f0eef4; font-size: 13px; }
  .biz-table tbody tr:hover { background: #fafbff; }
  .biz-table tbody tr:last-child td { border-bottom: none; }
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

{{-- Table: filterable list of business accounts with status filter tabs and approve/reject actions --}}
<div class="biz-card">
  <div class="biz-hdr">
    <span class="biz-title">
      <i class="ri ri-building-2-line me-2" style="color:#ff9f43;"></i>Business Accounts
    </span>

    <div class="d-flex gap-2 flex-wrap">
      @php $curStatus = request('status', 'pending'); @endphp
      @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $val => $label)
        <a href="{{ route('business.index', array_merge(request()->except('status','page'), ['status' => $val])) }}"
           class="filter-btn {{ $curStatus === $val ? 'active' : '' }}">
          {{ $label }} ({{ $statusCounts[$val] }})
        </a>
      @endforeach
    </div>
  </div>

  {{-- Search & city filter --}}
  <form method="GET" action="{{ route('business.index') }}" class="d-flex gap-2 flex-wrap align-items-center px-3 py-3" style="border-bottom:1px solid #f0eef4;">
    <input type="hidden" name="status" value="{{ $curStatus }}">
    <div style="position:relative;flex:1;min-width:200px;max-width:320px;">
      <i class="ri ri-search-line" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#8592a3;font-size:16px;"></i>
      <input type="text" name="search" value="{{ request('search') }}"
             placeholder="Search name, license, owner..."
             style="width:100%;border-radius:10px;border:1.5px solid #e4e4eb;padding:8px 14px 8px 36px;font-size:13px;background:#fafbff;">
    </div>
    <select name="city_id" style="border-radius:10px;border:1.5px solid #e4e4eb;padding:8px 14px;font-size:13px;background:#fafbff;min-width:150px;">
      <option value="">All Cities</option>
      @foreach($cities as $city)
        <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
          {{ $city->name_ar ?? $city->name_en }}
        </option>
      @endforeach
    </select>
    <button type="submit" style="padding:8px 18px;border-radius:10px;background:#696cff;color:#fff;border:none;font-size:13px;font-weight:700;">
      <i class="ri ri-filter-line me-1"></i>Filter
    </button>
    @if(request('search') || request('city_id'))
      <a href="{{ route('business.index', ['status' => $curStatus]) }}"
         style="padding:8px 14px;border-radius:10px;background:#f1f0f4;color:#585164;font-size:13px;font-weight:700;text-decoration:none;">
        <i class="ri ri-close-line me-1"></i>Clear
      </a>
    @endif
  </form>

  <div class="table-responsive">
    <table class="table biz-table mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Owner</th>
          <th>Business Name</th>
          <th>Type</th>
          <th>City</th>
          <th>License</th>
          <th>Status</th>
          <th>Date</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($businesses as $biz)
        <tr>
          <td style="color:#b0aab8;font-weight:700;">#{{ $biz->id }}</td>
          <td>
            <div style="font-weight:700;color:#312d4b;">{{ $biz->user?->name ?? '-' }}</div>
            <div style="font-size:11px;color:#97939e;">{{ $biz->user?->phone ?? $biz->user?->email ?? '' }}</div>
          </td>
          <td>
            <div style="font-weight:700;color:#312d4b;">{{ app()->getLocale() === 'ar' ? ($biz->job_name_ar ?? $biz->job_name_en ?? '-') : ($biz->job_name_en ?? $biz->job_name_ar ?? '-') }}</div>
            <div style="font-size:11px;color:#97939e;">{{ app()->getLocale() === 'ar' ? ($biz->job_name_en ?? '') : ($biz->job_name_ar ?? '') }}</div>
          </td>
          <td style="color:#585164;">{{ $biz->activeType?->name ?? '-' }}</td>
          <td style="color:#585164;">{{ app()->getLocale() === 'ar' ? ($biz->city?->name_ar ?? $biz->city?->name_en ?? '-') : ($biz->city?->name_en ?? $biz->city?->name_ar ?? '-') }}</td>
          <td style="font-family:monospace;color:#585164;">{{ $biz->license_number ?? '-' }}</td>
          <td><span class="sp sp-{{ $biz->status ?? 'pending' }}">{{ ucfirst($biz->status ?? 'pending') }}</span></td>
          <td style="color:#b0aab8;font-size:12px;">{{ $biz->created_at?->format('M d, Y') }}</td>
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
                        data-bs-target="#bizModal{{ $biz->id }}">
                  <i class="ri ri-eye-line me-1" style="color:#696cff;"></i>View Details
                </button>

                @if($biz->status !== 'approved')
                <form action="{{ route('approve', $biz->id) }}" method="POST">
                  @csrf
                  <button class="dropdown-item text-success" type="submit"
                          style="border-radius:10px;font-weight:600;padding:9px 14px;">
                    <i class="ri ri-check-line me-1"></i>Approve
                  </button>
                </form>
                @endif

                @if($biz->status !== 'rejected')
                <form action="{{ route('reject', $biz->id) }}" method="POST">
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
            <i class="ri ri-building-2-line" style="font-size:36px;display:block;margin-bottom:10px;opacity:.35;"></i>
            No business accounts found.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Pagination --}}
@if($businesses->hasPages())
<div class="d-flex justify-content-center py-3">
  {{ $businesses->links() }}
</div>
@endif

{{-- Modals: per-business detail modal with map, image, and approve/reject actions --}}
@foreach($businesses as $biz)
<div class="modal fade" id="bizModal{{ $biz->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border:0;border-radius:20px;box-shadow:0 24px 70px rgba(0,0,0,.18);">
      <div class="modal-header" style="border-bottom:1px solid #f0eef4;padding:20px 26px;">
        <h5 class="modal-title" style="font-weight:800;color:#312d4b;">
          <i class="ri ri-building-2-line me-2" style="color:#ff9f43;"></i>
          {{ $biz->job_name_en ?? 'Business Details' }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:26px;">
        <div class="row g-4">
          <div class="col-md-6">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Owner</div>
            <div style="font-weight:700;color:#312d4b;">{{ $biz->user?->name ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Business (EN)</div>
            <div style="font-weight:700;color:#312d4b;">{{ $biz->job_name_en ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Business (AR)</div>
            <div style="font-weight:700;color:#312d4b;">{{ $biz->job_name_ar ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">License Number</div>
            <div style="font-weight:700;color:#312d4b;font-family:monospace;">{{ $biz->license_number ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">City</div>
            <div style="font-weight:700;color:#312d4b;">{{ $biz->city?->name_en ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Status</div>
            <span class="sp sp-{{ $biz->status ?? 'pending' }}">{{ ucfirst($biz->status ?? 'pending') }}</span>
          </div>
          <div class="col-12">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Activities</div>
            <div style="color:#585164;line-height:1.6;">{{ $biz->activites ?? '-' }}</div>
          </div>
          <div class="col-12">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Details</div>
            <div style="color:#585164;line-height:1.6;">{{ $biz->details ?? '-' }}</div>
          </div>
          @if($biz->image)
          <div class="col-12">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Image</div>
            <img src="{{ asset('storage/'.$biz->image) }}" alt="Business"
                 style="max-width:100%;border-radius:14px;max-height:250px;object-fit:cover;">
          </div>
          @endif
          @if($biz->latitude && $biz->longitude)
          <div class="col-12">
            <div style="font-size:11px;font-weight:700;color:#97939e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">
              <i class="ri ri-map-pin-2-line me-1" style="color:#696cff;"></i>Location
              <span style="font-weight:500;color:#b0aab8;font-size:10px;margin-left:6px;">{{ $biz->latitude }}, {{ $biz->longitude }}</span>
            </div>
            <div id="biz-map-{{ $biz->id }}" style="height:260px;border-radius:14px;overflow:hidden;border:1px solid #f0eef4;"></div>
          </div>
          @endif
        </div>
      </div>
      <div class="modal-footer" style="border-top:1px solid #f0eef4;padding:18px 26px;gap:10px;">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                style="border-radius:12px;font-weight:700;">Close</button>
        @if($biz->status !== 'approved')
        <form action="{{ route('approve', $biz->id) }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-success" style="border-radius:12px;font-weight:700;">
            <i class="ri ri-check-line me-1"></i>Approve
          </button>
        </form>
        @endif
        @if($biz->status !== 'rejected')
        <form action="{{ route('reject', $biz->id) }}" method="POST">
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

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
  var maps = {};
  @foreach($businesses as $biz)
  @if($biz->latitude && $biz->longitude)
  document.getElementById('bizModal{{ $biz->id }}').addEventListener('shown.bs.modal', function () {
    var id = {{ $biz->id }};
    if (!maps[id]) {
      maps[id] = L.map('biz-map-' + id).setView([{{ $biz->latitude }}, {{ $biz->longitude }}], 15);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(maps[id]);
      L.marker([{{ $biz->latitude }}, {{ $biz->longitude }}])
        .addTo(maps[id])
        .bindPopup('<b>{{ addslashes($biz->job_name_en ?? '') }}</b>').openPopup();
    } else {
      maps[id].invalidateSize();
    }
  });
  @endif
  @endforeach
})();
</script>
@endpush

@endsection
