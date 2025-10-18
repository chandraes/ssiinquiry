@extends('layouts.guest')

@section('content')
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
            {{-- </div> --}}
                <br>
                {{-- <h2>SAIND</h2> --}}
                <p>Login in. To see it in action.</p>
            </div>
        </div>
        <div class="container-login100">
            <div class="wrap-login100 p-0">
                <div class="card-body">
                    <p class="text-danger text-center"><strong>{{session('error')}}</strong></p>
                    <form class="login100-form validate-form" action="{{ route('login') }}" method="POST">
                        @csrf
                        <span class="login100-form-title">
                            Login
                        </span>
                        @error('username')
                            <span class="text-red" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="wrap-input100 validate-input " data-bs-validate = "Valid user is required: ex@abc.xyz">
                            <input class="input100 @error('username') is-invalid state-invalid @enderror" type="text" name="username" placeholder="Username">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="zmdi zmdi-account" aria-hidden="true"></i>
                            </span>

                        </div>
                        @error('password')
                        <span class="text-red" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="wrap-input100 validate-input" data-bs-validate = "Password is required">
                            <input class="input100 @error('username') is-invalid state-invalid @enderror" type="password" name="password" placeholder="Password">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                            </span>

                        </div>
                        <div class="container-login100-form-btn">
                            <button type="submit" href="index.html" class="login100-form-btn btn-primary">
                                <strong>Login</strong>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
