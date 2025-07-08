@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center g-3">
        <div class="col-lg-4 d-flex align-items-center justify-content-center">
           <div class=" d-flex align-items-center justify-content-center rounded-circle bg-light-subtle">
            
            <p class="text-center">Elevate your research — join a dynamic community of scholars.</p>
           </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card rounded-4 bg-white border-0">
                <div class="row">
                    <div class="col-xl-5 d-none d-xl-block p-0">
                        <img src="{{ asset('images/register.jpg')}}" alt="register photo" class="img-fluid rounded-start-4 h-100">
                    </div>

                    <div class="col-xl-7 my-4">
                        <div class="col align-items-center p-4 p-md-0">
                            <span class="d-flex justify-content-center mb-4 fs-4">{{ __('Register') }}</span>
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <input type="hidden" name="role" id="role" value="institution">
                                <div class="row mb-4">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                    <div class="col-md-7">
                                        <input id="name" type="text" placeholder="Enter Your Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                    <div class="col-md-7">
                                        <input id="email" type="email" placeholder="Enter your Email-address" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="mobile_number" class="col-md-4 col-form-label text-md-end">{{ __('Mobile Number') }}</label>

                                    <div class="col-md-7">
                                        <input id="mobile_number" type="text" pattern="[789][0-9]{9}" 
                                        title="Mobile number must start with 9, 8, or 7 and be 10 digits long" placeholder="Enter a 10-digit mobile number" class="form-control @error('mobile_number') is-invalid @enderror" name="mobile_number" value="{{ old('mobile_number') }}" required autocomplete="mobile_number">

                                        @error('mobile_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                    <div class="col-md-7">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-7">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                {{-- <div class="row mb-4">
                                    <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Register As') }}</label>
                                    <div class="col-md-7">
                                        {{-- <input type="hidden" name="role" id="role" value="Institution" class="form-control" readonly> --}}
                                        {{-- <select name="role" id="role" class="form-control" required>
                                            <option value="">{{ __('--Select Role--')}}</option>
                                            <option value="researcher">{{ __('Researcher')}}</option>
                                            <option value="reviewer">{{ __('Reviewer')}}</option>
                                            <option value="admin">{{ __('Admin')}}</option>
                                            <option value="institution">{{ __('Institution')}}</option>
                                            <option value="department">{{ __('Department')}}</option>
                                        </select> --}}
                                        

                                    {{-- </div>
                                </div> --}}

                                <div class="row mb-0">
                                    <div class=" d-flex justify-content-center gap-5">
                                        <button type="submit" class="btn btn-primary shadow-sm">
                                                {{ __('Register') }}
                                            </button>
                                        <a href="{{ route('login')}}">Already registered? Click here</a>
                                    </div>
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