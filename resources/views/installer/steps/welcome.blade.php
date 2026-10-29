@extends('installer.install')

@section('langkah', 'mulai')

@section('step')
    <div class="sl-card-head">
        <p class="si-eyebrow">Langkah 1 dari 6</p>
        <h2 class="sl-card-title">Selamat datang</h2>
        <p class="sl-card-sub">Installer ini menyiapkan database, data awal, dan akun admin OpenSID untuk situs ini.</p>
    </div>

    <ul class="si-points">
        <li><span class="si-num">1</span><div><strong>Periksa server</strong>Versi PHP, ekstensi, dan izin folder dicek otomatis.</div></li>
        <li><span class="si-num">2</span><div><strong>Sambungkan database</strong>Isi data database yang sudah dibuat di hosting.</div></li>
        <li><span class="si-num">3</span><div><strong>Pasang data awal</strong>Tabel dan pengaturan dasar OpenSID dibuat.</div></li>
        <li><span class="si-num">4</span><div><strong>Buat akun admin</strong>Nama pengguna &amp; kata sandi untuk masuk ke /siteman.</div></li>
    </ul>

    <div class="sl-note">
        @include('admin.layouts.components.ikon', ['n' => 'database'])
        <div>
            <strong>Siapkan data database:</strong> host, port, nama database, pengguna, dan kata sandinya.
            Biasanya diberikan penyedia hosting. Situs di hosting Satima sudah dipasang otomatis oleh panel.
        </div>
    </div>

    <div class="si-foot-actions">
        <a class="si-link" href="{{ config('app.panduan.instalasi') }}" target="_blank" rel="noopener">Butuh bantuan? Panduan pemasangan</a>
        <a href="{{ site_url('install/server') }}" class="sl-btn sl-btn-primary">
            Mulai periksa server
            @include('admin.layouts.components.ikon', ['n' => 'next'])
        </a>
    </div>
@endsection
