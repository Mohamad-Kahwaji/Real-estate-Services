@extends('layouts/contentNavbarLayout')

@section('title', 'Pending Service Requests')

@section('content')

<h4 class="mb-1">Pending Service Requests</h4>
<p class="mb-4 text-muted">Review and manage incoming service requests</p>

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
    @forelse($requests as $request)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card request-card h-100 border-0 shadow-sm">

                <img
                    class="card-img-top"
                    src="{{ asset('assets/img/elements/5.png') }}"
                    alt="Service image"
                    style="height: 220px; object-fit: cover;"
                >

                <div class="card-body d-flex flex-column">



                    <div class="mb-2 text-muted small">
                        <i class="ri-briefcase-4-line me-1"></i>
                        {{ $request->service->business->job_name_en ?? 'No business name' }}
                    </div>

                    <h5 class="card-title mb-2">
                        {{ $request->service->title ?? '-' }}
                    </h5>

                    <p class="text-body-secondary small mb-3">
                        {{ $request->service->category->name_en ?? 'Uncategorized' }}
                    </p>

                    <div class="request-details-box mb-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-medium">Status</span>
                                <span class="badge bg-warning text-dark">Pending</span>
                            </li>

                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-medium">Price (USD)</span>
                                <span>{{ $request->service->price_usd ?? '-' }}</span>
                            </li>

                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-medium">Price (SYP)</span>
                                <span>{{ $request->service->price_syp ?? '-' }}</span>
                            </li>

                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="fw-medium">Requested At</span>
                                <span>{{ $request->created_at?->format('Y-m-d') ?? '-' }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-auto d-flex gap-2">
                        <form action="{{ route('approveserrec', $request->id) }}" method="POST" class="w-50">
                            @csrf
                            <button class="btn btn-success w-100 action-btn">
                                <i class="ri-check-line me-1"></i>
                                Approve
                            </button>
                        </form>

                        <form action="{{ route('rejectserrec', $request->id) }}" method="POST" class="w-50">
                            @csrf
                            <button class="btn btn-outline-danger w-100 action-btn">
                                <i class="ri-close-line me-1"></i>
                                Reject
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">
                No pending requests found.
            </div>
        </div>
    @endforelse
</div>

@endsection

@push('styles')
<style>
.request-card {
    border-radius: 18px;
    overflow: hidden;
    transition: all 0.25s ease;
    background: #fff;
}

.request-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 30px rgba(0, 0, 0, 0.12) !important;
}

.request-card .card-title {
    line-height: 1.4;
    min-height: 48px;
    font-weight: 600;
}

.request-details-box {
    background: #f8f8fc;
    border-radius: 14px;
    padding: 10px 14px;
}

.request-details-box .list-group-item {
    background: transparent;
    border-color: #ececf3;
    font-size: 14px;
    padding-top: 10px;
    padding-bottom: 10px;
}

.request-details-box .list-group-item:last-child {
    border-bottom: 0;
}

.action-btn {
    border-radius: 12px;
    font-weight: 600;
    padding: 11px 14px;
    transition: 0.25s ease;
}

.action-btn:hover {
    transform: translateY(-1px);
}
</style>
@endpush
