@extends('layouts/contentNavbarLayout')

@section('title', 'All Services')

@section('page-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
/* ─── Cards ───────────────────────────────────────── */
.service-card {
    border-radius: 20px;
    overflow: hidden;
    background: #fff;
    transition: .3s ease;
    box-shadow: 0 8px 28px rgba(15,23,42,.08);
    border: 1px solid #f1f5f9;
}
.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 48px rgba(15,23,42,.13);
}

/* Image */
.service-img-wrap { position: relative; overflow: hidden; }
.service-img {
    height: 220px;
    object-fit: cover;
    width: 100%;
    transition: .4s ease;
    display: block;
}
.service-card:hover .service-img { transform: scale(1.04); }

/* Badges */
.badge-status {
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}
.badge-type {
    background: rgba(17,24,39,.75);
    backdrop-filter: blur(6px);
    color: #fff;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}

/* Card body */
.card-body-inner { padding: 18px; display: flex; flex-direction: column; height: 100%; }

.biz-label {
    font-size: 12px;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 6px;
}
.biz-label i { color: #696cff; }

.svc-title {
    font-size: 17px;
    font-weight: 800;
    color: #111827;
    line-height: 1.4;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.svc-desc {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.65;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 14px;
}

/* Creator row */
.creator-row {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border-radius: 10px;
    padding: 9px 12px;
    margin-bottom: 12px;
}
.creator-ava {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    color: #fff;
    font-size: 12px;
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.creator-info { font-size: 12px; line-height: 1.3; }
.creator-info strong { display: block; color: #111827; }
.creator-info span { color: #9ca3af; }

/* Stats row */
.stats-row {
    display: flex;
    gap: 10px;
    margin-bottom: 14px;
}
.stat-chip {
    flex: 1;
    background: #f8fafc;
    border-radius: 10px;
    padding: 8px 10px;
    text-align: center;
    font-size: 11px;
    color: #6b7280;
}
.stat-chip strong {
    display: block;
    font-size: 14px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 2px;
}
.stat-chip strong.price-val { color: #16a34a; }

/* Location chip */
.loc-chip {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #6b7280;
    background: #fef2f2;
    border-radius: 10px;
    padding: 7px 12px;
    margin-bottom: 14px;
}
.loc-chip i { color: #ef4444; }

/* View btn */
.btn-view {
    border-radius: 12px;
    padding: 11px;
    font-weight: 700;
    font-size: 14px;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    border: none;
    color: #fff;
    transition: .2s ease;
    width: 100%;
}
.btn-view:hover { opacity: .88; transform: scale(1.01); color: #fff; }

/* ─── Modal ─────────────────────────────────────────── */
.modal-content { border-radius: 24px; border: 0; overflow: hidden; }

.modal-hero {
    width: 100%;
    height: 340px;
    object-fit: cover;
    border-radius: 18px;
    margin-bottom: 20px;
    display: block;
}

.info-card {
    background: #f8fafc;
    border-radius: 16px;
    padding: 18px 20px;
    margin-bottom: 16px;
}
.info-card-title {
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #696cff;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.info-row-m {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px dashed #e5e7eb;
    font-size: 13px;
}
.info-row-m:last-child { border-bottom: 0; padding-bottom: 0; }
.info-row-m span { color: #6b7280; }
.info-row-m strong { color: #111827; text-align: right; max-width: 58%; word-break: break-word; }

/* Creator card in modal */
.creator-card-modal {
    background: linear-gradient(135deg, #696cff15, #9c9eff15);
    border: 1px solid #696cff30;
    border-radius: 16px;
    padding: 18px 20px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
}
.creator-ava-lg {
    width: 50px; height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #696cff, #9c9eff);
    color: #fff;
    font-size: 20px;
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.creator-card-modal .details strong { font-size: 16px; font-weight: 800; color: #111827; }
.creator-card-modal .details span { font-size: 12px; color: #6b7280; }
.creator-card-modal .details p { font-size: 13px; color: #374151; margin: 4px 0 0; }

/* Map */
.map-box {
    height: 280px;
    border-radius: 14px;
    overflow: hidden;
    margin-top: 10px;
}
.btn-gmap {
    border-radius: 10px;
    padding: 9px 16px;
    font-size: 13px;
    font-weight: 700;
    background: #4285f4;
    border: none;
    color: #fff;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: .2s ease;
}
.btn-gmap:hover { background: #2563eb; color: #fff; }

/* Page header */
.page-header {
    background: linear-gradient(135deg, #696cff, #9c9eff);
    border-radius: 20px;
    padding: 28px 32px;
    color: #fff;
    margin-bottom: 28px;
}
.page-header h4 { font-size: 24px; font-weight: 800; margin: 0 0 6px; }
.page-header p  { font-size: 14px; opacity: .85; margin: 0; }
.svc-count-badge {
    background: rgba(255,255,255,.2);
    border-radius: 999px;
    padding: 5px 14px;
    font-size: 13px;
    font-weight: 700;
}

@media(max-width:768px){
    .modal-hero { height: 200px; }
    .map-box { height: 200px; }
}
</style>
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h4><i class="ri-service-line me-2"></i>All Services</h4>
        <p>Browse and inspect all platform services</p>
    </div>
    <div class="svc-count-badge">
        {{ $services->count() }} {{ Str::plural('Service', $services->count()) }}
    </div>
</div>

{{-- Status Filter --}}
@php
  $allCount      = \App\Models\Service::count();
  $pendingCount  = \App\Models\Service::where('status','pending')->count();
  $approvedCount = \App\Models\Service::where('status','approved')->count();
  $rejectedCount = \App\Models\Service::where('status','rejected')->count();
@endphp
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('allserviesad') }}"
           class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill fw-bold">
            All ({{ $allCount }})
        </a>
        <a href="{{ route('allserviesad') }}?status=pending{{ request('search') ? '&search='.request('search') : '' }}"
           class="btn btn-sm {{ request('status')==='pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }} rounded-pill fw-bold">
            Pending ({{ $pendingCount }})
        </a>
        <a href="{{ route('allserviesad') }}?status=approved{{ request('search') ? '&search='.request('search') : '' }}"
           class="btn btn-sm {{ request('status')==='approved' ? 'btn-success' : 'btn-outline-success' }} rounded-pill fw-bold">
            Approved ({{ $approvedCount }})
        </a>
        <a href="{{ route('allserviesad') }}?status=rejected{{ request('search') ? '&search='.request('search') : '' }}"
           class="btn btn-sm {{ request('status')==='rejected' ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill fw-bold">
            Rejected ({{ $rejectedCount }})
        </a>
    </div>
    <form method="GET" action="{{ route('allserviesad') }}" class="d-flex gap-2">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control form-control-sm rounded-pill"
               style="min-width:220px;"
               placeholder="Search by title...">
        <button type="submit" class="btn btn-sm btn-primary rounded-pill fw-bold">
            <i class="ri-search-line me-1"></i>Search
        </button>
        @if(request('search'))
            <a href="{{ route('allserviesad') }}{{ request('status') ? '?status='.request('status') : '' }}"
               class="btn btn-sm btn-outline-secondary rounded-pill">
                <i class="ri-close-line"></i>
            </a>
        @endif
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
        <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
        <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
    </div>
@endif

<div class="row g-4">

    @forelse($services as $service)

        @php
            $statusClass = match($service->status) {
                'approved' => 'bg-success',
                'rejected' => 'bg-danger',
                default    => 'bg-warning text-dark',
            };
            $modalId  = 'svcModal'  . $service->id;
            $mapId    = 'leafMap'   . $service->id;
            $creator  = $service->business->user ?? null;
            $initials = strtoupper(substr($creator->name ?? '?', 0, 1));
            $price    = $service->currency === 'USD'
                        ? '$' . $service->price_usd
                        : number_format($service->price_syp) . ' SYP';
        @endphp

        {{-- ── CARD ── --}}
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card service-card border-0 h-100">

                {{-- Image --}}
                <div class="service-img-wrap">
                    <img
                        class="service-img"
                        src="{{ $service->image ? asset('storage/'.$service->image) : asset('assets/img/elements/5.png') }}"
                        alt="{{ $service->title }}"
                    >
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge {{ $statusClass }} badge-status">
                            {{ ucfirst($service->status) }}
                        </span>
                    </div>
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge-type">{{ strtoupper($service->services_type) }}</span>
                    </div>
                </div>

                <div class="card-body-inner">

                    {{-- Business --}}
                    <div class="biz-label">
                        <i class="ri-store-2-line"></i>
                        {{ $service->business->job_name_en ?? '-' }}
                    </div>

                    {{-- Title --}}
                    <div class="svc-title">{{ $service->title }}</div>

                    {{-- Description --}}
                    <div class="svc-desc">{{ $service->description }}</div>

                    {{-- Creator --}}
                    <div class="creator-row">
                        <div class="creator-ava">{{ $initials }}</div>
                        <div class="creator-info">
                            <strong>{{ $creator->name ?? 'Unknown' }}</strong>
                            <span>{{ $creator->email ?? '' }}</span>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="stats-row">
                        <div class="stat-chip">
                            <strong class="price-val">{{ $price }}</strong>
                            Price
                        </div>
                        <div class="stat-chip">
                            <strong>{{ $service->quantity }}</strong>
                            Qty
                        </div>
                        <div class="stat-chip">
                            <strong>{{ $service->category->name_en ?? '-' }}</strong>
                            Category
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="loc-chip">
                        <i class="ri-map-pin-line"></i>
                        {{ $service->location_name ?? 'Unknown Location' }}
                    </div>

                    <div class="mt-auto d-flex flex-column gap-2">
                        <button
                            class="btn-view"
                            data-bs-toggle="modal"
                            data-bs-target="#{{ $modalId }}"
                        >
                            <i class="ri-eye-line me-1"></i> View Full Details
                        </button>

                        @if($service->status === 'pending')
                        <div class="d-flex gap-2">
                            <form action="{{ route('approveser', $service->id) }}" method="POST" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm w-100 rounded-3 fw-bold">
                                    <i class="ri-check-line me-1"></i>Approve
                                </button>
                            </form>
                            <form action="{{ route('rejectser', $service->id) }}" method="POST" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm w-100 rounded-3 fw-bold">
                                    <i class="ri-close-line me-1"></i>Reject
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- ── MODAL ── --}}
        <div class="modal fade" id="{{ $modalId }}" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">

                    {{-- Header --}}
                    <div class="modal-header border-0 px-4 pt-4 pb-2">
                        <div>
                            <h4 class="fw-bold mb-2">{{ $service->title }}</h4>
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge {{ $statusClass }} badge-status">{{ ucfirst($service->status) }}</span>
                                <span class="badge-type">{{ strtoupper($service->services_type) }}</span>
                                <span class="badge bg-label-secondary">{{ $service->category->name_en ?? '-' }}</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body px-4 pb-4">
                        <div class="row g-4">

                            {{-- ── LEFT column ── --}}
                            <div class="col-lg-7">

                                <img
                                    src="{{ $service->image ? asset('storage/'.$service->image) : asset('assets/img/elements/5.png') }}"
                                    class="modal-hero"
                                    alt="{{ $service->title }}"
                                >

                                {{-- Description --}}
                                <div class="info-card">
                                    <div class="info-card-title">
                                        <i class="ri-align-left"></i> Description
                                    </div>
                                    <p class="text-muted mb-0" style="line-height:1.75;">
                                        {{ $service->description }}
                                    </p>
                                </div>

                                {{-- Map --}}
                                <div class="info-card">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="info-card-title mb-0">
                                            <i class="ri-map-pin-line"></i> Service Location
                                        </div>
                                        @if($service->latitude && $service->longitude)
                                        <a
                                            href="https://www.google.com/maps?q={{ $service->latitude }},{{ $service->longitude }}"
                                            target="_blank"
                                            class="btn-gmap"
                                        >
                                            <i class="ri-map-2-line"></i> Open in Google Maps
                                        </a>
                                        @endif
                                    </div>
                                    <div class="mb-2 text-muted small">
                                        <i class="ri-map-pin-2-line me-1 text-danger"></i>
                                        <span id="locName{{ $service->id }}">
                                            {{ $service->location_name ?? 'Loading...' }}
                                        </span>
                                    </div>
                                    <div id="{{ $mapId }}" class="map-box"></div>
                                </div>

                            </div>

                            {{-- ── RIGHT column ── --}}
                            <div class="col-lg-5">

                                {{-- Creator card --}}
                                <div class="creator-card-modal">
                                    <div class="creator-ava-lg">{{ $initials }}</div>
                                    <div class="details">
                                        <strong>{{ $creator->name ?? 'Unknown' }}</strong>
                                        <span>{{ $creator->email ?? '-' }}</span>
                                        <p>{{ $service->business->job_name_en ?? '-' }}</p>
                                    </div>
                                </div>

                                {{-- Service info --}}
                                <div class="info-card">
                                    <div class="info-card-title">
                                        <i class="ri-file-list-3-line"></i> Service Details
                                    </div>
                                    <div class="info-row-m">
                                        <span>Business</span>
                                        <strong>{{ $service->business->job_name_en ?? '-' }}</strong>
                                    </div>
                                    <div class="info-row-m">
                                        <span>Category</span>
                                        <strong>{{ $service->category->name_en ?? '-' }}</strong>
                                    </div>
                                    <div class="info-row-m">
                                        <span>Subcategory</span>
                                        <strong>{{ $service->subcategory->name_en ?? '-' }}</strong>
                                    </div>
                                    <div class="info-row-m">
                                        <span>Service Type</span>
                                        <strong>{{ ucfirst($service->services_type) }}</strong>
                                    </div>
                                    <div class="info-row-m">
                                        <span>Quantity</span>
                                        <strong>{{ $service->quantity }}</strong>
                                    </div>
                                    <div class="info-row-m">
                                        <span>Price</span>
                                        <strong style="color:#16a34a;">{{ $price }}</strong>
                                    </div>
                                    <div class="info-row-m">
                                        <span>Currency</span>
                                        <strong>{{ $service->currency }}</strong>
                                    </div>
                                    <div class="info-row-m">
                                        <span>Status</span>
                                        <strong>
                                            <span class="badge {{ $statusClass }}">
                                                {{ ucfirst($service->status) }}
                                            </span>
                                        </strong>
                                    </div>
                                    <div class="info-row-m">
                                        <span>Created At</span>
                                        <strong>{{ $service->created_at?->format('d M Y') }}</strong>
                                    </div>
                                </div>

                                {{-- Creator detailed info --}}
                                <div class="info-card">
                                    <div class="info-card-title">
                                        <i class="ri-user-line"></i> Owner Information
                                    </div>
                                    <div class="info-row-m">
                                        <span>Full Name</span>
                                        <strong>{{ $creator->name ?? '-' }}</strong>
                                    </div>
                                    <div class="info-row-m">
                                        <span>Email</span>
                                        <strong>{{ $creator->email ?? '-' }}</strong>
                                    </div>
                                    <div class="info-row-m">
                                        <span>Phone</span>
                                        <strong>{{ $creator->phone ?? '-' }}</strong>
                                    </div>
                                    <div class="info-row-m">
                                        <span>Business</span>
                                        <strong>{{ $service->business->job_name_en ?? '-' }}</strong>
                                    </div>
                                    <div class="info-row-m">
                                        <span>Member Since</span>
                                        <strong>{{ $creator?->created_at?->format('d M Y') ?? '-' }}</strong>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    @if($service->status === 'pending')
                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                        <form action="{{ route('approveser', $service->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success rounded-3 fw-bold px-4">
                                <i class="ri-check-line me-1"></i>Approve Service
                            </button>
                        </form>
                        <form action="{{ route('rejectser', $service->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger rounded-3 fw-bold px-4">
                                <i class="ri-close-line me-1"></i>Reject Service
                            </button>
                        </form>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    @empty

        <div class="col-12">
            <div class="text-center py-5">
                <i class="ri-service-line" style="font-size:64px;color:#dde0f5;"></i>
                <h5 class="mt-3 fw-bold text-muted">No services found</h5>
                <p class="text-muted">No services have been added yet.</p>
            </div>
        </div>

    @endforelse

</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('shown.bs.modal', function (e) {
    const modal = e.target;

    @foreach($services as $service)
    if (modal.id === 'svcModal{{ $service->id }}') {

        const lat   = {{ $service->latitude  ?? 33.5138 }};
        const lng   = {{ $service->longitude ?? 36.2765 }};
        const mapId = 'leafMap{{ $service->id }}';

        if (window['_map_' + mapId]) return;

        const map = L.map(mapId).setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
        L.marker([lat, lng]).addTo(map).bindPopup('{{ addslashes($service->title) }}').openPopup();
        window['_map_' + mapId] = map;
        setTimeout(() => map.invalidateSize(), 300);

        @if(!$service->location_name)
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(r => r.json())
            .then(d => {
                const name = d.address?.city || d.address?.town || d.address?.village || d.address?.suburb || d.display_name || 'Unknown';
                const el = document.getElementById('locName{{ $service->id }}');
                if (el) el.textContent = name;
            }).catch(() => {});
        @endif
    }
    @endforeach
});
</script>
@endpush
