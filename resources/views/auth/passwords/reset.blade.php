@extends('auth.layouts.app')

@section('content')
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <a href="https://preview.keenthemes.com/metronic8/demo19/../demo19/index.html" class="d-block d-lg-none mx-auto py-20">
            <img alt="Logo" src="https://preview.keenthemes.com/metronic8/demo19/assets/media/logos/default.svg" class="theme-light-show h-25px" />
            <img alt="Logo" src="https://preview.keenthemes.com/metronic8/demo19/assets/media/logos/default-dark.svg" class="theme-dark-show h-25px" />
        </a>
        <div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
            <div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
                <div class="d-flex flex-stack py-2">
                    <div class="me-2">
                        <a href="https://preview.keenthemes.com/metronic8/demo19/../demo19/authentication/layouts/fancy/sign-in.html" class="btn btn-icon bg-light rounded-circle">
                            <span class="svg-icon svg-icon-2 svg-icon-gray-800">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.60001 11H21C21.6 11 22 11.4 22 12C22 12.6 21.6 13 21 13H9.60001V11Z" fill="currentColor" />
                                    <path opacity="0.3" d="M9.6 20V4L2.3 11.3C1.9 11.7 1.9 12.3 2.3 12.7L9.6 20Z" fill="currentColor" />
                                </svg>
                            </span>
                        </a>
                    </div>
                    <div class="m-0">
                        <span class="text-gray-400 fw-bold fs-5 me-2" data-kt-translate="new-password-head-desc">Already a member ?</span>
                        <a href="https://preview.keenthemes.com/metronic8/demo19/../demo19/authentication/layouts/fancy/sign-in.html" class="link-primary fw-bold fs-5" data-kt-translate="new-password-head-link">Sign In</a>
                    </div>
                </div>
                @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
                <div class="py-20">
                    <form class="form w-100" novalidate="novalidate" id="kt_new_password_form" 
                    action="{{ route('reset.password.update') }}" method="POST">
                    @csrf
                        <div class="text-start mb-10">
                            <h1 class="text-dark mb-3 fs-3x" data-kt-translate="new-password-title">Setup New Password</h1>
                            <div class="text-gray-400 fw-semibold fs-6" data-kt-translate="new-password-desc">Have you already reset the password ?</div>
                        </div>
                        <div class="fv-row mb-10">
                            <input type="hidden" value="{{$token}}" name="token">
                            <input
                                class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                                type="email" placeholder="Email" name="email" autocomplete="off"
                                data-kt-translate="sign-up-input-email" value="{{$email}}" readonly/>
                        </div>
                        <div class="mb-10 fv-row" data-kt-password-meter="true">
                            <div class="mb-1">
                                <div class="position-relative mb-3">
                                    <input class="form-control form-control-lg form-control-solid" type="password" placeholder="Password" name="password" autocomplete="off" data-kt-translate="new-password-input-password" />
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                        <i class="bi bi-eye-slash fs-2"></i>
                                        <i class="bi bi-eye fs-2 d-none"></i>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                </div>
                            </div>
                            <div class="text-muted" data-kt-translate="new-password-hint">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
                        </div>
                        <div class="fv-row mb-10">
                            <input class="form-control form-control-lg form-control-solid" type="password" placeholder="Confirm Password" name="password_confirmation" autocomplete="off" data-kt-translate="new-password-confirm-password" />
                        </div>
                        <div class="d-flex flex-stack">
                            <button id="kt_new_password_submit" class="btn btn-primary" data-kt-translate="new-password-submit">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait... 
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <div class="d-flex align-items-center">
                                <div class="text-gray-400 fw-semibold fs-6 me-6" data-kt-translate="general-or">Or</div>
                                <a href="#" class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
                                    <img alt="Logo" src="https://preview.keenthemes.com/metronic8/demo19/assets/media/svg/brand-logos/google-icon.svg" class="p-4" />
                                </a>
                                <a href="#" class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
                                    <img alt="Logo" src="https://preview.keenthemes.com/metronic8/demo19/assets/media/svg/brand-logos/facebook-3.svg" class="p-4" />
                                </a>
                                <a href="#" class="symbol symbol-circle symbol-45px w-45px bg-light">
                                    <img alt="Logo" src="https://preview.keenthemes.com/metronic8/demo19/assets/media/svg/brand-logos/apple-black.svg" class="theme-light-show p-4" />
                                    <img alt="Logo" src="https://preview.keenthemes.com/metronic8/demo19/assets/media/svg/brand-logos/apple-black-dark.svg" class="theme-dark-show p-4" />
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-start bgi-no-repeat" style="background-image: url(https://preview.keenthemes.com/metronic8/demo19/assets/media/auth/bg11.png)"></div>
    </div>
</div>
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

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
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
