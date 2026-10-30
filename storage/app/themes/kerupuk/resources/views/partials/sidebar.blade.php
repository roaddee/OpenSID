@php
    // Widget yang sama bisa tercatat dua kali di tabel `widget` (sisa migrasi/impor lama) sehingga tampil ganda.
    // Tampilkan sekali saja (urutan pertama), dan lewati widget yang berkas tampilannya tidak ada di tema ini.
    $widget_tampil = collect($widgetAktif ?: [])
        ->filter(static fn ($w) => view()->exists("theme::widgets.{$w['isi']}"))
        ->unique(static fn ($w) => $w['isi']);
@endphp
<aside class="kr-sidebar sidebar" aria-label="Informasi tambahan">
    @foreach ($widget_tampil as $widget)
        @php
            $judul_widget = [
                'judul_widget' => str_replace('Desa', ucwords(setting('sebutan_desa')), strip_tags($widget['judul'])),
            ];
        @endphp
        <section class="kr-widget">
            @include("theme::widgets.{$widget['isi']}", $judul_widget)
        </section>
    @endforeach
</aside>
