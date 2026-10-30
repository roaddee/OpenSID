<link rel="preload" href="{{ kr_asset('fonts/bricolage.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ kr_asset('fonts/figtree.woff2') }}" as="font" type="font/woff2" crossorigin>
<style>
    @font-face { font-family: 'Bricolage Kerupuk'; font-style: normal; font-weight: 200 800; font-display: swap; src: url('{{ kr_asset('fonts/bricolage.woff2') }}') format('woff2'); }
    @font-face { font-family: 'Figtree Kerupuk'; font-style: normal; font-weight: 300 900; font-display: swap; src: url('{{ kr_asset('fonts/figtree.woff2') }}') format('woff2'); }
</style>
<link rel="stylesheet" href="{{ kr_asset('css/style.min.css') }}">
<link rel="stylesheet" href="{{ kr_asset('css/kerupuk.css') }}">
@if (kr_online())
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    {{-- ikon: tidak menahan tampilan halaman; ikon muncul begitu berkasnya tiba --}}
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"></noscript>
@endif
@stack('kr_css')
