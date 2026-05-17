@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
@endphp

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if(isset($navbarFull))
<div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-6">
    <a href="{{url('/')}}" class="app-brand-link gap-2">
        <span class="app-brand-logo demo">@include('_partials.macros')</span>
        <span class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName')}}</span>
    </a>
</div>
@endif

<!-- ! Not required for layout-without-menu -->
@if(!isset($navbarHideToggle))
<div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
        <i class="icon-base ri ri-menu-line icon-md"></i>
    </a>
</div>
@endif

{{-- ── Three-zone navbar: [spacer] [search-center] [user-right] ── --}}
<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse"
     style="flex:1; min-width:0;">

    {{-- Zone 1: invisible spacer (mirrors zone 3 width) --}}
    <div style="flex:1; min-width:0;"></div>

    {{-- Zone 2: Centered Search Trigger --}}
    <button id="searchTrigger"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#globalSearchModal"
            title="Search (Ctrl+K)"
            style="
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                width: 320px;
                max-width: 100%;
                background: #f4f5ff;
                border: 1.5px solid #e5e7ff;
                border-radius: 10px;
                padding: 9px 16px;
                color: #94a3b8;
                font-size: 13px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s ease;
                outline: none;
                flex-shrink: 0;
            "
            onmouseover="this.style.background='#eef0ff';this.style.borderColor='#c5c8ff';this.style.color='#696cff';"
            onmouseout="this.style.background='#f4f5ff';this.style.borderColor='#e5e7ff';this.style.color='#94a3b8';">
        <span style="display:flex;align-items:center;gap:8px;">
            <i class="ri ri-search-line" style="font-size:15px;color:#696cff;flex-shrink:0;"></i>
            <span>Search services, users...</span>
        </span>
        <kbd style="
            background: rgba(105,108,255,.12);
            color: #696cff;
            border: 1px solid rgba(105,108,255,.2);
            border-radius: 5px;
            padding: 2px 7px;
            font-size: 10px;
            font-weight: 700;
            font-family: inherit;
            flex-shrink: 0;
            pointer-events: none;
        ">Ctrl K</kbd>
    </button>

    {{-- Zone 3: Right side — language switch + user dropdown --}}
    <div style="flex:1; display:flex; justify-content:flex-end; align-items:center;">
        <ul class="navbar-nav flex-row align-items-center">

            <!-- Language Switch -->
            @php $currentLang = app()->getLocale(); @endphp
            <li class="nav-item me-2">
                <div class="dropdown">
                    <button type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            style="
                                display:flex;align-items:center;gap:6px;
                                background:#f4f5ff;
                                border:1.5px solid #e5e7ff;
                                border-radius:10px;
                                padding:7px 13px;
                                font-size:13px;font-weight:700;
                                color:#696cff;
                                cursor:pointer;
                                outline:none;
                                transition:all .18s;
                            "
                            onmouseover="this.style.background='#eef0ff';this.style.borderColor='#c5c8ff';"
                            onmouseout="this.style.background='#f4f5ff';this.style.borderColor='#e5e7ff';">
                        <i class="ri ri-global-line" style="font-size:16px;"></i>
                        <span>{{ $currentLang === 'ar' ? 'AR' : 'EN' }}</span>
                        <i class="ri ri-arrow-down-s-line" style="font-size:14px;opacity:.7;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="
                        min-width:170px;
                        border-radius:14px;
                        border:1.5px solid #e5e7ff;
                        box-shadow:0 10px 30px rgba(105,108,255,.15),0 2px 8px rgba(0,0,0,.06);
                        padding:6px;
                        margin-top:6px;
                    ">
                        <li>
                            <a href="{{ route('language.switch', 'en') }}"
                               style="
                                   display:flex;align-items:center;gap:12px;
                                   border-radius:9px;
                                   padding:10px 14px;
                                   font-size:13px;font-weight:600;
                                   text-decoration:none;
                                   transition:background .15s;
                                   {{ $currentLang === 'en' ? 'color:#696cff;background:#f4f5ff;' : 'color:#475569;' }}
                               ">
                                <span style="
                                    width:32px;height:32px;border-radius:8px;
                                    background:{{ $currentLang==='en' ? '#696cff' : '#e5e7ff' }};
                                    color:{{ $currentLang==='en' ? '#fff' : '#8592a3' }};
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:12px;font-weight:800;flex-shrink:0;
                                ">EN</span>
                                <div>
                                    <div style="font-size:13px;font-weight:700;line-height:1.2;">English</div>
                                    <div style="font-size:11px;color:#94a3b8;font-weight:400;">English</div>
                                </div>
                                @if($currentLang === 'en')
                                <i class="ri ri-check-line ms-auto" style="color:#696cff;font-size:16px;"></i>
                                @endif
                            </a>
                        </li>
                        <li style="padding:3px 6px;"><hr style="margin:0;border-color:#f1f5f9;"></li>
                        <li>
                            <a href="{{ route('language.switch', 'ar') }}"
                               style="
                                   display:flex;align-items:center;gap:12px;
                                   border-radius:9px;
                                   padding:10px 14px;
                                   font-size:13px;font-weight:600;
                                   text-decoration:none;
                                   font-family:'Cairo',sans-serif;
                                   transition:background .15s;
                                   {{ $currentLang === 'ar' ? 'color:#696cff;background:#f4f5ff;' : 'color:#475569;' }}
                               ">
                                <span style="
                                    width:32px;height:32px;border-radius:8px;
                                    background:{{ $currentLang==='ar' ? '#696cff' : '#e5e7ff' }};
                                    color:{{ $currentLang==='ar' ? '#fff' : '#8592a3' }};
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:14px;font-weight:800;flex-shrink:0;
                                ">ع</span>
                                <div>
                                    <div style="font-size:13px;font-weight:700;line-height:1.2;">العربية</div>
                                    <div style="font-size:11px;color:#94a3b8;font-weight:400;font-family:'Cairo',sans-serif;">Arabic</div>
                                </div>
                                @if($currentLang === 'ar')
                                <i class="ri ri-check-line ms-auto" style="color:#696cff;font-size:16px;"></i>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- User Dropdown -->
            @php
                $navUser = auth('superadmins')->user()
                        ?? auth('admins')->user()
                        ?? auth('users')->user();
                $navRole = auth('superadmins')->check() ? __('app.super_admin')
                         : (auth('admins')->check()       ? __('app.admins')
                                                          : __('app.users'));
            @endphp
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <div style="
                            width: 38px; height: 38px;
                            border-radius: 50%;
                            background: linear-gradient(135deg, #696cff, #9c9eff);
                            color: #fff; font-size: 15px; font-weight: 800;
                            display: flex; align-items: center; justify-content: center;
                            cursor: pointer; flex-shrink: 0;">
                            {{ strtoupper(substr($navUser->name ?? 'U', 0, 1)) }}
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width:220px;">
                    <li>
                        <div class="dropdown-item pe-none">
                            <div class="d-flex align-items-center gap-3">
                                <div style="
                                    width: 42px; height: 42px; border-radius: 50%;
                                    background: linear-gradient(135deg, #696cff, #9c9eff);
                                    color: #fff; font-size: 17px; font-weight: 800;
                                    display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    {{ strtoupper(substr($navUser->name ?? 'U', 0, 1)) }}
                                </div>
                                <div style="min-width:0;">
                                    <div style="font-size:14px;font-weight:700;color:#1e293b;
                                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:140px;">
                                        {{ $navUser->name ?? '' }}
                                    </div>
                                    <div style="font-size:11px;color:#696cff;font-weight:600;">
                                        {{ $navRole }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><div class="dropdown-divider my-1"></div></li>
                    <li>
                        <div class="d-grid px-3 pt-1 pb-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm d-flex w-100 justify-content-center gap-2">
                                    <span>{{ __('app.logout') }}</span>
                                    <i class="ri ri-logout-box-r-line"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </li>
            <!--/ User -->

        </ul>
    </div>

</div>

@push('scripts')
{{-- GLOBAL SEARCH + FILTER MODAL --}}
<style>
#globalSearchModal .modal-dialog {
    margin: 65px auto 0;
    max-width: 680px;
    transform: scale(0.97);
    transition: transform 0.18s ease;
}
#globalSearchModal.show .modal-dialog { transform: scale(1); }
.gs-section-label {
    padding: 10px 18px 4px;
    font-size: 10px; font-weight: 800;
    color: #b0b8c4;
    letter-spacing: .12em;
    text-transform: uppercase;
}
.gs-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 14px; margin: 2px 6px;
    border-radius: 10px;
    text-decoration: none; color: inherit;
    transition: background 0.12s ease;
    cursor: pointer;
}
.gs-item:hover, .gs-item.active { background: #f4f5ff; color: inherit; }
.gs-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
}
.gs-title  { font-size:13px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.gs-sub    { font-size:11px;color:#8592a3;margin-top:1px; }
.gs-badge  { font-size:10px;padding:3px 10px;border-radius:999px;font-weight:700;flex-shrink:0;margin-left:auto; }
.gsf-select {
    border: 1.5px solid #e5e7ff;
    border-radius: 8px;
    padding: 6px 10px;
    font-size: 12px; font-weight: 500;
    color: #475569;
    background: #f8f9fc;
    outline: none;
    cursor: pointer;
    width: 100%;
    transition: border-color .15s;
}
.gsf-select:focus { border-color: #696cff; background: #fff; }
.gsf-input {
    border: 1.5px solid #e5e7ff;
    border-radius: 8px;
    padding: 6px 10px;
    font-size: 12px; font-weight: 500;
    color: #475569;
    background: #f8f9fc;
    outline: none;
    width: 100%;
    transition: border-color .15s;
}
.gsf-input:focus { border-color: #696cff; background: #fff; }
</style>

<div class="modal fade" id="globalSearchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="
            border: 0; border-radius: 16px; overflow: hidden;
            box-shadow: 0 25px 60px rgba(15,23,42,.25), 0 0 0 1px rgba(105,108,255,.1);
        ">

            {{-- ── Search Input Row ── --}}
            <div style="display:flex;align-items:center;padding:14px 18px;
                        border-bottom:1.5px solid #f1f5f9;background:#fff;gap:12px;">
                <i class="ri ri-search-line" style="font-size:20px;color:#696cff;flex-shrink:0;"></i>
                <input id="gsInput" type="text"
                       placeholder="Search services, businesses, users..."
                       autocomplete="off"
                       style="flex:1;border:none;outline:none;font-size:15px;font-weight:500;
                              color:#1e293b;background:transparent;line-height:1.4;">
                <button type="button" data-bs-dismiss="modal"
                        style="background:#f1f5f9;border:1px solid #e2e8f0;border-radius:6px;
                               color:#64748b;font-size:11px;padding:3px 9px;cursor:pointer;
                               flex-shrink:0;font-family:inherit;font-weight:600;">Esc</button>
            </div>

            {{-- ── Filter Row ── --}}
            <div id="gsFilterSection"
                 style="padding:12px 16px;background:#fafbff;border-bottom:1.5px solid #f1f5f9;">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;margin-bottom:8px;">
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#b0b8c4;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">
                            <i class="ri ri-map-pin-line" style="color:#696cff;"></i> City
                        </div>
                        <select id="gsCity" class="gsf-select">
                            <option value="">All cities</option>
                        </select>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#b0b8c4;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">
                            <i class="ri ri-layout-grid-line" style="color:#696cff;"></i> Category
                        </div>
                        <select id="gsCat" class="gsf-select">
                            <option value="">All</option>
                        </select>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#b0b8c4;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">
                            <i class="ri ri-list-check" style="color:#696cff;"></i> Subcategory
                        </div>
                        <select id="gsSub" class="gsf-select">
                            <option value="">All</option>
                        </select>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#b0b8c4;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">
                            <i class="ri ri-exchange-line" style="color:#696cff;"></i> Type
                        </div>
                        <select id="gsType" class="gsf-select">
                            <option value="">All types</option>
                            <option value="rent">Rent</option>
                            <option value="sale">Sale</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:8px;align-items:flex-end;">
                    <div style="flex:1;">
                        <div style="font-size:10px;font-weight:700;color:#b0b8c4;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">
                            <i class="ri ri-money-dollar-circle-line" style="color:#696cff;"></i> Min Price
                        </div>
                        <input id="gsPriceMin" type="number" min="0" placeholder="0" class="gsf-input">
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:10px;font-weight:700;color:#b0b8c4;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">Max Price</div>
                        <input id="gsPriceMax" type="number" min="0" placeholder="Any" class="gsf-input">
                    </div>
                    <button id="gsSearchBtn" type="button" style="
                        background:linear-gradient(135deg,#696cff,#9c9eff);
                        color:#fff;border:none;border-radius:8px;
                        padding:7px 20px;font-size:13px;font-weight:700;
                        cursor:pointer;white-space:nowrap;height:32px;
                        display:flex;align-items:center;gap:6px;flex-shrink:0;
                        transition:.15s;
                    ">
                        <i class="ri ri-search-line"></i> Search
                    </button>
                    <button id="gsClearBtn" type="button" style="
                        background:#f1f5f9;color:#64748b;border:1.5px solid #e2e8f0;
                        border-radius:8px;padding:7px 14px;font-size:12px;font-weight:600;
                        cursor:pointer;white-space:nowrap;height:32px;
                        display:flex;align-items:center;gap:5px;flex-shrink:0;
                        transition:.15s;
                    ">
                        <i class="ri ri-close-line"></i> Clear
                    </button>
                </div>
            </div>

            {{-- ── Results Area ── --}}
            <div id="gsResults" style="min-height:180px;max-height:360px;overflow-y:auto;background:#fff;padding:6px 0;">
                <div id="gsPlaceholder" style="padding:44px 20px;text-align:center;">
                    <div style="width:52px;height:52px;background:#f4f5ff;border-radius:14px;
                                display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;">
                        <i class="ri ri-search-line" style="font-size:24px;color:#696cff;"></i>
                    </div>
                    <div style="font-size:14px;color:#475569;font-weight:600;margin-bottom:5px;">
                        Search or apply filters
                    </div>
                    <div style="font-size:12px;color:#94a3b8;">
                        Services · Businesses · Users
                    </div>
                </div>
            </div>

            {{-- ── Footer Hints ── --}}
            <div style="padding:9px 18px;border-top:1.5px solid #f1f5f9;background:#fafbff;
                        display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:5px;">
                    <kbd style="background:#f1f5f9;border:1px solid #e2e8f0;border-radius:4px;padding:1px 6px;color:#64748b;font-family:inherit;font-size:10px;">↵</kbd> Open
                </span>
                <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:5px;">
                    <kbd style="background:#f1f5f9;border:1px solid #e2e8f0;border-radius:4px;padding:1px 6px;color:#64748b;font-family:inherit;font-size:10px;">↑ ↓</kbd> Navigate
                </span>
                <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:5px;">
                    <kbd style="background:#f1f5f9;border:1px solid #e2e8f0;border-radius:4px;padding:1px 6px;color:#64748b;font-family:inherit;font-size:10px;">Esc</kbd> Close
                </span>
                <span style="margin-left:auto;font-size:10px;color:#b0b8c4;display:flex;align-items:center;gap:5px;">
                    <kbd style="background:#eef0ff;border:1px solid #d4d7ff;border-radius:4px;padding:1px 6px;color:#696cff;font-family:inherit;font-size:10px;font-weight:700;">Ctrl K</kbd> anywhere
                </span>
            </div>

        </div>
    </div>
</div>

<script>
(function () {
    const modal       = document.getElementById('globalSearchModal');
    const input       = document.getElementById('gsInput');
    const results     = document.getElementById('gsResults');
    const placeholder = document.getElementById('gsPlaceholder');
    const gsCity      = document.getElementById('gsCity');
    const gsCat       = document.getElementById('gsCat');
    const gsSub       = document.getElementById('gsSub');
    const gsType      = document.getElementById('gsType');
    const gsPriceMin  = document.getElementById('gsPriceMin');
    const gsPriceMax  = document.getElementById('gsPriceMax');
    const gsSearchBtn = document.getElementById('gsSearchBtn');
    const gsClearBtn  = document.getElementById('gsClearBtn');

    let timer, activeIdx = -1, filtersLoaded = false, allSubcats = [];

    /* ── Ctrl+K opener ── */
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            bootstrap.Modal.getOrCreateInstance(modal).show();
        }
    });

    /* ── On open: reset, focus, load filters, pre-fill from URL ── */
    modal.addEventListener('shown.bs.modal', function () {
        input.value = '';
        resetResults();
        input.focus();
        activeIdx = -1;
        loadFilters();
        prefillFromUrl();
    });

    /* ── Prefill filter fields from current URL params ── */
    function prefillFromUrl() {
        const p = new URLSearchParams(window.location.search);
        if (p.get('search'))      input.value = p.get('search');
        if (p.get('city_id'))     gsCity.value = p.get('city_id');
        if (p.get('category_id')) {
            gsCat.value = p.get('category_id');
            filterSubcatOptions(p.get('category_id'));
        }
        if (p.get('subcategory_id')) gsSub.value = p.get('subcategory_id');
        if (p.get('services_type'))  gsType.value = p.get('services_type');
        if (p.get('price_min'))   gsPriceMin.value = p.get('price_min');
        if (p.get('price_max'))   gsPriceMax.value = p.get('price_max');
    }

    /* ── Load city / category / subcategory options via AJAX (once) ── */
    function loadFilters() {
        if (filtersLoaded) return;
        fetch('/dashboard/filters', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(d => {
                d.cities.forEach(c => {
                    const o = new Option(c.name_ar || c.name_en, c.id);
                    gsCity.add(o);
                });
                d.categories.forEach(c => {
                    const o = new Option(c.name_ar || c.name_en, c.id);
                    gsCat.add(o);
                });
                allSubcats = d.subcategories;
                d.subcategories.forEach(s => {
                    const o = new Option(s.name_ar || s.name_en, s.id);
                    o.dataset.cat = s.category_id;
                    gsSub.add(o);
                });
                filtersLoaded = true;
                prefillFromUrl();
            })
            .catch(() => {});
    }

    /* ── Filter subcategory dropdown by selected category ── */
    function filterSubcatOptions(catId) {
        Array.from(gsSub.options).forEach(opt => {
            if (!opt.value) return;
            opt.hidden = catId ? (opt.dataset.cat != catId) : false;
        });
        if (catId && gsSub.value && gsSub.options[gsSub.selectedIndex]?.dataset.cat != catId) {
            gsSub.value = '';
        }
    }

    gsCat.addEventListener('change', function () {
        filterSubcatOptions(this.value);
    });

    /* ── Reset results ── */
    function resetResults() {
        results.innerHTML = '';
        results.appendChild(placeholder);
        placeholder.style.display = '';
    }

    /* ── Live typing → quick search ── */
    input.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 2) { resetResults(); return; }
        timer = setTimeout(() => fetchResults(q), 260);
    });

    /* ── Keyboard navigation ── */
    input.addEventListener('keydown', function (e) {
        const items = results.querySelectorAll('a.gs-item');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIdx = Math.min(activeIdx + 1, items.length - 1);
            highlightItem(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIdx = Math.max(activeIdx - 1, -1);
            highlightItem(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIdx >= 0 && items[activeIdx]) {
                window.location.href = items[activeIdx].href;
            } else {
                doSearchNavigate();
            }
        }
    });

    function highlightItem(items) {
        items.forEach((el, i) => el.classList.toggle('active', i === activeIdx));
        if (activeIdx >= 0) items[activeIdx]?.scrollIntoView({ block: 'nearest' });
    }

    /* ── Search button → navigate to allserviesad with all params ── */
    gsSearchBtn.addEventListener('click', doSearchNavigate);

    function doSearchNavigate() {
        const params = new URLSearchParams();
        const q = input.value.trim();
        if (q)              params.set('search',        q);
        if (gsCity.value)   params.set('city_id',       gsCity.value);
        if (gsCat.value)    params.set('category_id',   gsCat.value);
        if (gsSub.value)    params.set('subcategory_id', gsSub.value);
        if (gsType.value)   params.set('services_type', gsType.value);
        if (gsPriceMin.value) params.set('price_min',   gsPriceMin.value);
        if (gsPriceMax.value) params.set('price_max',   gsPriceMax.value);
        const str = params.toString();
        window.location.href = '/allserviesad' + (str ? '?' + str : '');
        bootstrap.Modal.getInstance(modal)?.hide();
    }

    /* ── Clear all filters ── */
    gsClearBtn.addEventListener('click', function () {
        input.value = '';
        gsCity.value = '';
        gsCat.value = '';
        filterSubcatOptions('');
        gsSub.value = '';
        gsType.value = '';
        gsPriceMin.value = '';
        gsPriceMax.value = '';
        resetResults();
        input.focus();
    });

    /* ── AJAX quick results ── */
    function fetchResults(q) {
        fetch('/dashboard/search?q=' + encodeURIComponent(q), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => renderResults(d.results, q))
        .catch(() => {});
    }

    const badgeColor = s =>
        s === 'approved' ? '#28c76f' : s === 'rejected' ? '#ea5455' : '#ff9f43';

    function renderResults(items, q) {
        placeholder.style.display = 'none';
        if (!items.length) {
            results.innerHTML = `
                <div style="padding:40px 20px;text-align:center;">
                    <div style="width:48px;height:48px;background:#fef3f2;border-radius:14px;
                                display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;">
                        <i class="ri ri-search-eye-line" style="font-size:22px;color:#ea5455;"></i>
                    </div>
                    <div style="font-size:14px;color:#475569;font-weight:600;margin-bottom:6px;">
                        No quick results for "<strong>${q}</strong>"
                    </div>
                    <div style="font-size:12px;color:#94a3b8;">Use the Search button above to apply filters</div>
                </div>`;
            return;
        }
        const grouped = {};
        items.forEach(item => (grouped[item.type] = grouped[item.type] || []).push(item));
        let html = '';
        Object.entries(grouped).forEach(([type, list]) => {
            html += `<div class="gs-section-label">${type}s</div>`;
            list.forEach(item => {
                const badge = item.badge
                    ? `<span class="gs-badge" style="background:${badgeColor(item.badge)}18;color:${badgeColor(item.badge)};">${item.badge}</span>`
                    : '';
                html += `
                    <a href="${item.url}" class="gs-item">
                        <div class="gs-icon" style="background:${item.color}18;color:${item.color};">
                            <i class="${item.icon}"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="gs-title">${item.title}</div>
                            <div class="gs-sub">${item.subtitle}</div>
                        </div>
                        ${badge}
                    </a>`;
            });
        });
        html += `
            <div style="padding:10px 20px 8px;text-align:center;border-top:1px solid #f1f5f9;margin-top:6px;">
                <a href="/allserviesad?search=${encodeURIComponent(q)}"
                   style="font-size:12px;color:#696cff;font-weight:700;text-decoration:none;">
                    View all results for "${q}" →
                </a>
            </div>`;
        results.innerHTML = html;
        activeIdx = -1;
    }
})();
</script>
@endpush
