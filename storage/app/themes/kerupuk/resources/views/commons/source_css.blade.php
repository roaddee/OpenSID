<link rel="preload" href="{{ kr_asset('fonts/bricolage.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ kr_asset('fonts/figtree.woff2') }}" as="font" type="font/woff2" crossorigin>
<style>
    @font-face { font-family: 'Bricolage Kerupuk'; font-style: normal; font-weight: 200 800; font-display: swap; src: url('{{ kr_asset('fonts/bricolage.woff2') }}') format('woff2'); }
    @font-face { font-family: 'Figtree Kerupuk'; font-style: normal; font-weight: 300 900; font-display: swap; src: url('{{ kr_asset('fonts/figtree.woff2') }}') format('woff2'); }
</style>
@if (cek_koneksi_internet())
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" media="print" onload="this.media='all'">
    @if (setting('mapbox_key'))
        <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.css">
    @endif
@endif
<link rel="stylesheet" href="{{ kr_asset('css/style.min.css') }}">
<link rel="stylesheet" href="{{ kr_asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ kr_asset('css/kerupuk.css') }}">
