@extends('layouts/contentNavbarLayout')

@section('title', __('app.all_services'))

@section('content')

@php $isAr = app()->getLocale() === 'ar'; @endphp

<h4 class="mb-1">{{ __('app.all_services') }}</h4>
<p class="mb-4 text-muted">{{ __('app.browse_all_services') }}</p>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- ═══ MAP FILTER PANEL ═══ --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" id="mapFilterPanel">
    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between px-4 py-3">
        <div class="d-flex align-items-center gap-2">
            <i class="ri-map-pin-range-line text-primary fs-5"></i>
            <span class="fw-semibold">{{ __('app.filter_by_location') }}</span>
            <span id="filterBadge" class="badge bg-primary-subtle text-primary rounded-pill d-none" style="font-size:12px;"></span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select id="radiusSelect" class="form-select form-select-sm rounded-3" style="width:140px;">
                <option value="">{{ __('app.all_syria') }}</option>
                <option value="5">{{ __('app.within_km', ['count' => 5]) }}</option>
                <option value="10">{{ __('app.within_km', ['count' => 10]) }}</option>
                <option value="25">{{ __('app.within_km', ['count' => 25]) }}</option>
                <option value="50">{{ __('app.within_km', ['count' => 50]) }}</option>
            </select>
            <button id="useMyLocationBtn" class="btn btn-sm btn-outline-primary rounded-3" title="{{ __('app.my_location') }}">
                <i class="ri-focus-3-line me-1"></i>{{ __('app.my_location') }}
            </button>
            <button id="resetFilterBtn" class="btn btn-sm btn-outline-secondary rounded-3 d-none">
                <i class="ri-refresh-line me-1"></i>{{ __('app.reset') }}
            </button>
            <button id="toggleMapBtn" class="btn btn-sm btn-light rounded-3">
                <i class="ri-arrow-up-s-line" id="toggleMapIcon"></i>
            </button>
        </div>
    </div>
    <div id="filterMapWrapper">
        <div id="filterMap" style="height:350px;"></div>
        <div class="px-4 py-2 bg-light border-top d-flex align-items-center gap-2" style="font-size:13px; color:#6c757d;">
            <i class="ri-cursor-line"></i>
            <span id="filterHint">{{ __('app.click_map_hint') }}</span>
        </div>
    </div>
</div>

