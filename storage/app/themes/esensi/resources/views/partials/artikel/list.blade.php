@php
    $url = $post->url_slug;
    $abstract = potong_teks(html_entity_decode(strip_tags($post['isi'])), 160);
    $ada_gambar = $post['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $post['gambar']);
    $image = $ada_gambar ? AmbilFotoArtikel($post['gambar'], 'sedang') : gambar_desa($desa['logo']);
    $kategori = $post['kategori'] ? $post['category']['kategori'] ?? null : null;
@endphp

<article class="es-post">
    <a href="{{ $url }}" class="es-post-media {{ $ada_gambar ? '' : 'is-logo' }}" tabindex="-1" aria-hidden="true">
        <img src="{{ $image }}" alt="" loading="lazy">
    </a>
    <div class="es-post-body">
        @if ($kategori)
            <span class="es-post-cat">{{ $kategori }}</span>
        @endif
        <h3 class="es-post-title"><a href="{{ $url }}">{{ $post['judul'] }}</a></h3>
        <p class="es-post-excerpt">{{ $abstract }}</p>
        <p class="es-post-meta">
            <span><i class="far fa-calendar"></i> {{ tgl_indo($post['tgl_upload']) }}</span>
            @if ($post['owner'])
                <span><i class="far fa-user"></i> {{ $post['owner'] }}</span>
            @endif
        </p>
    </div>
</article>
