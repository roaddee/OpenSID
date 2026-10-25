@extends('admin.auth.index')

@php
    preg_match('/(\d+)/', $errors?->first('email'), $matches);
    $second = $matches[0] ?? 0;
    $isProduction = app()->isProduction();
@endphp

@section('content')
    <form id="validasi" class="login-form sl-form" action="{{ $form_action }}" method="post">
        <div class="form-group sl-field">
            <label for="username" class="sl-label">Nama pengguna</label>
            <div class="sl-input">
                @include('admin.layouts.components.ikon', ['n' => 'user', 'k' => 'sl-input-ikon'])
                <input
                    id="username"
                    name="username"
                    type="text"
                    autocomplete="username"
                    placeholder="contoh: operator"
                    @disabled($second)
                    class="form-username form-control required"
                    maxlength="100"
                    autofocus
                >
            </div>
        </div>
        <div class="form-group sl-field">
            <div class="sl-label-row">
                <label for="password" class="sl-label">Kata sandi</label>
                <a href="{{ site_url('siteman/lupa_sandi') }}" class="sl-link">Lupa kata sandi?</a>
            </div>
            <div class="sl-input">
                @include('admin.layouts.components.ikon', ['n' => 'lock', 'k' => 'sl-input-ikon'])
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    placeholder="Kata sandi"
                    @disabled($second)
                    class="form-username form-control required"
                    maxlength="100"
                >
                <button type="button" class="sl-eye" id="checkbox" aria-label="Tampilkan kata sandi" aria-pressed="false" @disabled($second)>@include('admin.layouts.components.ikon', ['n' => 'eye', 'k' => 'sl-eye-on'])@include('admin.layouts.components.ikon', ['n' => 'eye-off', 'k' => 'sl-eye-off'])</button>
            </div>
        </div>

        @if ($isProduction && setting('google_recaptcha'))
            <div class="sl-recaptcha">{!! app('captcha')->display() !!}</div>
        @elseif ($isProduction)
            <div class="form-group sl-field">
                <label for="captcha_code" class="sl-label">Kode keamanan</label>
                <div class="sl-captcha">
                    <span class="sl-captcha-img"><img id="captcha" src="{{ site_url('captcha') }}" alt="Kode keamanan" width="150" height="44" /></span>
                    <button type="button" id="b-captcha" class="sl-captcha-btn" title="Ganti gambar kode" aria-label="Ganti gambar kode" onclick="document.getElementById('captcha').src = '{{ site_url('captcha') }}?' + Math.random();">@include('admin.layouts.components.ikon', ['n' => 'refresh'])</button>
                    <div class="sl-input captcha">
                        <input
                            id="captcha_code"
                            name="captcha_code"
                            type="text"
                            class="form-control required"
                            maxlength="6"
                            placeholder="Ketik kode"
                            @disabled($second)
                            autocomplete="off"
                        />
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group">
            <button type="submit" class="btn sl-btn sl-btn-primary" @disabled($second)>Masuk</button>
        </div>

        @if (setting('login_otp'))
            <div class="sl-divider"><span>atau</span></div>
            <div class="form-group">
                <a href="{{ ci_route('siteman.otp.form_login_otp') }}" class="btn sl-btn sl-btn-ghost" role="button">@include('admin.layouts.components.ikon', ['n' => 'phone']) Masuk dengan kode OTP</a>
            </div>
        @endif
    </form>
@endsection

@push('js')
    @if ($isProduction && setting('google_recaptcha'))
        {!! app('captcha')->renderJs('id', true, 'recaptchaCallback') !!}

        <script>
            var recaptchaCallback = function() {
                grecaptcha.render(document.querySelector('.g-recaptcha'), {
                    'sitekey': '{{ $list_setting->firstWhere('key', 'google_recaptcha_site_key')?->value }}',
                    'error-callback': function() {
                        $.ajax({
                            url: '{{ site_url('siteman/matikan-captcha') }}',
                            type: 'post',
                            success: function(response) {
                                // Redirect to the 'siteman' URL after disabling captcha
                                window.location.href = '{{ site_url('siteman') }}';
                            },
                            error: function(xhr, status, error) {
                                // Log the error for debugging
                                console.error('Error in captcha disabling request:', error);
                            }
                        });
                    }
                });
            }
        </script>
    @endif

    <script>
        function start_countdown() {
            let totalSeconds = {{ $second }};
            const timer = setInterval(function() {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;

                if (totalSeconds <= 0) {
                    clearInterval(timer);
                    location.reload();
                } else {
                    document.getElementById("countdown").innerHTML = `Terlalu banyak upaya masuk. Silakan coba lagi dalam ${minutes} menit ${seconds} detik.`;
                    totalSeconds--;
                }
            }, 1000);
        }

        $(document).ready(function() {
            var pass = $("#password");
            $('#checkbox').click(function() {
                var tampil = pass.attr('type') === 'password';
                pass.attr('type', tampil ? 'text' : 'password');
                $(this).attr('aria-pressed', tampil).attr('aria-label', tampil ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            });
            if ($('#countdown').length) {
                start_countdown();
            }

            // Hapus localStorage untuk timer OTP login setiap kali halaman login utama dimuat.
            // Ini untuk memastikan timer direset jika pengguna kembali ke halaman ini
            // setelah gagal OTP atau membatalkan proses.
            localStorage.removeItem('otpLoginExpiry');
        });
    </script>
@endpush
