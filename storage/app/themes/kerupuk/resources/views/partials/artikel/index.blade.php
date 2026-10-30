@extends($halaman_awal ? 'theme::layouts.beranda' : 'theme::layouts.right-sidebar')
@php
    $title = !empty($judul_kategori) ? $judul_kategori : 'Kabar terbaru';
    $slug = 'terkini';
    if (is_array($title)) {
        $slug = $title['slug'];
        $title = $title['kategori'];
    }
    $alt_slug = PREMIUM ? 'artikel' : 'first';
    // Slider & sorotan hanya di halaman awal: tidak di halaman kategori, halaman berikutnya, atau hasil pencarian
    $halaman_awal = empty($cari) && request()->segment(2) != 'kategori' && request()->segment(2) !== 'index' && request()->segment(1) !== 'index';
    $tampil_slider = $halaman_awal && count($slider_gambar ?? []) > 0;
@endphp
@section('content')
    @if ($halaman_awal)
        @php
            // Blok kabar: berita utama (atau artikel terbaru bila tidak ada) besar di kiri, 4 berikutnya berbaris di kanan
            $utama = $headline ?: $artikel->first();
            $deret = $artikel->reject(fn ($a) => $a['id'] == $utama['id'])->take(4);
            $sisa = $artikel->reject(fn ($a) => $a['id'] == $utama['id'])->slice(4);
        @endphp
        <section class="kr-wrap kr-pita">
            <div class="kr-section-head">
                <h2 class="kr-section-title">Kabar {{ strtolower(setting('sebutan_desa')) }}</h2>
                <a href="{{ site_url('arsip') }}" class="kr-link-more">Semua berita</a>
            </div>
            <div class="kr-kabar-blok">
                @include('theme::partials.headline', ['headline' => $utama, 'label_utama' => $headline ? 'Berita utama' : 'Terbaru'])
                <div class="kr-kabar kr-kabar-ringkas">
                    @foreach ($deret as $post)
                        @include('theme::partials.artikel.list', ['post' => $post])
                    @endforeach
                </div>
            </div>
            @if (count($menu_kiri ?? []))
                <ul class="kr-chips kr-chips-bawah" aria-label="Kategori berita">
                    @foreach ($menu_kiri as $kat)
                        <li><a href="{{ site_url("{$alt_slug}/kategori/{$kat['slug']}") }}" class="kr-chip">{{ $kat['kategori'] }}</a></li>
                    @endforeach
                </ul>
            @endif
        </section>

        @if ($tampil_slider)
            <section class="kr-pita kr-pita-foto" aria-labelledby="kr-foto-judul">
                <div class="kr-wrap">
                    <h2 class="kr-pita-judul" id="kr-foto-judul">Sorotan</h2>
                </div>
                @include('theme::partials.slider')
            </section>
        @endif
    @else
    <div class="kr-section-head">
        <h2 class="kr-section-title">
            @if (!empty($cari))
                Hasil pencarian “{{ $cari }}”
            @else
                {{ $title }}
            @endif
        </h2>
        <a href="{{ site_url('arsip') }}" class="kr-link-more">Semua berita</a>
    </div>

    @if ($artikel->count() > 0)
        <div class="kr-kabar">
            @foreach ($artikel as $post)
                @include('theme::partials.artikel.list', ['post' => $post])
            @endforeach
        </div>
        <div class="pagination kr-pagination">
            @include('theme::commons.paging', ['paging_page' => $paging_page])
        </div>
    @else
        @include('theme::partials.artikel.empty', ['title' => $title])
    @endif
    @endif
@endsection
