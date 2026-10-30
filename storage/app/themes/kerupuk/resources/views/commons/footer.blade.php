<div class="kr-wrap kr-apbdes">
    @includeWhen($transparansi, 'theme::partials.apbdesa', $transparansi)
</div>

@php
    $nama_desa = ucwords(setting('sebutan_desa')) . ' ' . ucwords($desa['nama_desa']);
    $social_media = [
        'facebook' => [
            'color' => 'bg-blue-600',
            'icon' => 'fa-facebook-f',
        ],
        'twitter' => [
            'color' => 'bg-blue-400',
            'icon' => 'fa-twitter',
        ],
        'instagram' => [
            'color' => 'bg-pink-500',
            'icon' => 'fa-instagram',
        ],
        'telegram' => [
            'color' => 'bg-blue-500',
            'icon' => 'fa-telegram',
        ],
        'whatsapp' => [
            'color' => 'bg-green-500',
            'icon' => 'fa-whatsapp',
        ],
        'youtube' => [
            'color' => 'bg-red-500',
            'icon' => 'fa-youtube',
        ],
    ];
@endphp

@foreach ($sosmed as $social)
    @if ($social['link'])
        @php
            $social_media[strtolower($social['nama'])]['link'] = $social['link'];
        @endphp
    @endif
@endforeach

@include('theme::commons.back_to_top')

<footer class="kr-footer">
    <div class="kr-anyaman" aria-hidden="true"></div>
    <div class="kr-wrap kr-footer-grid">
        <div class="kr-footer-id">
            <img src="{{ gambar_desa($desa['logo']) }}" alt="Logo {{ $nama_desa }}" class="kr-footer-logo" width="64" height="64">
            <p class="kr-footer-name">{{ $nama_desa }}</p>
            <p>{{ ucfirst(setting('sebutan_kecamatan')) }} {{ ucwords($desa['nama_kecamatan']) }}, {{ ucfirst(setting('sebutan_kabupaten')) }} {{ ucwords($desa['nama_kabupaten']) }}, {{ ucwords($desa['nama_propinsi']) }}</p>
        </div>

        <div>
            <h2 class="kr-footer-head">Kantor {{ ucwords(setting('sebutan_desa')) }}</h2>
            <ul class="kr-footer-list">
                @if ($desa['alamat_kantor'])
                    <li><i class="fas fa-map-marker-alt" aria-hidden="true"></i><span>{{ $desa['alamat_kantor'] }}@if ($desa['kode_pos']), {{ $desa['kode_pos'] }}@endif</span></li>
                @endif
                @if ($desa['telepon'])
                    <li><i class="fas fa-phone-alt" aria-hidden="true"></i><a href="tel:{{ preg_replace('/[^0-9+]/', '', $desa['telepon']) }}">{{ $desa['telepon'] }}</a></li>
                @endif
                @if ($desa['email_desa'])
                    <li><i class="fas fa-envelope" aria-hidden="true"></i><a href="mailto:{{ $desa['email_desa'] }}">{{ $desa['email_desa'] }}</a></li>
                @endif
            </ul>
        </div>

        <div>
            <h2 class="kr-footer-head">Jelajahi</h2>
            <ul class="kr-footer-links">
                <li><a href="{{ site_url('arsip') }}">Semua berita</a></li>
                <li><a href="{{ site_url('data-statistik') }}">Data statistik</a></li>
                <li><a href="{{ site_url('informasi-publik') }}">Informasi publik</a></li>
                <li><a href="{{ site_url('pengaduan') }}">Pengaduan warga</a></li>
                <li><a href="{{ site_url('peta') }}">Peta wilayah</a></li>
                <li><a href="{{ site_url('galeri') }}">Galeri</a></li>
            </ul>
        </div>

        <div>
            <h2 class="kr-footer-head">Ikuti kami</h2>
            @php $ada_sosmed = collect($social_media)->contains(fn ($sm) => !empty($sm['link'])) @endphp
            @if ($ada_sosmed)
                <ul class="kr-footer-social">
                    @foreach ($social_media as $nama_sm => $sm)
                        @if ($link = $sm['link'] ?? null)
                            <li><a href="{{ $link }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($nama_sm) }}"><i class="fab {{ $sm['icon'] }}"></i></a></li>
                        @endif
                    @endforeach
                </ul>
            @else
                <p class="kr-footer-muted">Media sosial {{ strtolower(setting('sebutan_desa')) }} belum ditautkan.</p>
            @endif
            @if (setting('tte'))
                <img src="{{ asset('assets/images/bsre.png?v', false) }}" alt="Didukung BSrE" class="kr-footer-bsre" />
            @endif
        </div>
    </div>

    <div class="kr-footer-bottom">
        <div class="kr-wrap kr-footer-bottom-inner">
            <p>&copy; {{ date('Y') }} {{ $nama_desa }}</p>
            <p>
                Tema Kerupuk {{ $themeVersion }} oleh <a href="https://satima.id" target="_blank" rel="noopener">Satima</a>,
                <a href="https://opensid.my.id" target="_blank" rel="noopener">OpenSID</a> v{{ ambilVersi() }}
                @if (file_exists('mitra'))
                    , hosting didukung
                    <a href="https://my.idcloudhost.com/aff.php?aff=3172" rel="noopener noreferrer" target="_blank">
                        <img src="{{ base_url('/assets/images/Logo-IDcloudhost.png') }}" class="kr-footer-mitra" alt="IDCloudHost">
                    </a>
                @endif
            </p>
        </div>
    </div>
</footer>

{{-- Dok bawah untuk HP: empat tujuan yang paling sering dicari warga --}}
<nav class="kr-dock" aria-label="Navigasi cepat">
    <a href="{{ site_url('/') }}" class="kr-dock-item {{ in_array((string) request()->segment(1), ['', 'first'], true) ? 'is-here' : '' }}"><i class="fas fa-home" aria-hidden="true"></i><span>Beranda</span></a>
    <a href="{{ site_url('arsip') }}" class="kr-dock-item {{ request()->segment(1) === 'arsip' ? 'is-here' : '' }}"><i class="far fa-newspaper" aria-hidden="true"></i><span>Berita</span></a>
    @if (setting('layanan_mandiri') == 1)
        <a href="{{ site_url('layanan-mandiri/masuk') }}" class="kr-dock-item kr-dock-utama"><i class="fas fa-id-card" aria-hidden="true"></i><span>Mandiri</span></a>
    @else
        <button type="button" class="kr-dock-item" data-kr-toggle="cari" aria-controls="kr-cari"><i class="fas fa-search" aria-hidden="true"></i><span>Cari</span></button>
    @endif
    <button type="button" class="kr-dock-item" data-kr-toggle="laci" aria-controls="kr-laci"><i class="fas fa-bars" aria-hidden="true"></i><span>Menu</span></button>
</nav>
