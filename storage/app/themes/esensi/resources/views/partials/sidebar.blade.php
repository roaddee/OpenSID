@php
    // Widget yang sama bisa tercatat dua kali di tabel `widget` (sisa migrasi/impor lama) sehingga tampil ganda.
    // Tampilkan sekali saja (urutan pertama), dan lewati widget yang berkas tampilannya tidak ada di tema ini
    // agar tidak muncul kotak kosong (perilaku sama dengan @includeIf bawaan).
    $widget_tampil = collect($widgetAktif ?: [])
        ->filter(static fn ($w) => view()->exists("theme::widgets.{$w['isi']}"))
        ->unique(static fn ($w) => $w['isi']);
@endphp
<aside class="es-sidebar sidebar">
    <form action="{{ site_url('/') }}" role="search" class="es-side-search">
        <i class="fas fa-search"></i>
        <input type="search" name="cari" class="form-input" placeholder="Cari berita…" aria-label="Cari berita">
    </form>
    @foreach ($widget_tampil as $widget)
        @php
            $judul_widget = [
                'judul_widget' => str_replace('Desa', ucwords(setting('sebutan_desa')), strip_tags($widget['judul'])),
            ];
        @endphp
        <div class="es-widget">
            @include("theme::widgets.{$widget['isi']}", $judul_widget)
        </div>
    @endforeach
</aside>
