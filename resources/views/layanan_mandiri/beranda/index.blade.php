@extends('layanan_mandiri.layouts.index')

@php
    $pengguna = $ci->is_login;
    $kunci = $pengguna->ganti_pin === '1';
    $tautan = fn (string $uri) => $kunci ? '#' : site_url('layanan-mandiri/' . $uri);
    $jam = (int) date('G');
    $salam = $jam < 11 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 18 ? 'Selamat sore' : 'Selamat malam'));
    $nama_depan = ucwords(strtolower(strtok(trim($pengguna->nama), ' ')));
    $label_status = \App\Models\PermohonanSurat::STATUS_PERMOHONAN;
    $warna_status = [0 => 'is-amber', 1 => 'is-blue', 2 => 'is-violet', 3 => 'is-green', 4 => 'is-gray', 5 => 'is-red'];
    $petunjuk = [
        0 => 'Lengkapi syarat yang diminta operator.',
        1 => 'Operator sedang memeriksa permohonan Anda.',
        2 => 'Surat menunggu tanda tangan.',
        3 => 'Surat sudah jadi. Silakan ambil di kantor ' . strtolower(setting('sebutan_desa')) . '.',
        4 => 'Surat sudah Anda ambil.',
        5 => 'Permohonan dibatalkan.',
    ];
    $layanan = [
        ['surat/buat', 'Ajukan surat', 'fa-pencil-square-o', 'Pilih jenis surat lalu isi datanya', 'is-green'],
        ['permohonan-surat', 'Permohonan saya', 'fa-list-alt', 'Lihat status surat yang diajukan', 'is-blue'],
        ['pesan-masuk', 'Pesan', 'fa-envelope-o', $inbox ? "{$inbox} pesan belum dibaca" : 'Tanya atau balas pesan operator', 'is-amber'],
        ['dokumen', 'Dokumen', 'fa-folder-open-o', 'Simpan KTP, KK, dan berkas lain', 'is-violet'],
        ['bantuan', 'Bantuan sosial', 'fa-handshake-o', 'Program bantuan yang Anda terima', 'is-rose'],
        ['profil', 'Profil saya', 'fa-user-o', 'Biodata dan keanggotaan kelompok', 'is-sky'],
    ];
@endphp

@section('content')
    <section class="sm-hello">
        <div>
            <p class="sm-hello-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            <h1 class="sm-hello-title">{{ $salam }}, {{ $nama_depan }}</h1>
            <p class="sm-hello-sub">Mau mengurus apa hari ini?</p>
        </div>
        <a href="{{ $tautan('surat/buat') }}" class="sm-btn sm-btn-light"><i class="fa fa-plus"></i> Ajukan surat</a>
    </section>

    @if ($kunci)
        <div class="sm-callout is-amber">
            <i class="fa fa-lock"></i>
            <div>
                <strong>Ganti PIN dulu, ya.</strong>
                <p>Demi keamanan akun, buat PIN baru sebelum memakai layanan lain.</p>
            </div>
            <a href="{{ site_url('layanan-mandiri/ganti-pin') }}" class="sm-btn sm-btn-primary">Ganti PIN</a>
        </div>
    @endif

    <h2 class="sm-section-title">Layanan</h2>
    <div class="sm-tiles">
        @foreach ($layanan as [$uri, $judul, $ikon, $ket, $warna])
            <a href="{{ $tautan($uri) }}" class="sm-tile {{ $warna }} {{ $kunci ? 'is-locked' : '' }}">
                <span class="sm-tile-icon"><i class="fa {{ $ikon }}"></i></span>
                <span class="sm-tile-title">{{ $judul }}</span>
                <span class="sm-tile-desc">{{ $ket }}</span>
                @if ($uri === 'pesan-masuk' && $inbox)
                    <span class="sm-tile-count">{{ $inbox }}</span>
                @endif
            </a>
        @endforeach
    </div>

    <div class="sm-card">
        <div class="sm-card-head">
            <div>
                <h2 class="sm-card-title">Permohonan surat terakhir</h2>
                <p class="sm-card-sub">Status diperbarui oleh operator {{ strtolower(setting('sebutan_desa')) }}</p>
            </div>
            <a href="{{ $tautan('permohonan-surat') }}" class="sm-link">Lihat semua <i class="fa fa-angle-right"></i></a>
        </div>
        @if ($permohonan->count())
            <ol class="sm-timeline">
                @foreach ($permohonan as $item)
                    <li>
                        <span class="sm-badge-status {{ $warna_status[$item->status] ?? 'is-gray' }}">{{ $label_status[$item->status] ?? '-' }}</span>
                        <strong>{{ $item->surat->nama ?? 'Surat' }}</strong>
                        <span class="sm-timeline-meta">
                            Diajukan {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d F Y') }}@if ($item->no_antrian), nomor antrean {{ $item->no_antrian }}@endif
                        </span>
                        <span class="sm-timeline-hint">{{ $petunjuk[$item->status] ?? '' }}</span>
                    </li>
                @endforeach
            </ol>
        @else
            <div class="sm-empty">
                <i class="fa fa-file-text-o"></i>
                <div>
                    <strong>Belum ada permohonan surat</strong>
                    <p>Ajukan surat pertama Anda, lalu pantau statusnya di sini.</p>
                </div>
                <a href="{{ $tautan('surat/buat') }}" class="sm-btn sm-btn-primary">Ajukan surat</a>
            </div>
        @endif
    </div>

    <div class="sm-steps">
        <h2 class="sm-section-title">Cara mengajukan surat</h2>
        <ol class="sm-steps-list">
            <li><strong>Pilih jenis surat</strong> di menu Ajukan surat.</li>
            <li><strong>Isi data & unggah syarat</strong> yang diminta.</li>
            <li><strong>Tunggu status "Siap Diambil"</strong> lalu datang ke kantor {{ strtolower(setting('sebutan_desa')) }}.</li>
        </ol>
    </div>
@endsection
