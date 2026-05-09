@extends('layouts/blankLayout')

@section('title', 'Login Basic - Pages')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6 mx-4">
            <!-- Login -->
            <div class="card p-sm-7 p-2">
                <!-- Logo -->
                <div class="app-brand justify-content-center mt-5">
                    <a href="{{ url('/') }}" class="app-brand-link gap-3">
                        <span class="app-brand-logo demo">@include('_partials.macros')</span>
                        <span class="app-brand-text demo text-heading fw-semibold">{{ config('variables.templateName') }}</span>
                    </a>
                </div>
                <!-- /Logo -->

                <div class="card-body mt-1">
                    <h4 class="mb-1">Welcome to {{ config('variables.templateName') }}! 👋🏻</h4>
                    <p class="mb-5">Please sign-in to your account and start the adventure</p>

                    <form id="formAuthentication" class="mb-5" action="{{route('login.store')}}" method="post">
                      @csrf
                        <div class="form-floating form-floating-outline mb-5 form-control-validation">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus />
                            <label for="email">Email</label>
                        </div>
                        <div class="mb-5">
                            <div class="form-password-toggle form-control-validation">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                        <label for="password">Password</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="icon-base ri ri-eye-off-line icon-20px"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5 pb-2 d-flex justify-content-between pt-2 align-items-center">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="remember-me" />
                                <label class="form-check-label" for="remember-me"> Remember Me </label>
                            </div>
                            <a href="{{ url('auth/forgot-password-basic') }}" class="float-end mb-1">
                                <span>Forgot Password?</span>
                            </a>
                        </div>
                        <div class="mb-5">
                            <button class="btn btn-primary d-grid w-100" type="submit">login</button>
                        </div>
                        @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@error('email')
    <div class="text-danger mb-2">{{ $message }}</div>
@enderror

@error('password')
    <div class="text-danger mb-2">{{ $message }}</div>
@enderror

@if($errors->has('email'))
    <div class="text-danger mb-2">
        Invalid credentials.
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif
                    </form>

                    <p class="text-center mb-5">
                        <span>New on our platform?</span>
                        <a href="{{ route('register.create')}}">
                            <span>Create an account</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- /Login -->
            <img src="{{ asset('assets/img/illustrations/tree-3.png') }}" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block" />
            <img src="{{ asset('assets/img/illustrations/auth-basic-mask-light.png') }}" class="authentication-image d-none d-lg-block scaleX-n1-rtl" height="172" alt="triangle-bg" />
            <img src="{{ asset('assets/img/illustrations/tree.png') }}" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block" />
        </div>
    </div>
</div>
@endsection
