@php
    $bg_header = $latar_website;
    $sebutan_desa = ucwords(setting('sebutan_desa'));
    $nama_desa_lengkap = $sebutan_desa . ' ' . ucwords($desa['nama_desa']);
    $wilayah = ucfirst(setting('sebutan_kecamatan')) . ' ' . ucwords($desa['nama_kecamatan']) . ', ' . ucfirst(setting('sebutan_kabupaten')) . ' ' . ucwords($desa['nama_kabupaten']);
    $provinsi = ucwords($desa['nama_propinsi']);
    $di_beranda = in_array((string) request()->segment(1), ['', 'first'], true) && ! request()->get('cari');
    $mandiri_aktif = setting('layanan_mandiri') == 1;
    // Layanan cepat hanya untuk halaman yang menunya sudah aktif (Admin Web → Menu); halaman lain menampilkan "Menu Belum Aktif".
    $menu_aktif = \App\Models\Menu::active()->pluck('link')->all();
    $layanan_cepat = [];
    if ($mandiri_aktif) {
        $layanan_cepat[] = ['Layanan Mandiri', 'fas fa-id-card', site_url('layanan-mandiri/masuk'), 'Ajukan surat dari rumah'];
    }
    foreach ([
        ['pengaduan', 'Pengaduan', 'fas fa-bullhorn', 'Sampaikan keluhan'],
        ['peta', 'Peta ' . $sebutan_desa, 'fas fa-map-marked-alt', 'Wilayah & lokasi'],
        ['data-wilayah', 'Data Wilayah', 'fas fa-chart-pie', 'Dusun, RW, RT'],
        ['informasi_publik', 'Informasi Publik', 'fas fa-folder-open', 'Dokumen terbuka'],
        ['peraturan-desa', 'Produk Hukum', 'fas fa-balance-scale', 'Peraturan ' . strtolower($sebutan_desa)],
        ['pembangunan', 'Pembangunan', 'fas fa-hard-hat', 'Proyek berjalan'],
        ['lapak', 'Lapak Warga', 'fas fa-store', 'Produk warga'],
        ['galeri', 'Galeri', 'fas fa-images', 'Foto kegiatan'],
        ['pemerintah', 'Pemerintahan', 'fas fa-landmark', 'Perangkat ' . strtolower($sebutan_desa)],
    ] as [$tautan, $label, $icon, $ket]) {
        if (count($layanan_cepat) < 8 && in_array($tautan, $menu_aktif, true)) {
            $layanan_cepat[] = [$label, $icon, site_url(str_replace('_', '-', $tautan)), $ket];
        }
    }
    try {
        $hari_ini = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, j F Y');
    } catch (\Throwable $e) {
        $hari_ini = date('d-m-Y');
    }
@endphp

<div class="kr-strip-top">
    <div class="kr-wrap kr-strip-top-inner">
        <span class="kr-today"><i class="far fa-calendar-alt" aria-hidden="true"></i> {{ $hari_ini }}</span>
        @if ($teks_berjalan)
            <div class="kr-ticker" aria-label="Kabar singkat">
                <marquee onmouseover="this.stop();" onmouseout="this.start();" scrollamount="4">
                    @foreach ($teks_berjalan as $marquee)
                        <span class="kr-ticker-item">
                            {{ $marquee['teks'] }}
                            @if (trim($marquee['tautan']) && $marquee['judul_tautan'])
                                <a href="{{ $marquee['tautan'] }}">{{ $marquee['judul_tautan'] }}</a>
                            @endif
                        </span>
                    @endforeach
                </marquee>
            </div>
        @endif
        <a href="{{ site_url('siteman') }}" class="kr-strip-admin"><i class="fas fa-lock" aria-hidden="true"></i> Masuk admin</a>
    </div>
</div>

