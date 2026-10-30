@php
    $url = $post->url_slug;
    $abstract = potong_teks(html_entity_decode(strip_tags($post['isi'])), 150);
    $ada_gambar = $post['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'kecil_' . $post['gambar']);
    $kategori = $post['kategori'] ? $post['category']['kategori'] ?? null : null;
    $waktu = strtotime($post['tgl_upload']);
    $bulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
@endphp

<article class="kr-kabar-item">
    <time class="kr-tanggal" datetime="{{ date('Y-m-d', $waktu) }}">
        <span class="kr-tanggal-hari">{{ date('j', $waktu) }}</span>
        <span class="kr-tanggal-bulan">{{ $bulan[(int) date('n', $waktu)] }} {{ date('Y', $waktu) }}</span>
    </time>
    <div class="kr-kabar-body">
        @if ($kategori)
            <span class="kr-kabar-cat">{{ $kategori }}</span>
        @endif
        <h3 class="kr-kabar-title"><a href="{{ $url }}">{{ $post['judul'] }}</a></h3>
        <p class="kr-kabar-excerpt">{{ $abstract }}</p>
        @if ($post['owner'])
            <p class="kr-kabar-meta">Ditulis {{ $post['owner'] }}</p>
        @endif
    </div>
    @if ($ada_gambar)
        <a href="{{ $url }}" class="kr-kabar-thumb" tabindex="-1" aria-hidden="true">
            <img src="{{ AmbilFotoArtikel($post['gambar'], 'kecil') }}" alt="" loading="lazy" width="160" height="120">
        </a>
    @endif
</article>
