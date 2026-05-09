@extends('layouts/contentNavbarLayout')

@section('title', 'Create Business')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create Business Account</h4>
                    <small class="text-muted">Fill in the information below</small>
                </div>

                <div class="card-body">
                    <form action="{{ route('business.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select" name="activetype_id">
                                        <option value="">Select Activity Type</option>
                                        @foreach($activetypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <label>Activity Type</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="number" class="form-control" name="license_number" placeholder="License Number">
                                    <label>License Number</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="job_name_ar" placeholder="Job Name Arabic">
                                    <label>Job Name Arabic</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="job_name_en" placeholder="Job Name English">
                                    <label>Job Name English</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="activites" placeholder="Activites">
                                    <label>Activities</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating form-floating-outline">
                                    <textarea class="form-control" name="details" placeholder="Details" style="height: 120px"></textarea>
                                    <label>Details</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select" name="city_id">
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name_en }}</option>
                                        @endforeach
                                    </select>
                                    <label>City</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control" name="image">
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-2">Service Location</label>
                            <div id="service-map" style="height: 380px; border-radius: 14px; overflow: hidden;"></div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Latitude</label>
                            <input
                                type="text"
                                name="latitude"
                                id="latitude"
                                class="form-control"
                                value="{{ old('latitude') }}"
                                readonly
                            >
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Longitude</label>
                            <input
                                type="text"
                                name="longitude"
                                id="longitude"
                                class="form-control"
                                value="{{ old('longitude') }}"
                                readonly
                            >
                        </div>

                        <div class="col-12">
                            <button type="button" class="btn btn-outline-primary" onclick="getServiceLocation()">
                                Use Current Location
                            </button>
                        </div>
                        @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const defaultLat = 33.5138;
    const defaultLng = 36.2765;

    const map = L.map('service-map').setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;

    function setLocation(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            marker.on('dragend', function () {
                const position = marker.getLatLng();
                document.getElementById('latitude').value = position.lat;
                document.getElementById('longitude').value = position.lng;
            });
        }

        map.setView([lat, lng], 15);
    }

    map.on('click', function (e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        setLocation(lat, lng);
    });

    window.getServiceLocation = function () {
        if (!navigator.geolocation) {
            alert('Browser does not support Geolocation');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                console.log('latitude:', lat);
                console.log('longitude:', lng);

                setLocation(lat, lng);
                alert('Location captured successfully!');
            },
            function (error) {
                console.error(error);

                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert('Location access denied');
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert('Location not available');
                        break;
                    case error.TIMEOUT:
                        alert('Timeout occurred while fetching location');
                        break;
                    default:
                        alert('Unknown error occurred');
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    };

    @if(old('latitude') && old('longitude'))
        setLocation({{ old('latitude') }}, {{ old('longitude') }});
    @endif
});
</script>
@endpush
@endsection
