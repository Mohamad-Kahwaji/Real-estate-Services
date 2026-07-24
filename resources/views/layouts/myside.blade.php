<style>
/* ── Sidebar custom styles ── */
#layout-menu .app-brand {
    padding: 0 !important;
    background: linear-gradient(135deg, var(--role-accent, #696cff) 0%, var(--role-accent-light, #9c9eff) 100%);
    border-bottom: none !important;
}

#layout-menu .brand-inner {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 20px 22px;
}

#layout-menu .brand-icon {
    width: 36px; height: 36px;
    background: rgba(255,255,255,.22);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #fff;
    flex-shrink: 0;
}

#layout-menu .brand-name {
    font-size: 17px; font-weight: 800;
    color: #fff; letter-spacing: .2px;
}

#layout-menu .brand-sub {
    font-size: 10px; color: rgba(255,255,255,.7);
    font-weight: 500; margin-top: 1px;
}

/* Menu headers */
#layout-menu .menu-header {
    margin-top: 20px !important;
    margin-bottom: 4px;
    padding: 0 18px !important;
}

#layout-menu .menu-header-text {
    font-size: 10px !important;
    font-weight: 800 !important;
    letter-spacing: 1.2px;
    color: #a8b1c3 !important;
}

/* Menu items */
#layout-menu .menu-item .menu-link {
    border-radius: 10px;
    margin: 1px 10px;
    padding: 10px 14px !important;
    font-size: 14px;
    transition: background .18s ease, color .18s ease;
}

#layout-menu .menu-text {
    font-size: 14px;
}

#layout-menu .menu-header-text {
    font-size: 10.5px !important;
}

#layout-menu .menu-item.active > .menu-link,
#layout-menu .menu-item > .menu-link:hover {
    background: var(--role-accent-soft, #eef0ff) !important;
}

#layout-menu .menu-item.active > .menu-link .menu-icon,
#layout-menu .menu-item.active > .menu-link div {
    color: var(--role-accent, #696cff) !important;
}

#layout-menu .menu-icon {
    font-size: 20px !important;
    width: 22px !important;
    margin-right: 10px !important;
    flex-shrink: 0;
}

/* Sub-menu indent */
#layout-menu .menu-sub .menu-item .menu-link {
    padding-left: 48px !important;
}

/* Sidebar badge pill */
.sb-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 20px;
    padding: 0 7px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
    margin-left: auto;
    flex-shrink: 0;
}

