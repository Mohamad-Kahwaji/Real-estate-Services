@extends('layouts/contentNavbarLayout')

@section('title', 'Super Admin Panel')

@section('content')

{{-- Pending Business Account Requests --}}
<div class="card mb-6 admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Pending Business Account Requests</h5>
        <span class="badge bg-label-warning text-warning">{{ $accounts->count() ?? 0 }} Pending</span>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Business Name</th>
                    <th>License Number</th>
                    <th>City</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($accounts as $account)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-icon me-3">
                                    <i class="icon-base ri ri-user-line"></i>
                                </div>
                                <span class="fw-semibold">{{ $account->user->name ?? '-' }}</span>
                            </div>
                        </td>

                        <td>{{ $account->job_name_en ?? '-' }}</td>
                        <td>{{ $account->license_number ?? '-' }}</td>
                        <td>{{ $account->city->name_en ?? '-' }}</td>

                        <td>
                            @php
                                $status = $account->status ?? 'pending';
                                $statusClass = match($status) {
                                    'approved' => 'bg-label-success text-success',
                                    'rejected' => 'bg-label-danger text-danger',
                                    default => 'bg-label-warning text-warning',
                                };
                            @endphp

                            <span class="badge rounded-pill {{ $statusClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>

                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-icon btn-light dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-more-2-line"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <form action="{{ route('approve', $account->id) }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-success" type="submit">
                                            <i class="icon-base ri ri-check-line me-1"></i>
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('reject', $account->id) }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="icon-base ri ri-close-line me-1"></i>
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">No pending requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- Services --}}
<div class="card mb-6 admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Services</h5>
        <span class="badge bg-label-primary text-primary">{{ $services->count() ?? 0 }} Services</span>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Business</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($services as $service)
                    <tr>
                        <td>{{ $service->business->job_name_en ?? '-' }}</td>
                        <td class="fw-semibold">{{ $service->title }}</td>
                        <td>{{ $service->category->name_en ?? '-' }}</td>
                        <td>
                            {{ $service->price_usd ?? $service->price ?? '-' }}
                            {{ $service->currency ?? '' }}
                        </td>

                        <td>
                            @php
                                $status = $service->status ?? 'pending';
                                $statusClass = match($status) {
                                    'approved' => 'bg-label-success text-success',
                                    'rejected' => 'bg-label-danger text-danger',
                                    default => 'bg-label-warning text-warning',
                                };
                            @endphp

                            <span class="badge rounded-pill {{ $statusClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>

                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-icon btn-light dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-more-2-line"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <form action="{{ route('approveser', $service->id) }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-success" type="submit">
                                            <i class="icon-base ri ri-check-line me-1"></i>
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('rejectser', $service->id) }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="icon-base ri ri-close-line me-1"></i>
                                            Reject
                                        </button>
                                    </form>

                                    <form action="{{ route('pendingser', $service->id) }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-warning" type="submit">
                                            <i class="icon-base ri ri-time-line me-1"></i>
                                            Pending
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">No services found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- Categories --}}
<div class="card mb-6 admin-card">
    <div class="card-header">
        <h5 class="mb-0">Categories | التصنيفات</h5>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Arabic Name | الاسم بالعربي</th>
                    <th>English Name | الاسم بالإنجليزي</th>
                    <th class="text-center">Actions | الإجراءات</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>#{{ $category->id }}</td>
                        <td>{{ $category->name_ar }}</td>
                        <td>{{ $category->name_en }}</td>

                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-icon btn-light dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-more-2-line"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <button class="dropdown-item" type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal{{ $category->id }}">
                                        <i class="icon-base ri ri-pencil-line me-1"></i>
                                        Edit | تعديل
                                    </button>

                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="icon-base ri ri-delete-bin-6-line me-1"></i>
                                            Delete | حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Category Modal --}}
                    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4">
                                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Category | تعديل التصنيف</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Arabic Name | الاسم بالعربي</label>
                                            <input type="text" name="name_ar" class="form-control"
                                                   value="{{ $category->name_ar }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">English Name | الاسم بالإنجليزي</label>
                                            <input type="text" name="name_en" class="form-control"
                                                   value="{{ $category->name_en }}" required>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                            Cancel | إلغاء
                                        </button>

                                        <button type="submit" class="btn btn-primary">
                                            Update | تحديث
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Arabic Name | الاسم بالعربي</label>
                    <input type="text" name="name_ar" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">English Name | الاسم بالإنجليزي</label>
                    <input type="text" name="name_en" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-4">
                Add Category | إضافة تصنيف
            </button>
        </form>
    </div>
