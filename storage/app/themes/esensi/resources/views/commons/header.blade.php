@php
    $bg_header = $latar_website;
    $sebutan_desa = ucwords(setting('sebutan_desa'));
    $nama_desa_lengkap = $sebutan_desa . ' ' . ucwords($desa['nama_desa']);
    $wilayah = ucfirst(setting('sebutan_kecamatan')) . ' ' . ucwords($desa['nama_kecamatan']) . ', ' . ucfirst(setting('sebutan_kabupaten')) . ' ' . ucwords($desa['nama_kabupaten']) . ', ' . ucwords($desa['nama_propinsi']);
    $di_beranda = in_array((string) request()->segment(1), ['', 'first'], true) && ! request()->get('cari');
    // Layanan cepat hanya untuk halaman yang menunya sudah aktif (Admin Web → Menu); halaman lain menampilkan "Menu Belum Aktif".
    $menu_aktif = \App\Models\Menu::active()->pluck('link')->all();
    $layanan_cepat = [];
    if (setting('layanan_mandiri') == 1) {
        $layanan_cepat[] = ['Layanan Mandiri', 'fas fa-id-card', site_url('layanan-mandiri/masuk'), 'Surat & data warga'];
    }
    foreach ([
        ['pengaduan', 'Pengaduan', 'fas fa-bullhorn', 'Sampaikan keluhan'],
        ['peta', 'Peta ' . $sebutan_desa, 'fas fa-map-marked-alt', 'Wilayah & lokasi'],
        ['data-wilayah', 'Data Wilayah', 'fas fa-chart-pie', 'Dusun, RW, RT'],
        ['informasi_publik', 'Informasi Publik', 'fas fa-folder-open', 'Dokumen terbuka'],
        ['peraturan-desa', 'Produk Hukum', 'fas fa-balance-scale', 'Peraturan ' . strtolower($sebutan_desa)],
        ['pembangunan', 'Pembangunan', 'fas fa-hard-hat', 'Proyek berjalan'],
        ['lapak', 'Lapak', 'fas fa-store', 'Produk warga'],
        ['galeri', 'Galeri', 'fas fa-images', 'Foto kegiatan'],
        ['pemerintah', 'Pemerintahan', 'fas fa-landmark', 'Perangkat ' . strtolower($sebutan_desa)],
    ] as [$tautan, $label, $icon, $ket]) {
        if (count($layanan_cepat) < 8 && in_array($tautan, $menu_aktif, true)) {
            $layanan_cepat[] = [$label, $icon, site_url(str_replace('_', '-', $tautan)), $ket];
        }
    }
@endphp

@if ($teks_berjalan)
    <div class="es-ticker">
        <div class="es-wrap es-ticker-inner">
            <span class="es-ticker-label"><i class="fas fa-bullhorn"></i> Kabar</span>
            <marquee onmouseover="this.stop();" onmouseout="this.start();" scrollamount="4" class="es-ticker-text">
                @foreach ($teks_berjalan as $marquee)
                    <span class="es-ticker-item">
                        {{ $marquee['teks'] }}
                        @if (trim($marquee['tautan']) && $marquee['judul_tautan'])
                            <a href="{{ $marquee['tautan'] }}">{{ $marquee['judul_tautan'] }}</a>
                        @endif
                    </span>
                @endforeach
            </marquee>
        </div>
    </div>
@endif

