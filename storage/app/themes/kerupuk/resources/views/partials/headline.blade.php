@if ($headline)
    @php
        $abstrak_headline = potong_teks(html_entity_decode(strip_tags($headline['isi'])), 200);
        $ada_gambar = $headline['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $headline['gambar']);
    @endphp
    <article class="kr-utama {{ $ada_gambar ? '' : 'kr-utama-teks' }}">
        @if ($ada_gambar)
            <a href="{{ $headline->url_slug }}" class="kr-utama-media" tabindex="-1" aria-hidden="true">
                <img src="{{ AmbilFotoArtikel($headline['gambar'], 'sedang') }}" alt="">
            </a>
        @endif
        <div class="kr-utama-body">
            <span class="kr-utama-flag">{{ $label_utama ?? 'Berita utama' }}</span>
            <h2 class="kr-utama-title"><a href="{{ $headline->url_slug }}">{{ $headline['judul'] }}</a></h2>
            <p class="kr-utama-excerpt">{{ $abstrak_headline }}</p>
            <p class="kr-kabar-meta">{{ tgl_indo($headline['tgl_upload']) }}</p>
        </div>
    </article>
@endif
