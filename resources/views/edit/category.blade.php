<div class="card">
<h5 class="card-header">Categories | التصنيفات</h5>

<div class="table-responsive text-nowrap">
<table class="table">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Arabic Name | الاسم بالعربي</th>
<th>English Name | الاسم بالإنجليزي</th>
<th>Actions | الإجراءات</th>
</tr>
</thead>

<tbody class="table-border-bottom-0">

@foreach($categories as $category)

@if(isset($editCategory) && $editCategory->id == $category->id)

<form action="{{ route('categories.update',$category->id) }}" method="POST">
@csrf
@method('PUT')

<tr>

<td>{{ $category->id }}</td>

<td>
<input type="text"
name="name_ar"
class="form-control"
value="{{ $category->name_ar }}">
</td>

<td>
<input type="text"
name="name_en"
class="form-control"
value="{{ $category->name_en }}">
</td>

<td>

<button type="submit" class="btn btn-success btn-sm">
Save
</button>

<a href="{{ route('pages.admin.index') }}"
class="btn btn-secondary btn-sm">
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
