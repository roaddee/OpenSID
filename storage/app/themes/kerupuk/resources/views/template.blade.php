@php
    $themeVersion = 'v2610.2.2';
@endphp
@include('theme::commons.kr_fungsi')
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1f4e79">
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

    <script src="{{ kr_asset('js/script.min.js') }}" defer></script>
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