</div>


{{-- Subcategories --}}
<div class="card mb-6 admin-card">
    <div class="card-header">
        <h5 class="mb-0">Subcategories | التصنيفات الفرعية</h5>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Arabic Name | الاسم بالعربي</th>
                    <th>English Name | الاسم بالإنجليزي</th>
                    <th>Category | التصنيف الرئيسي</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($subcategories as $subcategory)
                    <tr>
                        <td>#{{ $subcategory->id }}</td>
                        <td>{{ $subcategory->name_ar }}</td>
                        <td>{{ $subcategory->name_en }}</td>
                        <td>{{ $subcategory->category->name_en ?? '-' }}</td>

                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-icon btn-light dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-more-2-line"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <button class="dropdown-item" type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editSubcategoryModal{{ $subcategory->id }}">
                                        <i class="icon-base ri ri-pencil-line me-1"></i>
                                        Edit | تعديل
                                    </button>

                                    <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="icon-base ri ri-delete-bin-6-line me-1"></i>
                                            Delete | حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Subcategory Modal --}}
                    <div class="modal fade" id="editSubcategoryModal{{ $subcategory->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4">
                                <form action="{{ route('subcategories.update', $subcategory->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Subcategory | تعديل التصنيف الفرعي</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Arabic Name | الاسم بالعربي</label>
                                            <input type="text" name="name_ar" class="form-control"
                                                   value="{{ $subcategory->name_ar }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">English Name | الاسم بالإنجليزي</label>
                                            <input type="text" name="name_en" class="form-control"
                                                   value="{{ $subcategory->name_en }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Category | التصنيف الرئيسي</label>
                                            <select name="category_id" class="form-select" required>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        @selected($subcategory->category_id == $category->id)>
                                                        {{ $category->name_en }} | {{ $category->name_ar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                            Cancel | إلغاء
                                        </button>

                                        <button type="submit" class="btn btn-primary">
                                            Update | تحديث
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">No subcategories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-body">
        <form action="{{ route('subcategories.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Arabic Name | الاسم بالعربي</label>
                    <input type="text" name="name_ar" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">English Name | الاسم بالإنجليزي</label>
                    <input type="text" name="name_en" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Category | التصنيف الرئيسي</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category | اختر التصنيف</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name_en }} | {{ $category->name_ar }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Dynamic Fields --}}
            <div class="dynamic-fields-card">
                <div class="dynamic-fields-header">
                    <div>
                        <h6 class="mb-1">Dynamic Fields | الحقول الديناميكية</h6>
                        <p class="text-muted mb-0 small">
                            أضف الحقول التي سيقوم المستخدم بتعبئتها عند إنشاء خدمة ضمن هذا التصنيف الفرعي.
                        </p>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-dynamic-field">
                        <i class="icon-base ri ri-add-line me-1"></i>
                        Add Field
                    </button>
                </div>

                <div id="dynamic-fields-wrapper">
                    <div class="dynamic-field-item">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label small">Field Name</label>
                                <input type="text" name="fields[0][name]" class="form-control" placeholder="area">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small">Label</label>
                                <input type="text" name="fields[0][label]" class="form-control" placeholder="المساحة">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small">Type</label>
                                <select name="fields[0][type]" class="form-select">
                                    <option value="">Select Type</option>
                                    <option value="text">Text</option>
                                    <option value="number">Number</option>
                                    <option value="date">Date</option>
                                    <option value="select">Select</option>
                                    <option value="boolean">Yes / No</option>
                                    <option value="textarea">Textarea</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small">Required</label>
                                <select name="fields[0][is_required]" class="form-select">
                                    <option value="0">Optional</option>
                                    <option value="1">Required</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">Options</label>
                                <input type="text" name="fields[0][options]" class="form-control" placeholder="option1,option2">
                            </div>

                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger remove-dynamic-field w-100">
                                    <i class="icon-base ri ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-4">
                Add Subcategory | إضافة تصنيف فرعي
            </button>
        </form>
    </div>
