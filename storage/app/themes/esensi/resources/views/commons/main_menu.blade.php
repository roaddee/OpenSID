{{-- Menu utama desktop: menu statis buatan admin (Admin Web → Menu), sampai 4 tingkat. --}}
@php
    $menu_atas = menu_tema() ?: [];
    // Menu yang tidak muat di satu baris dipindah ke "Lainnya"
    $batas_menu = 6;
    if (count($menu_atas) > $batas_menu + 1) {
        $menu_lain = array_slice($menu_atas, $batas_menu);
        $menu_atas = array_slice($menu_atas, 0, $batas_menu);
        $menu_atas[] = ['nama' => 'Lainnya', 'link_url' => '#!', 'childrens' => $menu_lain];
    }
@endphp
<nav class="es-menu" role="navigation" aria-label="Menu utama">
    <ul class="es-menu-list">
        <li><a href="{{ site_url('/') }}" class="es-menu-link">Beranda</a></li>
        @foreach ($menu_atas as $menu)
            @php $sub = $menu['childrens'] ?? [] @endphp
            <li class="es-menu-item">
                <a href="{{ $sub ? '#!' : $menu['link_url'] }}" class="es-menu-link" @if ($sub) aria-haspopup="true" @endif>
                    {!! $menu['nama'] !!}
                    @if ($sub)
                        <i class="fas fa-chevron-down es-caret"></i>
                    @endif
                </a>
                @if ($sub)
                    <ul class="es-dropdown">
                        @foreach ($sub as $child)
                            @php $sub2 = $child['childrens'] ?? [] @endphp
                            <li class="es-menu-item">
                                <a href="{{ $sub2 ? '#!' : $child['link_url'] }}" class="es-dropdown-link">
                                    <span>{!! $child['nama'] !!}</span>
                                    @if ($sub2)
                                        <i class="fas fa-chevron-right es-caret"></i>
                                    @endif
                                </a>
                                @if ($sub2)
                                    <ul class="es-dropdown es-dropdown-side">
                                        @foreach ($sub2 as $grand)
                                            @php $sub3 = $grand['childrens'] ?? [] @endphp
                                            <li class="es-menu-item">
                                                <a href="{{ $sub3 ? '#!' : $grand['link_url'] }}" class="es-dropdown-link">
                                                    <span>{!! $grand['nama'] !!}</span>
                                                    @if ($sub3)
                                                        <i class="fas fa-chevron-right es-caret"></i>
                                                    @endif
                                                </a>
                                                @if ($sub3)
                                                    <ul class="es-dropdown es-dropdown-side">
                                                        @foreach ($sub3 as $great)
                                                            <li><a href="{{ $great['link_url'] }}" class="es-dropdown-link"><span>{!! $great['nama'] !!}</span></a></li>
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
