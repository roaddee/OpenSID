@if ($headline)
    @php
        $abstrak_headline = potong_teks(html_entity_decode(strip_tags($headline['isi'])), 220);
        $ada_gambar = $headline['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $headline['gambar']);
        $image = $ada_gambar ? AmbilFotoArtikel($headline['gambar'], 'sedang') : gambar_desa($desa['logo']);
    @endphp
    <article class="es-feature">
        <a href="{{ $headline->url_slug }}" class="es-feature-media {{ $ada_gambar ? '' : 'is-logo' }}" tabindex="-1" aria-hidden="true">
            <img src="{{ $image }}" alt="">
        </a>
        <div class="es-feature-body">
            <span class="es-feature-flag"><i class="fas fa-star"></i> Berita utama</span>
            <h2 class="es-feature-title"><a href="{{ $headline->url_slug }}">{{ $headline['judul'] }}</a></h2>
            <p class="es-feature-excerpt">{{ $abstrak_headline }}</p>
            <p class="es-post-meta"><span><i class="far fa-calendar"></i> {{ tgl_indo($headline['tgl_upload']) }}</span></p>
        </div>
    </article>
@endif
