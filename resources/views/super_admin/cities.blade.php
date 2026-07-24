@extends('layouts/contentNavbarLayout')
{{-- Cities management page: lists all available cities (bilingual) and provides add/edit/delete modals for location management. --}}

@section('title', __('app.cities'))

@section('page-style')
<style>
:root {
    --accent: var(--role-accent); --accent-soft: var(--role-accent-soft);
    --info: #00cfe8; --info-soft: #e3f8fc;
    --danger: #ea5455; --danger-soft: #fdeaea;
    --shadow: 0 4px 24px rgba(var(--role-accent-rgb),.10);
    --radius: 16px;
}
.pg-header h4 { font-size: 22px; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
.pg-header p  { font-size: 13px; color: #8592a3; margin: 0; }

.stat-card {
    background: #fff; border-radius: var(--radius);
    box-shadow: var(--shadow); padding: 20px 24px;
    display: flex; align-items: center; gap: 16px;
}
.stat-icon {
    width: 50px; height: 50px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.stat-label { font-size: 12px; color: #8592a3; font-weight: 600; }
.stat-value { font-size: 26px; font-weight: 800; line-height: 1; color: #1e293b; }

.table-card { background: #fff; border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
.table-card-header {
    padding: 18px 24px; border-bottom: 1px solid #f0eef8;
    display: flex; align-items: center; justify-content: space-between;
}
.table-card-header h6 { font-weight: 700; color: #1e293b; margin: 0; }

.table { margin: 0; }
.table thead th {
    background: #fafbff; font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .7px;
    color: #8592a3; border-bottom: 1px solid #f0eef8; padding: 14px 20px;
}
.table tbody td {
    padding: 14px 20px; vertical-align: middle;
    border-bottom: 1px solid #f7f7f9; font-size: 14px; color: #1e293b;
}
.table tbody tr:last-child td { border-bottom: none; }
.table tbody tr:hover td { background: #fafbff; }

.badge-pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
.badge-accent { background: var(--accent-soft); color: var(--accent); }
.badge-info   { background: var(--info-soft);   color: var(--info); }

.btn-add {
    background: linear-gradient(135deg,var(--role-accent),var(--role-accent-light));
    border: none; color: #fff; padding: 10px 20px; border-radius: 12px;
    font-size: 13px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px;
    box-shadow: 0 4px 14px rgba(var(--role-accent-rgb),.3); transition: opacity .2s;
}
.btn-add:hover { opacity: .88; color: #fff; }

.action-btn {
    width: 34px; height: 34px; border-radius: 10px;
    display: inline-flex; align-items: center; justify-content: center;
    border: none; cursor: pointer; font-size: 16px; transition: .2s;
}
.btn-edit   { background: var(--role-accent-soft); color: var(--role-accent); }
.btn-edit:hover   { background: var(--role-accent); color: #fff; }
.btn-delete { background: #fdeaea; color: #ea5455; }
.btn-delete:hover { background: #ea5455; color: #fff; }

.empty-state { padding: 60px 20px; text-align: center; }
.empty-state i { font-size: 48px; color: #c8ccda; display: block; margin-bottom: 14px; }
.empty-state p { color: #8592a3; font-weight: 600; margin: 0; }

.modal-content { border: none; border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,.18); }
.modal-header  { border-bottom: 1px solid #f0eef8; padding: 22px 26px; }
.modal-body    { padding: 26px; }
.modal-footer  { border-top: 1px solid #f0eef8; padding: 16px 26px; }
.modal-title   { font-weight: 800; font-size: 16px; }
.form-label    { font-size: 13px; font-weight: 600; color: #3b3551; }
.form-control  { border-radius: 12px; border: 1.5px solid #e4e4eb; padding: 11px 14px; font-size: 14px; }
.form-control:focus { border-color: var(--role-accent); box-shadow: 0 0 0 3px rgba(var(--role-accent-rgb),.12); }

.al-alert { border-radius: 12px; padding: 12px 16px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.al-alert-success { background: #e8f8ef; color: #1a7a45; border: 1px solid #c3e9d4; }
</style>
@endsection

@section('content')

{{-- Header --}}
<div class="pg-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>{{ __('app.cities') }}</h4>
        <p>{{ __('app.cities_desc') }}</p>
    </div>
    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addCityModal">
        <i class="ri ri-add-line"></i> {{ __('app.add_city') }}
    </button>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="al-alert al-alert-success mb-4">
        <i class="ri ri-checkbox-circle-line"></i> {{ session('success') }}
    </div>
@endif

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e3f8fc;">
                <i class="ri ri-map-pin-2-line" style="color:#00cfe8;"></i>
            </div>
            <div>
                <div class="stat-label">{{ __('app.total_cities') }}</div>
                <div class="stat-value">{{ $cities->count() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="table-card">
    <div class="table-card-header">
        <h6><i class="ri ri-map-pin-2-line me-2" style="color:var(--role-accent);"></i>{{ __('app.cities') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</th>
                    <th>{{ app()->getLocale() === 'ar' ? 'الخدمات' : 'Services' }}</th>
                    <th>{{ app()->getLocale() === 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cities as $city)
                <tr>
                    <td><span class="badge-pill badge-accent">{{ $city->id }}</span></td>
                    <td>
                        <strong>{{ app()->getLocale() === 'ar' ? $city->name_ar : $city->name_en }}</strong>
                        <div style="font-size:11px;color:#8592a3;">{{ app()->getLocale() === 'ar' ? $city->name_en : $city->name_ar }}</div>
                    </td>
                    <td>
                        <button type="button"
                                class="badge-pill"
                                style="background:var(--role-accent-soft);color:var(--role-accent);border:none;cursor:pointer;"
                                data-bs-toggle="modal"
                                data-bs-target="#svcModal{{ $city->id }}"
                                title="View services">
                            <i class="ri ri-store-2-line"></i> {{ $city->services_count }}
                        </button>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="action-btn btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCityModal{{ $city->id }}"
                                    title="Edit">
                                <i class="ri ri-pencil-line"></i>
                            </button>
                            <form action="{{ route('cities.destroy', $city->id) }}" method="POST"
                                  onsubmit="return confirm('{{ __("app.confirm_delete") }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" title="Delete">
                                    <i class="ri ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="ri ri-map-pin-2-line"></i>
                            <p>{{ __('app.no_cities_found') }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══ Add Modal ══ --}}
<div class="modal fade" id="addCityModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('cities.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri ri-map-pin-add-line me-2" style="color:var(--role-accent);"></i>{{ __('app.add_city') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('app.arabic_name') }}</label>
                        <input type="text" class="form-control" name="name_ar"
                               placeholder="اسم المدينة" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('app.english_name') }}</label>
                        <input type="text" class="form-control" name="name_en"
                               placeholder="City name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px;">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:10px;font-weight:700;">
                        <i class="ri ri-save-line me-1"></i>{{ __('app.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ Edit Modals ══ --}}
@foreach ($cities as $city)
<div class="modal fade" id="editCityModal{{ $city->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('cities.update', $city->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri ri-pencil-line me-2" style="color:var(--role-accent);"></i>{{ __('app.edit_city') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('app.arabic_name') }}</label>
                        <input type="text" class="form-control" name="name_ar"
                               value="{{ $city->name_ar }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('app.english_name') }}</label>
                        <input type="text" class="form-control" name="name_en"
                               value="{{ $city->name_en }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px;">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:10px;font-weight:700;">
                        <i class="ri ri-save-line me-1"></i>{{ __('app.update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- ══ Services Modals ══ --}}
@foreach ($cities as $city)
<div class="modal fade" id="svcModal{{ $city->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri ri-store-2-line me-2" style="color:var(--role-accent);"></i>
                    {{ app()->getLocale() === 'ar' ? ($city->name_ar ?: $city->name_en) : ($city->name_en ?: $city->name_ar) }} — {{ __('app.services') }}
                    <span style="font-size:13px;font-weight:600;color:#8592a3;margin-right:6px;">({{ $city->services_count }})</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px 24px;">
                @if($city->services->isEmpty())
                    <div style="text-align:center;padding:40px 0;color:#8592a3;">
                        <i class="ri ri-store-2-line" style="font-size:40px;display:block;margin-bottom:10px;opacity:.35;"></i>
                        {{ __('app.no_services_in_city') }}
                    </div>
                @else
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        @foreach($city->services as $svc)
                        @php
                            $locale   = app()->getLocale();
                            $catName  = $locale === 'ar'
                                ? ($svc->category?->name_ar ?: $svc->category?->name_en)
                                : ($svc->category?->name_en ?: $svc->category?->name_ar);
                            $bizName  = $svc->business
                                ? ($locale === 'ar'
                                    ? ($svc->business->job_name_ar ?: $svc->business->job_name_en)
                                    : ($svc->business->job_name_en ?: $svc->business->job_name_ar))
                                : null;
                        @endphp
                        <div style="background:#fafbff;border:1px solid #f0eef8;border-radius:14px;padding:14px 18px;display:flex;align-items:center;gap:14px;">
                            <img src="{{ $svc->image_url }}"
                                 style="width:52px;height:52px;object-fit:cover;border-radius:10px;flex-shrink:0;"
                                 alt="{{ $svc->title }}">
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:700;color:#1e293b;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ auto_translate($svc->title ?? '') ?: '-' }}
                                </div>
                                <div style="font-size:12px;color:#8592a3;margin-top:2px;">
                                    {{ $catName ?? '-' }}
                                    @if($bizName)
                                        &nbsp;·&nbsp; {{ $bizName }}
                                    @endif
                                </div>
                            </div>
                            @if($svc->services_type)
                            <span style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;
                                background:{{ $svc->services_type === 'sale' ? 'var(--role-accent-soft)' : '#e3f8fc' }};
                                color:{{ $svc->services_type === 'sale' ? 'var(--role-accent)' : '#00cfe8' }};">
                                {{ __('app.' . $svc->services_type) }}
                            </span>
                            @endif
                            <div style="text-align:right;flex-shrink:0;">
                                @if($svc->price_usd)
                                    <div style="font-weight:800;color:#1e293b;font-size:13px;">${{ number_format($svc->price_usd, 0) }}</div>
                                @endif
                                @if($svc->price_syp)
                                    <div style="font-size:11px;color:#8592a3;">{{ number_format($svc->price_syp, 0) }} ل.س</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px;">{{ __('app.close') }}</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
