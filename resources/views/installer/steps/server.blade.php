@extends('installer.install')

@section('langkah', 'server')

@section('step')
    @php
        $syarat = $ci->config->item('server');
        $gagal = collect($syarat)->filter(fn ($c) => ! $c['check']())->count();
    @endphp
    <div class="sl-card-head">
        <p class="si-eyebrow">Langkah 2 dari 6</p>
        <h2 class="sl-card-title">Persyaratan server</h2>
        <p class="sl-card-sub">Versi PHP dan ekstensi yang dibutuhkan OpenSID.</p>
    </div>

    @if ($result)
        <div class="si-summary si-ok" role="status">
            @include('admin.layouts.components.ikon', ['n' => 'check-circle'])
            <div><strong>Semua syarat terpenuhi</strong>PHP {{ PHP_VERSION }} siap menjalankan OpenSID.</div>
        </div>
    @else
        <div class="si-summary si-bad" role="alert">
            @include('admin.layouts.components.ikon', ['n' => 'x-circle'])
            <div><strong>{{ $gagal }} syarat belum terpenuhi</strong>Aktifkan ekstensi yang ditandai merah di pengaturan PHP hosting (PHP {{ PHP_VERSION }}), lalu periksa ulang.</div>
        </div>
    @endif

    <ul class="si-checks si-cols">
        @foreach ($syarat as $check)
            @php $ok = $check['check'](); @endphp
            <li class="{{ $ok ? '' : 'si-fail' }}">
                <div>
                    {{-- "Curl extention" (ejaan bawaan config/installer.php) -> "Ekstensi curl" --}}
                    {{ preg_match('/^(\w+)\s+extens?t?ion$/i', $check['name'], $m) ? 'Ekstensi ' . strtolower($m[1]) : str_replace('PHP Version', 'Versi PHP', $check['name']) }}
                    @isset($check['version'])
                        <small>{{ $check['version'] }}</small>
                    @endisset
                </div>
                @if ($ok)
                    <span class="si-badge si-badge-ok">@include('admin.layouts.components.ikon', ['n' => 'check']) Ada</span>
                @else
                    <span class="si-badge si-badge-bad">@include('admin.layouts.components.ikon', ['n' => 'x']) Belum</span>
                @endif
            </li>
        @endforeach
    </ul>

    <div class="si-foot-actions">
        <a class="si-link" href="{{ site_url('install') }}">Kembali</a>
        @if ($result)
            <a href="{{ site_url('install/folders') }}" class="sl-btn sl-btn-primary">
                Lanjut periksa folder
                @include('admin.layouts.components.ikon', ['n' => 'next'])
            </a>
        @else
            <a href="{{ site_url('install/server') }}" class="sl-btn sl-btn-primary">
                @include('admin.layouts.components.ikon', ['n' => 'refresh'])
                Periksa ulang
            </a>
        @endif
    </div>
@endsection
