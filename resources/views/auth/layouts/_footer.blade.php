		{{-- <script>var hostUrl = "{{/metronic8/demo19/assets/}}";</script> --}}
		<script src="{{ asset('admin/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('admin/js/scripts.bundle.js') }}"></script>
		<script src="{{ asset('admin/js/custom/authentication/sign-in/general.js') }}"></script>
		<script src="{{ asset('admin/js/custom/authentication/sign-in/i18n.js') }}"></script>
		<script src="{{ asset('admin/js/custom/authentication/sign-in/otp.js') }}"></script>
		@yield('otherJsQuery')
		<script>
            const submitFormResendWa = () => {
                $('#form-resend-wa').submit();
            }
        </script>