</div>


{{-- Cities --}}
<div class="card mb-6 admin-card">
    <div class="card-header">
        <h5 class="mb-0">Cities | المدن</h5>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Arabic Name | الاسم بالعربي</th>
                    <th>English Name | الاسم بالإنجليزي</th>
                    <th class="text-center">Actions | الإجراءات</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($cities as $city)
                    <tr>
                        <td>#{{ $city->id }}</td>
                        <td>{{ $city->name_ar }}</td>
                        <td>{{ $city->name_en }}</td>

                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-icon btn-light dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-more-2-line"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <button class="dropdown-item" type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCityModal{{ $city->id }}">
                                        <i class="icon-base ri ri-pencil-line me-1"></i>
                                        Edit | تعديل
                                    </button>

                                    <form action="{{ route('cities.destroy', $city->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="icon-base ri ri-delete-bin-6-line me-1"></i>
                                            Delete | حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit City Modal --}}
                    <div class="modal fade" id="editCityModal{{ $city->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4">
                                <form action="{{ route('cities.update', $city->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit City | تعديل المدينة</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Arabic Name | الاسم بالعربي</label>
                                            <input type="text" name="name_ar" class="form-control"
                                                    value="{{ $city->name_ar }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">English Name | الاسم بالإنجليزي</label>
                                            <input type="text" name="name_en" class="form-control"
                                                    value="{{ $city->name_en }}" required>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                            Cancel | إلغاء
                                        </button>

                                        <button type="submit" class="btn btn-primary">
                                            Update | تحديث
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">No cities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-body">
        <form action="{{ route('cities.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Arabic Name | الاسم بالعربي</label>
                    <input type="text" name="name_ar" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">English Name | الاسم بالإنجليزي</label>
                    <input type="text" name="name_en" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-4">
                Add City | إضافة مدينة
            </button>
        </form>
    </div>
</div>

@endsection


