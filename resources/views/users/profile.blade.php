@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
@vite(['resources/assets/js/pages-account-settings-account.js'])
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header border-bottom">
        <h5 class="mb-0">Profile Information</h5>
    </div>
       <div class="nav-align-top mb-4">
            <ul class="nav nav-pills flex-column flex-md-row gap-2 gap-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);">
                        <i class="icon-base ri ri-group-line icon-sm me-1_5"></i>
                        Account
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('pages/account-settings-notifications') }}">
                        <i class="icon-base ri ri-notification-4-line icon-sm me-1_5"></i>
                        Notifications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('pages/account-settings-connections') }}">
                        <i class="icon-base ri ri-link-m icon-sm me-1_5"></i>
                        Connections
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('business.create') }}">
                        Add Business
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('service.create') }}">
                        Add Service
                    </a>
                </li>
            </ul>
        </div>
    <div class="card-body pt-4">
        <form id="formAccountSettings"
              method="POST"
              action="{{ route('profile.update') }}"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control"
                               type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               autofocus />
                        <label>Name</label>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="input-group input-group-merge">
                        <div class="form-floating form-floating-outline">
                            <input type="text"
                                   name="phone"
                                   class="form-control"
                                   value="{{ old('phone', $user->phone) }}" />
                            <label>Phone Number</label>
                        </div>
                        <span class="input-group-text">SYR (+963)</span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control"
                               type="password"
                               name="password" />
                        <label>Password</label>
                    </div>
                </div>

                
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2 border-top pt-4">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="reset" class="btn btn-outline-secondary">Reset</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <h5 class="card-header">My Business Accounts</h5>

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th>Business Name</th>
                    <th>License Number</th>
                    <th>Activity Type</th>
                    <th>City</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody class="table-border-bottom-0">
                @forelse($accounts as $account)
                    <tr>
                            <td>
                                <i class="icon-base ri ri-user-line icon-22px text-primary me-3"></i>
                                <span>{{ $account->user->name ?? '-' }}</span>
                            </td>

                            <td>{{ $account->job_name_en ?? '-' }}</td>

                            <td>{{ $account->license_number ?? '-' }}</td>

                            <td>{{ $account->city->name_en ?? '-' }}</td>



                        <td>
                            <div class="dropdown">
                                <button type="button"
                                    class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                                    data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-more-2-line icon-18px"></i>
                                </button>

                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('business.show', $account->id) }}">
                                        <i class="icon-base ri ri-eye-line icon-18px me-1"></i>
                                        View
                                    </a>

                                    <a class="dropdown-item" href="{{ route('business.edit', $account->id) }}">
                                        <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('business.destroy', $account->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No business accounts found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<div class="card mt-4">
    <h5 class="card-header">My Services</h5>

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th>Business Account</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Title</th>
                    <th>Quantity</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Currency</th>
                    <th>Image</th>
                </tr>
            </thead>

            <tbody class="table-border-bottom-0">
                @forelse($services as $service)
                    <tr>
                        <td>{{ $service->business->job_name_en ?? '-' }}</td>
                        <td>{{ $service->category->name_en ?? '-' }}</td>
                        <td>{{ $service->subcategory->name_en ?? '-' }}</td>
                        <td>{{ $service->title ?? '-' }}</td>
                        <td>{{ $service->quantity ?? '-' }}</td>
                        <td>{{ ucfirst($service->services_type ?? '-') }}</td>
                        <td>{{ $service->price ?? '-' }}</td>
                        <td>{{ $service->currency ?? '-' }}</td>
                        <td>
                            @if($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}"
                                     alt="service image"
                                     width="60"
                                     height="60"
                                     style="object-fit: cover; border-radius: 8px;">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No services found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