<div class="es-navbar" x-data="{ menuOpen: false, searchOpen: false }" @keydown.escape.window="menuOpen = false; searchOpen = false">
    <div class="es-wrap es-navbar-inner">
        <a href="{{ site_url('/') }}" class="es-brand" aria-label="Beranda {{ $nama_desa_lengkap }}">
            <img src="{{ gambar_desa($desa['logo']) }}" alt="Logo {{ $nama_desa_lengkap }}" class="es-brand-logo">
            <span class="es-brand-text">
                <span class="es-brand-name">{{ $nama_desa_lengkap }}</span>
                <span class="es-brand-sub">{{ ucfirst(setting('sebutan_kabupaten')) }} {{ ucwords($desa['nama_kabupaten']) }}</span>
            </span>
        </a>

        @include('theme::commons.main_menu')

        <div class="es-navbar-actions">
            <button type="button" class="es-icon-btn" @click="searchOpen = !searchOpen" :aria-expanded="searchOpen" aria-label="Cari berita">
                <i class="fas fa-search"></i>
            </button>
            @if (setting('layanan_mandiri') == 1)
                <a href="{{ site_url('layanan-mandiri/masuk') }}" class="es-btn es-btn-primary es-hide-sm"><i class="fas fa-id-card"></i> Layanan Mandiri</a>
            @endif
            <a href="{{ site_url('siteman') }}" class="es-icon-btn es-hide-sm" title="Masuk sebagai admin" aria-label="Masuk sebagai admin"><i class="fas fa-user-shield"></i></a>
            <button type="button" class="es-icon-btn es-show-lg-down" @click="menuOpen = true" aria-label="Buka menu"><i class="fas fa-bars"></i></button>
        </div>
    </div>

    <div class="es-search-panel" x-show="searchOpen" x-transition.opacity x-cloak>
        <form action="{{ site_url('/') }}" role="search" class="es-wrap es-search-form">
            <i class="fas fa-search"></i>
            <input type="search" name="cari" value="{{ request()->get('cari') }}" placeholder="Cari berita, pengumuman, kegiatan…" aria-label="Kata kunci" x-ref="cari" x-init="$watch('searchOpen', v => v && $nextTick(() => $refs.cari.focus()))">
            <button type="submit" class="es-btn es-btn-primary">Cari</button>
        </form>
    </div>

    @include('theme::commons.mobile_menu')
</div>

@if ($di_beranda)
    <header class="es-hero {{ $layanan_cepat ? '' : 'es-hero-solo' }}" style="--hero-img: url('{{ $bg_header }}')">
        <div class="es-hero-terrace" aria-hidden="true"></div>
        <div class="es-wrap es-hero-inner">
            <img src="{{ gambar_desa($desa['logo']) }}" alt="" class="es-hero-logo" aria-hidden="true">
            <h1 class="es-hero-title">{{ $nama_desa_lengkap }}</h1>
            <p class="es-hero-region">{{ $wilayah }}</p>
            @if ($desa['alamat_kantor'])
                <p class="es-hero-address"><i class="fas fa-map-marker-alt"></i> {{ $desa['alamat_kantor'] }}@if ($desa['kode_pos']), {{ $desa['kode_pos'] }}@endif</p>
            @endif
        </div>
    </header>

    @if ($layanan_cepat)
    <nav class="es-wrap es-quick" aria-label="Layanan cepat">
        <ul class="es-quick-grid" @php $n_cepat = count($layanan_cepat) @endphp style="--es-quick-sm: {{ min(4, $n_cepat) }}; --es-quick-lg: {{ $n_cepat <= 4 ? $n_cepat : (int) ceil($n_cepat / 2) }}">
            @foreach ($layanan_cepat as [$label, $icon, $link, $ket])
                <li>
                    <a href="{{ $link }}" class="es-quick-item">
                        <span class="es-quick-icon"><i class="{{ $icon }}"></i></span>
                        <span class="es-quick-label">{{ $label }}</span>
                        <span class="es-quick-desc">{{ $ket }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
    @endif
@else
    <header class="es-pagehead" style="--hero-img: url('{{ $bg_header }}')">
        <div class="es-hero-terrace" aria-hidden="true"></div>
        <div class="es-wrap es-pagehead-inner">
            <span class="es-pagehead-name">{{ $nama_desa_lengkap }}</span>
            <span class="es-pagehead-region">{{ $wilayah }}</span>
        </div>
    </header>
@endif
