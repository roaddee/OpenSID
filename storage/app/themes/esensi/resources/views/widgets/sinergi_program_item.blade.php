@php
    $host = parse_url($item['tautan'] ?? '', PHP_URL_HOST);
    $host = $host ? preg_replace('/^www\./', '', $host) : null;
@endphp
<li>
    <a href="{{ $item['tautan'] }}" class="es-sinergi-item" target="_blank" rel="noopener" title="{{ $item['judul'] }}{{ $host ? ' (' . $host . ')' : '' }}">
        <span class="es-sinergi-logo">
            <img src="{{ $item['gambar_url'] }}" alt="Logo {{ $item['judul'] }}" loading="lazy">
        </span>
        <span class="es-sinergi-nama">{{ $item['judul'] }}</span>
    </a>
</li>
