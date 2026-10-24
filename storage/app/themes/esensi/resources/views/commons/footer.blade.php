<div class="es-wrap es-apbdes">
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

<footer class="es-footer">
    <div class="es-footer-terrace" aria-hidden="true"></div>
    <div class="es-wrap es-footer-grid">
        <div class="es-footer-id">
            <img src="{{ gambar_desa($desa['logo']) }}" alt="Logo {{ $nama_desa }}" class="es-footer-logo">
            <div>
                <p class="es-footer-name">{{ $nama_desa }}</p>
                <p>{{ ucfirst(setting('sebutan_kecamatan')) }} {{ ucwords($desa['nama_kecamatan']) }}, {{ ucfirst(setting('sebutan_kabupaten')) }} {{ ucwords($desa['nama_kabupaten']) }}</p>
                <p>{{ ucwords($desa['nama_propinsi']) }}</p>
            </div>
        </div>

        <div>
            <p class="es-footer-head">Kantor {{ ucwords(setting('sebutan_desa')) }}</p>
            <ul class="es-footer-list">
                @if ($desa['alamat_kantor'])
                    <li><i class="fas fa-map-marker-alt"></i><span>{{ $desa['alamat_kantor'] }}@if ($desa['kode_pos']), {{ $desa['kode_pos'] }}@endif</span></li>
                @endif
                @if ($desa['telepon'])
                    <li><i class="fas fa-phone-alt"></i><a href="tel:{{ preg_replace('/[^0-9+]/', '', $desa['telepon']) }}">{{ $desa['telepon'] }}</a></li>
                @endif
                @if ($desa['email_desa'])
                    <li><i class="fas fa-envelope"></i><a href="mailto:{{ $desa['email_desa'] }}">{{ $desa['email_desa'] }}</a></li>
                @endif
            </ul>
        </div>

        <div>
            <p class="es-footer-head">Jelajahi</p>
            <ul class="es-footer-links">
                <li><a href="{{ site_url('arsip') }}">Semua berita</a></li>
                <li><a href="{{ site_url('data-statistik') }}">Data statistik</a></li>
                <li><a href="{{ site_url('informasi-publik') }}">Informasi publik</a></li>
                <li><a href="{{ site_url('pengaduan') }}">Pengaduan warga</a></li>
                <li><a href="{{ site_url('peta') }}">Peta wilayah</a></li>
                <li><a href="{{ site_url('galeri') }}">Galeri</a></li>
            </ul>
        </div>

        <div>
            <p class="es-footer-head">Ikuti kami</p>
            @php $ada_sosmed = collect($social_media)->contains(fn ($sm) => !empty($sm['link'])) @endphp
            @if ($ada_sosmed)
                <ul class="es-footer-social">
                    @foreach ($social_media as $nama_sm => $sm)
                        @if ($link = $sm['link'] ?? null)
                            <li><a href="{{ $link }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($nama_sm) }}"><i class="fab {{ $sm['icon'] }}"></i></a></li>
                        @endif
                    @endforeach
                </ul>
            @else
                <p class="es-footer-muted">Belum ada media sosial yang ditautkan.</p>
            @endif
            @if (setting('tte'))
                <img src="{{ asset('assets/images/bsre.png?v', false) }}" alt="Didukung BSrE" class="es-footer-bsre" />
            @endif
        </div>
    </div>

    <div class="es-footer-bottom">
        <div class="es-wrap es-footer-bottom-inner">
            <p>&copy; {{ date('Y') }} {{ $nama_desa }}. Hak cipta dilindungi.</p>
            <p>
                Tema <a href="https://github.com/OpenSID/tema-esensi" target="_blank" rel="noopener">Esensi</a> {{ $themeVersion }},
                <a href="https://opensid.my.id" target="_blank" rel="noopener">OpenSID</a> v{{ ambilVersi() }}-perjuangan
                @if (file_exists('mitra'))
                    , hosting didukung
                    <a href="https://my.idcloudhost.com/aff.php?aff=3172" rel="noopener noreferrer" target="_blank">
                        <img src="{{ base_url('/assets/images/Logo-IDcloudhost.png') }}" class="es-footer-mitra" alt="IDCloudHost">
                    </a>
                @endif
            </p>
        </div>
    </div>
</footer>
