@extends('layouts/blankLayout')

@section('title', 'Real Estate Services')

@section('page-style')
<style>
* { box-sizing: border-box; }
body { margin: 0; padding: 0; color: #2e263d; background: #fff; }
a { text-decoration: none; }

/* ── Navbar ── */
.hm-nav {
    position: sticky; top: 0; z-index: 50;
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid #eeecf4;
}
.hm-nav-inner {
    max-width: 1200px; margin: 0 auto;
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 24px;
}
.hm-brand { display: flex; align-items: center; gap: 10px; }
.hm-brand-icon {
    width: 40px; height: 40px; border-radius: 12px;
    background: linear-gradient(135deg, var(--role-accent), var(--role-accent-light));
    display: flex; align-items: center; justify-content: center;
    font-size: 19px; color: #fff; flex-shrink: 0;
}
.hm-brand-name { font-size: 18px; font-weight: 800; color: #2e263d; }
.hm-nav-links { display: flex; align-items: center; gap: 28px; }
.hm-nav-links a {
    font-size: 14px; font-weight: 600; color: #585164;
    transition: color .15s;
}
.hm-nav-links a:hover { color: var(--role-accent); }
.hm-nav-actions { display: flex; align-items: center; gap: 12px; }
.hm-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 10px;
    font-size: 14px; font-weight: 700;
    border: none; cursor: pointer; transition: .18s;
    white-space: nowrap;
}
.hm-btn-ghost { background: transparent; color: #2e263d; border: 1.5px solid #e4e2ec; }
.hm-btn-ghost:hover { border-color: var(--role-accent); color: var(--role-accent); }
.hm-btn-solid {
    background: linear-gradient(135deg, var(--role-accent), var(--role-accent-light));
    color: #fff;
    box-shadow: 0 4px 16px rgba(var(--role-accent-rgb),.3);
}
.hm-btn-solid:hover { opacity: .92; transform: translateY(-1px); color: #fff; }
.hm-lang {
    display: flex; align-items: center; gap: 5px;
    padding: 8px 12px; border-radius: 9px;
    border: 1.5px solid #e4e2ec; background: #fff;
    font-size: 13px; font-weight: 700; color: #585164;
}
.hm-lang:hover { border-color: var(--role-accent); color: var(--role-accent); }

/* ── Hero ── */
.hm-hero {
    background: linear-gradient(150deg, var(--role-accent) 0%, var(--role-accent-light) 100%);
    padding: 80px 24px 130px;
    position: relative; overflow: hidden;
}
.hm-hero::before {
    content: ''; position: absolute; top: -120px; right: -100px;
    width: 420px; height: 420px; border-radius: 50%;
    background: rgba(255,255,255,.07);
}
.hm-hero::after {
    content: ''; position: absolute; bottom: -160px; left: -120px;
    width: 380px; height: 380px; border-radius: 50%;
    background: rgba(255,255,255,.06);
}
.hm-hero-inner {
    max-width: 760px; margin: 0 auto; text-align: center;
    position: relative; z-index: 1;
}
.hm-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.18); backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,.28);
    border-radius: 30px; padding: 7px 18px;
    font-size: 13px; font-weight: 700; color: #fff;
    margin-bottom: 24px;
}
.hm-hero h1 {
    font-size: 44px; font-weight: 800; color: #fff;
    line-height: 1.25; margin: 0 0 18px;
}
.hm-hero p {
    font-size: 17px; color: rgba(255,255,255,.88);
    line-height: 1.7; max-width: 600px; margin: 0 auto 34px;
}
.hm-hero-ctas { display: flex; align-items: center; justify-content: center; gap: 14px; flex-wrap: wrap; }
.hm-btn-lg { padding: 14px 30px; font-size: 15px; border-radius: 12px; }
.hm-btn-white { background: #fff; color: var(--role-accent); }
.hm-btn-white:hover { opacity: .93; transform: translateY(-1px); color: var(--role-accent); }
.hm-btn-outline-white {
    background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,.55);
}
.hm-btn-outline-white:hover { background: rgba(255,255,255,.12); color: #fff; }

/* ── Stats (overlapping hero) ── */
.hm-stats-wrap { max-width: 1000px; margin: -70px auto 0; padding: 0 24px; position: relative; z-index: 2; }
.hm-stats {
    background: #fff; border-radius: 20px;
    box-shadow: 0 20px 50px rgba(43,40,70,.12);
    display: grid; grid-template-columns: repeat(4, 1fr);
    overflow: hidden;
}
.hm-stat { padding: 30px 20px; text-align: center; border-right: 1px solid #f0eef4; }
.hm-stat:last-child { border-right: none; }
.hm-stat-num { font-size: 32px; font-weight: 800; color: var(--role-accent); line-height: 1; }
.hm-stat-lbl { font-size: 12.5px; font-weight: 600; color: #8a859c; margin-top: 8px; }

/* ── Section shell ── */
.hm-section { max-width: 1160px; margin: 0 auto; padding: 90px 24px; }
.hm-section-tight { padding-top: 40px; }
.hm-section-head { text-align: center; max-width: 620px; margin: 0 auto 50px; }
.hm-eyebrow {
    font-size: 12.5px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
    color: var(--role-accent); margin-bottom: 10px;
}
.hm-section-head h2 { font-size: 32px; font-weight: 800; color: #2e263d; margin: 0 0 12px; }
.hm-section-head p { font-size: 15.5px; color: #8a859c; line-height: 1.7; margin: 0; }

/* ── How it works ── */
.hm-steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
.hm-step { text-align: center; position: relative; }
.hm-step-num {
    width: 56px; height: 56px; border-radius: 16px; margin: 0 auto 18px;
    background: var(--role-accent-soft); color: var(--role-accent);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; font-weight: 800;
}
.hm-step h3 { font-size: 16.5px; font-weight: 700; color: #2e263d; margin: 0 0 8px; }
.hm-step p { font-size: 13.5px; color: #8a859c; line-height: 1.65; margin: 0; }

/* ── Why cards ── */
.hm-why-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }
.hm-why-card {
    background: #fff; border: 1px solid #f0eef4; border-radius: 18px;
    padding: 28px 22px; transition: .2s;
}
.hm-why-card:hover { box-shadow: 0 12px 34px rgba(43,40,70,.09); transform: translateY(-3px); border-color: transparent; }
.hm-why-icon {
    width: 48px; height: 48px; border-radius: 13px;
    background: var(--role-accent-soft); color: var(--role-accent);
    display: flex; align-items: center; justify-content: center;
    font-size: 21px; margin-bottom: 16px;
}
.hm-why-card h3 { font-size: 15.5px; font-weight: 700; color: #2e263d; margin: 0 0 8px; }
.hm-why-card p { font-size: 13.5px; color: #8a859c; line-height: 1.65; margin: 0; }

/* ── Services ── */
.hm-services-bg { background: #faf9fc; }
.hm-services-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
.hm-svc-card {
    background: #fff; border-radius: 18px; overflow: hidden;
    border: 1px solid #f0eef4; transition: .2s;
    display: flex; flex-direction: column;
}
.hm-svc-card:hover { box-shadow: 0 14px 36px rgba(43,40,70,.1); transform: translateY(-3px); }
.hm-svc-img { width: 100%; height: 170px; object-fit: cover; display: block; background: #eee; }
.hm-svc-body { padding: 18px 20px; flex: 1; display: flex; flex-direction: column; }
.hm-svc-cat {
    display: inline-flex; align-self: flex-start;
    background: var(--role-accent-soft); color: var(--role-accent);
    font-size: 11px; font-weight: 700; padding: 4px 11px; border-radius: 30px;
    margin-bottom: 10px;
}
.hm-svc-title { font-size: 15.5px; font-weight: 700; color: #2e263d; margin: 0 0 6px; line-height: 1.4; }
.hm-svc-meta { display: flex; align-items: center; gap: 6px; font-size: 12.5px; color: #8a859c; margin-bottom: 12px; }
.hm-svc-footer { display: flex; align-items: center; justify-content: space-between; margin-top: auto; padding-top: 12px; border-top: 1px solid #f5f4f8; }
.hm-svc-price { font-size: 15px; font-weight: 800; color: var(--role-accent); }
.hm-svc-rating { display: flex; align-items: center; gap: 4px; font-size: 12.5px; font-weight: 700; color: #2e263d; }
.hm-svc-rating i { color: #ff9f43; }
.hm-services-cta { text-align: center; margin-top: 44px; }

/* ── Categories ── */
.hm-cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
.hm-cat-card {
    display: flex; align-items: center; gap: 14px;
    background: #fff; border: 1px solid #f0eef4; border-radius: 16px;
    padding: 18px 20px; transition: .18s;
    color: #2e263d;
}
.hm-cat-card:hover { border-color: var(--role-accent); box-shadow: 0 8px 22px rgba(var(--role-accent-rgb),.12); color: #2e263d; }
.hm-cat-icon {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    background: var(--role-accent-soft); color: var(--role-accent);
    display: flex; align-items: center; justify-content: center; font-size: 19px;
}
.hm-cat-name { font-size: 14.5px; font-weight: 700; margin: 0 0 2px; }
.hm-cat-sub { font-size: 12px; color: #8a859c; margin: 0; }

/* ── Final CTA ── */
.hm-final-cta {
    background: linear-gradient(150deg, var(--role-accent) 0%, var(--role-accent-light) 100%);
    border-radius: 28px; padding: 64px 40px; text-align: center;
    position: relative; overflow: hidden; margin: 0 24px;
}
.hm-final-cta::before {
    content: ''; position: absolute; top: -100px; right: -80px;
    width: 300px; height: 300px; border-radius: 50%; background: rgba(255,255,255,.07);
}
.hm-final-cta h2 { font-size: 30px; font-weight: 800; color: #fff; margin: 0 0 12px; position: relative; z-index: 1; }
.hm-final-cta p { font-size: 15.5px; color: rgba(255,255,255,.85); margin: 0 0 30px; position: relative; z-index: 1; }

/* ── Footer ── */
.hm-footer { background: #1e1a2b; color: rgba(255,255,255,.65); padding: 56px 24px 28px; margin-top: 90px; }
.hm-footer-inner { max-width: 1160px; margin: 0 auto; display: flex; justify-content: space-between; gap: 40px; flex-wrap: wrap; }
.hm-footer-brand { max-width: 320px; }
.hm-footer-brand p { font-size: 13.5px; line-height: 1.7; margin-top: 14px; }
.hm-footer-col h4 { font-size: 13px; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: .05em; margin: 0 0 16px; }
.hm-footer-col a { display: block; font-size: 13.5px; color: rgba(255,255,255,.65); margin-bottom: 11px; transition: .15s; }
.hm-footer-col a:hover { color: #fff; }
.hm-footer-bottom {
    max-width: 1160px; margin: 40px auto 0; padding-top: 24px;
    border-top: 1px solid rgba(255,255,255,.1);
    font-size: 12.5px; color: rgba(255,255,255,.45); text-align: center;
}

@media (max-width: 900px) {
    .hm-nav-links { display: none; }
    .hm-hero h1 { font-size: 32px; }
    .hm-stats { grid-template-columns: repeat(2, 1fr); }
    .hm-stat:nth-child(2) { border-right: none; }
    .hm-steps, .hm-why-grid, .hm-cat-grid { grid-template-columns: repeat(2, 1fr); }
    .hm-services-grid { grid-template-columns: 1fr; }
    .hm-section { padding: 60px 20px; }
}
@media (max-width: 560px) {
    .hm-steps, .hm-why-grid, .hm-cat-grid { grid-template-columns: 1fr; }
    .hm-stats { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 480px) {
    .hm-nav-inner { padding: 14px 16px; }
    .hm-brand-name { display: none; }
    .hm-nav-actions { gap: 8px; }
    .hm-btn-ghost { display: none; }
    .hm-lang { padding: 8px 10px; }
    .hm-lang span, .hm-lang { font-size: 12px; }
    .hm-btn-solid { padding: 9px 14px; font-size: 13px; }
}
</style>
@endsection

@section('content')
@php
    $currentLang = app()->getLocale();
@endphp

{{-- ── Navbar ── --}}
<nav class="hm-nav">
    <div class="hm-nav-inner">
        <a href="{{ route('home') }}" class="hm-brand">
            <div class="hm-brand-icon"><i class="ri ri-building-4-fill"></i></div>
            <div class="hm-brand-name">Real Estate Services</div>
        </a>

        <div class="hm-nav-links">
            <a href="#how-it-works">{{ __('app.home_nav_how_it_works') }}</a>
            <a href="#services">{{ __('app.home_nav_services') }}</a>
            <a href="#categories">{{ __('app.home_nav_categories') }}</a>
        </div>

        <div class="hm-nav-actions">
            <a href="{{ route('language.switch', $currentLang === 'ar' ? 'en' : 'ar') }}" class="hm-lang">
                <i class="ri ri-global-line"></i> {{ $currentLang === 'ar' ? 'EN' : 'AR' }}
            </a>
            <a href="{{ route('login') }}" class="hm-btn hm-btn-ghost">{{ __('app.home_nav_login') }}</a>
            <a href="{{ route('register.create') }}" class="hm-btn hm-btn-solid">{{ __('app.home_nav_get_started') }}</a>
        </div>
    </div>
</nav>

{{-- ── Hero ── --}}
<section class="hm-hero">
    <div class="hm-hero-inner">
        <div class="hm-hero-badge">
            <i class="ri ri-shield-check-fill"></i> {{ __('app.home_why_1_title') }}
        </div>
        <h1>{{ __('app.home_hero_title') }}</h1>
        <p>{{ __('app.home_hero_subtitle') }}</p>
        <div class="hm-hero-ctas">
            <a href="#services" class="hm-btn hm-btn-lg hm-btn-white">
                <i class="ri ri-search-line"></i> {{ __('app.home_hero_cta_browse') }}
            </a>
            <a href="{{ route('register.create') }}" class="hm-btn hm-btn-lg hm-btn-outline-white">
                <i class="ri ri-user-add-line"></i> {{ __('app.home_hero_cta_start') }}
            </a>
        </div>
    </div>
</section>

{{-- ── Stats ── --}}
<div class="hm-stats-wrap">
    <div class="hm-stats">
        <div class="hm-stat">
            <div class="hm-stat-num">{{ $stats['services'] }}+</div>
            <div class="hm-stat-lbl">{{ __('app.home_stat_services') }}</div>
        </div>
        <div class="hm-stat">
            <div class="hm-stat-num">{{ $stats['businesses'] }}+</div>
            <div class="hm-stat-lbl">{{ __('app.home_stat_businesses') }}</div>
        </div>
        <div class="hm-stat">
            <div class="hm-stat-num">{{ $stats['cities'] }}</div>
            <div class="hm-stat-lbl">{{ __('app.home_stat_cities') }}</div>
        </div>
        <div class="hm-stat">
            <div class="hm-stat-num">{{ $stats['categories'] }}</div>
            <div class="hm-stat-lbl">{{ __('app.home_stat_categories') }}</div>
        </div>
    </div>
</div>

{{-- ── How It Works ── --}}
<section class="hm-section" id="how-it-works">
    <div class="hm-section-head">
        <div class="hm-eyebrow">{{ __('app.home_nav_how_it_works') }}</div>
        <h2>{{ __('app.home_how_title') }}</h2>
        <p>{{ __('app.home_how_subtitle') }}</p>
    </div>
    <div class="hm-steps">
        <div class="hm-step">
            <div class="hm-step-num">1</div>
            <h3>{{ __('app.home_how_step1_title') }}</h3>
            <p>{{ __('app.home_how_step1_desc') }}</p>
        </div>
        <div class="hm-step">
            <div class="hm-step-num">2</div>
            <h3>{{ __('app.home_how_step2_title') }}</h3>
            <p>{{ __('app.home_how_step2_desc') }}</p>
        </div>
        <div class="hm-step">
            <div class="hm-step-num">3</div>
            <h3>{{ __('app.home_how_step3_title') }}</h3>
            <p>{{ __('app.home_how_step3_desc') }}</p>
        </div>
        <div class="hm-step">
            <div class="hm-step-num">4</div>
            <h3>{{ __('app.home_how_step4_title') }}</h3>
            <p>{{ __('app.home_how_step4_desc') }}</p>
        </div>
    </div>
</section>

{{-- ── Why Choose Us ── --}}
<section class="hm-section hm-section-tight">
    <div class="hm-section-head">
        <h2>{{ __('app.home_why_title') }}</h2>
    </div>
    <div class="hm-why-grid">
        <div class="hm-why-card">
            <div class="hm-why-icon"><i class="ri ri-shield-check-line"></i></div>
            <h3>{{ __('app.home_why_1_title') }}</h3>
            <p>{{ __('app.home_why_1_desc') }}</p>
        </div>
        <div class="hm-why-card">
            <div class="hm-why-icon"><i class="ri ri-chat-3-line"></i></div>
            <h3>{{ __('app.home_why_2_title') }}</h3>
            <p>{{ __('app.home_why_2_desc') }}</p>
        </div>
        <div class="hm-why-card">
            <div class="hm-why-icon"><i class="ri ri-bank-card-line"></i></div>
            <h3>{{ __('app.home_why_3_title') }}</h3>
            <p>{{ __('app.home_why_3_desc') }}</p>
        </div>
        <div class="hm-why-card">
            <div class="hm-why-icon"><i class="ri ri-star-smile-line"></i></div>
            <h3>{{ __('app.home_why_4_title') }}</h3>
            <p>{{ __('app.home_why_4_desc') }}</p>
        </div>
    </div>
</section>

{{-- ── Featured Services ── --}}
<section class="hm-section hm-services-bg" id="services" style="max-width:100%;">
    <div style="max-width:1160px;margin:0 auto;">
        <div class="hm-section-head">
            <div class="hm-eyebrow">{{ __('app.home_nav_services') }}</div>
            <h2>{{ __('app.home_services_title') }}</h2>
            <p>{{ __('app.home_services_subtitle') }}</p>
        </div>

        @if($featuredServices->count())
        <div class="hm-services-grid">
            @foreach($featuredServices as $service)
            @php
                $cityName = $currentLang === 'ar'
                    ? ($service->business?->city?->name_ar ?? '')
                    : ($service->business?->city?->name_en ?? '');
                $catName = $currentLang === 'ar'
                    ? ($service->category?->name_ar ?? '')
                    : ($service->category?->name_en ?? '');
                $price = $service->price_usd ? number_format($service->price_usd) . ' USD' : '—';
            @endphp
            <a href="{{ route('register.create') }}" class="hm-svc-card">
                <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="hm-svc-img"
                     loading="lazy" referrerpolicy="no-referrer"
                     onerror="this.onerror=null;this.src='https://picsum.photos/seed/svc{{ $service->id }}/600/400';">
                <div class="hm-svc-body">
                    <span class="hm-svc-cat">{{ $catName }}</span>
                    <h3 class="hm-svc-title">{{ $service->title }}</h3>
                    <div class="hm-svc-meta">
                        <i class="ri ri-map-pin-line"></i> {{ $cityName ?: '—' }}
                    </div>
                    <div class="hm-svc-footer">
                        <span class="hm-svc-price">{{ $price }}</span>
                        <span class="hm-svc-rating">
                            <i class="ri ri-star-fill"></i>
                            {{ $service->reviews_avg_rating ? number_format($service->reviews_avg_rating, 1) : '—' }}
                            <span style="color:#b0aab8;font-weight:500;">({{ $service->reviews_count }})</span>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <p style="text-align:center;color:#8a859c;">{{ __('app.home_services_empty') }}</p>
        @endif

        <div class="hm-services-cta">
            <a href="{{ route('register.create') }}" class="hm-btn hm-btn-lg hm-btn-solid">
                <i class="ri ri-arrow-right-line"></i> {{ __('app.home_services_cta') }}
            </a>
        </div>
    </div>
</section>

{{-- ── Categories ── --}}
<section class="hm-section" id="categories">
    <div class="hm-section-head">
        <div class="hm-eyebrow">{{ __('app.home_nav_categories') }}</div>
        <h2>{{ __('app.home_categories_title') }}</h2>
        <p>{{ __('app.home_categories_subtitle') }}</p>
    </div>
    <div class="hm-cat-grid">
        @foreach($categories as $category)
        @php
            $catName = $currentLang === 'ar' ? $category->name_ar : $category->name_en;
        @endphp
        <a href="{{ route('register.create') }}" class="hm-cat-card">
            <div class="hm-cat-icon"><i class="ri ri-shapes-line"></i></div>
            <div>
                <div class="hm-cat-name">{{ $catName }}</div>
                <div class="hm-cat-sub">{{ __('app.subcategories_count', ['count' => $category->subcategories_count]) }}</div>
            </div>
        </a>
        @endforeach
    </div>
</section>

{{-- ── Final CTA ── --}}
<section class="hm-final-cta">
    <h2>{{ __('app.home_cta_title') }}</h2>
    <p>{{ __('app.home_cta_subtitle') }}</p>
    <a href="{{ route('register.create') }}" class="hm-btn hm-btn-lg hm-btn-white" style="position:relative;z-index:1;">
        <i class="ri ri-user-add-line"></i> {{ __('app.home_cta_button') }}
    </a>
</section>

{{-- ── Footer ── --}}
<footer class="hm-footer">
    <div class="hm-footer-inner">
        <div class="hm-footer-brand">
            <div class="hm-brand">
                <div class="hm-brand-icon"><i class="ri ri-building-4-fill"></i></div>
                <div class="hm-brand-name" style="color:#fff;">Real Estate Services</div>
            </div>
            <p>{{ __('app.home_footer_tagline') }}</p>
        </div>

        <div class="hm-footer-col">
            <h4>{{ __('app.home_footer_platform') }}</h4>
            <a href="#how-it-works">{{ __('app.home_nav_how_it_works') }}</a>
            <a href="#services">{{ __('app.home_nav_services') }}</a>
            <a href="#categories">{{ __('app.home_nav_categories') }}</a>
        </div>

        <div class="hm-footer-col">
            <h4>{{ __('app.home_footer_company') }}</h4>
            <a href="{{ route('login') }}">{{ __('app.home_nav_login') }}</a>
            <a href="{{ route('register.create') }}">{{ __('app.home_nav_get_started') }}</a>
            <a href="{{ route('logina.create') }}">{{ __('app.home_footer_login_admin') }}</a>
            <a href="{{ route('loginsa.create') }}">{{ __('app.home_footer_login_superadmin') }}</a>
        </div>
    </div>

    <div class="hm-footer-bottom">
        &copy; {{ date('Y') }} Real Estate Services. {{ __('app.home_footer_rights') }}
    </div>
</footer>
@endsection
