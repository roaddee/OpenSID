@php
    $themeVersion = 'v2610.1.0';
    if (! function_exists('kr_asset')) {
        /**
         * URL aset tema Kerupuk. Tema di desa/themes dilayani langsung oleh server web (berkas statis, bisa di-cache browser),
         * jauh lebih cepat daripada lewat theme_asset() yang menyalakan PHP untuk setiap berkas. Tema di lokasi lain
         * (mis. storage/app/themes) tetap lewat theme_asset().
         */
        function kr_asset(string $file): string
        {
            $tema = theme_active();
            if (str_starts_with($tema->path, 'desa/') && is_file(FCPATH . $tema->path . '/assets/' . $file)) {
                return base_url($tema->path . '/assets/' . $file) . '?v=2610.1.0';
            }

            return theme_asset($file, ['themeVersion' => 'v2610.1.0']);
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1f2a6b">
    @include('theme::commons.meta')
    @include('theme::commons.source_css')
    @include('theme::commons.source_js')
    <title>@yield('title')</title>
    @stack('styles')
</head>

<body class="font-primary kr-body">
    <a href="#konten" class="kr-skip">Langsung ke isi</a>
    @include('theme::commons.header')

    @yield('layout')

    @include('theme::commons.footer')

    <script src="{{ kr_asset('js/script.min.js') }}"></script>
    <script src="{{ kr_asset('js/kerupuk.js') }}" defer></script>
    <script type="text/javascript">
        function formatRupiah(angka, prefix = 'Rp ') {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '') + ',00';
        }
    </script>
    @stack('scripts')
</body>

</html>
