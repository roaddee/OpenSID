<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Layanan Mandiri {{ ucwords(setting('sebutan_desa') . ' ' . ($desa['nama_desa'] ?? '')) . get_dynamic_title_page_from_path() }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="theme-color" content="#0f4f33">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap3-wysihtml5.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/select2.min.css') }}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-colorpicker.min.css') }}">
    <!-- Bootstrap Date time Picker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datetimepicker.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datepicker.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. -->
    <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.min.css') }}">

    @if (cek_koneksi_internet())
        <!-- Form Wizard - smartWizard -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/css/smart_wizard_all.min.css">
    @endif

    @if ($controller == 'lapak')
        <!-- Map -->
        <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}">
        <link rel="stylesheet" href="{{ asset('css/mapbox-gl.css') }}">
        <link rel="stylesheet" href="{{ asset('css/peta.css') }}">
    @endif

    <!-- Style Mandiri Modification CSS -->
    <link rel="stylesheet" href="{{ asset('css/mandiri-style.css') }}">
    <!-- Tampilan Layanan Mandiri edisi Satima -->
    <link rel="stylesheet" href="{{ asset('css/satima-font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/satima-mandiri.css') }}">

    <!-- Jquery Confirm -->
    <link rel="stylesheet" href="{{ asset('front/css/jquery-confirm.min.css') }}">
    <!-- Jquery UI -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/jquery-ui.min.css') }}">
    @if ($cek_anjungan)
        <!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
        <link rel="stylesheet" href="{{ asset('css/keyboard.min.css') }}">
        <link rel="stylesheet" href="{{ asset('front/css/mandiri-keyboard.css') }}">
    @endif

    @stack('css')
</head>

@php
    $pengguna = $ci->is_login;
    $kunci = $pengguna->ganti_pin === '1';
    $halaman = $ci->uri->segment(2);
    $sebutan_desa = ucwords(setting('sebutan_desa'));
    $foto_pengguna = AmbilFoto($pengguna->foto, '', $pengguna->sex);
    $tautan = fn (string $uri) => $kunci ? '#' : site_url('layanan-mandiri/' . $uri);
    $aktif = fn (array $segmen) => in_array($halaman, $segmen, true) ? 'is-active' : '';
    $menu_layanan = [
        ['beranda', 'Beranda', 'fa-home', ['beranda']],
        ['permohonan-surat', 'Surat', 'fa-file-text-o', ['permohonan-surat', 'arsip-surat', 'surat']],
        ['pesan-masuk', 'Pesan', 'fa-envelope-o', ['pesan-masuk', 'pesan-keluar', 'pesan']],
        ['dokumen', 'Dokumen', 'fa-folder-open-o', ['dokumen']],
        ['bantuan', 'Bantuan sosial', 'fa-handshake-o', ['bantuan']],
        ['lapak', 'Lapak', 'fa-shopping-basket', ['lapak']],
        ['produk', 'Produk saya', 'fa-cube', ['produk']],
        ['kehadiran', 'Kehadiran perangkat', 'fa-users', ['kehadiran']],
    ];
    $menu_akun = [
        ['profil', 'Profil saya', 'fa-user-o', ['profil'], false],
        ['cetak-biodata', 'Cetak biodata', 'fa-print', [], true],
        $pengguna->id_kk != null ? ['cetak-kk', 'Cetak salinan KK', 'fa-print', [], true] : null,
        ['verifikasi', 'Verifikasi email & Telegram', 'fa-check-circle-o', ['verifikasi'], false],
    ];
@endphp

