{{-- Highcharts (±400 KB) cukup dimuat sekali per halaman walau beberapa widget/bagian memintanya --}}
@once
@push('scripts')
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-3d.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-more.js') }}"></script>
    <script src="{{ asset('js/highcharts/sankey.js') }}"></script>
    <script src="{{ asset('js/highcharts/organization.js') }}"></script>
    <script src="{{ asset('js/highcharts/accessibility.js') }}"></script>
    <script>
        Highcharts.setOptions({
            lang: {
                thousandsSep: '.'
            }
        })
    </script>
@endpush
@endonce
