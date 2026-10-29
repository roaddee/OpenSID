@php
    // Penanda langkah: tiap halaman mengisi @section('langkah', 'server') dst.
    $daftarLangkah = ['mulai' => 'Mulai', 'server' => 'Server', 'folder' => 'Folder', 'database' => 'Database', 'data' => 'Data awal', 'akun' => 'Akun admin'];
    $langkahIni = trim($__env->yieldContent('langkah', 'mulai'));
    $urutanIni = array_search($langkahIni, array_keys($daftarLangkah), true);
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#002853">
    <title>Pemasangan {{ config_item('nama_aplikasi') }} v{{ AmbilVersi() }}</title>
    <link rel="shortcut icon" href="{{ base_url('favicon.ico') }}">
    <link rel="preload" href="{{ base_url('assets/fonts/plus-jakarta-sans/plus-jakarta-sans-latin-400-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ base_url('assets/fonts/plus-jakarta-sans/plus-jakarta-sans-latin-800-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/satima-font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/satima-login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/satima-installer.css') }}">
</head>

<body class="sl-body si-body">
    <div class="sl-backdrop" aria-hidden="true"></div>

    <div class="sl-shell">
        <header class="sl-intro">
            <img src="{{ asset('files/logo/opensid_logo.png') }}" alt="Logo OpenSID" class="sl-logo">
            <p class="sl-chip">PEMASANGAN · v{{ AmbilVersi() }}</p>
            <h1 class="sl-title">Pasang <span>{{ config_item('nama_aplikasi') }}</span></h1>
            <p class="sl-sub">Sistem Informasi Desa — enam langkah singkat, sekitar lima menit.</p>
        </header>

        <ol class="si-steps" aria-label="Langkah pemasangan">
            @foreach ($daftarLangkah as $kunci => $label)
                @php $urutan = array_search($kunci, array_keys($daftarLangkah), true); @endphp
                <li class="{{ $urutan < $urutanIni ? 'si-done' : ($urutan === $urutanIni ? 'si-now' : '') }}" @if ($urutan === $urutanIni) aria-current="step" @endif>
                    <span>{{ $label }}</span>
                </li>
            @endforeach
        </ol>

        <main class="sl-card si-card">
            @yield('step')
        </main>

        <footer class="sl-foot">
            <span><a href="https://github.com/OpenSID/OpenSID" target="_blank" rel="noopener">OpenSID</a> v{{ AmbilVersi() }} · edisi Satima</span>
            <a href="{{ config('app.panduan.instalasi') }}" target="_blank" rel="noopener">Panduan pemasangan</a>
        </footer>
    </div>

    <script>
        // Tombol kirim: kunci & tampilkan putaran supaya form tidak terkirim dua kali.
        document.querySelectorAll('form[data-si-form]').forEach(function (form) {
            form.addEventListener('submit', function () {
                var btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('si-busy');
                    var t = btn.querySelector('[data-si-label]');
                    if (t && btn.dataset.siBusy) { t.textContent = btn.dataset.siBusy; }
                }
                form.classList.add('si-busy-form');
            });
        });
        // Tampilkan / sembunyikan sandi
        document.querySelectorAll('[data-si-eye]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var on = btn.getAttribute('aria-pressed') !== 'true';
                btn.setAttribute('aria-pressed', on ? 'true' : 'false');
                btn.dataset.siEye.split(',').forEach(function (id) {
                    var el = document.getElementById(id);
                    if (el) { el.type = on ? 'text' : 'password'; }
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