.sb-new   { background: var(--role-accent-soft, #eef0ff); color: var(--role-accent, #696cff); }
.sb-warn  { background: #fff4e5; color: #ff9f43; }
.sb-green { background: #e8f8ef; color: #28c76f; }
.sb-red   { background: #fdeaea; color: #ea5455; }

/* Logout button */
#layout-menu .logout-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    width: calc(100% - 20px);
    margin: 1px 10px;
    padding: 10px 14px;
    border-radius: 10px;
    border: none;
    background: transparent;
    cursor: pointer;
    font-size: 14px;
    color: #ea5455;
    transition: background .18s;
}

#layout-menu .logout-btn:hover { background: #fdeaea; }
#layout-menu .logout-btn i { font-size: 20px; width: 22px; flex-shrink: 0; }

/* Divider */
.sb-divider {
    height: 1px;
    background: #f0eef8;
    margin: 8px 16px;
}
</style>

@if(auth('superadmins')->check())
    @include('super_admin.partials.sidebar')
@elseif(auth('admins')->check())
    @include('admin.partials.sidebar')
@elseif(auth('users')->check())
    @include('users.partials.sidebar')
@else

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    {{-- ── Brand ── --}}
    <div class="app-brand demo">
        <a href="{{ route('indexsuperadmin') }}" class="app-brand-link w-100" style="text-decoration:none;">
            <div class="brand-inner">
                <div class="brand-icon"><i class="ri ri-building-4-line"></i></div>
                <div>
                    <div class="brand-name">My System</div>
                    <div class="brand-sub">Admin Panel</div>
                </div>
            </div>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- ── Dashboard ── --}}
        <li class="menu-item {{ request()->routeIs('indexsuperadmin') ? 'active' : '' }}">
            <a href="{{ route('indexsuperadmin') }}" class="menu-link">
                <i class="menu-icon tf-icons ri ri-home-5-line"></i>
                <div>Dashboard</div>
            </a>
        </li>

        {{-- ── Profile ── --}}
        <li class="menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <a href="{{ route('profile.edit') }}" class="menu-link">
                <i class="menu-icon tf-icons ri ri-user-line"></i>
                <div>Profile</div>
            </a>
        </li>

        {{-- ════ MANAGEMENT ════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Management</span>
        </li>

        {{-- Admins --}}
        <li class="menu-item {{ request()->routeIs('adminsindex') ? 'active' : '' }}">
            <a href="{{ route('adminsindex') }}" class="menu-link">
                <i class="menu-icon tf-icons ri ri-admin-line"></i>
                <div>Admins</div>
            </a>
        </li>

        {{-- Roles --}}
        <li class="menu-item {{ request()->routeIs('roleindex') ? 'active' : '' }}">
            <a href="{{ route('roleindex') }}" class="menu-link">
                <i class="menu-icon tf-icons ri ri-shield-user-line"></i>
                <div>Roles</div>
            </a>
        </li>

        {{-- Permissions --}}
        @if(Route::has('permissions.index'))
        <li class="menu-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
            <a href="{{ route('permissions.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ri ri-key-2-line"></i>
                <div>Permissions</div>
            </a>
        </li>
        @endif

        {{-- ════ USERS & BUSINESSES ════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Users & Businesses</span>
        </li>

        {{-- Users --}}
        @if(Route::has('allindex.index'))
        <li class="menu-item {{ request()->routeIs('allindex.*') ? 'active' : '' }}">
            <a href="{{ route('allindex.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ri ri-group-line"></i>
                <div>Users</div>
            </a>
        </li>
        @endif

        {{-- Businesses --}}
        @if(Route::has('business.index'))
        <li class="menu-item {{ request()->routeIs('business.*') ? 'active' : '' }}">
            <a href="{{ route('business.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ri ri-building-2-line"></i>
                <div>Businesses</div>
            </a>
        </li>
        @endif

        {{-- ════ CONTENT ════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Content</span>
        </li>

        {{-- Categories + Subcategories --}}
        @php
            $catOpen = request()->routeIs('categories.*') || request()->routeIs('subcategories.*');
        @endphp

        <li class="menu-item {{ $catOpen ? 'open active' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ri ri-layout-grid-line"></i>
                <div>Categories</div>
            </a>
            <ul class="menu-sub">
                @if(Route::has('categories.index'))
                <li class="menu-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <a href="{{ route('categories.index') }}" class="menu-link">
                        <div>All Categories</div>
                    </a>
                </li>
                @endif
                @if(Route::has('subcategories.index'))
                <li class="menu-item {{ request()->routeIs('subcategories.*') ? 'active' : '' }}">
                    <a href="{{ route('subcategories.index') }}" class="menu-link">
                        <div>Subcategories</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>

        {{-- Cities --}}
        @if(Route::has('cities.index'))
        <li class="menu-item {{ request()->routeIs('cities.*') ? 'active' : '' }}">
            <a href="{{ route('cities.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ri ri-map-pin-2-line"></i>
                <div>Cities</div>
            </a>
        </li>
        @endif

        {{-- ════ SERVICES ════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Services</span>
        </li>

        {{-- All Services --}}
        @if(Route::has('allserviesad'))
        <li class="menu-item {{ request()->routeIs('allserviesad') ? 'active' : '' }}">
            <a href="{{ route('allserviesad') }}" class="menu-link">
                <i class="menu-icon tf-icons ri ri-service-line"></i>
                <div>All Services</div>
            </a>
        </li>
        @endif


        {{-- ════ MODERATION ════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Moderation</span>
        </li>

        @if(Route::has('reports.index'))
        <li class="menu-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">
            <a href="{{ route('reports.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ri ri-flag-2-line"></i>
                <div>Reports</div>
            </a>
        </li>
        @endif

        @if(Route::has('reviews.index'))
        <li class="menu-item {{ request()->routeIs('reviews.index') ? 'active' : '' }}">
            <a href="{{ route('reviews.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ri ri-star-half-line"></i>
                <div>Reviews</div>
            </a>
        </li>
        @endif

        {{-- ════ ACCOUNT ════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Account</span>
        </li>

        <li class="menu-item">
            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="ri ri-logout-box-r-line"></i>
                    <span>Logout</span>
                </button>
            </form>
        </li>

    </ul>
</aside>
@endif
