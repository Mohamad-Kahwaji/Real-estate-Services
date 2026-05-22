@extends('layouts/contentNavbarLayout')

@section('title', 'Create Service')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">

        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Create Service</h5>
            </div>

            <div class="card-body pt-4">
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

                <form method="POST" action="{{ route('user.service.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" name="business_id">
                                    <option value="">Select Business Account</option>
                                    @foreach($businesses as $business)
                                        <option value="{{ $business->id }}" {{ old('business_id') == $business->id ? 'selected' : '' }}>
                                            {{ $business->job_name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Business Account</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" name="category_id" id="categorySelect">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Category</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" name="subcategory_id" id="subcategorySelect">
                                    <option value="">Select Subcategory</option>
                                    @foreach($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}"
                                                data-category="{{ $subcategory->category_id }}"
                                                {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                            {{ $subcategory->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Subcategory</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input
                                    type="text"
                                    class="form-control"
                                    name="title"
                                    value="{{ old('title') }}"
                                    placeholder="Title"
                                >
                                <label>Title</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <textarea
                                    class="form-control"
                                    name="description"
                                    placeholder="Description"
                                    style="height: 140px"
                                >{{ old('description') }}</textarea>
                                <label>Description</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-floating form-floating-outline">
                                <input
                                    type="number"
                                    class="form-control"
                                    name="quantity"
                                    value="{{ old('quantity') }}"
                                    placeholder="Quantity"
                                >
                                <label>Quantity</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" name="services_type">
                                    <option value="">Select Service Type</option>
                                    <option value="sale" {{ old('services_type') == 'sale' ? 'selected' : '' }}>Sale</option>
                                    <option value="rent" {{ old('services_type') == 'rent' ? 'selected' : '' }}>Rent</option>
                                </select>
                                <label>Service Type</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input
                                    type="number"
                                    step="0.01"
                                    class="form-control"
                                    name="price_usd"
                                    value="{{ old('price_usd') }}"
                                    placeholder="Price in USD"
                                >
                                <label>Price (USD $)</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input
                                    type="number"
                                    step="0.01"
                                    class="form-control"
                                    name="price_syp"
                                    value="{{ old('price_syp') }}"
                                    placeholder="Price in SYP"
                                >
                                <label>Price (SYP ل.س)</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image">
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

                        <div class="col-12">
                            <div class="mt-4 d-flex justify-content-end gap-2 border-top pt-4">
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                <button type="submit" class="btn btn-primary">Create Service</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Category → Subcategory dynamic filter ──
    const catSel = document.getElementById('categorySelect');
    const subSel = document.getElementById('subcategorySelect');
    const allSubOptions = Array.from(subSel.querySelectorAll('option[data-category]'));

    function filterSubcategories(categoryId) {
        const selected = subSel.value;
        subSel.innerHTML = '<option value="">Select Subcategory</option>';
        allSubOptions.forEach(opt => {
            if (!categoryId || opt.dataset.category === categoryId) {
                const clone = opt.cloneNode(true);
                if (clone.value === selected) clone.selected = true;
                subSel.appendChild(clone);
            }
        });
    }

    catSel.addEventListener('change', function () {
        filterSubcategories(this.value);
    });

    // Run on load to respect old() values
    if (catSel.value) filterSubcategories(catSel.value);

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