<div class="kr-nav" data-kr-nav>
    <div class="kr-wrap kr-nav-inner">
        <a href="{{ site_url('/') }}" class="kr-brand" aria-label="Beranda {{ $nama_desa_lengkap }}">
            <img src="{{ gambar_desa($desa['logo']) }}" alt="" class="kr-brand-logo" width="44" height="44">
            <span class="kr-brand-text">
                <span class="kr-brand-name">{{ $nama_desa_lengkap }}</span>
                <span class="kr-brand-sub">{{ ucfirst(setting('sebutan_kabupaten')) }} {{ ucwords($desa['nama_kabupaten']) }}</span>
            </span>
        </a>

        @include('theme::commons.main_menu')

        <div class="kr-nav-actions">
            <button type="button" class="kr-icon-btn" data-kr-toggle="cari" aria-expanded="false" aria-controls="kr-cari" aria-label="Cari">
                <i class="fas fa-search"></i>
            </button>
            @if ($mandiri_aktif)
                <a href="{{ site_url('layanan-mandiri/masuk') }}" class="kr-btn kr-btn-udang kr-hide-sm">Layanan Mandiri</a>
            @endif
            <button type="button" class="kr-icon-btn kr-only-lgdown" data-kr-toggle="laci" aria-expanded="false" aria-controls="kr-laci" aria-label="Buka menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <div class="kr-search" id="kr-cari" hidden>
        <form action="{{ site_url('/') }}" role="search" class="kr-wrap kr-search-form">
            <label for="kr-cari-input" class="kr-sr">Kata kunci</label>
            <input type="search" id="kr-cari-input" name="cari" value="{{ request()->get('cari') }}" placeholder="Cari berita, pengumuman, kegiatan">
            <button type="submit" class="kr-btn kr-btn-nila">Cari</button>
        </form>
    </div>
</div>
<div class="kr-anyaman" aria-hidden="true"></div>

@include('theme::commons.mobile_menu')

@if ($di_beranda)
    <header class="kr-hero" @if ($bg_header) style="--kr-hero-img: url('{{ $bg_header }}')" @endif>
        <div class="kr-wrap kr-hero-inner">
            <div class="kr-hero-text">
                <p class="kr-hero-kicker">Situs resmi Pemerintah {{ $nama_desa_lengkap }}</p>
                <h1 class="kr-hero-title">
                    <span class="kr-hero-sebutan">{{ $sebutan_desa }}</span>
                    <span class="kr-hero-nama">{{ ucwords($desa['nama_desa']) }}</span>
                </h1>
                <p class="kr-hero-region">{{ $wilayah }}, {{ $provinsi }}</p>
                <form action="{{ site_url('/') }}" role="search" class="kr-hero-search">
                    <label for="kr-hero-cari" class="kr-sr">Cari di situs ini</label>
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input type="search" id="kr-hero-cari" name="cari" placeholder="Cari berita, pengumuman, kegiatan">
                    <button type="submit" class="kr-btn kr-btn-udang">Cari</button>
                </form>
            </div>
            <div class="kr-tampah" aria-hidden="true">
                <div class="kr-tampah-rim"></div>
                <div class="kr-tampah-face">
                    <img src="{{ gambar_desa($desa['logo']) }}" alt="" width="180" height="180">
                </div>
            </div>
        </div>
    </header>

    @if ($layanan_cepat)
        <nav class="kr-wrap kr-quick" aria-label="Layanan untuk warga">
            <ul class="kr-quick-list" style="--kr-quick-n: {{ count($layanan_cepat) }}">
                @foreach ($layanan_cepat as [$label, $icon, $link, $ket])
                    <li>
                        <a href="{{ $link }}" class="kr-quick-item">
                            <span class="kr-kerupuk"><i class="{{ $icon }}" aria-hidden="true"></i></span>
                            <span class="kr-quick-label">{{ $label }}</span>
                            <span class="kr-quick-desc">{{ $ket }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    @endif
@else
    <header class="kr-pagehead">
        <div class="kr-wrap kr-pagehead-inner">
            <span class="kr-pagehead-name">{{ $nama_desa_lengkap }}</span>
            <span class="kr-pagehead-region">{{ $wilayah }}</span>
        </div>
    </header>
@endif
