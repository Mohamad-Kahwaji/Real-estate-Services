@extends('layouts/contentNavbarLayout')

@section('title', 'All Services')

@section('content')

<h4 class="mb-1 fw-bold">All Services</h4>
<p class="mb-4 text-muted">
    Browse all user services in one page
</p>

@if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm">
        {{ session('error') }}
    </div>
@endif

<div class="row g-4">

    @forelse($services as $service)

        @php
            $isFavorite = in_array($service->id, $favorites ?? []);

            $statusClass = match($service->status) {
                'approved' => 'bg-success',
                'rejected' => 'bg-danger',
                default => 'bg-warning text-dark',
            };

            $modalId = 'serviceModal'.$service->id;
            $mapId = 'map'.$service->id;
        @endphp

        <div class="col-sm-6 col-lg-4 col-xl-3">

            <div class="card service-card border-0 h-100">

                {{-- IMAGE --}}
                <div class="position-relative overflow-hidden">

                    <img
                        class="card-img-top service-image"
                        src="{{ $service->image
                                ? asset('storage/'.$service->image)
                                : asset('assets/img/elements/5.png') }}"
                        alt="{{ $service->title }}"
                    >

                    {{-- FAVORITE --}}
                    <div class="position-absolute top-0 start-0 m-3">

                        <form action="{{ route('favorite.toggle', $service->id) }}" method="POST">
                            @csrf

                            <button type="submit"
                                    class="favorite-btn {{ $isFavorite ? 'active' : '' }}">
                                <i class="ri-star-fill"></i>
                            </button>

                        </form>

                    </div>

                    {{-- STATUS --}}
                    <div class="position-absolute top-0 end-0 m-3">

                        <span class="badge {{ $statusClass }} service-badge">
                            {{ ucfirst($service->status) }}
                        </span>

                    </div>

                    {{-- TYPE --}}
                    <div class="position-absolute bottom-0 end-0 m-3">

                        <span class="service-type-badge">
                            {{ strtoupper($service->services_type) }}
                        </span>

                    </div>

                </div>

                <div class="card-body d-flex flex-column">

                    {{-- BUSINESS --}}
                    <div class="business-row mb-2">

                        <i class="ri-store-2-line"></i>

                        <span>
                            {{ $service->business->job_name_en ?? '-' }}
                        </span>

                    </div>

                    {{-- TITLE --}}
                    <h5 class="service-title">
                        {{ $service->title }}
                    </h5>

                    {{-- DESCRIPTION --}}
                    <p class="service-description">
                        {{ \Illuminate\Support\Str::limit($service->description, 90) }}
                    </p>

                    {{-- LOCATION --}}
                    <div class="location-box mb-3">

                        <i class="ri-map-pin-line"></i>

                        <span>
                            {{ $service->location_name ?? 'Unknown Location' }}
                        </span>

                    </div>

                    {{-- DETAILS --}}
                    <div class="service-details-box mb-3">

                        <div class="detail-item">

                            <span>Category</span>

                            <strong>
                                {{ $service->category->name_en ?? '-' }}
                            </strong>

                        </div>

                        <div class="detail-item">

                            <span>Price</span>

                            <strong class="price-text">

                                @if($service->currency === 'USD')
                                    ${{ $service->price_usd }}
                                @else
                                    {{ number_format($service->price_syp) }} SYP
                                @endif

                            </strong>

                        </div>

                        <div class="detail-item">

                            <span>Quantity</span>

                            <strong>
                                {{ $service->quantity }}
                            </strong>

                        </div>

                    </div>

                    {{-- REQUEST STATUS --}}
                    <div class="mb-3">

                        @if(($service->user_request_status ?? null) === 'pending')

                            <div class="request-status-box status-pending">
                                <i class="ri-time-line me-1"></i>
                                Request Pending
                            </div>

                        @elseif(($service->user_request_status ?? null) === 'approved')

                            <div class="request-status-box status-approved">
                                <i class="ri-check-line me-1"></i>
                                Request Approved
                            </div>

                        @elseif(($service->user_request_status ?? null) === 'rejected')

                            <div class="request-status-box status-rejected">
                                <i class="ri-close-line me-1"></i>
                                Request Rejected
                            </div>

                        @else

                            <div class="request-status-box status-none">
                                <i class="ri-information-line me-1"></i>
                                No Request Yet
                            </div>

                        @endif

                    </div>

                    {{-- BUTTONS --}}
                    <div class="mt-auto d-flex flex-column gap-2">

                        {{-- DETAILS BUTTON --}}
                        <button
                            class="btn btn-dark action-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#{{ $modalId }}"
                        >
                            <i class="ri-eye-line me-1"></i>
                            View Details
                        </button>

                        {{-- REQUEST BUTTONS --}}
                        @if(empty($service->user_request_status))

                            <form action="{{ route('pendingserrec', $service->id) }}"
                                  method="POST">

                                @csrf

                                <button class="btn btn-primary w-100 action-btn">
                                    <i class="ri-send-plane-line me-1"></i>
                                    Request Service
                                </button>

                            </form>

                        @elseif($service->user_request_status === 'pending')

                            <form action="{{ route('service.cancel', $service->id) }}"
                                  method="POST">

                                @csrf

                                <button class="btn btn-outline-danger w-100 action-btn">
                                    <i class="ri-close-circle-line me-1"></i>
                                    Cancel Request
                                </button>

                            </form>

                        @elseif($service->user_request_status === 'rejected')

                            <form action="{{ route('service.request', $service->id) }}"
                                  method="POST">

                                @csrf

                                <button class="btn btn-outline-primary w-100 action-btn">
                                    <i class="ri-restart-line me-1"></i>
                                    Request Again
                                </button>

                            </form>

                        @elseif($service->user_request_status === 'approved')

                            <button class="btn btn-success w-100 action-btn" disabled>
                                <i class="ri-checkbox-circle-line me-1"></i>
                                Approved
                            </button>

                        @endif

                    </div>

                </div>

            </div>

        </div>

        {{-- MODAL --}}
        <div class="modal fade"
             id="{{ $modalId }}"
             tabindex="-1">

            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content border-0 rounded-4">

                    <div class="modal-header border-0 pb-0">

                        <div>

                            <h3 class="fw-bold mb-2">
                                {{ $service->title }}
                            </h3>

                            <div class="d-flex gap-2 align-items-center">

                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($service->status) }}
                                </span>

                                <span class="service-type-badge">
                                    {{ strtoupper($service->services_type) }}
                                </span>

                            </div>

                        </div>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="row g-4">

                            {{-- LEFT --}}
                            <div class="col-lg-7">

                                <img
                                    src="{{ $service->image
                                            ? asset('storage/'.$service->image)
                                            : asset('assets/img/elements/5.png') }}"
                                    class="modal-service-image"
                                    alt="{{ $service->title }}"
                                >

                                {{-- DESCRIPTION --}}
                                <div class="details-card mt-4">

                                    <h5 class="section-title">
                                        Description
                                    </h5>

                                    <p class="text-muted lh-lg mb-0">
                                        {{ $service->description }}
                                    </p>

                                </div>

                                {{-- MAP --}}
                                <div class="details-card mt-4">

                                    <div class="d-flex justify-content-between mb-3">

                                        <h5 class="section-title mb-0">
                                            Service Location
                                        </h5>

                                        <span class="text-muted small">
                                            {{ $service->latitude }},
                                            {{ $service->longitude }}
                                        </span>

                                    </div>

                                    <div id="{{ $mapId }}"
                                         class="service-map">
                                    </div>

                                </div>

                            </div>

                            {{-- RIGHT --}}