<body class="sm-body hold-transition">
    <a href="#sm-konten" class="sm-skip">Langsung ke isi</a>

    <header class="sm-topbar">
        <div class="sm-wrap sm-topbar-inner">
            <a class="sm-brand" href="{{ site_url() }}" title="Ke website {{ $sebutan_desa }}">
                <img src="{{ gambar_desa($desa['logo']) }}" alt="" class="sm-brand-logo" />
                <span class="sm-brand-text">
                    <span class="sm-brand-name">Layanan Mandiri</span>
                    <span class="sm-brand-sub">{{ $sebutan_desa }} {{ $desa['nama_desa'] }}</span>
                </span>
            </a>

            <nav class="sm-topbar-actions" aria-label="Pemberitahuan">
                <a href="{{ $tautan('permohonan-surat') }}" class="sm-icon-btn" title="Permohonan surat" aria-label="Permohonan surat">
                    <i class="fa fa-file-text-o"></i>
                    <span class="sm-badge" id="b_surat" title="Surat perlu perhatian" style="display: none;"></span>
                </a>
                <a href="{{ $tautan('pesan-masuk') }}" class="sm-icon-btn" title="Pesan masuk" aria-label="Pesan masuk">
                    <i class="fa fa-envelope-o"></i>
                    <span class="sm-badge" id="b_pesan" style="display: none;"></span>
                </a>
                <div class="dropdown sm-user">
                    <a href="#" class="dropdown-toggle sm-user-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ $foto_pengguna }}" alt="" class="sm-avatar">
                        <span class="sm-user-name">{{ $pengguna->nama }}</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right sm-dropdown">
                        <li class="sm-dropdown-head">
                            <strong>{{ $pengguna->nama }}</strong>
                            <span>NIK {{ $pengguna->nik }}</span>
                        </li>
                        <li><a href="{{ $tautan('profil') }}"><i class="fa fa-user-o"></i> Profil saya</a></li>
                        <li><a href="{{ site_url('layanan-mandiri/ganti-pin') }}"><i class="fa fa-key"></i> Ganti PIN</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#" data-toggle="modal" data-target="#pendapat" class="sm-danger"><i class="fa fa-sign-out"></i> Keluar</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="sm-wrap sm-layout">
        <aside class="sm-sidebar" id="sm-menu" aria-label="Menu Layanan Mandiri">
            <div class="sm-sidebar-head">
                <span class="sm-sidebar-title">Menu</span>
                <button type="button" class="sm-icon-btn sm-sidebar-close" data-sm-tutup aria-label="Tutup menu"><i class="fa fa-times"></i></button>
            </div>
            <div class="sm-me">
                <img src="{{ $foto_pengguna }}" alt="Foto {{ $pengguna->nama }}" class="sm-me-photo">
                <div class="sm-me-text">
                    <strong>{{ $pengguna->nama }}</strong>
                    <span>NIK {{ $pengguna->nik }}</span>
                </div>
            </div>

            @if (cek_anjungan())
                <div class="sm-nav-extra">
                    @include('admin.layouts.components.tombol_kembali_layanan_mandiri', ['url' => $kunci ? '#' : site_url('layanan-mandiri'), 'label' => 'Anjungan'])
                </div>
            @endif

            <p class="sm-nav-caption">Layanan</p>
            <ul class="sm-nav">
                @foreach ($menu_layanan as [$uri, $label, $ikon, $segmen])
                    <li><a href="{{ $tautan($uri) }}" class="sm-nav-link {{ $aktif($segmen) }} {{ $kunci ? 'is-locked' : '' }}" @if ($aktif($segmen)) aria-current="page" @endif><i class="fa {{ $ikon }}"></i> {{ $label }}</a></li>
                @endforeach
            </ul>

            <p class="sm-nav-caption">Akun</p>
            <ul class="sm-nav">
                @foreach (array_filter($menu_akun) as [$uri, $label, $ikon, $segmen, $tab_baru])
                    <li><a href="{{ $tautan($uri) }}" class="sm-nav-link {{ $aktif($segmen) }} {{ $kunci ? 'is-locked' : '' }}" @if ($tab_baru) target="_blank" rel="noopener noreferrer" @endif><i class="fa {{ $ikon }}"></i> {{ $label }}</a></li>
                @endforeach
                <li><a href="{{ site_url('layanan-mandiri/ganti-pin') }}" class="sm-nav-link {{ $aktif(['ganti-pin']) }}"><i class="fa fa-key"></i> Ganti PIN</a></li>
                <li><button type="button" class="sm-nav-link sm-danger" data-toggle="modal" data-target="#pendapat"><i class="fa fa-sign-out"></i> Keluar</button></li>
            </ul>
        </aside>
        <div class="sm-sidebar-backdrop" data-sm-tutup></div>

        <main class="sm-main" id="sm-konten">
            @yield('content')

            @includeWhen($kunci && $ci->uri->segment(2) != 'ganti-pin', 'layanan_mandiri.layouts.components.notif', [
                'pesan' => 'Selamat datang pengguna layanan mandiri <b> ' . ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) . ' </b>, <br>Untuk keamanan akun anda, silakan ganti <b>PIN</b> Anda terlebih dahulu sebelum melanjutkan menggunakan layanan mandiri.',
                'aksi' => site_url('layanan-mandiri/ganti-pin'),
            ])

            @includeWhen($data['status'] == 1, 'layanan_mandiri.layouts.components.notif', $ci->session->flashdata('notif'))
        </main>
    </div>

    @include('layanan_mandiri.layouts.components.pendapat')

    <footer class="sm-footer">
        <div class="sm-wrap sm-footer-inner">
            <span>Layanan Mandiri {{ $sebutan_desa }} {{ $desa['nama_desa'] }}</span>
            <span><a href="https://github.com/OpenSID/OpenSID" target="_blank" rel="noopener">{{ config_item('nama_aplikasi') }}</a> v{{ AmbilVersi() }}-perjuangan</span>
        </div>
    </footer>

    <nav class="sm-tabbar" aria-label="Menu cepat">
        <a href="{{ $tautan('beranda') }}" class="{{ $aktif(['beranda']) }}"><i class="fa fa-home"></i><span>Beranda</span></a>
        <a href="{{ $tautan('permohonan-surat') }}" class="{{ $aktif(['permohonan-surat', 'arsip-surat', 'surat']) }}"><i class="fa fa-file-text-o"></i><span>Surat</span></a>
        <a href="{{ $tautan('pesan-masuk') }}" class="{{ $aktif(['pesan-masuk', 'pesan-keluar', 'pesan']) }}"><i class="fa fa-envelope-o"></i><span>Pesan</span></a>
        <a href="{{ $tautan('profil') }}" class="{{ $aktif(['profil']) }}"><i class="fa fa-user-o"></i><span>Profil</span></a>
        <button type="button" data-sm-buka aria-controls="sm-menu"><i class="fa fa-bars"></i><span>Menu</span></button>
    </nav>
    <!-- Diperlukan untuk global automatic base_url oleh external js file -->
    <script type="text/javascript">
        const BASE_URL = "{{ base_url() }}";
        const SITE_URL = "{{ site_url() }}";
    </script>
    <!-- jQuery 3 -->
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>

    @include('admin.layouts.components.token')
    <!-- Jquery UI -->
    <script src="{{ asset('bootstrap/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/jquery.ui.autocomplete.scroll.min.js') }}"></script>

    <script src="{{ asset('bootstrap/js/moment.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone-with-data.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('bootstrap/js/select2.full.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('bootstrap/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/dataTables.rowsgroup.min.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('bootstrap/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- bootstrap Date time picker -->
    <script src="{{ asset('bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/id.js') }}"></script>
    <!-- bootstrap Date picker -->
    <script src="{{ asset('bootstrap/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap-datepicker.id.min.js') }}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset('bootstrap/js/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <!-- Slimscroll -->
    <script src="{{ asset('bootstrap/js/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bootstrap/js/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.overlay.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery-confirm.min.js') }}"></script>
    <!-- Validasi js -->
    @include('admin.layouts.components.validasi_form')
    <!-- Numeral js -->
    <script src="{{ asset('js/numeral.min.js') }}"></script>
    <!-- Khusus modul layanan mandiri -->
    <script src="{{ asset('front/js/mandiri.js') }}"></script>

    @if ($cek_anjungan)
        <!-- keyboard widget script -->
        <script src="{{ asset('js/jquery.keyboard.min.js') }}"></script>
        <script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
        <script src="{{ asset('js/jquery.keyboard.extension-all.min.js') }}"></script>
        <script src="{{ asset('front/js/mandiri-keyboard.js') }}"></script>
    @endif
    <script type="text/javascript">
        $(window).on('load', function() {
            $('#notif').modal('show');
        });

        $('document').ready(function() {
            // Laci menu di HP
            $('[data-sm-buka]').on('click', function() { $('body').addClass('sm-menu-open'); });
            $('[data-sm-tutup], #sm-menu [data-toggle="modal"]').on('click', function() { $('body').removeClass('sm-menu-open'); });
            $(document).on('keydown', function(e) { if (e.key === 'Escape') $('body').removeClass('sm-menu-open'); });
            $('.sm-nav-link.is-locked').on('click', function(e) { e.preventDefault(); });

            window.setTimeout(function() {
                $("#notifikasi").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 1000);

            setTimeout(function() {
                refresh_badge($("#b_pesan"), "{{ site_url('notif_web/inbox') }}");
                refresh_badge($("#b_surat"), "{{ site_url('notif_web/surat_perlu_perhatian') }}");
            }, 500);

            $.extend($.fn.dataTable.defaults, {
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                pageLength: 10,
                language: {
                    url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}",
                }
            });
        });
    </script>

    @if (cek_koneksi_internet())
        <!-- Form Wizard - jquery.smartWizard -->
        <script src="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/js/jquery.smartWizard.min.js"></script>
    @endif
    @stack('scripts')
</body>

</html>
