<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ setting('login_title') . ' ' . ucwords(setting('sebutan_desa')) . ($header['nama_desa'] ? ' ' . $header['nama_desa'] : '') . get_dynamic_title_page_from_path() }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#002853">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/satima-font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/satima-login.css') }}">
    @if (is_file('desa/pengaturan/siteman/siteman.css'))
        <link rel='stylesheet' href="{{ base_url('desa/pengaturan/siteman/siteman.css') }}">
    @endif
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validasi.js') }}"></script>
    <script src="{{ asset('js/localization/messages_id.js') }}"></script>
    <script src="{{ asset('js/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @include('admin.layouts.components.token')
</head>

@php
    $sebutan_desa = ucwords(setting('sebutan_desa'));
    $tanggal = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y');
@endphp

<body class="sl-body sl-admin">
    <div class="sl-backdrop" aria-hidden="true" @if ($latar_login) style="--sl-latar: url('{{ $latar_login }}')" @endif>
        <img class="sl-mark" src="{{ gambar_desa($header['logo']) }}" alt="">
    </div>

    <div class="sl-shell">
        <header class="sl-intro">
            <a href="{{ site_url() }}" class="sl-logo-link" title="Ke halaman depan website">
                <img src="{{ gambar_desa($header['logo']) }}" alt="Logo {{ $sebutan_desa }} {{ $header['nama_desa'] }}" class="sl-logo">
            </a>
            <p class="sl-chip">{{ $tanggal }}</p>
            <h1 class="sl-title">Admin <span>{{ $sebutan_desa }} {{ $header['nama_desa'] }}</span></h1>
            <p class="sl-sub">
                {{ ucwords(setting('sebutan_kecamatan')) }} {{ $header['nama_kecamatan'] }}, {{ ucwords(setting('sebutan_kabupaten')) }} {{ $header['nama_kabupaten'] }}
            </p>
            @if ($header['alamat_kantor'])
                <p class="sl-tags"><span><i class="fa fa-map-marker"></i> {{ $header['alamat_kantor'] }}@if ($header['kode_pos']), {{ $header['kode_pos'] }}@endif</span></p>
            @endif
        </header>

        <main class="sl-card">
            <div class="sl-card-head">
                <h2 class="sl-card-title">@yield('judul', 'Masuk ke akun Anda')</h2>
                <p class="sl-card-sub">@yield('keterangan', 'Gunakan nama pengguna dan kata sandi yang diberikan operator.')</p>
            </div>

            @if ($errors?->any())
                <div class="sl-alert sl-alert-danger" role="alert">
                    <i class="fa fa-exclamation-circle"></i>
                    <div>
                        @foreach ($errors->all() as $item)
                            @if (str_contains($item, 'Terlalu banyak upaya masuk.'))
                                <p id="countdown">{{ $item }}</p>
                            @else
                                <p>{{ $item }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
            @if ($notif = session('notif'))
                <div class="sl-alert sl-alert-danger" role="alert">
                    <i class="fa fa-exclamation-circle"></i>
                    <div><p>{{ $notif }}</p></div>
                </div>
            @endif

            @yield('content')

            @if (setting('tte') && $logo_bsre)
                <div class="sl-bsre"><img src="{{ $logo_bsre }}" alt="Tanda tangan elektronik BSrE"></div>
            @endif
        </main>

        <footer class="sl-foot">
            <a href="{{ site_url() }}"><i class="fa fa-arrow-left"></i> Kembali ke website</a>
            <span><a href="https://github.com/OpenSID/OpenSID" target="_blank" rel="noopener">OpenSID</a> v{{ AmbilVersi() }}</span>
        </footer>
    </div>

    @stack('js')

</body>

</html>
