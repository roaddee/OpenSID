<aside class="es-sidebar sidebar">
    <form action="{{ site_url('/') }}" role="search" class="es-side-search">
        <i class="fas fa-search"></i>
        <input type="search" name="cari" class="form-input" placeholder="Cari berita…" aria-label="Cari berita">
    </form>
    @if ($widgetAktif)
        @foreach ($widgetAktif as $widget)
            @php
                $judul_widget = [
                    'judul_widget' => str_replace('Desa', ucwords(setting('sebutan_desa')), strip_tags($widget['judul'])),
                ];
            @endphp
            <div class="es-widget">
                @includeIf("theme::widgets.{$widget['isi']}", $judul_widget)
            </div>
        @endforeach
    @endif
</aside>