<div class="col-lg-5">

    <div class="details-card">

        <h5 class="section-title mb-3">
            Service Information
        </h5>

        <div class="info-list">

            <div class="info-row">
                <span>Business Name</span>
                <strong>{{ $service->business->job_name_en ?? '-' }}</strong>
            </div>

            <div class="info-row">
                <span>Category</span>
                <strong>{{ $service->category->name_en ?? '-' }}</strong>
            </div>

            <div class="info-row">
                <span>Subcategory</span>
                <strong>{{ $service->subcategory->name_en ?? '-' }}</strong>
            </div>

            <div class="info-row">
                <span>Service Type</span>
                <strong>{{ ucfirst($service->services_type) }}</strong>
            </div>

            <div class="info-row">
                <span>Quantity</span>
                <strong>{{ $service->quantity }}</strong>
            </div>

            <div class="info-row">
                <span>Price</span>
                <strong>
                    {{ $service->price_usd ? '$'.$service->price_usd : $service->price_syp.' SYP' }}
                </strong>
            </div>

            <div class="info-row">
                <span>Currency</span>
                <strong>{{ $service->currency }}</strong>
            </div>

            <div class="info-row">
                <span>Created At</span>
                <strong>{{ $service->created_at?->format('Y-m-d') }}</strong>
            </div>

        </div>
    </div>

    {{-- LOCATION CARD --}}
    <div class="details-card mt-4">

        <h5 class="section-title mb-3">
            Location
        </h5>

        {{-- اسم المنطقة بدل الاحداثيات --}}
        <div class="mb-3 text-muted small">
            <i class="ri-map-pin-line me-1"></i>
            <span id="location-name-{{ $service->id }}">
                Loading location...
            </span>
        </div>

        {{-- MAP فقط --}}
        <div id="map{{ $service->id }}" class="service-map"></div>

    </div>

