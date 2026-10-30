{{-- Laci menu layar kecil: dibuka tombol menu di navbar atau dok bawah. Tanpa Alpine (kerupuk.js). --}}
@php
    $menu_atas = menu_tema();
    $alt_slug = PREMIUM ? 'artikel' : 'first';
@endphp
<div class="kr-laci-latar" data-kr-close="laci" hidden></div>
<aside class="kr-laci" id="kr-laci" aria-label="Menu" aria-hidden="true">
    <div class="kr-laci-head">
        <span class="kr-laci-title">Menu</span>
        <button type="button" class="kr-icon-btn" data-kr-close="laci" aria-label="Tutup menu"><i class="fas fa-times"></i></button>
    </div>
    <div class="kr-laci-body">
        <ul class="kr-laci-list">
            <li><a href="{{ site_url('/') }}" class="kr-laci-link">Beranda</a></li>
            @foreach ($menu_atas ?: [] as $menu)
                @include('theme::commons.mobile_menu_item', ['item' => $menu, 'level' => 1])
            @endforeach
        </ul>

        @if (count($menu_kiri ?? []))
            <p class="kr-laci-caption">Kategori berita</p>
            <ul class="kr-laci-list">
                @foreach ($menu_kiri as $kat)
                    <li><a href="{{ site_url("{$alt_slug}/kategori/{$kat['slug']}") }}" class="kr-laci-link">{{ $kat['kategori'] }}</a></li>
                    @foreach ($kat['submenu'] ?? [] as $submenu)
                        <li><a href="{{ site_url("{$alt_slug}/kategori/{$submenu['slug']}") }}" class="kr-laci-link kr-lvl-2">{{ $submenu['kategori'] }}</a></li>
                    @endforeach
                @endforeach
            </ul>
        @endif

        <div class="kr-laci-actions">
            @if (setting('layanan_mandiri') == 1)
                <a href="{{ site_url('layanan-mandiri/masuk') }}" class="kr-btn kr-btn-udang kr-btn-block">Layanan Mandiri</a>
            @endif
            <a href="{{ site_url('siteman') }}" class="kr-btn kr-btn-garis kr-btn-block">Masuk admin</a>
        </div>
    </div>
</aside>
