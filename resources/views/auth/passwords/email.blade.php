@extends('auth.layouts.app')
@section('title', 'Lupa Kata Sandi')
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
                                data-kt-translate="password-reset-head-desc">Already a member ?</span>
                            <a href="https://preview.keenthemes.com/metronic8/demo19/../demo19/authentication/layouts/fancy/sign-in.html"
                                class="link-primary fw-bold fs-5" data-kt-translate="password-reset-head-link">Sign In</a>
                        </div>
                    </div>
                    <div class="pt-0 pb-40">
                        <form class="form w-100" novalidate="novalidate" id="kt_password_reset_form" method="POST"
                            action="{{ route('reset.password.aksi') }}">
                            @csrf
                            <div class="text-start mb-10">
                                <h1 class="text-dark mb-3 fs-3x" data-kt-translate="password-reset-title">Forgot Password ?
                                </h1>
                                <div class="text-gray-400 fw-semibold fs-6" data-kt-translate="password-reset-desc">Enter
                                    your email to reset your password.</div>
                            </div>
                            <div class="fv-row mb-10">
                                <input class="form-control form-control-solid @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" type="email" placeholder="Email" name="email"
                                    autocomplete="off" data-kt-translate="password-reset-input-email" />
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="d-flex flex-stack">
                                <div class="m-0">
                                    <button id="kt_password_reset_submit" class="btn btn-primary me-2"
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
    {{-- <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-md-center align-items-center h-100">
                <div class="col-12 col-md-6 col-lg-5">
                    <div class="brand">
                        <img src="{{ config('general.icon-text') }}" alt="logo">
                    </div>
                    @include('auth.errors._validation')
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="card fat">
                        <div class="card-body">
                            <h4 class="card-title text-center">Lupa Kata Sandi</h4>
                            <form method="POST" class="my-login-validation" novalidate=""
                                action="{{ route('reset.password.aksi') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="new-password">Alamat Email</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-text text-muted">
                                        Gunakan Email yang terhubung ke Akun Anda
                                    </div>
                                </div>

                                <div class="form-group m-0">
                                    <button type="submit" class="btn btn-my-home btn-block">
                                        Kirim Email Ganti Kata Sandi
                                    </button>
                                </div>
                            </form>
                            @if ($statusApp['data']['waNotifStatus'] == 1)
                                <hr>
                                <p class="card-subtitle text-center mt-4 mb-2">Ingin menggunakan cara lain?</p>
                                <div class="form-group m-0">
                                    <a href="{{ route('reset.password.whatsapp') }}" id="btn-ulangi"
                                        class="btn text-dark border-dark btn-block btn-sm">
                                        <img src="{{ asset('admin/img/whatsapp.png') }}" width="25px"
                                            class="img" alt="WhatsApp Icon">
                                        Ganti Sandi Melalui WhatsApp </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="footer" id="copyright">
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
@endsection
