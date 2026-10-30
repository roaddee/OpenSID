@php $sub = $item['childrens'] ?? [] @endphp
<li>
    @if ($sub)
        <button type="button" class="kr-laci-link kr-lvl-{{ $level }}" data-kr-sub aria-expanded="false">
            <span>{!! $item['nama'] !!}</span>
            <i class="fas fa-chevron-down kr-caret" aria-hidden="true"></i>
        </button>
        <ul class="kr-laci-sub" hidden>
            @foreach ($sub as $child)
                @include('theme::commons.mobile_menu_item', ['item' => $child, 'level' => min($level + 1, 4)])
            @endforeach
        </ul>
    @else
        <a href="{{ $item['link_url'] }}" class="kr-laci-link kr-lvl-{{ $level }}">{!! $item['nama'] !!}</a>
    @endif
</li>
