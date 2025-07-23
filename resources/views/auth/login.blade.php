@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-9 mt-4">
            <div class="card rounded-4 bg-white border-0">
                <div class="row ">
                    <div class="col-lg-5 d-none d-lg-block">
                        <img src="{{ asset('images/login2.png')}}" alt="register photo" class="img-fluid rounded-start-4 h-100">
                    </div>

                    <div class="col-lg-7 my-4 d-flex align-items-center">
                        <div class="col p-4 p-md-0 m-0 mx-md-3">
                            <p class="text-center mb-4 me-2 fs-4">{{ __('Innovation Starts with You — Log in Now!') }}</p>
                            <form method="POST" action="{{ route('login') }}" autocomplete="off">
                                @csrf

                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                    <div class="col-md-7">
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

                                    <div class="col-md-7">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-sm-4 text-md-end">
                                        {{-- <div class="d-flex justify-content-end align-items-center ">
                                            <input class="form-check-input mt-0 me-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div> --}}
                                    </div>
                                    <div class="col-sm-7 text-end">
                                        @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                    </div>
                                </div>
                               
                                <div class="row gap-5 mb-3 ">
                                    <div class="col-12 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary w-75">
                                        {{ __('Login') }}
                                    </button>
                                </div>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
