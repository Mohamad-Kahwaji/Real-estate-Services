@extends('layouts/contentNavbarLayout')

@section('title', 'My Requested Services')

@section('content')

<h4 class="mb-1">My Requested Services</h4>
<p class="mb-4 text-muted">All services you have requested</p>

<div class="row g-4">
    @forelse($requests as $request)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 border-0 shadow-sm service-card">

                <img
                    class="card-img-top"
                    src="{{ asset('assets/img/elements/5.png') }}"
                    alt="Service image"
                    style="height: 220px; object-fit: cover;"
                >

                <div class="card-body d-flex flex-column">
                    <p class="text-muted small mb-2">
                        {{ $request->service->business->job_name_en ?? 'No business name' }}
                    </p>

                    <h5 class="card-title mb-2">
                        {{ $request->service->title ?? '-' }}
                    </h5>

                    <p class="small text-body-secondary mb-2">
                        {{ $request->service->category->name_en ?? 'Uncategorized' }}
                    </p>

                    <p class="mb-2">
                        <strong>Price:</strong>
                        {{ $request->service->price ?? '-' }} {{ $request->service->currency ?? '' }}
                    </p>

                    <p class="mb-3">
                        <strong>Status:</strong>
                        @if($request->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($request->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </p>

                    <p class="text-muted small mb-0">
                        Requested at: {{ $request->created_at->format('Y-m-d') }}
                    </p>
                </div>

            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                You have not requested any services yet.
            </div>
        </div>
    @endforelse
</div>

@endsection

<style>
    .service-card {
        border-radius: 18px;
        overflow: hidden;
        transition: all 0.25s ease;
    }

    .service-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 30px rgba(0, 0, 0, 0.12) !important;
    }

    .service-card .card-title {
        line-height: 1.4;
        min-height: 48px;
    }
</style>