{{-- Result count --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <p class="mb-0 text-muted small" id="resultCount">
        {!! __('app.showing_of_services', ['shown' => '<strong id="shownCount">'.$services->count().'</strong>', 'total' => $services->count()]) !!}
    </p>
</div>

<div class="row g-4" id="servicesGrid">
    @forelse($services as $service)
    @php
        $categoryName = $isAr ? ($service->category->name_ar ?? $service->category->name_en ?? '-') : ($service->category->name_en ?? '-');
        $bizName = $isAr ? ($service->business->job_name_ar ?? $service->business->job_name_en ?? __('app.no_business_name')) : ($service->business->job_name_en ?? __('app.no_business_name'));
        $activeTypeName = $isAr ? ($service->business->activeType->name_ar ?? $service->business->activeType->name ?? '') : ($service->business->activeType->name ?? '');
    @endphp
    <div class="col-sm-6 col-lg-4 col-xl-3 service-grid-item"
         data-lat="{{ $service->latitude ?? '' }}"
         data-lng="{{ $service->longitude ?? '' }}">
        <div class="card service-card h-100 border-0 shadow-sm">

            <div class="position-relative">
                <img class="card-img-top" src="{{ $service->image_url }}" alt="{{ $service->title }}"
                     style="height: 220px; object-fit: cover;">

                <div class="position-absolute top-0 start-0 m-3">
                    <form action="{{ route('favorite.toggle', $service->id) }}" method="POST">
                        @csrf
                        <button class="favorite-btn {{ in_array($service->id, $favorites ?? []) ? 'active' : '' }}">
                            <i class="ri-star-fill"></i>
                        </button>
                    </form>
                </div>

                <div class="position-absolute top-0 end-0 m-3">
                    @if($service->status == 'approved')
                        <span class="badge bg-success service-badge">{{ __('app.approved') }}</span>
                    @elseif($service->status == 'rejected')
                        <span class="badge bg-danger service-badge">{{ __('app.rejected') }}</span>
                    @else
                        <span class="badge bg-warning text-dark service-badge">{{ __('app.pending') }}</span>
                    @endif
                </div>
            </div>

            <div class="card-body d-flex flex-column">

                <div class="mb-2 text-muted small">
                    <i class="ri-briefcase-4-line me-1"></i>{{ $bizName }}
                </div>

                <h5 class="card-title mb-1">{{ $service->title }}</h5>

                @php
                    $avgRating   = round($service->review_avg_rating ?? 0, 1);
                    $reviewCount = $service->review_count ?? 0;
                    $fullStars   = (int) floor($avgRating);
                    $halfStar    = ($avgRating - $fullStars) >= 0.5;
                @endphp
                <div class="svc-rating-row mb-2">
                    @if($avgRating > 0)
                        <div class="d-flex align-items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <i class="ri-star-fill svc-star filled"></i>
                                @elseif($i == $fullStars + 1 && $halfStar)
                                    <i class="ri-star-half-fill svc-star filled"></i>
                                @else
                                    <i class="ri-star-line svc-star empty"></i>
                                @endif
                            @endfor
                            <span class="svc-rating-score">{{ number_format($avgRating, 1) }}</span>
                            @if($reviewCount > 0)
                                <span class="svc-rating-count">({{ $reviewCount }} {{ __('app.review') }})</span>
                            @endif
                        </div>
                    @else
                        <span class="svc-no-rating"><i class="ri-star-line me-1"></i>{{ __('app.no_reviews_yet') }}</span>
                    @endif
                </div>

                <p class="card-text text-muted small mb-3 service-description">
                    {{ \Illuminate\Support\Str::limit($service->description ?? __('app.no_description_available'), 100) }}
                </p>

                <div class="service-details-box mb-3">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="fw-medium">{{ __('app.category') }}</span>
                            <span>{{ $categoryName }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="fw-medium">{{ __('app.price_usd') }}</span>
                            <span>{{ $service->price_usd ?? '-' }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="fw-medium">{{ __('app.price_syp') }}</span>
                            <span>{{ $service->price_syp ?? '-' }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="fw-medium">{{ __('app.city') }}</span>
                            <span>{{ $service->location_name }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="fw-medium">{{ __('app.created_label') }}</span>
                            <span>{{ $service->created_at?->format('Y-m-d') ?? '-' }}</span>
                        </li>
                        @if($service->latitude && $service->longitude)
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="fw-medium"><i class="ri-map-pin-line text-danger me-1"></i>{{ __('app.location') }}</span>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2 rounded-3"
                                        style="font-size:11px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#mapModal{{ $service->id }}">
                                    <i class="ri-map-2-line me-1"></i>{{ __('app.map') }}
                                </button>
                                <a href="https://www.google.com/maps?q={{ $service->latitude }},{{ $service->longitude }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary py-0 px-2 rounded-3"
                                   style="font-size:11px;">
                                    <i class="ri-external-link-line"></i>
                                </a>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>

                <div class="mb-3">
                    @if(($service->user_request_status ?? null) === 'pending')
                        <div class="request-status-box status-pending">
                            <i class="ri-time-line me-1"></i>{{ __('app.request_pending_status') }}
                        </div>
                    @elseif(($service->user_request_status ?? null) === 'approved')
                        <div class="request-status-box status-approved">
                            <i class="ri-check-line me-1"></i>{{ __('app.request_approved_status') }}
                        </div>
                    @elseif(($service->user_request_status ?? null) === 'rejected')
                        <div class="request-status-box status-rejected">
                            <i class="ri-close-line me-1"></i>{{ __('app.request_rejected_status') }}
                        </div>
                    @else
                        <div class="request-status-box status-none">
                            <i class="ri-information-line me-1"></i>{{ __('app.not_requested_yet') }}
                        </div>
                    @endif
                </div>

                <button class="btn btn-light w-100 mb-2 rounded-3"
                        style="font-size:13px;font-weight:600;border:1px solid #e5e3ef;"
                        data-bs-toggle="modal"
                        data-bs-target="#detailModal{{ $service->id }}">
                    <i class="ri-eye-line me-1"></i> {{ __('app.view_full_details') }}
                </button>

                <div class="mt-auto">
                    @if(empty($service->user_request_status))
                        <button class="btn btn-primary w-100 action-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#requestModal{{ $service->id }}">
                            <i class="ri-send-plane-line me-1"></i>{{ __('app.request_service') }}
                        </button>
                    @elseif($service->user_request_status === 'pending')
                        <button class="btn btn-warning w-100 action-btn" disabled>
                            <i class="ri-time-line me-1"></i>{{ __('app.request_pending_btn') }}
                        </button>
                    @elseif($service->user_request_status === 'rejected')
                        <button class="btn btn-outline-primary w-100 action-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#requestModal{{ $service->id }}">
                            <i class="ri-restart-line me-1"></i>{{ __('app.request_again') }}
                        </button>
                    @elseif($service->user_request_status === 'approved')
                        <button class="btn btn-success w-100 action-btn" disabled>
                            <i class="ri-checkbox-circle-line me-1"></i>{{ __('app.service_approved_btn') }}
                        </button>
                    @endif

                    @php
                        $ownerId = $service->business?->user_id;
                        $isFav   = in_array($service->id, $favorites ?? []);
                    @endphp
                    <div class="d-flex gap-2 mt-2">
                        <form action="{{ route('favorite.toggle', $service->id) }}" method="POST" style="flex:1;min-width:0;">
                            @csrf
                            <button type="submit" class="btn w-100 action-btn {{ $isFav ? 'btn-warning' : 'btn-outline-warning' }}">
                                <i class="ri-star-{{ $isFav ? 'fill' : 'line' }} me-1"></i>
                                {{ $isFav ? __('app.saved') : __('app.save') }}
                            </button>
                        </form>
                        @if($ownerId && $ownerId !== auth('users')->id())
                        <a href="{{ route('chat.show', $ownerId) }}"
                           class="btn btn-outline-secondary action-btn" style="flex:1;min-width:0;">
                            <i class="ri-chat-1-line me-1"></i>{{ __('app.chat') }}
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Request Modal --}}
                <div class="modal fade" id="requestModal{{ $service->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold">{{ __('app.request_service') }}: {{ $service->title }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('servicerequest.store', $service->id) }}" method="POST">
                                @csrf
                                <div class="modal-body pt-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">{{ __('app.quantity') }}</label>
                                        <input type="number" name="quantity" class="form-control" min="1" max="{{ $service->quantity }}" value="1" required>
                                        <small class="text-muted">{{ __('app.available_count', ['count' => $service->quantity]) }}</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">{{ __('app.needed_by') }}</label>
                                        <input type="date" name="needed_at" class="form-control" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">{{ __('app.notes') }} <span class="text-muted">({{ __('app.optional') }})</span></label>
                                        <textarea name="details" class="form-control" rows="3" placeholder="{{ __('app.any_additional_details') }}"></textarea>
                                    </div>
                                    <div class="alert alert-info py-2 px-3 small mb-0">
                                        <i class="ri-information-line me-1"></i>{{ __('app.after_submit_redirect') }}
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                                    <button type="submit" class="btn btn-primary rounded-3 px-4">
                                        <i class="ri-send-plane-line me-1"></i>{{ __('app.submit_request') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div class="modal fade" id="detailModal{{ $service->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <div class="position-relative">
                    <img src="{{ $service->image_url }}" alt="{{ $service->title }}"
                         style="width:100%;height:220px;object-fit:cover;">
                    <div style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 40%,rgba(0,0,0,.65));"></div>
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                    <div class="position-absolute bottom-0 start-0 p-4">
                        <h5 class="text-white fw-bold mb-1">{{ $service->title }}</h5>
                        <span class="badge bg-white text-dark" style="font-size:11px;">{{ $categoryName }}</span>
                    </div>
                </div>

                <div class="modal-body p-4">
                    @if($service->description)
                    <p style="font-size:14px;color:#5d596c;line-height:1.7;margin-bottom:20px;">{{ $service->description }}</p>
                    @endif

                    <div class="row g-3">

                        <div class="col-12">
                            <div style="background:#f8f7ff;border-radius:14px;padding:16px 18px;">
                                <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9391a4;margin-bottom:12px;">
                                    <i class="ri-store-3-line me-1"></i> {{ __('app.service_info') }}
                                </p>
                                <div class="row g-2" style="font-size:13px;">
                                    <div class="col-6 col-md-4">
                                        <span style="color:#9391a4;">{{ __('app.quantity') }}</span><br>
                                        <strong>{{ $service->quantity ?? '—' }}</strong>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <span style="color:#9391a4;">{{ __('app.price_usd') }}</span><br>
                                        <strong>{{ $service->price_usd ? '$'.number_format($service->price_usd,2) : '—' }}</strong>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <span style="color:#9391a4;">{{ __('app.price_syp') }}</span><br>
                                        <strong>{{ $service->price_syp ? number_format($service->price_syp).' ل.س' : '—' }}</strong>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <span style="color:#9391a4;">{{ __('app.city') }}</span><br>
                                        <strong>{{ $service->location_name }}</strong>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <span style="color:#9391a4;">{{ __('app.category') }}</span><br>
                                        <strong>{{ $categoryName }}</strong>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <span style="color:#9391a4;">{{ __('app.added_label') }}</span><br>
                                        <strong>{{ $service->created_at?->format('Y-m-d') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php $biz = $service->business; $bizOwner = $biz?->user; @endphp
                        @if($biz)
                        <div class="col-12">
                            <div style="background:#f0f0ff;border-radius:14px;padding:16px 18px;">
                                <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9391a4;margin-bottom:12px;">
                                    <i class="ri-building-4-line me-1"></i> {{ __('app.business_account_section') }}
                                </p>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if($biz->image)
                                    <img src="{{ asset('storage/'.$biz->image) }}"
                                         style="width:52px;height:52px;border-radius:12px;object-fit:cover;border:2px solid #d4d4ff;">
                                    @else
                                    <div style="width:52px;height:52px;border-radius:12px;background:#d4d4ff;
                                                display:flex;align-items:center;justify-content:center;font-size:22px;color:var(--role-accent);">
                                        <i class="ri-building-4-line"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <div style="font-size:15px;font-weight:800;color:#312d4b;">{{ $bizName }}</div>
                                        @if($biz->activeType)
                                        <div style="font-size:12px;color:#9391a4;">{{ $activeTypeName }}</div>
                                        @endif
                                    </div>
                                </div>
                                @if($biz->details)
                                <p style="font-size:13px;color:#5d596c;margin-bottom:10px;">{{ $biz->details }}</p>
                                @endif
                                @if($bizOwner)
                                <div style="font-size:13px;color:#9391a4;">
                                    <i class="ri-user-line me-1"></i>
                                    {{ __('app.owner') }}: <strong style="color:#312d4b;">{{ $bizOwner->name }}</strong>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    @php $ownerId2 = $service->business?->user_id; @endphp
                    @if($ownerId2 && $ownerId2 !== auth('users')->id())
                    <a href="{{ route('chat.show', $ownerId2) }}" class="btn btn-outline-secondary rounded-3 px-4">
                        <i class="ri-chat-1-line me-1"></i> {{ __('app.chat') }}
                    </a>
                    @endif
                    @if(empty($service->user_request_status))
                    <button class="btn btn-primary rounded-3 px-4"
                            data-bs-dismiss="modal"
                            data-bs-toggle="modal"
                            data-bs-target="#requestModal{{ $service->id }}">
                        <i class="ri-send-plane-line me-1"></i> {{ __('app.request_service') }}
                    </button>
                    @endif
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">
                        {{ __('app.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Map Modal --}}
    @if($service->latitude && $service->longitude)
    <div class="modal fade" id="mapModal{{ $service->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="modal-header border-0 px-4 py-3">
                    <div>
                        <h6 class="fw-bold mb-0">{{ $service->title }}</h6>
                        <small class="text-muted">
                            <i class="ri-map-pin-line me-1 text-danger"></i>{{ $service->location_name }}
                        </small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div id="userMap{{ $service->id }}" style="height:360px;"></div>
                <div class="modal-footer border-0 px-4 py-3 justify-content-between">
                    <a href="https://www.google.com/maps?q={{ $service->latitude }},{{ $service->longitude }}"
                       target="_blank"
                       class="btn btn-sm rounded-3 fw-bold"
                       style="background:#4285f4;color:#fff;border:0;">
                        <i class="ri-map-2-line me-1"></i>{{ __('app.open_in_google_maps') }}
                    </a>
                    <button type="button" class="btn btn-sm btn-light rounded-3" data-bs-dismiss="modal">{{ __('app.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    @endif

@empty
    <div class="col-12">
        <div class="alert alert-info mb-0">{{ __('app.no_services_found') }}</div>
    </div>
@endforelse
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
.service-card { border-radius:18px; transition:0.3s ease; overflow:hidden; background:#fff; }
.service-card:hover { transform:translateY(-5px); box-shadow:0 14px 28px rgba(0,0,0,.10)!important; }
.service-card .card-title { line-height:1.4; min-height:48px; font-weight:600; }
.service-description { min-height:60px; }
.service-badge { border-radius:20px; padding:8px 12px; font-size:12px; }
.service-details-box { background:#f8f8fc; border-radius:14px; padding:10px 14px; }
.service-details-box .list-group-item { background:transparent; border-color:#ececf3; font-size:14px; padding-top:10px; padding-bottom:10px; }
.service-details-box .list-group-item:last-child { border-bottom:0; }
.request-status-box { padding:12px 14px; border-radius:14px; font-size:13px; font-weight:500; border:1px solid transparent; }
.status-none     { background:#f7f7fb; color:#6c757d; border-color:#ececf3; }
.status-pending  { background:rgba(255,193,7,.12); color:#9a6b00; border-color:rgba(255,193,7,.25); }
.status-approved { background:rgba(40,167,69,.12); color:#1f7a36; border-color:rgba(40,167,69,.25); }
.status-rejected { background:rgba(220,53,69,.12); color:#a52834; border-color:rgba(220,53,69,.25); }
.action-btn { border-radius:12px; font-weight:600; padding:11px 14px; transition:.25s ease; }
.action-btn:hover:not(:disabled) { transform:translateY(-1px); }
.btn:disabled { opacity:.9; cursor:not-allowed; }
#filterMap { z-index:1; }
#mapFilterPanel .card-header { border-bottom:1px solid #f0f0f7; }
.leaflet-popup-content-wrapper { border-radius:12px!important; }
.svc-rating-row { min-height:22px; }
.svc-star { font-size:15px; line-height:1; }
.svc-star.filled { color:#f59e0b; }
.svc-star.empty  { color:#d1d5db; }
.svc-rating-score { font-size:13px; font-weight:800; color:#1f2937; margin-left:3px; }
.svc-rating-count { font-size:12px; color:#9ca3af; margin-left:1px; }
.svc-no-rating    { font-size:12px; color:#9ca3af; }
.favorite-btn { width:42px; height:42px; border:none; border-radius:50%; background:rgba(255,255,255,.95); color:#6c757d; display:flex; align-items:center; justify-content:center; box-shadow:0 6px 16px rgba(0,0,0,.12); transition:all .25s ease; font-size:20px; }
.favorite-btn:hover { transform:scale(1.08); background:#fff; color:#f4b400; }
.favorite-btn.active { background:#fff7db; color:#f4b400; border:1px solid rgba(244,180,0,.25); }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const TRANS = {
    clickMapHint:      '{{ __("app.click_map_hint") }}',
    showingWithinKm:   '{{ __("app.showing_within_km") }}',
    results:           '{{ __("app.no_results") }}',
    searchCenter:      '{{ __("app.search_center_label") }}',
    yourLocation:      '{{ __("app.your_location_label") }}',
    geoNotSupported:   '{{ __("app.geolocation_not_supported_browser") }}',
    couldNotGetLoc:    '{{ __("app.could_not_get_location_browser") }}',
    autoSelect10km:    true,
};

(function () {
    const filterMap = L.map('filterMap', { zoomControl: true }).setView([34.8, 38.5], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(filterMap);

    const servicePoints = [
        @foreach($services as $s)
        @if($s->latitude && $s->longitude)
        { lat: {{ $s->latitude }}, lng: {{ $s->longitude }}, title: @json($s->title), city: @json($s->location_name), id: {{ $s->id }} },
        @endif
        @endforeach
    ];

    const serviceIcon = L.divIcon({
        className: '',
        html: '<div style="background:var(--role-accent);width:12px;height:12px;border-radius:50%;border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>',
        iconSize: [12, 12], iconAnchor: [6, 6],
    });

    servicePoints.forEach(function (s) {
        L.marker([s.lat, s.lng], { icon: serviceIcon })
            .addTo(filterMap)
            .bindPopup(`<div><strong>${s.title}</strong><br><small>${s.city}</small></div>`);
    });

    let filterCenter = null, filterCircle = null, centerMarker = null;

    function haversine(lat1, lon1, lat2, lon2) {
        const R = 6371, dLat = (lat2-lat1)*Math.PI/180, dLon = (lon2-lon1)*Math.PI/180;
        const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    function applyFilter() {
        const radius = parseFloat(document.getElementById('radiusSelect').value);
        const cards  = document.querySelectorAll('.service-grid-item');
        const badge  = document.getElementById('filterBadge');
        const hint   = document.getElementById('filterHint');
        const resetBtn = document.getElementById('resetFilterBtn');
        const shownEl  = document.getElementById('shownCount');

        if (!filterCenter || isNaN(radius)) {
            cards.forEach(el => el.style.display = '');
            badge.classList.add('d-none');
            resetBtn.classList.add('d-none');
            hint.textContent = TRANS.clickMapHint;
            shownEl.textContent = cards.length;
            return;
        }

        if (filterCircle) filterMap.removeLayer(filterCircle);
        filterCircle = L.circle([filterCenter.lat, filterCenter.lng], {
            radius: radius * 1000, color: 'var(--role-accent)', fillColor: 'var(--role-accent)', fillOpacity: 0.08, weight: 2
        }).addTo(filterMap);

        let shown = 0;
        cards.forEach(function (el) {
            const lat = parseFloat(el.dataset.lat), lng = parseFloat(el.dataset.lng);
            if (!lat || !lng) { el.style.display = 'none'; return; }
            const visible = haversine(filterCenter.lat, filterCenter.lng, lat, lng) <= radius;
            el.style.display = visible ? '' : 'none';
            if (visible) shown++;
        });

        badge.textContent = shown + ' ' + TRANS.results.replace('No results found','').trim() || shown;
        badge.classList.remove('d-none');
        resetBtn.classList.remove('d-none');
        hint.textContent = TRANS.showingWithinKm.replace(':radius', radius);
        shownEl.textContent = shown;
    }

    filterMap.on('click', function (e) {
        filterCenter = e.latlng;
        if (centerMarker) filterMap.removeLayer(centerMarker);
        centerMarker = L.marker([e.latlng.lat, e.latlng.lng], {
            icon: L.divIcon({
                className: '',
                html: '<div style="background:#ff3e1d;width:16px;height:16px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.35);"></div>',
                iconSize: [16,16], iconAnchor: [8,8],
            })
        }).addTo(filterMap).bindPopup(TRANS.searchCenter).openPopup();
        applyFilter();
    });

    document.getElementById('radiusSelect').addEventListener('change', applyFilter);

    document.getElementById('useMyLocationBtn').addEventListener('click', function () {
        if (!navigator.geolocation) { alert(TRANS.geoNotSupported); return; }
        navigator.geolocation.getCurrentPosition(function (pos) {
            filterCenter = L.latLng(pos.coords.latitude, pos.coords.longitude);
            filterMap.setView(filterCenter, 12);
            if (centerMarker) filterMap.removeLayer(centerMarker);
            centerMarker = L.marker([filterCenter.lat, filterCenter.lng], {
                icon: L.divIcon({
                    className: '',
                    html: '<div style="background:#ff3e1d;width:16px;height:16px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.35);"></div>',
                    iconSize: [16,16], iconAnchor: [8,8],
                })
            }).addTo(filterMap).bindPopup(TRANS.yourLocation).openPopup();
            const sel = document.getElementById('radiusSelect');
            if (!sel.value) sel.value = '10';
            applyFilter();
        }, function () { alert(TRANS.couldNotGetLoc); });
    });

    document.getElementById('resetFilterBtn').addEventListener('click', function () {
        filterCenter = null;
        if (centerMarker) { filterMap.removeLayer(centerMarker); centerMarker = null; }
        if (filterCircle)  { filterMap.removeLayer(filterCircle);  filterCircle  = null; }
        document.getElementById('radiusSelect').value = '';
        applyFilter();
        filterMap.setView([34.8, 38.5], 7);
    });

    document.getElementById('toggleMapBtn').addEventListener('click', function () {
        const wrapper = document.getElementById('filterMapWrapper');
        const icon    = document.getElementById('toggleMapIcon');
        if (wrapper.style.display === 'none') {
            wrapper.style.display = '';
            icon.className = 'ri-arrow-up-s-line';
            filterMap.invalidateSize();
        } else {
            wrapper.style.display = 'none';
            icon.className = 'ri-arrow-down-s-line';
        }
    });
})();

document.addEventListener('shown.bs.modal', function (e) {
    const modal = e.target;
    @foreach($services as $service)
    @if($service->latitude && $service->longitude)
    if (modal.id === 'mapModal{{ $service->id }}') {
        const mapId = 'userMap{{ $service->id }}';
        if (window['_umap_' + mapId]) return;
        const map = L.map(mapId).setView([{{ $service->latitude }}, {{ $service->longitude }}], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
        L.marker([{{ $service->latitude }}, {{ $service->longitude }}])
            .addTo(map)
            .bindPopup('<strong>{{ addslashes($service->title) }}</strong><br>{{ addslashes($service->location_name) }}')
            .openPopup();
        window['_umap_' + mapId] = map;
        setTimeout(() => map.invalidateSize(), 300);
    }
    @endif
    @endforeach
});
</script>
@endpush
