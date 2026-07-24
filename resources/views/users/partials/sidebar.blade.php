<aside id="layout-menu" class="layout-menu menu-vertical menu">

    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-logo demo me-1">@include('_partials.macros')</span>
            <span class="app-brand-text demo menu-text fw-semibold ms-2">
                {{ config('variables.templateName') }}
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="menu-toggle-icon d-xl-inline-block align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    @php
        $current = Route::currentRouteName();
        $isAr    = app()->getLocale() === 'ar';
        $navUser = auth('users')->user();

        $items = [
            [
                'route' => 'dashi',
                'icon'  => 'ri ri-home-smile-line',
                'label' => __('app.dashboard'),
            ],
            [
                'route' => 'allservices.user',
                'icon'  => 'ri ri-store-3-line',
                'label' => __('app.all_services'),
            ],
            [
                'route' => 'myservices.user',
                'icon'  => 'ri ri-briefcase-4-line',
                'label' => __('app.my_services'),
            ],
            [
                'route' => 'favorite',
                'icon'  => 'ri ri-star-line',
                'label' => __('app.favorites'),
            ],
            [
                'route' => 'sentservice.user',
                'icon'  => 'ri ri-send-plane-line',
                'label' => __('app.sent_requests'),
            ],
            [
                'route' => 'incoming.user',
                'icon'  => 'ri ri-file-list-3-line',
                'label' => __('app.incoming_requests_nav'),
            ],
            [
                'route' => 'ads.browse',
                'icon'  => 'ri ri-advertisement-line',
                'label' => __('app.ads'),
            ],
            [
                'route' => 'chat.index',
                'icon'  => 'ri ri-wechat-line',
                'label' => __('app.conversations'),
            ],
            [
                'route' => 'profile.edit',
                'icon'  => 'ri ri-user-settings-line',
                'label' => __('app.profile'),
            ],
        ];
    @endphp

    <ul class="menu-inner py-1">

        <li class="menu-header mt-4">
            <span class="menu-header-text" style="font-size:10.5px;text-transform:uppercase;letter-spacing:.08em;">
                {{ $navUser->name ?? '' }}
            </span>
        </li>

        @foreach($items as $item)
        @php $active = $current === $item['route'] ? 'active' : ''; @endphp
        <li class="menu-item {{ $active }}">
            <a href="{{ route($item['route']) }}" class="menu-link">
                <i class="menu-icon icon-base {{ $item['icon'] }}"></i>
                <div class="menu-text">{{ $item['label'] }}</div>
            </a>
        </li>
        @endforeach

        <li class="menu-header mt-4">
            <span class="menu-header-text" style="font-size:10.5px;text-transform:uppercase;letter-spacing:.08em;">
                {{ __('app.settings') }}
            </span>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link"
               onclick="event.preventDefault(); document.getElementById('sidebar-logout').submit();">
                <i class="menu-icon icon-base ri ri-logout-box-r-line" style="color:#ea5455;"></i>
                <div class="menu-text" style="color:#ea5455;">{{ __('app.logout') }}</div>
            </a>
            <form id="sidebar-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

    </ul>

</aside>
