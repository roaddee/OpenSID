{{-- Menu utama desktop: menu statis buatan admin (Admin Web → Menu), sampai 4 tingkat. --}}
@php
    $menu_atas = menu_tema() ?: [];
    // Menu yang tidak muat di satu baris dipindah ke "Lainnya" supaya tidak menabrak tombol Layanan Mandiri.
    // Lebar tiap menu diperkirakan dari panjang namanya (huruf 15px ≈ 8,3px, jarak 26px, panah 16px).
    // Anggaran 630px = ruang menu pada layar desktop terkecil (1200px) setelah logo & tombol kanan.
    $lebar_menu = static fn (array $m): float => mb_strlen(trim(strip_tags($m['nama']))) * 8.3 + 26 + (count($m['childrens'] ?? []) ? 16 : 0);
    $anggaran = 640 - $lebar_menu(['nama' => 'Beranda']);
    $total = array_sum(array_map($lebar_menu, $menu_atas));
    if ($total > $anggaran) {
        $anggaran -= $lebar_menu(['nama' => 'Lainnya', 'childrens' => [1]]);
        $muat = [];
        foreach ($menu_atas as $m) {
            if ($anggaran - $lebar_menu($m) < 0) {
                break;
            }
            $anggaran -= $lebar_menu($m);
            $muat[] = $m;
        }
        $menu_lain = array_slice($menu_atas, count($muat));
        $menu_atas = $muat;
        $menu_atas[] = ['nama' => 'Lainnya', 'link_url' => '#!', 'childrens' => $menu_lain];
    }
@endphp
<nav class="kr-menu" role="navigation" aria-label="Menu utama">
    <ul class="kr-menu-list">
        <li><a href="{{ site_url('/') }}" class="kr-menu-link">Beranda</a></li>
        @foreach ($menu_atas as $menu)
            @php $sub = $menu['childrens'] ?? [] @endphp
            <li class="kr-menu-item">
                <a href="{{ $sub ? '#!' : $menu['link_url'] }}" class="kr-menu-link" @if ($sub) aria-haspopup="true" @endif>
                    {!! $menu['nama'] !!}
                    @if ($sub)
                        <i class="fas fa-chevron-down kr-caret" aria-hidden="true"></i>
                    @endif
                </a>
                @if ($sub)
                    <ul class="kr-dropdown">
                        @foreach ($sub as $child)
                            @php $sub2 = $child['childrens'] ?? [] @endphp
                            <li class="kr-menu-item">
                                <a href="{{ $sub2 ? '#!' : $child['link_url'] }}" class="kr-dropdown-link">
                                    <span>{!! $child['nama'] !!}</span>
                                    @if ($sub2)
                                        <i class="fas fa-chevron-right kr-caret" aria-hidden="true"></i>
                                    @endif
                                </a>
                                @if ($sub2)
                                    <ul class="kr-dropdown kr-dropdown-side">
                                        @foreach ($sub2 as $grand)
                                            @php $sub3 = $grand['childrens'] ?? [] @endphp
                                            <li class="kr-menu-item">
                                                <a href="{{ $sub3 ? '#!' : $grand['link_url'] }}" class="kr-dropdown-link">
                                                    <span>{!! $grand['nama'] !!}</span>
                                                    @if ($sub3)
                                                        <i class="fas fa-chevron-right kr-caret" aria-hidden="true"></i>
                                                    @endif
                                                </a>
                                                @if ($sub3)
                                                    <ul class="kr-dropdown kr-dropdown-side">
                                                        @foreach ($sub3 as $great)
                                                            <li><a href="{{ $great['link_url'] }}" class="kr-dropdown-link"><span>{!! $great['nama'] !!}</span></a></li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
