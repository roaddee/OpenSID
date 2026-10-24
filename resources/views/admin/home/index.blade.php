@extends('admin.layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/satima-font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/satima-beranda.css') }}">
    <style>
        .catatan-scroll {
            height: 400px;
            overflow-y: scroll;
        }

        @media (max-width: 576px) {
            .komunikasi-opendk {
                display: none !important;
            }
        }
    </style>
@endpush

@php
    $sebutan_desa = ucwords(setting('sebutan_desa'));
    $jam = (int) date('G');
    $salam = $jam < 11 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 18 ? 'Selamat sore' : 'Selamat malam'));
    $nama_pengguna = isset($auth) ? ($auth->nama ?? '') : '';
    $penduduk = $ringkasan['penduduk'] ?? null;
    $umur = $ringkasan['umur'] ?? null;
    $permohonan = $ringkasan['permohonan'] ?? collect();
    $artikel = $ringkasan['artikel'] ?? collect();
    $label_status = \App\Models\PermohonanSurat::STATUS_PERMOHONAN;
    $warna_status = [0 => 'is-amber', 1 => 'is-blue', 2 => 'is-violet', 3 => 'is-green', 4 => 'is-gray', 5 => 'is-red'];
    $pintasan = array_filter([
        auth()->user()?->can('penduduk:ubah') ? ['Tambah penduduk', 'fa-user-plus', site_url('penduduk/form')] : null,
        auth()->user()?->can('cetak-surat:baca') ? ['Buat surat', 'fa-file-text-o', site_url('surat')] : null,
        auth()->user()?->can('permohonan-surat:baca') ? ['Permohonan warga', 'fa-inbox', site_url('permohonan_surat_admin')] : null,
        auth()->user()?->can('artikel:ubah') ? ['Tulis berita', 'fa-pencil', site_url('web/form/1')] : null,
    ]);
@endphp

@section('title')
    <h1>Beranda</h1>
@endsection

@section('breadcrumb')
    <li class="active">Beranda</li>
@endsection

