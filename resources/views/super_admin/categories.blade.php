@extends('layouts/contentNavbarLayout')

@section('title', 'Categories')

@section('page-style')
<style>
:root {
    --accent: #696cff;
    --accent-soft: #eef0ff;
    --success: #28c76f;
    --success-soft: #e8f8ef;
    --danger: #ea5455;
    --danger-soft: #fdeaea;
    --shadow: 0 4px 24px rgba(105,108,255,.10);
    --radius: 16px;
}

.pg-header { margin-bottom: 28px; }
.pg-header h4 { font-size: 22px; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
.pg-header p  { font-size: 13px; color: #8592a3; margin: 0; }

.stat-card {
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 20px 24px;
    display: flex; align-items: center; gap: 16px;
}
.stat-icon {
    width: 50px; height: 50px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.stat-label { font-size: 12px; color: #8592a3; font-weight: 600; }
.stat-value { font-size: 26px; font-weight: 800; line-height: 1; color: #1e293b; }

.table-card {
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.table-card-header {
    padding: 18px 24px;
    border-bottom: 1px solid #f0eef8;
    display: flex; align-items: center; justify-content: space-between;
}
.table-card-header h6 { font-weight: 700; color: #1e293b; margin: 0; }

.table { margin: 0; }
.table thead th {
    background: #fafbff;
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .7px;
    color: #8592a3; border-bottom: 1px solid #f0eef8;
    padding: 14px 20px;
}
.table tbody td {
    padding: 14px 20px;
    vertical-align: middle;
    border-bottom: 1px solid #f7f7f9;
    font-size: 14px; color: #1e293b;
}
.table tbody tr:last-child td { border-bottom: none; }
.table tbody tr:hover td { background: #fafbff; }

.badge-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 999px;
    font-size: 12px; font-weight: 700;
}
.badge-accent  { background: var(--accent-soft); color: var(--accent); }
.badge-success { background: var(--success-soft); color: var(--success); }

.btn-add {
    background: linear-gradient(135deg,#696cff,#9c9eff);
    border: none; color: #fff;
    padding: 10px 20px; border-radius: 12px;
    font-size: 13px; font-weight: 700;
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    box-shadow: 0 4px 14px rgba(105,108,255,.3);
    transition: opacity .2s;
}
.btn-add:hover { opacity: .88; color: #fff; }

.action-btn {
    width: 34px; height: 34px; border-radius: 10px;
    display: inline-flex; align-items: center; justify-content: center;
    border: none; cursor: pointer; font-size: 16px; transition: .2s;
}
.btn-edit   { background: #eef0ff; color: #696cff; }
.btn-edit:hover   { background: #696cff; color: #fff; }
.btn-delete { background: #fdeaea; color: #ea5455; }
.btn-delete:hover { background: #ea5455; color: #fff; }

.empty-state {
    padding: 60px 20px; text-align: center;
}
.empty-state i { font-size: 48px; color: #c8ccda; display: block; margin-bottom: 14px; }
.empty-state p { color: #8592a3; font-weight: 600; margin: 0; }

/* Modal */
.modal-content  { border: none; border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,.18); }
.modal-header   { border-bottom: 1px solid #f0eef8; padding: 22px 26px; }
.modal-body     { padding: 26px; }
.modal-footer   { border-top: 1px solid #f0eef8; padding: 16px 26px; }
.modal-title    { font-weight: 800; font-size: 16px; }

.form-label     { font-size: 13px; font-weight: 600; color: #3b3551; }
.form-control   { border-radius: 12px; border: 1.5px solid #e4e4eb; padding: 11px 14px; font-size: 14px; }
.form-control:focus { border-color: #696cff; box-shadow: 0 0 0 3px rgba(105,108,255,.12); }

.al-alert {
    border-radius: 12px; padding: 12px 16px;
    font-size: 13px; margin-bottom: 20px;
    display: flex; align-items: center; gap: 10px;
}
.al-alert-success { background: #e8f8ef; color: #1a7a45; border: 1px solid #c3e9d4; }
</style>
@endsection

@section('content')

{{-- Header --}}
<div class="pg-header d-flex justify-content-between align-items-center">
    <div>
        <h4>Categories</h4>
        <p>Manage all categories and their subcategories</p>
    </div>
    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="ri ri-add-line"></i> Add Category
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
            <div class="stat-icon" style="background:#eef0ff;">
                <i class="ri ri-layout-grid-line" style="color:#696cff;"></i>
            </div>
            <div>
                <div class="stat-label">Total Categories</div>
                <div class="stat-value">{{ $categories->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8ef;">
                <i class="ri ri-list-check-2" style="color:#28c76f;"></i>
            </div>
            <div>
                <div class="stat-label">Total Subcategories</div>
                <div class="stat-value">{{ $categories->sum('subcategory_count') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="table-card">
    <div class="table-card-header">
        <h6><i class="ri ri-layout-grid-line me-2" style="color:#696cff;"></i>All Categories</h6>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name (Arabic)</th>
                    <th>Name (English)</th>
                    <th>Subcategories</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                <tr>
                    <td><span class="badge-pill badge-accent">{{ $category->id }}</span></td>
                    <td><strong>{{ $category->name_ar }}</strong></td>
                    <td>{{ $category->name_en }}</td>
                    <td>
                        <span class="badge-pill badge-success">
                            <i class="ri ri-list-check-2"></i> {{ $category->subcategory_count }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="action-btn btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal{{ $category->id }}"
                                    title="Edit">
                                <i class="ri ri-pencil-line"></i>
                            </button>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $category->name_en }}?')">
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
                            <i class="ri ri-layout-grid-line"></i>
                            <p>No categories found. Add your first category.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══ Add Modal ══ --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri ri-add-circle-line me-2" style="color:#696cff;"></i>Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name (Arabic)</label>
                        <input type="text" class="form-control" name="name_ar"
                               placeholder="اسم التصنيف" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name (English)</label>
                        <input type="text" class="form-control" name="name_en"
                               placeholder="Category name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:10px;font-weight:700;">
                        <i class="ri ri-save-line me-1"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ Edit Modals ══ --}}
@foreach ($categories as $category)
<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri ri-pencil-line me-2" style="color:#696cff;"></i>Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name (Arabic)</label>
                        <input type="text" class="form-control" name="name_ar"
                               value="{{ $category->name_ar }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name (English)</label>
                        <input type="text" class="form-control" name="name_en"
                               value="{{ $category->name_en }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:10px;font-weight:700;">
                        <i class="ri ri-save-line me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection
