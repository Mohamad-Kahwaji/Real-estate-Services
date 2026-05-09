@extends('layouts/contentNavbarLayout')

@section('title', 'Received Requests')

@section('content')

<h4 class="mb-1">Received Requests</h4>
<p class="mb-4 text-muted">Orders Received</p>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row g-4">
    @foreach($serviceRequests as $request)
        <div class="col-sm-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm service-card">

                <img
                    class="card-img-top"
                    src="{{ asset('assets/img/elements/5.png') }}"
                    alt="Service image"
                    style="height: 220px; object-fit: cover;"
                >

                <div class="card-body d-flex flex-column">
                    <p class="text-muted small mb-2">
                        طالب الخدمة: {{ $request->user->name ?? '-' }}
                    </p>

                    <h5 class="card-title mb-2">
                        {{ $request->service->title ?? '-' }}
                    </h5>

                    <p class="small text-body-secondary mb-2">
                        التصنيف: {{ $request->service->category->name_en ?? '-' }}
                    </p>

                    <p class="mb-2">
                        <strong>السعر:</strong>
                        {{ $request->service->price ?? '-' }} {{ $request->service->currency ?? '' }}
                    </p>

                    <p class="mb-3">
                        <strong>الحالة:</strong>
                        {{ $request->status == 'approved' }}
                            <span class="badge bg-success">Approved</span>

                    </p>

                    <div class="mb-2">
                      <form action="">
                        <label class="form-label review-label">favorite</label>
                        <option value="5"> ⭐</option>

                      </form>
                      </div>


                    {{-- فورم التقييم --}}
                        <div class="review-box">
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf

                                <input type="hidden" name="service_id" value="{{ $service->id }}">

                                <div class="mb-2">
                                    <label class="form-label review-label">التقييم</label>
                                    <select name="rating" class="form-select">
                                        <option value="5">5 ⭐</option>
                                        <option value="4">4 ⭐</option>
                                        <option value="3">3 ⭐</option>
                                        <option value="2">2 ⭐</option>
                                        <option value="1">1 ⭐</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label review-label">التعليق</label>
                                    <textarea name="comment" class="form-control" rows="3" placeholder="اكتب تعليقك هنا..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    إرسال التقييم
                                </button>
                            </form>
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

.admin-actions .btn,
.review-box .btn,
.review-box .form-control,
.review-box .form-select {
    border-radius: 12px;
}

.admin-actions .btn {
    font-weight: 500;
}

.review-box {
    margin-top: 10px;
    padding: 14px;
    background: #fafafe;
    border: 1px solid #ececf3;
    border-radius: 16px;
}

.review-label {
    font-size: 13px;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 6px;
}

.review-box textarea {
    resize: none;
    min-height: 90px;
}

.review-box .form-control,
.review-box .form-select {
    border: 1px solid #dfe3ea;
    box-shadow: none;
}

.review-box .form-control:focus,
.review-box .form-select:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 0.15rem rgba(105, 108, 255, 0.15);
}
</style>
@endpush