@section('content')
    <div class="sb">
        @include('admin.layouts.components.notifikasi')

        @include('admin.home.saas')

        @include('admin.home.premium')

        @include('admin.home.rilis')

        @include('admin.home.percobaan')

        <section class="sb-hero">
            <img class="sb-hero-mark" src="{{ gambar_desa($desa['logo'] ?? null) }}" alt="" aria-hidden="true">
            <div class="sb-hero-text">
                <p class="sb-hero-chip">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                <h2 class="sb-hero-title">{{ $salam }}@if ($nama_pengguna), <span>{{ $nama_pengguna }}</span>@endif</h2>
                <p class="sb-hero-sub">
                    Admin {{ $sebutan_desa }} {{ $desa['nama_desa'] ?? '' }}, {{ ucwords(setting('sebutan_kecamatan')) }} {{ $desa['nama_kecamatan'] ?? '' }}, {{ ucwords(setting('sebutan_kabupaten')) }} {{ $desa['nama_kabupaten'] ?? '' }}
                </p>
            </div>
            @if ($pintasan)
                <div class="sb-hero-actions">
                    @foreach ($pintasan as [$label, $icon, $link])
                        <a href="{{ $link }}" class="sb-hero-btn"><i class="fa {{ $icon }}"></i> {{ $label }}</a>
                    @endforeach
                </div>
            @endif
        </section>

        @if (count($shortcut))
            <div class="sb-stats">
                @foreach ($shortcut as $sc)
                    @can("{$sc['akses']}:baca")
                        <a href="{{ ci_route($sc['link'] ?? '#') }}" class="sb-stat" style="--c: {{ $sc['warna'] }}">
                            <span class="sb-stat-icon"><i class="faa {!! $sc['icon'] !!}"></i></span>
                            <span class="sb-stat-body">
                                <span class="sb-stat-value">{{ number_format((int) ($sc['count'] ?? 0), 0, ',', '.') }}</span>
                                <span class="sb-stat-label">{{ SebutanDesa($sc['judul']) }}</span>
                            </span>
                            <i class="fa fa-angle-right sb-stat-go" aria-hidden="true"></i>
                        </a>
                    @endcan
                @endforeach
            </div>
        @endif

        <div class="sb-grid">
            <div class="sb-col-main">
                @can('permohonan-surat:baca')
                    <section class="sb-card">
                        <header class="sb-card-head">
                            <div>
                                <h3 class="sb-card-title">Permohonan surat dari warga</h3>
                                <p class="sb-card-sub">
                                    @if ($ringkasan['permohonan_baru'] ?? 0)
                                        <strong>{{ $ringkasan['permohonan_baru'] }}</strong> permohonan menunggu diperiksa
                                    @else
                                        Tidak ada permohonan yang menunggu diperiksa
                                    @endif
                                </p>
                            </div>
                            <a href="{{ site_url('permohonan_surat_admin') }}" class="sb-link">Lihat semua <i class="fa fa-angle-right"></i></a>
                        </header>
                        @if ($permohonan->count())
                            <ul class="sb-list">
                                @foreach ($permohonan as $item)
                                    <li class="sb-list-item">
                                        <span class="sb-avatar">{{ mb_strtoupper(mb_substr($item->penduduk->nama ?? '?', 0, 1)) }}</span>
                                        <span class="sb-list-main">
                                            <span class="sb-list-title">{{ $item->surat->nama ?? 'Surat' }}</span>
                                            <span class="sb-list-meta">{{ $item->penduduk->nama ?? 'Warga' }}, {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->diffForHumans() }}</span>
                                        </span>
                                        <span class="sb-badge {{ $warna_status[$item->status] ?? 'is-gray' }}">{{ $label_status[$item->status] ?? '-' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="sb-empty">
                                <i class="fa fa-inbox"></i>
                                <p>Belum ada permohonan surat. Warga bisa mengajukan surat lewat Layanan Mandiri.</p>
                            </div>
                        @endif
                    </section>
                @endcan

                @can('artikel:baca')
                    <section class="sb-card">
                        <header class="sb-card-head">
                            <div>
                                <h3 class="sb-card-title">Berita terbaru di website</h3>
                                <p class="sb-card-sub">Empat tulisan terakhir</p>
                            </div>
                            <a href="{{ site_url('web') }}" class="sb-link">Kelola berita <i class="fa fa-angle-right"></i></a>
                        </header>
                        @if ($artikel->count())
                            <ul class="sb-list">
                                @foreach ($artikel as $item)
                                    <li class="sb-list-item">
                                        <span class="sb-list-main">
                                            <span class="sb-list-title">{{ $item->judul }}</span>
                                            <span class="sb-list-meta">{{ tgl_indo($item->tgl_upload) }}, dibaca {{ number_format((int) $item->hit, 0, ',', '.') }} kali</span>
                                        </span>
                                        @if (! $item->enabled)
                                            <span class="sb-badge is-gray">Belum tayang</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="sb-empty">
                                <i class="fa fa-newspaper-o"></i>
                                <p>Belum ada berita. Tulis berita pertama agar website {{ strtolower($sebutan_desa) }} terlihat hidup.</p>
                            </div>
                        @endif
                    </section>
                @endcan
            </div>

            <div class="sb-col-side">
                @if ($penduduk && $penduduk['total'] > 0)
                    @php
                        $persen_l = round($penduduk['laki'] / $penduduk['total'] * 100);
                    @endphp
                    <section class="sb-card">
                        <header class="sb-card-head">
                            <div>
                                <h3 class="sb-card-title">Komposisi penduduk</h3>
                                <p class="sb-card-sub">Penduduk berstatus hidup</p>
                            </div>
                        </header>
                        <div class="sb-donut-wrap">
                            <div class="sb-donut" style="--p: {{ $persen_l }}" role="img" aria-label="Laki-laki {{ $persen_l }} persen, perempuan {{ 100 - $persen_l }} persen">
                                <span class="sb-donut-num">{{ number_format($penduduk['total'], 0, ',', '.') }}</span>
                                <span class="sb-donut-cap">jiwa</span>
                            </div>
                            <ul class="sb-legend">
                                <li><span class="sb-dot is-l"></span> Laki-laki <strong>{{ number_format($penduduk['laki'], 0, ',', '.') }}</strong></li>
                                <li><span class="sb-dot is-p"></span> Perempuan <strong>{{ number_format($penduduk['perempuan'], 0, ',', '.') }}</strong></li>
                            </ul>
                        </div>
                        @if ($umur && array_sum($umur) > 0)
                            @php $maks = max($umur) ?: 1 @endphp
                            <div class="sb-bars">
                                @foreach ($umur as $label => $jumlah)
                                    <div class="sb-bar">
                                        <span class="sb-bar-label">{{ $label }}</span>
                                        <span class="sb-bar-track"><span class="sb-bar-fill" style="width: {{ round($jumlah / $maks * 100) }}%"></span></span>
                                        <span class="sb-bar-num">{{ number_format($jumlah, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @can('penduduk:baca')
                            <a href="{{ site_url('penduduk') }}" class="sb-card-foot">Buka data penduduk <i class="fa fa-angle-right"></i></a>
                        @endcan
                    </section>
                @endif

                <section class="sb-card sb-card-plain">
                    <header class="sb-card-head">
                        <div>
                            <h3 class="sb-card-title">Website {{ strtolower($sebutan_desa) }}</h3>
                            <p class="sb-card-sub">Tampilan yang dilihat warga</p>
                        </div>
                    </header>
                    <div class="sb-links">
                        <a href="{{ site_url() }}" target="_blank" rel="noopener"><i class="fa fa-globe"></i> Buka website</a>
                        @if (setting('layanan_mandiri') == 1)
                            <a href="{{ site_url('layanan-mandiri/masuk') }}" target="_blank" rel="noopener"><i class="fa fa-id-card-o"></i> Layanan Mandiri warga</a>
                        @endif
                        @can('pendaftar-layanan-mandiri:baca')
                            <a href="{{ site_url('mandiri') }}"><i class="fa fa-key"></i> Akun & PIN warga</a>
                        @endcan
                    </div>
                    <p class="sb-version">OpenSID v{{ AmbilVersi() }}-perjuangan</p>
                </section>
            </div>
        </div>
    </div>
@endsection
