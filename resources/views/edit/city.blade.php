<div class="card">
    <h5 class="card-header">Cities | المدن</h5>

    <div class="table-responsive text-nowrap">
    <table class="table">

    <thead class="table-dark">
    <tr>
    <th>ID</th>
    <th>Arabic Name | الاسم بالعربي</th>
    <th>English Name | الاسم بالإنجليزي</th>
    </tr>
    </thead>

    <tbody class="table-border-bottom-0">

    <form action="{{ route('editcity.update',$city->id) }}" method="POST">
    @csrf
    @foreach($cities as $city)

    <tr>

    <td>{{ $city->id }}</td>

    <td>{{old( $city->name_ar) }}</td>

    <td>{{ old($city->name_en) }}</td>

    <td>

    <div class="dropdown">

    <button type="button"
    class="btn p-0 dropdown-toggle hide-arrow shadow-none"
    data-bs-toggle="dropdown">

    <i class="icon-base ri ri-more-2-line icon-18px"></i>

    </button>

    <div class="dropdown-menu">
        <button type="submit">
            Save
        </button>


    </div>

    </div>

    </td>

    </tr>

    @endforeach

    </tbody>

    </table>
    </div>
    </div>
