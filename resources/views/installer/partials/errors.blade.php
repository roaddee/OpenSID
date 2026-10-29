{{--
    Pesan galat installer dari flashdata `errors` (string atau array).
    Kelas `alert-danger` sengaja dipertahankan: agent Panel Satima (opensid-setup) membaca pesan galat dari kelas ini.
--}}
@php
    $galat = $ci->session->errors;
    $galat = is_array($galat) ? array_filter($galat) : (filled($galat) ? [$galat] : []);
@endphp
@foreach ($galat as $pesan)
    <div class="sl-alert sl-alert-danger alert-danger si-error" role="alert">
        @include('admin.layouts.components.ikon', ['n' => 'alert'])
        <p>{!! $pesan !!}</p>
    </div>
@endforeach
