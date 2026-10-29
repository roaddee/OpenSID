@extends('installer.install')

@section('langkah', 'folder')

@section('step')
    @php
        $folder = $ci->config->item('folders');
        $gagal = collect($folder)->filter(fn ($c) => ! $c['check']())->count();
    @endphp
    <div class="sl-card-head">
        <p class="si-eyebrow">Langkah 3 dari 6</p>
        <h2 class="sl-card-title">Izin folder</h2>
        <p class="sl-card-sub">OpenSID perlu menulis cache, log, dan cadangan di folder berikut.</p>
    </div>

    @if ($result)
        <div class="si-summary si-ok" role="status">
            @include('admin.layouts.components.ikon', ['n' => 'check-circle'])
            <div><strong>Semua folder bisa ditulis</strong>Izin minimal 0755 sudah terpenuhi.</div>
        </div>
    @else
        <div class="si-summary si-bad" role="alert">
            @include('admin.layouts.components.ikon', ['n' => 'x-circle'])
            <div><strong>{{ $gagal }} folder belum bisa ditulis</strong>Ubah izin folder yang ditandai menjadi 0755 (lewat File Manager / FTP), lalu periksa ulang.</div>
        </div>
    @endif

    <ul class="si-checks">
        @foreach ($folder as $check)
            @php $ok = $check['check'](); @endphp
            <li class="{{ $ok ? '' : 'si-fail' }}">
                <div>@include('admin.layouts.components.ikon', ['n' => 'folder']) <code>{{ str_replace(FCPATH, '', $check['name']) }}</code></div>
                @if ($ok)
                    <span class="si-badge si-badge-ok">@include('admin.layouts.components.ikon', ['n' => 'check']) Bisa ditulis</span>
                @else
                    <span class="si-badge si-badge-bad">@include('admin.layouts.components.ikon', ['n' => 'x']) Belum</span>
                @endif
            </li>
        @endforeach
    </ul>

    <div class="si-foot-actions">
        <a class="si-link" href="{{ site_url('install/server') }}">Kembali</a>
        @if ($result)
            <a href="{{ site_url('install/database') }}" class="sl-btn sl-btn-primary">
                Lanjut isi database
                @include('admin.layouts.components.ikon', ['n' => 'next'])
            </a>
        @else
            <a href="{{ site_url('install/folders') }}" class="sl-btn sl-btn-primary">
                @include('admin.layouts.components.ikon', ['n' => 'refresh'])
                Periksa ulang
            </a>
        @endif
    </div>
@endsection
