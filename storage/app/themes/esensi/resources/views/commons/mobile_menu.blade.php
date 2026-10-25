{{-- Laci menu untuk layar kecil (dibuka tombol ☰ di navbar). --}}
@php
    $menu_atas = menu_tema();
    $alt_slug = PREMIUM ? 'artikel' : 'first';
@endphp
<div class="es-drawer-backdrop" x-show="menuOpen" x-transition.opacity x-cloak @click="menuOpen = false"></div>
<aside class="es-drawer" :class="{ 'is-open': menuOpen }" aria-label="Menu">
    <div class="es-drawer-head">
        <span class="es-drawer-title">Menu</span>
        <button type="button" class="es-icon-btn" @click="menuOpen = false" aria-label="Tutup menu"><i class="fas fa-times"></i></button>
    </div>
    <div class="es-drawer-body">
        <ul class="es-drawer-list">
            <li><a href="{{ site_url('/') }}" class="es-drawer-link">Beranda</a></li>
            @foreach ($menu_atas ?: [] as $menu)
                @include('theme::commons.mobile_menu_item', ['item' => $menu, 'level' => 1])
            @endforeach
        </ul>

        @if (count($menu_kiri ?? []))
            <p class="es-drawer-caption">Kategori berita</p>
            <ul class="es-drawer-list">
                @foreach ($menu_kiri as $kat)
                    <li><a href="{{ site_url("{$alt_slug}/kategori/{$kat['slug']}") }}" class="es-drawer-link">{{ $kat['kategori'] }}</a></li>
                    @foreach ($kat['submenu'] ?? [] as $submenu)
                        <li><a href="{{ site_url("{$alt_slug}/kategori/{$submenu['slug']}") }}" class="es-drawer-link es-lvl-2">{{ $submenu['kategori'] }}</a></li>
                    @endforeach
                @endforeach
            </ul>
        @endif

        <div class="es-drawer-actions">
            @if (setting('layanan_mandiri') == 1)
                <a href="{{ site_url('layanan-mandiri/masuk') }}" class="es-btn es-btn-primary es-btn-block"><i class="fas fa-id-card"></i> Layanan Mandiri</a>
            @endif
            <a href="{{ site_url('siteman') }}" class="es-btn es-btn-ghost es-btn-block"><i class="fas fa-user-shield"></i> Masuk Admin</a>
        </div>
    </div>
</aside>
