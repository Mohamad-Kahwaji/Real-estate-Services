@extends('layouts/contentNavbarLayout')

@section('title', 'All Services')

@section('content')

<h4 class="mb-1">All Services</h4>
<p class="mb-4 text-muted">Browse all user services in one page</p>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="row g-4">
    @forelse($services as $service)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card service-card h-100 border-0 shadow-sm">

                {{-- صورة الخدمة --}}
                <div class="position-relative">
                    <img
                        class="card-img-top"
                        src="{{ asset('assets/img/elements/5.png') }}"
                        alt="Service image"
                        style="height: 220px; object-fit: cover;"
                    >

                    @php
                        $isFavorite = in_array($service->id, $favorites ?? []);
                    @endphp

                    {{-- زر المفضلة --}}
                    <div class="position-absolute top-0 start-0 m-3">
                  <form action="{{ route('favorite.toggle', $service->id) }}" method="POST">
    @csrf
    <button class="favorite-btn {{ in_array($service->id, $favorites ?? []) ? 'active' : '' }}">
        <i class="ri-star-fill"></i>
    </button>
</form>

                    </div>

                    {{-- حالة الخدمة --}}
                    <div class="position-absolute top-0 end-0 m-3">
                        @if($service->status == 'approved')
                            <span class="badge bg-success service-badge">Approved</span>
                        @elseif($service->status == 'rejected')
                            <span class="badge bg-danger service-badge">Rejected</span>
                        @else
                            <span class="badge bg-warning text-dark service-badge">Pending</span>
                        @endif
                    </div>
                </div>

                <div class="card-body d-flex flex-column">

                    {{-- اسم البزنس --}}
                    <div class="mb-2 text-muted small">
                        <i class="ri-briefcase-4-line me-1"></i>
                        {{ $service->business->job_name_en ?? 'No business name' }}
                    </div>

                    {{-- عنوان الخدمة --}}
                    <h5 class="card-title mb-2">
                        {{ $service->title }}
                    </h5>

                    {{-- الوصف --}}
                    <p class="card-text text-muted small mb-3 service-description">
                        {{ \Illuminate\Support\Str::limit($service->description ?? 'No description available', 100) }}
                    </p>

                    {{-- التفاصيل --}}
                    <div class="service-details-box mb-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-medium">Category</span>
                                <span>{{ $service->category->name_en ?? '-' }}</span>
                            </li>

                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-medium">Price (USD)</span>
                                <span>{{ $service->price_usd ?? '-' }}</span>
                            </li>

                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-medium">Price (SYP)</span>
                                <span>{{ $service->price_syp ?? '-' }}</span>
                            </li>

                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-medium">City</span>
                                <span>{{ $service->city->name_en ?? '-' }}</span>
                            </li>

                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-medium">Created</span>
                                <span>{{ $service->created_at?->format('Y-m-d') ?? '-' }}</span>
                            </li>
                        </ul>
                    </div>

                    {{-- حالة طلب المستخدم --}}
                    <div class="mb-3">
                        @if(($service->user_request_status ?? null) === 'pending')
                            <div class="request-status-box status-pending">
                                <i class="ri-time-line me-1"></i>
                                Your request is pending
                            </div>
                        @elseif(($service->user_request_status ?? null) === 'approved')
                            <div class="request-status-box status-approved">
                                <i class="ri-check-line me-1"></i>
                                Your request has been approved
                            </div>
                        @elseif(($service->user_request_status ?? null) === 'rejected')
                            <div class="request-status-box status-rejected">
                                <i class="ri-close-line me-1"></i>
                                Your request has been rejected
                            </div>
                        @else
                            <div class="request-status-box status-none">
                                <i class="ri-information-line me-1"></i>
                                You have not requested this service yet
                            </div>
                        @endif
                    </div>

                    {{-- أزرار الطلب --}}
                    <div class="mt-auto">
                        <div class="d-flex gap-2">

                            @if(empty($service->user_request_status))
                                {{-- طلب الخدمة --}}
                                <form action="{{ route('pendingserrec', $service->id) }}" method="POST" class="w-100">
                                    @csrf
                                    <button class="btn btn-primary w-100 action-btn">
                                        <i class="ri-send-plane-line me-1"></i>
                                        Request Service
                                    </button>
                                </form>
                            @elseif($service->user_request_status === 'pending')
                                {{-- إلغاء الطلب --}}
                                <form action="{{ route('service.cancel', $service->id) }}" method="POST" class="w-100">
                                    @csrf
                                    <button class="btn btn-outline-danger w-100 action-btn">
                                        <i class="ri-close-circle-line me-1"></i>
                                        Cancel Request
                                    </button>
                                </form>
                            @elseif($service->user_request_status === 'rejected')
                                {{-- إعادة الطلب --}}
                                <form action="{{ route('service.request', $service->id) }}" method="POST" class="w-100">
                                    @csrf
                                    <button class="btn btn-outline-primary w-100 action-btn">
                                        <i class="ri-restart-line me-1"></i>
                                        Request Again
                                    </button>
                                </form>
                            @elseif($service->user_request_status === 'approved')
                                <button class="btn btn-success w-100 action-btn" disabled>
                                    <i class="ri-checkbox-circle-line me-1"></i>
                                    Service Approved
                                </button>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">
                No services found.
            </div>
        </div>
    @endforelse
</div>

@endsection

@push('styles')
<style>
.service-card {
    border-radius: 18px;
    transition: 0.3s ease;
    overflow: hidden;
    background: #fff;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.10) !important;
}

.service-card .card-title {
    line-height: 1.4;
    min-height: 48px;
    font-weight: 600;
}

.service-description {
    min-height: 60px;
}

.service-badge {
    border-radius: 20px;
    padding: 8px 12px;
    font-size: 12px;
}

.service-details-box {
    background: #f8f8fc;
    border-radius: 14px;
    padding: 10px 14px;
}

.service-details-box .list-group-item {
    background: transparent;
    border-color: #ececf3;
    font-size: 14px;
    padding-top: 10px;
    padding-bottom: 10px;
}

.service-details-box .list-group-item:last-child {
    border-bottom: 0;
}

.request-status-box {
    padding: 12px 14px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 500;
    border: 1px solid transparent;
}

.status-none {
    background: #f7f7fb;
    color: #6c757d;
    border-color: #ececf3;
}

.status-pending {
    background: rgba(255, 193, 7, 0.12);
    color: #9a6b00;
    border-color: rgba(255, 193, 7, 0.25);
}

.status-approved {
    background: rgba(40, 167, 69, 0.12);
    color: #1f7a36;
    border-color: rgba(40, 167, 69, 0.25);
}

.status-rejected {
    background: rgba(220, 53, 69, 0.12);
    color: #a52834;
    border-color: rgba(220, 53, 69, 0.25);
}

.action-btn {
    border-radius: 12px;
    font-weight: 600;
    padding: 11px 14px;
    transition: 0.25s ease;
}

.action-btn:hover:not(:disabled) {
    transform: translateY(-1px);
}

.btn:disabled {
    opacity: 0.9;
    cursor: not-allowed;
}

/* زر النجمة */
.favorite-btn {
    width: 42px;
    height: 42px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.95);
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    transition: all 0.25s ease;
    font-size: 20px;
}

.favorite-btn:hover {
    transform: scale(1.08);
    background: #fff;
    color: #f4b400;
}

.favorite-btn.active {
    background: #fff7db;
    color: #f4b400;
    border: 1px solid rgba(244, 180, 0, 0.25);
}
</style>
@endpush
