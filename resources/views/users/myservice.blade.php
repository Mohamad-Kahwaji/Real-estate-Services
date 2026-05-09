@extends('layouts/contentNavbarLayout')

@section('title', 'Services Marketplace')

@section('content')

<h4 class="mb-4">Services Marketplace</h4>

<div class="row g-4">
    @foreach($services as $service)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 service-card border-0 shadow-sm">

                {{-- صورة الخدمة --}}
                <div class="position-relative">
                    <img
                        class="card-img-top"
                        src="{{ asset('assets/img/elements/5.png') }}"
                        alt="Service image"
                        style="height: 220px; object-fit: cover;"
                    >

                    {{-- الحالة --}}
                    <div class="position-absolute top-0 end-0 m-3">
                        @if($service->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($service->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </div>
                </div>

                <div class="card-body d-flex flex-column">

                    {{-- اسم النشاط --}}
                    <div class="info-row mb-2">
                        <span class="info-label">Business:</span>
                        <span class="info-value">{{ $service->business->job_name_en ?? 'Unknown Business' }}</span>
                    </div>

                    {{-- عنوان الخدمة --}}
                    <div class="info-row mb-2">
                        <span class="info-label">Title:</span>
                        <span class="info-value fw-semibold">{{ $service->title }}</span>
                    </div>

                    {{-- التصنيف --}}
                    <div class="info-row mb-2">
                        <span class="info-label">Category:</span>
                        <span class="info-value">{{ $service->category->name_en ?? 'Uncategorized' }}</span>
                    </div>

                    {{-- السعر --}}
                    <div class="info-row mb-3">
                        <span class="info-label">Price:</span>
                        <span class="info-value text-primary fw-bold">
                            {{ $service->price }} {{ $service->currency }}
                        </span>
                    </div>

                    {{-- الحالة --}}
                    <div class="info-row mb-3">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            @if($service->status == 'approved')
                                <span class="text-success fw-semibold">Approved</span>
                            @elseif($service->status == 'rejected')
                                <span class="text-danger fw-semibold">Rejected</span>
                            @else
                                <span class="text-warning fw-semibold">Pending</span>
                            @endif
                        </span>
                    </div>

                    {{-- أزرار التحكم --}}
                    <div class="d-flex gap-2 mt-auto">
                        <a href="{{ route('services.edit', $service->id) }}" class="btn btn-outline-primary w-100">
                            <i class="ri-edit-line me-1"></i>
                            Edit
                        </a>
                    </div>

                </div>

            </div>
        </div>
    @endforeach
</div>

@endsection

@push('styles')
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

    .service-card .btn {
        border-radius: 10px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f1f1;
    }

    .info-label {
        font-weight: 600;
        color: #6c757d;
        min-width: 80px;
    }

    .info-value {
        text-align: right;
        color: #2f2b3d;
        flex: 1;
    }
</style>
@endpush
