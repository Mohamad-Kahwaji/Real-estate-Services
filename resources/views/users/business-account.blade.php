@extends('layouts/contentNavbarLayout')

@php $editing = isset($business); @endphp

@section('title', $editing ? __('app.edit_business') : __('app.create_business'))

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">

        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h5 class="mb-0">{{ $editing ? __('app.edit_business') : __('app.create_business') }}</h5>
                <small class="text-muted">
                    {{ $editing ? __('app.update_business_details') : __('app.fill_in_details_wait') }}
                </small>
            </div>

            <div class="card-body pt-4">

                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                <form action="{{ $editing ? route('user.business.update', $business->id) : route('user.business.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($editing) @method('PUT') @endif

                    <div class="row g-4">

                        {{-- Activity Type --}}
                        @php $isAr = app()->getLocale() === 'ar'; @endphp
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select @error('activetype_id') is-invalid @enderror" name="activetype_id">
                                    <option value="">{{ __('app.select_activity_type') }}</option>
                                    @foreach($activetypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('activetype_id', $editing ? $business->activetype_id : '') == $type->id ? 'selected' : '' }}>
                                            {{ ($isAr && $type->name_ar) ? $type->name_ar : $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>{{ __('app.activity_type') }}</label>
                                @error('activetype_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- License Number --}}
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="number"
                                       class="form-control @error('license_number') is-invalid @enderror"
                                       name="license_number"
                                       value="{{ old('license_number', $editing ? $business->license_number : '') }}"
                                       placeholder="{{ __('app.license_number') }}">
                                <label>{{ __('app.license_number') }}</label>
                                @error('license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Job Name Arabic --}}
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text"
                                       class="form-control @error('job_name_ar') is-invalid @enderror"
                                       name="job_name_ar"
                                       value="{{ old('job_name_ar', $editing ? $business->job_name_ar : '') }}"
                                       placeholder="{{ __('app.job_name_arabic') }}">
                                <label>{{ __('app.job_name_arabic') }}</label>
                                @error('job_name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Job Name English --}}
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text"
                                       class="form-control @error('job_name_en') is-invalid @enderror"
                                       name="job_name_en"
                                       value="{{ old('job_name_en', $editing ? $business->job_name_en : '') }}"
                                       placeholder="{{ __('app.job_name_english') }}">
                                <label>{{ __('app.job_name_english') }}</label>
                                @error('job_name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Activities --}}
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text"
                                       class="form-control @error('activites') is-invalid @enderror"
                                       name="activites"
                                       value="{{ old('activites', $editing ? $business->activites : '') }}"
                                       placeholder="{{ __('app.activities') }}">
                                <label>{{ __('app.activities') }}</label>
                                @error('activites') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control @error('details') is-invalid @enderror"
                                          name="details" placeholder="{{ __('app.details') }}"
                                          style="height: 120px">{{ old('details', $editing ? $business->details : '') }}</textarea>
                                <label>{{ __('app.details') }}</label>
                                @error('details') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- City --}}
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select @error('city_id') is-invalid @enderror" name="city_id">
                                    <option value="">{{ __('app.select_city') }}</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ old('city_id', $editing ? $business->city_id : '') == $city->id ? 'selected' : '' }}>
                                            {{ $isAr ? $city->name_ar : $city->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>{{ __('app.city') }}</label>
                                @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('app.business_image') }} <span class="text-muted">{{ __('app.optional_label') }}</span></label>
                            @if($editing && $business->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $business->image) }}"
                                         style="height:60px;border-radius:8px;object-fit:cover;" alt="{{ __('app.current_image') }}">
                                    <small class="text-muted ms-2">{{ __('app.current_image') }}</small>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   name="image" accept="image/*">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Map --}}
                        <div class="col-12">
                            <label class="form-label mb-2">{{ __('app.business_location') }} <span class="text-muted">{{ __('app.business_location_hint') }}</span></label>
                            <div id="business-map" style="height: 360px; border-radius: 14px; overflow: hidden; border: 1px solid #e4e4eb;"></div>
                        </div>

                        {{-- Lat / Lng --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('app.latitude') }}</label>
                            <input type="text" name="latitude" id="latitude" class="form-control"
                                   value="{{ old('latitude', $editing ? $business->latitude : '') }}" readonly>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('app.longitude') }}</label>
                            <input type="text" name="longitude" id="longitude" class="form-control"
                                   value="{{ old('longitude', $editing ? $business->longitude : '') }}" readonly>
                        </div>

                        <div class="col-12">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="useMyLocation()">
                                <i class="ri ri-map-pin-line me-1"></i> {{ __('app.use_current_location') }}
                            </button>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('business.dashboard') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri {{ $editing ? 'ri-save-line' : 'ri-send-plane-line' }} me-1"></i>
                                    {{ $editing ? __('app.save_changes') : __('app.submit_for_approval') }}
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const defaultLat = {{ old('latitude', isset($business) && $business->latitude ? $business->latitude : 33.5138) }};
    const defaultLng = {{ old('longitude', isset($business) && $business->longitude ? $business->longitude : 36.2765) }};

    const map = L.map('business-map').setView([defaultLat, defaultLng], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;

    function setLocation(lat, lng) {
        document.getElementById('latitude').value  = lat;
        document.getElementById('longitude').value = lng;
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function () {
                const pos = marker.getLatLng();
                document.getElementById('latitude').value  = pos.lat;
                document.getElementById('longitude').value = pos.lng;
            });
        }
        map.setView([lat, lng], 15);
    }

    map.on('click', function (e) { setLocation(e.latlng.lat, e.latlng.lng); });

    window.useMyLocation = function () {
        if (!navigator.geolocation) { alert('{{ __("app.geolocation_not_supported") }}'); return; }
        navigator.geolocation.getCurrentPosition(
            pos => setLocation(pos.coords.latitude, pos.coords.longitude),
            ()  => alert('{{ __("app.could_not_get_location") }}')
        );
    };

    @if(old('latitude', isset($business) && $business->latitude ? $business->latitude : null))
        setLocation({{ old('latitude', $business->latitude ?? 0) }}, {{ old('longitude', $business->longitude ?? 0) }});
    @endif
});
</script>
@endpush
