{{-- Beranda Kerupuk: pita-pita lebar penuh tanpa sidebar. Widget pindah ke pita "Info desa" di bawah. --}}
@extends('theme::template')

@section('layout')
    <main id="konten" class="kr-beranda">
        @yield('content')
    </main>
    @php
        $widget_tampil = collect($widgetAktif ?: [])
            ->filter(static fn ($w) => view()->exists("theme::widgets.{$w['isi']}"))
            ->unique(static fn ($w) => $w['isi'])
            // perangkat desa sudah tampil sebagai pita sendiri di atas
            ->reject(static fn ($w) => $w['isi'] === 'aparatur_desa');
    @endphp
    @include('theme::partials.perangkat')
    @if ($widget_tampil->count())
        <section class="kr-info" aria-labelledby="kr-info-judul">
            <div class="kr-wrap">
                <h2 class="kr-pita-judul" id="kr-info-judul">Info {{ strtolower(setting('sebutan_desa')) }}</h2>
                <div class="kr-info-kolom sidebar" data-n="{{ $widget_tampil->count() }}">
                    @foreach ($widget_tampil as $widget)
                        <section class="kr-widget">
                            @include("theme::widgets.{$widget['isi']}", ['judul_widget' => str_replace('Desa', ucwords(setting('sebutan_desa')), strip_tags($widget['judul']))])
                        </section>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
