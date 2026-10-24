@php $sub = $item['childrens'] ?? [] @endphp
<li @if ($sub) x-data="{ open: false }" @endif>
    @if ($sub)
        <button type="button" class="es-drawer-link es-lvl-{{ $level }}" @click="open = !open" :aria-expanded="open">
            <span>{!! $item['nama'] !!}</span>
            <i class="fas fa-chevron-down es-caret" :class="{ 'is-flipped': open }"></i>
        </button>
        <ul class="es-drawer-sub" x-show="open" x-transition x-cloak>
            @foreach ($sub as $child)
                @include('theme::commons.mobile_menu_item', ['item' => $child, 'level' => min($level + 1, 4)])
            @endforeach
        </ul>
    @else
        <a href="{{ $item['link_url'] }}" class="es-drawer-link es-lvl-{{ $level }}">{!! $item['nama'] !!}</a>
    @endif
</li>
