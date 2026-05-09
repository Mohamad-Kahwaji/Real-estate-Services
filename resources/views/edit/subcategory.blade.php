<div class="card">
<h5 class="card-header">Subcategories | التصنيفات الفرعية</h5>

<div class="table-responsive text-nowrap">
<table class="table">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Arabic Name | الاسم بالعربي</th>
<th>English Name | الاسم بالإنجليزي</th>
<th>Category | التصنيف الرئيسي</th>
<th>Actions</th>
</tr>
</thead>

<tbody class="table-border-bottom-0">

@foreach($subcategories as $subcategory)

@if(isset($editSubcategory) && $editSubcategory->id == $subcategory->id)

<form action="{{ route('subcategories.update',$subcategory->id) }}" method="POST">
@csrf
@method('PUT')

<tr>

<td>{{ $subcategory->id }}</td>

<td>
<input type="text" name="name_ar" class="form-control"
value="{{ $subcategory->name_ar }}">
</td>

<td>
<input type="text" name="name_en" class="form-control"
value="{{ $subcategory->name_en }}">
</td>

<td>
<select name="category_id" class="form-control">

@foreach($categories as $category)

<option value="{{ $category->id }}"
{{ $subcategory->category_id == $category->id ? 'selected' : '' }}>
{{ $category->name_en }}
</option>

@endforeach

</select>
</td>

<td>

<button type="submit" class="btn btn-success btn-sm">
Save
</button>

<a href="{{ route('pages.admin.index') }}" class="btn btn-secondary btn-sm">
Cancel
</a>

</td>

</tr>

</form>


@endforeach

</tbody>

</table>
</div>
</div>
