@extends('layouts/contentNavbarLayout')

@section('title', __('app.reviews'))

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

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1">{{ __('app.reviews') }}</h4>
        <p class="text-muted small mb-0">{{ __('app.reviews_desc_admin') }}</p>
    </div>
    <form method="GET" action="{{ route('reviews.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
        <div class="input-group input-group-sm" style="width:220px;">
            <span class="input-group-text"><i class="ri ri-search-line"></i></span>
            <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_reviews') }}" value="{{ request('search') }}">
        </div>
        <select name="rating" class="form-select form-select-sm" style="width:130px;">
            <option value="">{{ __('app.all_ratings') }}</option>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} ★</option>
            @endfor
        </select>
        <button type="submit" class="btn btn-primary btn-sm">{{ __('app.filter') }}</button>
        @if(request('search') || request('rating'))
            <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('app.clear') }}</a>
        @endif
    </form>
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
                <div class="rev-comment" style="color:#b0aab8;font-style:italic;">{{ __('app.no_comment') }}</div>
                @endif

                {{-- Delete --}}
                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="mt-3">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="btn btn-sm w-100 rounded-3 fw-bold"
                            style="background:#fdeaea;color:#ea5455;border:none;font-size:12px;"
                            onclick="return confirm('{{ __('app.confirm_delete') }}')">
                        <i class="ri ri-delete-bin-line me-1"></i>{{ __('app.delete_review') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <i class="ri ri-star-line"></i>
            <p class="fw-bold mb-1">{{ __('app.no_reviews') }}</p>
            <p class="small text-muted mb-0">{{ __('app.reviews_empty_desc') }}</p>
        </div>
    </div>
    @endforelse
</div>

@if($reviews->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $reviews->links() }}
</div>
@endif

@endsection
