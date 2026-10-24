<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>
        {{ setting('login_title') . ' ' . ucwords(setting('sebutan_desa')) . ($desa['nama_desa'] ? ' ' . $desa['nama_desa'] : '') . get_dynamic_title_page_from_path() }}
    </title>
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="theme-color" content="#0f4f33">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.bar.css') }}" media="screen">
    <!-- bootstrap datetimepicker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datetimepicker.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/satima-font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/satima-login.css') }}">
    @if (is_file('desa/pengaturan/siteman/siteman_mandiri.css'))
        <link rel="stylesheet" href="{{ base_url('desa/pengaturan/siteman/siteman_mandiri.css') }}">
    @endif
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>

    @if ($cek_anjungan)
        <!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
        <link rel="stylesheet" href="{{ asset('css/keyboard.min.css') }}">
        <link rel="stylesheet" href="{{ asset('front/css/mandiri-keyboard.css') }}">
    @endif

    @include('admin.layouts.components.token')
</head>

@php
    $sebutan_desa = ucwords(setting('sebutan_desa'));
    $latar = default_file(LATAR_LOGIN . setting('latar_login_mandiri'), DEFAULT_LATAR_KEHADIRAN);
    preg_match('/(\d+)/', $errors->first('email'), $matches);
    $second = $matches[0] ?? 0;
    $manfaat = [
        ['fa-file-text-o', 'Ajukan surat dari rumah', 'Pilih jenis surat, isi data, lalu ambil di kantor saat siap.'],
        ['fa-bell-o', 'Pantau permohonan', 'Lihat apakah surat Anda sedang diperiksa atau sudah siap diambil.'],
        ['fa-comments-o', 'Kirim pesan ke ' . strtolower($sebutan_desa), 'Tanya atau sampaikan keperluan langsung ke operator.'],
        ['fa-id-card-o', 'Data diri & keluarga', 'Cek biodata, cetak salinan KK, dan lihat bantuan yang diterima.'],
    ];
@endphp

<body class="sl-body sl-mandiri sl-split @hasSection('lebar') sl-wide @endif">
    <div class="sl-backdrop" aria-hidden="true" style="--sl-latar: url('{{ $latar }}')">
        <span class="sl-terrace"></span>
    </div>

    <div class="sl-shell">
        <div class="sl-split-grid">
            <header class="sl-intro sl-intro-left">
                <a href="{{ base_url('/') }}" class="sl-brand" title="Ke halaman depan website">
                    <img src="{{ gambar_desa($desa['logo']) }}" alt="Logo {{ $sebutan_desa }} {{ $desa['nama_desa'] }}" class="sl-logo">
                    <span>
                        <span class="sl-brand-name">{{ $sebutan_desa }} {{ $desa['nama_desa'] }}</span>
                        <span class="sl-brand-sub">{{ ucwords(setting('sebutan_kecamatan')) }} {{ $desa['nama_kecamatan'] }}, {{ ucwords(setting('sebutan_kabupaten')) }} {{ $desa['nama_kabupaten'] }}</span>
                    </span>
                </a>
                <h1 class="sl-title sl-title-xl">Layanan Mandiri <span>untuk warga</span></h1>
                <p class="sl-lead">Urus surat dan keperluan administrasi tanpa harus antre di kantor {{ strtolower($sebutan_desa) }}.</p>
                <ul class="sl-benefits">
                    @foreach ($manfaat as [$ikon, $judul, $teks])
                        <li>
                            <span class="sl-benefit-icon"><i class="fa {{ $ikon }}"></i></span>
                            <span><strong>{{ $judul }}</strong>{{ $teks }}</span>
                        </li>
                    @endforeach
                </ul>
            </header>

            <main class="sl-card">
                <div class="sl-card-head">
                    <h2 class="sl-card-title">@yield('judul', 'Masuk')</h2>
                    <p class="sl-card-sub">@yield('keterangan', 'Masukkan NIK dan PIN dari operator ' . strtolower($sebutan_desa) . '.')</p>
                </div>

                @if ($errors->any())
                    <div @if (!str_contains($errors->first('email'), 'Terlalu banyak upaya masuk.')) id="notif" @endif class="sl-alert sl-alert-danger" role="alert">
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

                @if ($notif = $ci->session->flashdata('notif'))
                    <div id="notif" class="sl-alert sl-alert-danger" role="alert">
                        <i class="fa fa-exclamation-circle"></i>
                        <div><p>{{ $notif }}</p></div>
                    </div>
                @endif

                @yield('content')

                @hasSection('tanpa_catatan')
                @else
                    <p class="sl-note"><i class="fa fa-info-circle"></i><span>Belum punya PIN? Datang ke kantor {{ strtolower($sebutan_desa) }} dengan membawa KTP, operator akan membuatkannya.</span></p>
                @endif
            </main>
        </div>

        <footer class="sl-foot">
            <a href="{{ base_url('/') }}"><i class="fa fa-arrow-left"></i> Kembali ke website</a>
            @php
                $info_perangkat = ['Alamat IP ' . request()->ip()];
                if ($cek_anjungan) {
                    if ($cek_anjungan['mac_address']) {
                        $info_perangkat[] = 'MAC ' . $cek_anjungan['mac_address'];
                    }
                    $info_perangkat[] = 'Anjungan Mandiri' . ($cek_anjungan['keyboard'] == 1 ? ' (keyboard virtual aktif)' : '');
                }
            @endphp
            <span class="sl-foot-meta">{{ implode(', ', $info_perangkat) }}</span>
            <span><a href="https://github.com/OpenSID/OpenSID" rel="noopener noreferrer" target="_blank">OpenSID</a> v{{ AmbilVersi() }}-perjuangan</span>
        </footer>
    </div>

    @include('admin.layouts.components.konfirmasi_cookie', ['cookie_name' => 'pengunjung'])
    @include('admin.layouts.components.aktifkan_cookie')

    <!-- jQuery 3 -->
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- bootstrap Moment -->
    <script src="{{ asset('bootstrap/js/moment.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone-with-data.js') }}"></script>
    <!-- bootstrap Date time picker -->
    <script src="{{ asset('bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/id.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('bootstrap/js/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bootstrap/js/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <!-- Validasi -->
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validasi.js') }}"></script>
    <script src="{{ asset('js/localization/messages_id.js') }}"></script>

    @if ($cek_anjungan)
        <!-- keyboard widget css & script -->
        <script src="{{ asset('js/jquery.keyboard.min.js') }}"></script>
        <script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
        <script src="{{ asset('js/jquery.keyboard.extension-all.min.js') }}"></script>
        <script src="{{ asset('front/js/mandiri-keyboard.js') }}"></script>
    @endif
    <script src="{{ asset('js/id_browser.js') }}"></script>
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
            if ($('#nik').length && !$('#nik').val()) {
                $('#nik').focus();
            } else if ($('#pin').length) {
                $('#pin').focus();
            } else if ($('#tag').length) {
                $('#tag').focus();
            }

            if ($('#countdown').length) {
                start_countdown();
            }

            window.setTimeout(function() {
                $("#notif").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>

    @stack('script')
</body>

</html>