</div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="alert alert-info rounded-4 border-0 shadow-sm mb-0">
                No services found.
            </div>

        </div>

    @endforelse

</div>

@endsection

@push('styles')

<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>

.service-card{
    border-radius:24px;
    overflow:hidden;
    background:#fff;
    transition:.3s ease;
    box-shadow:0 12px 30px rgba(15,23,42,.07);
}

.service-card:hover{
    transform:translateY(-6px);
    box-shadow:0 20px 45px rgba(15,23,42,.12);
}

.service-image{
    height:240px;
    object-fit:cover;
    transition:.4s ease;
}

.service-card:hover .service-image{
    transform:scale(1.05);
}

.service-title{
    font-size:20px;
    font-weight:800;
    line-height:1.4;
    color:#111827;
    min-height:56px;
}

.service-description{
    color:#6b7280;
    min-height:65px;
    line-height:1.7;
}

.business-row{
    display:flex;
    align-items:center;
    gap:8px;
    color:#6b7280;
    font-size:14px;
}

.location-box{
    display:flex;
    align-items:center;
    gap:8px;
    color:#6b7280;
    font-size:14px;
}

.location-box i{
    color:#ef4444;
}

.service-details-box{
    background:#f8fafc;
    border-radius:18px;
    padding:14px;
}

.detail-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:10px 0;
    border-bottom:1px solid #e5e7eb;
}

.detail-item:last-child{
    border-bottom:0;
}

.price-text{
    color:#16a34a;
}

.favorite-btn{
    width:44px;
    height:44px;
    border:none;
    border-radius:50%;
    background:#fff;
    color:#9ca3af;
    font-size:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:.25s ease;
    box-shadow:0 8px 20px rgba(0,0,0,.15);
}

.favorite-btn:hover{
    transform:scale(1.08);
}

.favorite-btn.active{
    background:#fff7db;
    color:#f4b400;
}

.service-badge{
    padding:9px 14px;
    border-radius:999px;
    font-size:12px;
}

.service-type-badge{
    background:#111827;
    color:#fff;
    padding:8px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.action-btn{
    border-radius:14px;
    padding:12px;
    font-weight:700;
}

.request-status-box{
    border-radius:14px;
    padding:13px;
    font-size:13px;
    font-weight:600;
}

.status-none{
    background:#f3f4f6;
    color:#6b7280;
}

.status-pending{
    background:#fff7db;
    color:#a16207;
}

.status-approved{
    background:#ecfdf3;
    color:#15803d;
}

.status-rejected{
    background:#fef2f2;
    color:#b91c1c;
}

.modal-service-image{
    width:100%;
    height:380px;
    object-fit:cover;
    border-radius:24px;
}

.details-card{
    background:#fff;
    border:1px solid #edf0f7;
    border-radius:22px;
    padding:24px;
}

.section-title{
    font-size:18px;
    font-weight:800;
    color:#111827;
}

.info-list{
    display:flex;
    flex-direction:column;
    gap:14px;
}

.info-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding-bottom:10px;
    border-bottom:1px dashed #e5e7eb;
}

.service-map{
    height:350px;
    border-radius:18px;
    overflow:hidden;
}

@media(max-width:768px){

    .modal-service-image{
        height:240px;
    }

    .service-map{
        height:240px;
    }

}

</style>

@endpush

@push('scripts')



<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

document.addEventListener('shown.bs.modal', function (event) {

    const modal = event.target;

    @foreach($services as $service)

        if(modal.id === 'serviceModal{{ $service->id }}'){

            const lat = {{ $service->latitude ?? 33.5138 }};
            const lng = {{ $service->longitude ?? 36.2765 }};

            const mapId = 'map{{ $service->id }}';

            if(window[mapId]) return;

            const map = L.map(mapId).setView([lat, lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map);

            window[mapId] = map;

            setTimeout(() => map.invalidateSize(), 300);

            // 🔥 Reverse Geocoding (اسم المنطقة بدل الاحداثيات)
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    const name =
                        data.address.city ||
                        data.address.town ||
                        data.address.village ||
                        data.address.suburb ||
                        data.display_name;

                    document.getElementById('location-name-{{ $service->id }}').innerText = name;
                })
                .catch(() => {
                    document.getElementById('location-name-{{ $service->id }}').innerText = "Unknown location";
                });
        }

    @endforeach

});
</script>
@endpush
