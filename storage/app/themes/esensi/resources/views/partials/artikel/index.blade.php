@extends('theme::layouts.right-sidebar')
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
    @if ($tampil_slider)
        @include('theme::partials.slider')
    @endif

    @if ($halaman_awal && $headline)
        @include('theme::partials.headline')
    @endif

    <div class="es-section-head">
        <div>
            <h2 class="es-section-title">
                @if (!empty($cari))
                    Hasil pencarian “{{ $cari }}”
                @else
                    {{ $title }}
                @endif
            </h2>
            @if ($halaman_awal && empty($judul_kategori))
                <p class="es-section-sub">Berita, pengumuman, dan kegiatan terbaru dari {{ strtolower(setting('sebutan_desa')) }}.</p>
            @endif
        </div>
        <a href="{{ site_url('arsip') }}" class="es-link-more">Semua berita <i class="fas fa-chevron-right"></i></a>
    </div>

    @if ($halaman_awal && count($menu_kiri ?? []))
        <ul class="es-chips" aria-label="Kategori berita">
            @foreach ($menu_kiri as $kat)
                <li><a href="{{ site_url("{$alt_slug}/kategori/{$kat['slug']}") }}" class="es-chip">{{ $kat['kategori'] }}</a></li>
            @endforeach
        </ul>
    @endif

    @if ($artikel->count() > 0)
        <div class="es-post-grid">
            @foreach ($artikel as $post)
                @include('theme::partials.artikel.list', ['post' => $post])
            @endforeach
        </div>
        <div class="pagination es-pagination">
            @include('theme::commons.paging', ['paging_page' => $paging_page])
        </div>
    @else
        @include('theme::partials.artikel.empty', ['title' => $title])
    @endif
@endsection
