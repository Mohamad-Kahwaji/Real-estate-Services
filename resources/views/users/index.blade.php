@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('content')
<h1>Wellcom MBK</h1>
<form action="{{ route('profile.edit')}}" method="get">
  @csrf
  <button type="submit">profile</button>
</form>
<div class="card overflow-hidden">
  <h5 class="card-header">Table Dark</h5>
  <div class="table-responsive text-nowrap">

    <table class="table table-dark">
      <thead>
        <tr>
          <th>id</th>
          <th>name</th>
          <th>email</th>
          <th>//</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>





        <tr>
          <th>{{ $user->id }}</td>
          <th>{{ $user->name }}</td>
          <th>{{$user->email}}</td>
          <td>
            <span class="badge rounded-pill bg-label-primary me-1">Active</span>
          </td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none" data-bs-toggle="dropdown">
                <i class="icon-base ri ri-more-2-line icon-18px"></i>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="javascript:void(0);">
                  <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>
                  Edit</a>
                <a class="dropdown-item" href="javascript:void(0);">
                  <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>
                  Delete</a>
              </div>
            </div>
          </td>
        </tr>


      </tbody>

    </table>

  </div>
</div>
<div class="card mt-4">
    <h5 class="card-header">Services</h5>

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th>Business</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody class="table-border-bottom-0">
                @foreach($services as $service)
                    <tr>
                        <td>{{ $service->business->job_name_en ?? '-' }}</td>
                        <td>{{ $service->title }}</td>
                        <td>{{ $service->category->name_en ?? '-' }}</td>
                        <td>{{ $service->price }} {{ $service->currency }}</td>

                        {{-- الحالة --}}
                        <td>
                            @if($service->status == 'approved')
                                <span class="badge bg-label-success">Approved</span>

                            @elseif($service->status == 'rejected')
                                <span class="badge bg-label-danger">Rejected</span>

                            @else
                                <span class="badge bg-label-warning">Pending</span>
                            @endif
                        </td>

                        {{-- الإجراءات --}}
                        <td>
                            <div class="dropdown">
                                <button type="button"
                                    class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                                    data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-more-2-line icon-18px"></i>
                                </button>

                                <div class="dropdown-menu">

                                    {{-- approve --}}
                                    <form action="{{ route('approveser', $service->id) }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-success" type="submit">
                                            <i class="icon-base ri ri-check-line icon-18px me-1"></i>
                                            Approve
                                        </button>
                                    </form>

                                    {{-- reject --}}
                                    <form action="{{ route('rejectser', $service->id) }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="icon-base ri ri-close-line icon-18px me-1"></i>
                                            Reject
                                        </button>
                                    </form>
                                    <form action="{{ route('pendingser', $service->id) }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="badge bg-label-warning icon-18px me-1"></i>
                                            Pending
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
