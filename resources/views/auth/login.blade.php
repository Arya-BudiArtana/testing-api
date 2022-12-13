@extends('auth.layouts.app')

@section('content')
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <a href="https://preview.keenthemes.com/metronic8/demo19/../demo19/index.html"
                class="d-block d-lg-none mx-auto pt-5 pb-5">
                <img alt="Logo" src="https://preview.keenthemes.com/metronic8/demo19/assets/media/logos/default.svg"
                    class="theme-light-show h-25px" />
                <img alt="Logo" src="https://preview.keenthemes.com/metronic8/demo19/assets/media/logos/default-dark.svg"
                    class="theme-dark-show h-25px" />
            </a>
            <div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
                <div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
                    <div class="d-flex flex-stack py-5">
                        <div class="me-2">
                        </div>
                        <div class="m-0">
                            <span class="text-gray-400 fw-bold fs-5 me-2" data-kt-translate="sign-up-head-desc">Not a member
                                yet?</span>
                            <a href="https://preview.keenthemes.com/metronic8/demo19/../demo19/authentication/layouts/fancy/sign-in.html"
                                class="link-primary fw-bold fs-5" data-kt-translate="sign-up-head-link">Sign Up</a>
                        </div>
                    </div>
                    <div class="pt-0 pb-40">
                        <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form"
                            data-kt-redirect-url="https://preview.keenthemes.com/metronic8/demo19/../demo19/authentication/layouts/fancy/sign-in.html"
                            method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="text-start mb-10">
                                <h1 class="text-dark mb-3 fs-3x" data-kt-translate="sign-up-title">Login</h1>
                                <div class="text-gray-400 fw-semibold fs-6" data-kt-translate="general-desc">Go to the App!
                                </div>
                            </div>
                            <div class="fv-row mb-10">
                                <input
                                    class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                                    type="email" placeholder="Email" name="email" autocomplete="off"
                                    data-kt-translate="sign-up-input-email" />

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="fv-row mb-10" data-kt-password-meter="true">
                                <div class="mb-1">
                                    <div class="position-relative mb-3">
                                        <input
                                            class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                                            type="password" placeholder="Password" name="password" autocomplete="off"
                                            data-kt-translate="sign-up-input-password" />
                                        <span
                                            class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                            data-kt-password-meter-control="visibility">
                                            <i class="bi bi-eye-slash fs-2"></i>
                                            <i class="bi bi-eye fs-2 d-none"></i>
                                        </span>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-stack">
                                <button id="kt_sign_up_submit" class="btn btn-primary" type="submit"
                                    data-kt-translate="sign-up-submit">
                                    <span class="indicator-label">Submit</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <div class="d-flex align-items-center">
                                    <div class="text-gray-400 fw-semibold fs-6 me-6">Or</div>
                                    <a href="{{ route('google.auth.login') }}"
                                        class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
                                        <img alt="Logo"
                                            src="https://preview.keenthemes.com/metronic8/demo19/assets/media/svg/brand-logos/google-icon.svg"
                                            class="p-4" />
                                    </a>
                                    <a href="#" class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
                                        <img alt="Logo"
                                            src="https://preview.keenthemes.com/metronic8/demo19/assets/media/svg/brand-logos/facebook-3.svg"
                                            class="p-4" />
                                    </a>
                                    <a href="#" class="symbol  symbol-45px w-45px bg-light">
                                        <img alt="Logo" src="https://www.svgrepo.com/show/28155/whatsapp.svg"
                                            class="theme-light-show p-4" />
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-start bgi-no-repeat"
                style="background-image: url(https://preview.keenthemes.com/metronic8/demo19/assets/media/auth/bg11.png)">
            </div>
        </div>
    </div>
@endsection
