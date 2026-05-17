@extends('layouts/contentNavbarLayout')

@section('title', 'Reviews')

@section('page-style')
<style>
.rev-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 20px rgba(105,108,255,.08);
    overflow: hidden;
    transition: .2s;
}
.rev-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(105,108,255,.13); }

.rev-header { padding: 18px 20px 14px; border-bottom: 1px solid #f1f3f9; }
.rev-service-title { font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 3px; }
.rev-business      { font-size: 12px; color: #8592a3; }

.stars { color: #ff9f43; font-size: 16px; letter-spacing: 1px; }
.stars .empty { color: #dde1ec; }

.rev-body { padding: 14px 20px; }
.rev-user  { font-size: 13px; font-weight: 600; color: #312d4b; }
.rev-date  { font-size: 11px; color: #b0aab8; }
.rev-comment {
    background: #fafbff;
    border: 1px solid #eef0f8;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 13px;
    color: #5d6a82;
    line-height: 1.6;
    margin-top: 10px;
}

.empty-state { text-align:center; padding:60px 20px; }
.empty-state i { font-size:48px; color:#d5d8ff; display:block; margin-bottom:14px; }
</style>
@endsection

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Reviews</h4>
        <p class="text-muted small mb-0">All service reviews submitted by users</p>
    </div>
    <span class="badge px-3 py-2 rounded-pill"
          style="background:#eef0ff;color:#696cff;font-size:13px;font-weight:700;">
        {{ $reviews->count() }} Reviews
    </span>
</div>

@if(session('success'))
<div class="alert alert-success rounded-3 mb-4">{{ session('success') }}</div>
@endif

<div class="row g-3">
    @forelse($reviews as $review)
    <div class="col-12 col-md-6 col-xl-4">
        <div class="rev-card h-100">

            {{-- Service info --}}
            <div class="rev-header d-flex justify-content-between align-items-start">
                <div>
                    <div class="rev-service-title">
                        <i class="ri ri-store-3-line me-1" style="color:#696cff;"></i>
                        {{ $review->service->title ?? 'Unknown Service' }}
                    </div>
                    <div class="rev-business">
                        {{ $review->service->business->job_name_en ?? '—' }}
                    </div>
                </div>
                {{-- Stars --}}
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="ri {{ $i <= $review->rating ? 'ri-star-fill' : 'ri-star-line empty' }}"></i>
                    @endfor
                </div>
            </div>

            {{-- User + comment --}}
            <div class="rev-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="rev-user">
                        <i class="ri ri-user-3-line me-1" style="color:#8592a3;"></i>
                        {{ $review->user->name ?? 'Anonymous' }}
                    </div>
                    <div class="rev-date">
                        {{ $review->created_at?->format('M d, Y') }}
                    </div>
                </div>

                @if($review->comment)
                <div class="rev-comment">{{ $review->comment }}</div>
                @else
                <div class="rev-comment" style="color:#b0aab8;font-style:italic;">No comment provided.</div>
                @endif

                {{-- Delete --}}
                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="mt-3">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="btn btn-sm w-100 rounded-3 fw-bold"
                            style="background:#fdeaea;color:#ea5455;border:none;font-size:12px;"
                            onclick="return confirm('Delete this review?')">
                        <i class="ri ri-delete-bin-line me-1"></i>Delete Review
                    </button>
                </form>
            </div>

        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <i class="ri ri-star-line"></i>
            <p class="fw-bold mb-1">No Reviews Yet</p>
            <p class="small text-muted mb-0">Reviews will appear here once users submit them.</p>
        </div>
    </div>
    @endforelse
</div>

@endsection
