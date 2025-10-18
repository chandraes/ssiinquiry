@extends('layouts.guest')

@section('content')
@include('swal')

<!-- PAGE -->
@php
    $logoPath = get_setting('app_logo');
    $logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('assets/images/brand/logo.png');
@endphp

<div class="page login-page" style="background-color: aliceblue">
    <div>
        <!-- CONTAINER OPEN -->
        <div class="col col-login mx-auto mt-7">
            <div class="text-center">
                <img src="{{ $logoUrl }}" class="header-brand-img" alt="logo" style="width: 30%; height:30%">
                <!-- LOGO -->
            </div>
        </div>
        <div class="container-login100">
            <div class="wrap-login100 p-0">
                <div class="card-body">
                    <form class="login100-form validate-form" method="POST" action="{{ route('register') }}">
                        @csrf
                        <span class="login100-form-title">
                            Registration
                        </span>

                        {{-- Full Name --}}
                        <div class="wrap-input100 validate-input" data-bs-validate="Full name is required">
                            <input class="input100" type="text" name="name" placeholder="Full Name" required>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="mdi mdi-account-outline" aria-hidden="true"></i>
                            </span>
                        </div>

                        {{-- Username --}}
                        <div class="wrap-input100 validate-input" data-bs-validate="Username is required">
                            <input class="input100" type="text" name="username" placeholder="Username" required>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="mdi mdi-account" aria-hidden="true"></i>
                            </span>
                        </div>

                        {{-- Email --}}
                        <div class="wrap-input100 validate-input" data-bs-validate="Valid email is required: ex@abc.xyz">
                            <input class="input100" type="email" name="email" placeholder="Email" required>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="zmdi zmdi-email" aria-hidden="true"></i>
                            </span>
                        </div>

                        {{-- Password --}}
                        <div class="wrap-input100 validate-input" data-bs-validate="Password is required">
                            <input class="input100" type="password" name="password" placeholder="Password" required>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                            </span>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="wrap-input100 validate-input" data-bs-validate="Password confirmation is required">
                            <input class="input100" type="password" name="password_confirmation" placeholder="Confirm Password" required>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="zmdi zmdi-lock-outline" aria-hidden="true"></i>
                            </span>
                        </div>

                        {{-- Checkbox --}}
                        <label class="custom-control custom-checkbox mt-4">
                            <input type="checkbox" class="custom-control-input" required>
                            <span class="custom-control-label">Agree the <a href="terms.html">terms and policy</a></span>
                        </label>

                        {{-- Button --}}
                        <div class="container-login100-form-btn">
                            <button type="submit" class="login100-form-btn btn-primary">
                                Register
                            </button>
                        </div>

                        <div class="text-center pt-3">
                            <p class="text-dark mb-0">
                                Already have account?
                                <a href="{{ route('login') }}" class="text-primary ms-1">Sign In</a>
                            </p>
                        </div>
                    </form>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-center my-3">
                        <a href="#" class="social-login text-center me-4">
                            <i class="fa fa-google"></i>
                        </a>
                        <a href="#" class="social-login text-center me-4">
                            <i class="fa fa-facebook"></i>
                        </a>
                        <a href="#" class="social-login text-center">
                            <i class="fa fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>
<!-- END PAGE -->
@endsection
