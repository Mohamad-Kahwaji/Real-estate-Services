@php
    $currentRoute = Route::currentRouteName();
    $active = fn(string $prefix) =>
        str_starts_with($currentRoute ?? '', $prefix) ? 'active' : '';
    $is = fn(string $name) =>
        $currentRoute === $name ? 'active' : '';
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu">

    {{-- Brand --}}
    <div class="app-brand demo">
        <a href="{{ route('indexsuperadmin') }}" class="app-brand-link">
            <span class="app-brand-logo demo me-1">@include('_partials.macros')</span>
            <span class="app-brand-text demo menu-text fw-semibold ms-2">Super Admin</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="menu-toggle-icon d-xl-inline-block align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- ── Dashboard ── --}}
        <li class="menu-item {{ $is('indexsuperadmin') }}">
            <a href="{{ route('indexsuperadmin') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-home-smile-line"></i>
                <span class="menu-text">{{ __('app.dashboard') }}</span>
            </a>
        </li>

        {{-- ── Admins ── --}}
        <li class="menu-header mt-4">
            <span class="menu-header-text">{{ __('app.administration') }}</span>
        </li>
        <li class="menu-item {{ $active('indexadmin') }}">
            <a href="{{ route('indexadmin.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-shield-user-line"></i>
                <span class="menu-text">{{ __('app.admins') }}</span>
            </a>
        </li>
        <li class="menu-item {{ $is('adminsindex') }}">
            <a href="{{ route('adminsindex') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-group-line"></i>
                <span class="menu-text">{{ __('app.users') }}</span>
            </a>
        </li>

        {{-- ── Roles & Permissions ── --}}
        <li class="menu-header mt-4">
            <span class="menu-header-text">{{ __('app.roles_permissions') }}</span>
        </li>
        <li class="menu-item {{ $is('roleindex') }}">
            <a href="{{ route('roleindex') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-key-2-line"></i>
                <span class="menu-text">{{ __('app.roles') }}</span>
            </a>
        </li>
        <li class="menu-item {{ $active('permissions') }}">
            <a href="{{ route('permissions.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-lock-password-line"></i>
                <span class="menu-text">{{ __('app.permissions') }}</span>
            </a>
        </li>

        {{-- ── Content ── --}}
        <li class="menu-header mt-4">
            <span class="menu-header-text">{{ __('app.content') }}</span>
        </li>
        <li class="menu-item {{ $active('business') }}">
            <a href="{{ route('business.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-building-4-line"></i>
                <span class="menu-text">{{ __('app.businesses') }}</span>
            </a>
        </li>
        <li class="menu-item {{ $is('allserviesad') }}">
            <a href="{{ route('allserviesad') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-store-3-line"></i>
                <span class="menu-text">{{ __('app.services') }}</span>
            </a>
        </li>
        <li class="menu-item {{ $is('superadmin.service-requests') }}">
            <a href="{{ route('superadmin.service-requests') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-file-list-3-line"></i>
                <span class="menu-text">{{ __('app.service_requests') }}</span>
            </a>
        </li>

        {{-- ── Taxonomy ── --}}
        <li class="menu-header mt-4">
            <span class="menu-header-text">{{ __('app.taxonomy') }}</span>
        </li>
        <li class="menu-item {{ $active('categories') }}">
            <a href="{{ route('categories.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-price-tag-3-line"></i>
                <span class="menu-text">{{ __('app.categories') }}</span>
            </a>
        </li>
        <li class="menu-item {{ $active('subcategories') }}">
            <a href="{{ route('subcategories.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-list-check-3"></i>
                <span class="menu-text">{{ __('app.subcategories') }}</span>
            </a>
        </li>
        <li class="menu-item {{ $active('cities') }}">
            <a href="{{ route('cities.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-map-pin-2-line"></i>
                <span class="menu-text">{{ __('app.cities') }}</span>
            </a>
        </li>

        {{-- ── Reports & Ads ── --}}
        <li class="menu-header mt-4">
            <span class="menu-header-text">{{ __('app.monitoring') }}</span>
        </li>
        <li class="menu-item {{ $active('reports') }}">
            <a href="{{ route('reports.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-flag-2-line"></i>
                <span class="menu-text">{{ __('app.reports') }}</span>
            </a>
        </li>
        <li class="menu-item {{ $active('ads') }}">
            <a href="{{ route('ads.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-advertisement-line"></i>
                <span class="menu-text">{{ __('app.ads') }}</span>
            </a>
        </li>
        <li class="menu-item {{ $active('reviews') }}">
            <a href="{{ route('reviews.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-star-line"></i>
                <span class="menu-text">{{ __('app.reviews') }}</span>
            </a>
        </li>

        {{-- ── Logout ── --}}
        <li class="menu-header mt-4">
            <span class="menu-header-text">{{ __('app.settings') }}</span>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link"
               onclick="event.preventDefault(); document.getElementById('sa-logout').submit();">
                <i class="menu-icon icon-base ri ri-logout-box-r-line" style="color:#ea5455;"></i>
                <span class="menu-text" style="color:#ea5455;">{{ __('app.logout') }}</span>
            </a>
            <form id="sa-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

    </ul>
</aside>
