@extends('layouts/contentNavbarLayout')

@section('title', 'Subcategories')

@section('page-style')
<style>
:root {
    --accent: #696cff; --accent-soft: #eef0ff;
    --success: #28c76f; --success-soft: #e8f8ef;
    --warning: #ff9f43; --warning-soft: #fff4e5;
    --danger: #ea5455; --danger-soft: #fdeaea;
    --shadow: 0 4px 24px rgba(105,108,255,.10);
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
.badge-accent   { background: var(--accent-soft); color: var(--accent); }
.badge-warning  { background: var(--warning-soft); color: var(--warning); }
.badge-success  { background: var(--success-soft); color: var(--success); }
.badge-gray     { background: #f1f0f4; color: #585164; }

.btn-add {
    background: linear-gradient(135deg,#696cff,#9c9eff);
    border: none; color: #fff; padding: 10px 20px; border-radius: 12px;
    font-size: 13px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px;
    box-shadow: 0 4px 14px rgba(105,108,255,.3); transition: opacity .2s;
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

.empty-state { padding: 60px 20px; text-align: center; }
.empty-state i { font-size: 48px; color: #c8ccda; display: block; margin-bottom: 14px; }
.empty-state p { color: #8592a3; font-weight: 600; margin: 0; }

.modal-content { border: none; border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,.18); }
.modal-header  { border-bottom: 1px solid #f0eef8; padding: 22px 26px; }
.modal-body    { padding: 26px; }
.modal-footer  { border-top: 1px solid #f0eef8; padding: 16px 26px; }
.modal-title   { font-weight: 800; font-size: 16px; }
.form-label    { font-size: 13px; font-weight: 600; color: #3b3551; }
.form-control, .form-select { border-radius: 12px; border: 1.5px solid #e4e4eb; padding: 11px 14px; font-size: 14px; }
.form-control:focus, .form-select:focus { border-color: #696cff; box-shadow: 0 0 0 3px rgba(105,108,255,.12); }

/* Dynamic Fields */
.fields-section {
    background: #fafbff; border-radius: 14px;
    border: 1.5px dashed #c5c7ff; padding: 16px; margin-top: 8px;
}
.fields-section-title {
    font-size: 12px; font-weight: 700; color: #696cff;
    text-transform: uppercase; letter-spacing: .5px; margin-bottom: 12px;
    display: flex; align-items: center; justify-content: space-between;
}
.field-row {
    background: #fff; border-radius: 12px; border: 1px solid #eceaff;
    padding: 14px; margin-bottom: 10px; position: relative;
}
.field-row:last-child { margin-bottom: 0; }
.field-row-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
}
.btn-remove-field {
    position: absolute; top: 10px; right: 10px;
    width: 26px; height: 26px; border-radius: 8px;
    background: #fdeaea; color: #ea5455; border: none;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; cursor: pointer; transition: .2s;
}
.btn-remove-field:hover { background: #ea5455; color: #fff; }
.btn-add-field {
    width: 100%; padding: 9px; border-radius: 10px;
    border: 1.5px dashed #696cff; background: transparent;
    color: #696cff; font-size: 13px; font-weight: 700;
    cursor: pointer; transition: .2s; margin-top: 10px;
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
.btn-add-field:hover { background: #eef0ff; }
.type-badge {
    display: inline-block; padding: 2px 8px; border-radius: 6px;
    font-size: 11px; font-weight: 700; text-transform: uppercase;
}
.type-text    { background: #e3f8fc; color: #00cfe8; }
.type-number  { background: #eef0ff; color: #696cff; }
.type-date    { background: #e8f8ef; color: #28c76f; }
.type-select  { background: #fff4e5; color: #ff9f43; }

.al-alert { border-radius: 12px; padding: 12px 16px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.al-alert-success { background: #e8f8ef; color: #1a7a45; border: 1px solid #c3e9d4; }
</style>
@endsection

@section('content')

{{-- Header --}}
<div class="pg-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>Subcategories</h4>
        <p>Manage subcategories and their dynamic fields</p>
    </div>
    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal">
        <i class="ri ri-add-line"></i> Add Subcategory
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
                <i class="ri ri-list-check-2" style="color:#696cff;"></i>
            </div>
            <div>
                <div class="stat-label">Total Subcategories</div>
                <div class="stat-value">{{ $subcategories->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff4e5;">
                <i class="ri ri-layout-grid-line" style="color:#ff9f43;"></i>
            </div>
            <div>
                <div class="stat-label">Parent Categories</div>
                <div class="stat-value">{{ $categories->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8ef;">
                <i class="ri ri-input-field" style="color:#28c76f;"></i>
            </div>
            <div>
                <div class="stat-label">Total Fields</div>
                <div class="stat-value">{{ $subcategories->sum(fn($s) => $s->dynamicFields->count()) }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="table-card">
    <div class="table-card-header">
        <h6><i class="ri ri-list-check-2 me-2" style="color:#696cff;"></i>All Subcategories</h6>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name (Arabic)</th>
                    <th>Name (English)</th>
                    <th>Parent Category</th>
                    <th>Dynamic Fields</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subcategories as $sub)
                <tr>
                    <td><span class="badge-pill badge-accent">{{ $sub->id }}</span></td>
                    <td><strong>{{ $sub->name_ar }}</strong></td>
                    <td>{{ $sub->name_en }}</td>
                    <td>
                        <span class="badge-pill badge-warning">
                            <i class="ri ri-layout-grid-line"></i>
                            {{ $sub->category->name_en ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        @if($sub->dynamicFields->count() > 0)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($sub->dynamicFields as $f)
                                    <span class="type-badge type-{{ $f->type }}" title="{{ $f->label }}">
                                        {{ $f->label }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="badge-pill badge-gray">No fields</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="action-btn btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editSubModal{{ $sub->id }}"
                                    title="Edit">
                                <i class="ri ri-pencil-line"></i>
                            </button>
                            <form action="{{ route('subcategories.destroy', $sub->id) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $sub->name_en }}?')">
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
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="ri ri-list-check-2"></i>
                            <p>No subcategories found. Add your first subcategory.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══ Add Modal ══ --}}
<div class="modal fade" id="addSubcategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('subcategories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri ri-add-circle-line me-2" style="color:#696cff;"></i>Add Subcategory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label">Parent Category</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name_en }} ({{ $cat->name_ar }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name (Arabic)</label>
                            <input type="text" class="form-control" name="name_ar" placeholder="اسم التصنيف الفرعي" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name (English)</label>
                            <input type="text" class="form-control" name="name_en" placeholder="Subcategory name" required>
                        </div>
                    </div>

                    {{-- Dynamic Fields --}}
                    <div class="fields-section">
                        <div class="fields-section-title">
                            <span><i class="ri ri-input-field me-1"></i>Dynamic Fields</span>
                            <span style="font-size:11px;color:#8592a3;font-weight:500;text-transform:none;">Fields that appear when posting a service in this subcategory</span>
                        </div>
                        <div id="addFieldsContainer"></div>
                        <button type="button" class="btn-add-field" onclick="addField('addFieldsContainer', Date.now())">
                            <i class="ri ri-add-line"></i> Add Field
                        </button>
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
@foreach ($subcategories as $sub)
<div class="modal fade" id="editSubModal{{ $sub->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('subcategories.update', $sub->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri ri-pencil-line me-2" style="color:#696cff;"></i>Edit Subcategory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label">Parent Category</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ $sub->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name_en }} ({{ $cat->name_ar }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name (Arabic)</label>
                            <input type="text" class="form-control" name="name_ar" value="{{ $sub->name_ar }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name (English)</label>
                            <input type="text" class="form-control" name="name_en" value="{{ $sub->name_en }}" required>
                        </div>
                    </div>

                    {{-- Dynamic Fields --}}
                    <div class="fields-section">
                        <div class="fields-section-title">
                            <span><i class="ri ri-input-field me-1"></i>Dynamic Fields</span>
                            <span style="font-size:11px;color:#8592a3;font-weight:500;text-transform:none;">
                                {{ $sub->dynamicFields->count() }} field(s)
                            </span>
                        </div>
                        <div id="editFieldsContainer{{ $sub->id }}">
                            @foreach($sub->dynamicFields as $fi => $field)
                            <div class="field-row">
                                <button type="button" class="btn-remove-field" onclick="this.closest('.field-row').remove()">
                                    <i class="ri ri-close-line"></i>
                                </button>
                                <div class="field-row-grid">
                                    <div>
                                        <label class="form-label" style="font-size:12px;">Label (display name)</label>
                                        <input type="text" class="form-control form-control-sm"
                                               name="fields[{{ $fi }}][label]"
                                               value="{{ $field->label }}"
                                               placeholder="e.g. تاريخ الانتهاء" required>
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-size:12px;">Field Key (no spaces)</label>
                                        <input type="text" class="form-control form-control-sm"
                                               name="fields[{{ $fi }}][name]"
                                               value="{{ $field->name }}"
                                               placeholder="e.g. expiry_date" required>
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-size:12px;">Type</label>
                                        <select class="form-select form-select-sm"
                                                name="fields[{{ $fi }}][type]"
                                                onchange="toggleOptions(this)">
                                            <option value="text"   {{ $field->type==='text'   ? 'selected' : '' }}>Text</option>
                                            <option value="number" {{ $field->type==='number' ? 'selected' : '' }}>Number</option>
                                            <option value="date"   {{ $field->type==='date'   ? 'selected' : '' }}>Date</option>
                                            <option value="select" {{ $field->type==='select' ? 'selected' : '' }}>Select (dropdown)</option>
                                        </select>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox"
                                                   name="fields[{{ $fi }}][is_required]"
                                                   id="req_edit_{{ $sub->id }}_{{ $fi }}"
                                                   {{ $field->is_required ? 'checked' : '' }}>
                                            <label class="form-check-label" for="req_edit_{{ $sub->id }}_{{ $fi }}" style="font-size:13px;font-weight:600;">Required</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="options-row mt-2" style="{{ $field->type === 'select' ? '' : 'display:none;' }}">
                                    <label class="form-label" style="font-size:12px;">Options (comma separated)</label>
                                    <input type="text" class="form-control form-control-sm"
                                           name="fields[{{ $fi }}][options]"
                                           value="{{ is_array($field->options) ? implode(', ', $field->options) : '' }}"
                                           placeholder="Option 1, Option 2, Option 3">
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn-add-field"
                                onclick="addField('editFieldsContainer{{ $sub->id }}', Date.now())">
                            <i class="ri ri-add-line"></i> Add Field
                        </button>
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

@push('scripts')
<script>
function addField(containerId, idx) {
    const container = document.getElementById(containerId);
    const count = container.querySelectorAll('.field-row').length;
    const i = count; // use count as index for sequential naming
    const html = `
    <div class="field-row">
        <button type="button" class="btn-remove-field" onclick="this.closest('.field-row').remove()">
            <i class="ri ri-close-line"></i>
        </button>
        <div class="field-row-grid">
            <div>
                <label class="form-label" style="font-size:12px;">Label (display name)</label>
                <input type="text" class="form-control form-control-sm"
                       name="fields[new_${idx}][label]"
                       placeholder="e.g. تاريخ الانتهاء" required>
            </div>
            <div>
                <label class="form-label" style="font-size:12px;">Field Key (no spaces)</label>
                <input type="text" class="form-control form-control-sm"
                       name="fields[new_${idx}][name]"
                       placeholder="e.g. expiry_date" required>
            </div>
            <div>
                <label class="form-label" style="font-size:12px;">Type</label>
                <select class="form-select form-select-sm"
                        name="fields[new_${idx}][type]"
                        onchange="toggleOptions(this)">
                    <option value="text">Text</option>
                    <option value="number">Number</option>
                    <option value="date">Date</option>
                    <option value="select">Select (dropdown)</option>
                </select>
            </div>
            <div class="d-flex align-items-end">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox"
                           name="fields[new_${idx}][is_required]"
                           id="req_${idx}">
                    <label class="form-check-label" for="req_${idx}" style="font-size:13px;font-weight:600;">Required</label>
                </div>
            </div>
        </div>
        <div class="options-row mt-2" style="display:none;">
            <label class="form-label" style="font-size:12px;">Options (comma separated)</label>
            <input type="text" class="form-control form-control-sm"
                   name="fields[new_${idx}][options]"
                   placeholder="Option 1, Option 2, Option 3">
        </div>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
}

function toggleOptions(select) {
    const row = select.closest('.field-row');
    const optionsRow = row.querySelector('.options-row');
    if (optionsRow) {
        optionsRow.style.display = select.value === 'select' ? '' : 'none';
    }
}
</script>
@endpush
