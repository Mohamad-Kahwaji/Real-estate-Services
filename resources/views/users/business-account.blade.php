@extends('layouts/contentNavbarLayout')

@section('title', 'Create Business Account')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">

        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Create Business Account</h5>
                <small class="text-muted">Fill in the details below and wait for admin approval</small>
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
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('user.business.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">

                        {{-- Activity Type --}}
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select @error('activetype_id') is-invalid @enderror" name="activetype_id">
                                    <option value="">Select Activity Type</option>
                                    @foreach($activetypes as $type)
                                        <option value="{{ $type->id }}" {{ old('activetype_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Activity Type</label>
                                @error('activetype_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- License Number --}}
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="number"
                                       class="form-control @error('license_number') is-invalid @enderror"
                                       name="license_number"
                                       value="{{ old('license_number') }}"
                                       placeholder="License Number">
                                <label>License Number</label>
                                @error('license_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Job Name Arabic --}}
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text"
                                       class="form-control @error('job_name_ar') is-invalid @enderror"
                                       name="job_name_ar"
                                       value="{{ old('job_name_ar') }}"
                                       placeholder="Job Name Arabic">
                                <label>Job Name (Arabic)</label>
                                @error('job_name_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Job Name English --}}
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text"
                                       class="form-control @error('job_name_en') is-invalid @enderror"
                                       name="job_name_en"
                                       value="{{ old('job_name_en') }}"
                                       placeholder="Job Name English">
                                <label>Job Name (English)</label>
                                @error('job_name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Activities --}}
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text"
                                       class="form-control @error('activites') is-invalid @enderror"
                                       name="activites"
                                       value="{{ old('activites') }}"
                                       placeholder="Activities">
                                <label>Activities</label>
                                @error('activites')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control @error('details') is-invalid @enderror"
                                          name="details"
                                          placeholder="Details"
                                          style="height: 120px">{{ old('details') }}</textarea>
                                <label>Details</label>
                                @error('details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- City --}}
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select @error('city_id') is-invalid @enderror" name="city_id">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>City</label>
                                @error('city_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label">Business Image <span class="text-muted">(optional)</span></label>
                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   name="image"
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Map --}}
                        <div class="col-12">
                            <label class="form-label mb-2">Business Location <span class="text-muted">(optional — click map or use button)</span></label>
                            <div id="business-map" style="height: 360px; border-radius: 14px; overflow: hidden; border: 1px solid #e4e4eb;"></div>
                        </div>

                        {{-- Lat / Lng --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" id="latitude"
                                   class="form-control" value="{{ old('latitude') }}" readonly>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" id="longitude"
                                   class="form-control" value="{{ old('longitude') }}" readonly>
                        </div>

                        <div class="col-12">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="useMyLocation()">
                                <i class="ri ri-map-pin-line me-1"></i> Use Current Location
                            </button>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri ri-send-plane-line me-1"></i> Submit for Approval
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
    const defaultLat = 33.5138;
    const defaultLng = 36.2765;

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

    map.on('click', function (e) {
        setLocation(e.latlng.lat, e.latlng.lng);
    });

    window.useMyLocation = function () {
        if (!navigator.geolocation) { alert('Geolocation not supported'); return; }
        navigator.geolocation.getCurrentPosition(
            pos => setLocation(pos.coords.latitude, pos.coords.longitude),
            ()  => alert('Could not get your location')
        );
    };

    @if(old('latitude') && old('longitude'))
        setLocation({{ old('latitude') }}, {{ old('longitude') }});
    @endif
});
</script>
@endpush
