{{-- jQuery dimuat di commons/meta. Pustaka berat (peta, tabel, galeri, Alpine) hanya di halaman yang memakainya: commons/butuh. --}}
@stack('kr_js')
@include('core::admin.layouts.components.token')

@if (!setting('inspect_element'))
    <script src="{{ asset('js/disabled.min.js') }}" defer></script>
@endif
