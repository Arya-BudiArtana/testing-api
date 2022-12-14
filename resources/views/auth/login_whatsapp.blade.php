@extends('auth.layouts.app')
@section('title', 'Masuk Dengan WhatsApp')
@section('content')
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <a href="https://preview.keenthemes.com/metronic8/demo19/../demo19/index.html"
                class="d-block d-lg-none mx-auto py-20">
                <img alt="Logo" src="https://preview.keenthemes.com/metronic8/demo19/assets/media/logos/default.svg"
                    class="theme-light-show h-25px" />
                <img alt="Logo" src="https://preview.keenthemes.com/metronic8/demo19/assets/media/logos/default-dark.svg"
                    class="theme-dark-show h-25px" />
            </a>
            <div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
                <div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
                    <div class="d-flex flex-stack py-2">
                        <div class="me-2">
                            <a href="https://preview.keenthemes.com/metronic8/demo19/../demo19/authentication/layouts/fancy/sign-in.html"
                                class="btn btn-icon bg-light rounded-circle">
                                <span class="svg-icon svg-icon-2 svg-icon-gray-800">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.60001 11H21C21.6 11 22 11.4 22 12C22 12.6 21.6 13 21 13H9.60001V11Z"
                                            fill="currentColor" />
                                        <path opacity="0.3" d="M9.6 20V4L2.3 11.3C1.9 11.7 1.9 12.3 2.3 12.7L9.6 20Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                        @if (Session::has('otp_session'))
                        <div class="m-0">
                            @if (session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-success">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                            <span class="text-gray-400 fw-bold fs-5 me-2" data-kt-translate="two-step-head-desc">Didn’t
                                get the code ?</span>
                            <a href="#" onclick='submitFormResendWa()'
                                class="link-primary fw-bold fs-5" data-kt-translate="two-step-head-resend">Resend</a>
                            <span class="text-gray-400 fw-bold fs-5 mx-1" data-kt-translate="two-step-head-or">or</span>
                            <a href="#" class="link-primary fw-bold fs-5"
                                data-kt-translate="two-step-head-call-us">Call Us</a>
                        </div>
                </div>
                <div class="py-20">
                    <form action="{{ route('login-whatsapp.verifikasi') }}" id="form-resend-wa" method="POST">
                        <input type="hidden" name="whatsapp" id="no_whatsapp"
                            value="{{ Session::get('otp_session')['phone'] }}">
                        @csrf
                    </form>
                    <form class="form w-100 mb-10" novalidate="novalidate" id="kt_sing_in_two_steps_form" method="POST"
                        action="{{ route('login-whatsapp.aksi') }}">
                        @csrf
                        <div class="text-center mb-10">
                            <img alt="Logo" class="theme-light-show mh-125px"
                                src="/metronic8/demo19/assets/media/svg/misc/smartphone-2.svg" />
                            <img alt="Logo" class="theme-dark-show mh-125px"
                                src="/metronic8/demo19/assets/media/svg/misc/smartphone-2-dark.svg" />
                        </div>
                        <div class="text-center mb-10">
                            <h1 class="text-dark mb-3" data-kt-translate="two-step-title">Two Step Verification</h1>
                            <div class="text-muted fw-semibold fs-5 mb-5" data-kt-translate="two-step-deck">Enter the
                                verification code we sent to</div>
                            <div class="fw-bold text-dark fs-3">(+62{{ Session::get('otp_session')['phone'] }})</div>
                        </div>
                        <div class="mb-10">
                            <div class="fw-bold text-start text-dark fs-6 mb-1 ms-1" data-kt-translate="two-step-label">
                                Type your 6 digit security code</div>
                            <div class="d-flex flex-wrap flex-stack">
                                <input type="text" name="code_1" data-inputmask="'mask': '9', 'placeholder': ''"
                                    maxlength="1"
                                    class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2"
                                    value="" />
                                <input type="text" name="code_2" data-inputmask="'mask': '9', 'placeholder': ''"
                                    maxlength="1"
                                    class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2"
                                    value="" />
                                <input type="text" name="code_3" data-inputmask="'mask': '9', 'placeholder': ''"
                                    maxlength="1"
                                    class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2"
                                    value="" />
                                <input type="text" name="code_4" data-inputmask="'mask': '9', 'placeholder': ''"
                                    maxlength="1"
                                    class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2"
                                    value="" />
                                <input type="text" name="code_5" data-inputmask="'mask': '9', 'placeholder': ''"
                                    maxlength="1"
                                    class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2"
                                    value="" />
                                <input type="text" name="code_6" data-inputmask="'mask': '9', 'placeholder': ''"
                                    maxlength="1"
                                    class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2"
                                    value="" />
                            </div>
                        </div>
                        @else
                        <div class="m-0">
                            @if (session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif

                            @if (session()->has('error'))
                                <div class="alert alert-success">
                                    {{ session()->get('error') }}
                                </div>
                            @endif
                            <span class="text-gray-400 fw-bold fs-5 me-2"
                                data-kt-translate="password-reset-head-desc">Not a member yet?</span>
                            <a href="https://preview.keenthemes.com/metronic8/demo19/../demo19/authentication/layouts/fancy/sign-in.html"
                                class="link-primary fw-bold fs-5" data-kt-translate="password-reset-head-link">Sign
                                Up</a>
                        </div>
                </div>
                <div class="pt-0 pb-40">
                    <form class="form w-100" novalidate="novalidate" id="kt_password_reset_form" method="POST"
                        action="{{ route('login-whatsapp.verifikasi') }}">
                        @csrf
                        <div class="text-start mb-10">
                            <h1 class="text-dark mb-3 fs-3x" data-kt-translate="password-reset-title">Login With
                                WhatsApp
                            </h1>
                            <div class="text-gray-400 fw-semibold fs-6" data-kt-translate="password-reset-desc">Enter
                                your whatsapp number to get OTP.</div>
                        </div>
                        <div class="fv-row mb-10">
                            <input class="form-control form-control-solid @error('whatsapp') is-invalid @enderror"
                                value="{{ old('whatsapp') }}" type="whatsapp" placeholder="Whatsapp" name="whatsapp"
                                autocomplete="off" onkeyup="this.value = +this.value.replace(/[^0-9]/g, '');" />
                            @error('whatsapp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                            @endif
                            <div class="d-flex flex-stack">
                                <div class="m-0">
                                    <button id="kt_sing_in_two_steps_submit" class="btn btn-primary me-2"
                                        data-kt-translate="password-reset-submit" type="submit">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                    <a href="https://preview.keenthemes.com/metronic8/demo19/../demo19/authentication/layouts/fancy/sign-in.html"
                                        class="btn btn-lg btn-light-primary fw-bold"
                                        data-kt-translate="password-reset-cancel">Cancel</a>
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
