@php
    /** @var \App\Models\Admin $admin */
    $admin       = auth('admins')->user();
    $currentRoute = Route::currentRouteName();

    $active = fn(array $routes) =>
        collect($routes)->contains(fn($r) => str_starts_with($currentRoute, $r))
            ? 'active' : '';
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu">

    {{-- Brand --}}
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo me-1">@include('_partials.macros')</span>
            <span class="app-brand-text demo menu-text fw-semibold ms-2">Admin Panel</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="menu-toggle-icon d-xl-inline-block align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- ── Dashboard ── --}}
        <li class="menu-item {{ $active(['admin.dashboard']) }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-home-smile-line"></i>
                <span class="menu-text">{{ __('app.dashboard') }}</span>
            </a>
        </li>

        {{-- ── Business Accounts ── --}}
        @if($admin->canAny(['view business accounts','accept business account','reject business account','edit business account']))
        <li class="menu-header mt-4"><span class="menu-header-text">{{ __('app.businesses') }}</span></li>
        <li class="menu-item {{ $active(['business.']) }}">
            <a href="{{ route('business.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-building-4-line"></i>
                <span class="menu-text">{{ __('app.businesses') }}</span>
            </a>
        </li>
        @endif

        {{-- ── Services ── --}}
        @if($admin->canAny(['view services','accept service','reject service','pending service','add service','edit service','delete service']))
        <li class="menu-header mt-4"><span class="menu-header-text">{{ __('app.services') }}</span></li>
        <li class="menu-item {{ $active(['allserviesad','myservices']) }}">
            <a href="{{ route('allserviesad') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-store-3-line"></i>
                <span class="menu-text">{{ __('app.services') }}</span>
            </a>
        </li>
        @endif

        {{-- ── Service Requests ── --}}
        @if($admin->canAny(['view orders received','accept order service','reject order service']))
        <li class="menu-item {{ $active(['servicereceived']) }}">
            <a href="{{ route('servicereceived') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-file-list-3-line"></i>
                <span class="menu-text">{{ __('app.incoming_requests') }}</span>
            </a>
        </li>
        @endif

        {{-- ── Categories & Subcategories ── --}}
        @if($admin->canAny(['add category','edit category','delete category','add subcategory','edit subcategory','delete subcategory']))
        <li class="menu-header mt-4"><span class="menu-header-text">{{ __('app.categories') }}</span></li>
        @if($admin->canAny(['add category','edit category','delete category']))
        <li class="menu-item {{ $active(['categories.']) }}">
            <a href="{{ route('categories.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-price-tag-3-line"></i>
                <span class="menu-text">{{ __('app.categories') }}</span>
            </a>
        </li>
        @endif
        @if($admin->canAny(['add subcategory','edit subcategory','delete subcategory']))
        <li class="menu-item {{ $active(['subcategories.']) }}">
            <a href="{{ route('subcategories.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-list-check-3"></i>
                <span class="menu-text">{{ __('app.subcategories') }}</span>
            </a>
        </li>
        @endif
        @endif

        {{-- ── Cities ── --}}
        @if($admin->can('add city'))
        <li class="menu-header mt-4"><span class="menu-header-text">{{ __('app.cities') }}</span></li>
        <li class="menu-item {{ $active(['cities.']) }}">
            <a href="{{ route('cities.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-map-pin-2-line"></i>
                <span class="menu-text">{{ __('app.cities') }}</span>
            </a>
        </li>
        @endif

        {{-- ── Reports ── --}}
        @if($admin->can('manage report'))
        <li class="menu-header mt-4"><span class="menu-header-text">{{ __('app.reports') }}</span></li>
        <li class="menu-item {{ $active(['reports.']) }}">
            <a href="{{ route('reports.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-flag-2-line"></i>
                <span class="menu-text">{{ __('app.reports') }}</span>
            </a>
        </li>
        @endif


        {{-- ── Ads ── --}}
        @if($admin->can('manage slider ads'))
        <li class="menu-header mt-4"><span class="menu-header-text">{{ __('app.ads') }}</span></li>
        <li class="menu-item {{ $active(['ads.']) }}">
            <a href="{{ route('ads.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-advertisement-line"></i>
                <span class="menu-text">{{ __('app.ads') }}</span>
            </a>
        </li>
        @endif

        {{-- ── Reviews ── --}}
        <li class="menu-header mt-4"><span class="menu-header-text">{{ __('app.reviews') }}</span></li>
        <li class="menu-item {{ $active(['reviews.']) }}">
            <a href="{{ route('reviews.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-star-line"></i>
                <span class="menu-text">{{ __('app.reviews') }}</span>
            </a>
        </li>

        {{-- ── Profile ── --}}
        <li class="menu-header mt-4"><span class="menu-header-text">{{ __('app.profile') }}</span></li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link"
               data-bs-toggle="modal" data-bs-target="#adminProfileModal">
                <i class="menu-icon icon-base ri ri-user-settings-line"></i>
                <span class="menu-text">{{ __('app.profile') }}</span>
            </a>
        </li>

    </ul>
</aside>
