{{--
    Pustaka berat hanya dimuat di halaman yang memakainya, tidak di semua halaman.
    Pakai: @include('theme::commons.butuh', ['pustaka' => ['peta', 'tabel', 'galeri', 'alpine']])
    Harus dipanggil dari view halaman/widget (yang dirender sebelum <head>), bukan dari header/footer.
--}}
@include('theme::commons.kr_fungsi')
@php $pustaka = (array) ($pustaka ?? []); @endphp

@if (in_array('peta', $pustaka, true))
    @once
        @push('kr_css')
            @if (kr_online())
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css">
                @if (setting('mapbox_key'))
                    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.css">
                @endif
            @endif
        @endpush
        @push('kr_js')
            @if (kr_online())
                <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-providers/1.6.0/leaflet-providers.min.js"></script>
                @if (setting('mapbox_key'))
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl/1.13.0/mapbox-gl.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl-leaflet/0.0.14/leaflet-mapbox-gl.min.js"></script>
                @endif
            @endif
            <script src="{{ asset('js/peta.js') }}"></script>
        @endpush
    @endonce
@endif

@if (in_array('tabel', $pustaka, true))
    @once
        @push('kr_css')
            @if (kr_online())
                <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
            @endif
        @endpush
        @push('kr_js')
            @if (kr_online())
                <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
            @endif
            <script>
                if (window.jQuery && $.fn.dataTable) {
                    $.extend($.fn.dataTable.defaults, {
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
                        pageLength: 10,
                        language: { url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}" }
                    });
                }
            </script>
        @endpush
    @endonce
@endif

@if (in_array('galeri', $pustaka, true))
    @once
        @push('kr_css')
            @if (kr_online())
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css">
            @endif
        @endpush
        @push('kr_js')
            @if (kr_online())
                <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js" defer></script>
            @endif
        @endpush
    @endonce
@endif

@if (in_array('alpine', $pustaka, true))
    @once
        @push('kr_js')
            @if (kr_online())
                <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
            @endif
        @endpush
    @endonce
@endif