@push('styles')
<style>
    body {
        background: #f6f7fb;
    }

    .admin-card {
        border: 0;
        border-radius: 20px;
        box-shadow: 0 12px 35px rgba(18, 38, 63, 0.07);
        overflow: hidden;
        background: #fff;
    }

    .card-header {
        font-weight: 700;
        border-bottom: 1px solid #eef0f5;
        background: #fff;
        padding: 20px 24px;
    }

    .card-body {
        padding: 24px;
    }

    .admin-table {
        margin-bottom: 0;
    }

    .admin-table thead {
        background: #1f2937;
        color: #fff;
    }

    .admin-table thead th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .45px;
        padding: 16px 18px;
        border-bottom: 0;
        white-space: nowrap;
    }

    .admin-table tbody td {
        padding: 17px 18px;
        vertical-align: middle;
        border-color: #eef0f5;
    }

    .admin-table tbody tr {
        transition: .2s ease;
    }

    .admin-table tbody tr:hover {
        background: #fafbff;
    }

    .avatar-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: #eef2ff;
        color: #696cff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .btn {
        border-radius: 12px;
        font-weight: 600;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-light {
        background: #f4f5fa;
        border-color: #f4f5fa;
    }

    .btn-light:hover {
        background: #e9ebf5;
        border-color: #e9ebf5;
    }

    .dropdown-menu {
        border: 0;
        border-radius: 15px;
        box-shadow: 0 14px 38px rgba(0, 0, 0, .13);
        padding: 8px;
        min-width: 180px;
    }

    .dropdown-item {
        border-radius: 10px;
        padding: 10px 14px;
        font-weight: 600;
        transition: .2s ease;
    }

    .dropdown-item:hover {
        background: #f4f5fa;
    }

    .badge {
        padding: 8px 13px;
        font-weight: 700;
        font-size: 12px;
    }

    .bg-label-success {
        background: #e8f8ef !important;
    }

    .bg-label-danger {
        background: #fdecec !important;
    }

    .bg-label-warning {
        background: #fff6df !important;
    }

    .bg-label-primary {
        background: #eef2ff !important;
    }

    .text-success {
        color: #16a34a !important;
    }

    .text-danger {
        color: #dc2626 !important;
    }

    .text-warning {
        color: #d97706 !important;
    }

    .text-primary {
        color: #696cff !important;
    }

    .form-control,
    .form-select {
        border-radius: 12px;
        border-color: #e2e6ef;
        padding: 10px 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 .2rem rgba(105, 108, 255, .12);
    }

    .dynamic-fields-card {
        background: #f8f9fc;
        border: 1px solid #eceef5;
        border-radius: 18px;
        padding: 20px;
        margin-top: 10px;
    }

    .dynamic-fields-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }

    .dynamic-field-item {
        background: #fff;
        border: 1px solid #e7e9f2;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        transition: .2s ease;
    }

    .dynamic-field-item:hover {
        box-shadow: 0 8px 22px rgba(0, 0, 0, .06);
        transform: translateY(-1px);
    }

    .form-label.small {
        font-size: 12px;
        font-weight: 700;
        color: #6c757d;
    }

    .modal-content {
        border: 0;
        box-shadow: 0 24px 70px rgba(0, 0, 0, .2);
    }

    .modal-header {
        border-bottom: 1px solid #eef0f5;
        padding: 20px 24px;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        border-top: 1px solid #eef0f5;
        padding: 18px 24px;
    }

    @media (max-width: 768px) {
        .dynamic-fields-header {
            flex-direction: column;
            align-items: stretch;
        }

        .card-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start !important;
        }
    }
</style>
@endpush


@push('scripts')
<script>
let dynamicFieldIndex = 1;

document.getElementById('add-dynamic-field')?.addEventListener('click', function () {
    const wrapper = document.getElementById('dynamic-fields-wrapper');

    const html =
        <div class="dynamic-field-item">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small">Field Name</label>
                    <input type="text" name="fields[${dynamicFieldIndex}][name]" class="form-control" placeholder="area"/>
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Label</label>
                    <input type="text" name="fields[${dynamicFieldIndex}][label]" class="form-control" placeholder="المساحة"/>
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Type</label>
                    <select name="fields[${dynamicFieldIndex}][type]" class="form-select">
                        <option value="">Select Type</option>
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="select">Select</option>
                        <option value="boolean">Yes / No</option>
                        <option value="textarea">Textarea</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Required</label>
                    <select name="fields[${dynamicFieldIndex}][is_required]" class="form-select">
                        <option value="0">Optional</option>
                        <option value="1">Required</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small">Options</label>
                    <input type="text" name="fields[${dynamicFieldIndex}][options]" class="form-control" placeholder="option1,option2" />
                </div>

                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger remove-dynamic-field w-100">
                        <i class="icon-base ri ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>

    wrapper.insertAdjacentHTML('beforeend', html);
    dynamicFieldIndex++;
});

document.addEventListener('click', function (e) {
    if (e.target.closest('.remove-dynamic-field')) {
        e.target.closest('.dynamic-field-item').remove();
    }
});
</script>
@endpush
