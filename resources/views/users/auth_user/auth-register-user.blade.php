@extends('layouts/blankLayout')

@section('title', 'Register - Pages')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6 mx-4">
            <div class="card p-sm-7 p-2">

                <!-- Logo -->
                <div class="app-brand justify-content-center mt-5">
                    <a href="{{ url('/') }}" class="app-brand-link gap-3">
                        <span class="app-brand-logo demo">@include('_partials.macros')</span>
                        <span class="app-brand-text demo text-heading fw-semibold">
                            {{ config('variables.templateName') }}
                        </span>
                    </a>
                </div>

                <div class="card-body mt-1">
                    <h4 class="mb-1">Welcome 👋🏻</h4>
                    <p class="mb-5">Create your account</p>

                    <form class="mb-5" action="{{ route('register.store') }}" method="POST">
                        @csrf

                        <!-- Name -->
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" name="name" class="form-control"
                                   placeholder="Enter your name" value="{{ old('name') }}">
                            <label>Name</label>
                        </div>

                        <!-- Phone -->
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" name="phone" class="form-control"
                                   placeholder="Enter your phone" value="{{ old('phone') }}">
                            <label>Phone Number</label>
                        </div>

                        <!-- Password -->
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="password" name="password" class="form-control"
                                   placeholder="Password">
                            <label>Password</label>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="password" name="password_confirmation" class="form-control"
                                   placeholder="Confirm Password">
                            <label>Confirm Password</label>
                        </div>

                        <!-- Errors -->
                        @if($errors->any())
                            <div class="alert alert-danger mb-3">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <button class="btn btn-primary w-100">Sign Up</button>
                    </form>

                    <p class="text-center">
                        <span>Already have an account?</span>
                        <a href="{{ route('login') }}">Login</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
