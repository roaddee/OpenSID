@extends('installer.install')

@section('langkah', 'data')

@section('step')
    <div class="sl-card-head">
        <p class="si-eyebrow">Langkah 5 dari 6</p>
        <h2 class="sl-card-title">Pasang data awal</h2>
        <p class="sl-card-sub">Tabel, pengaturan dasar, dan data referensi OpenSID dibuat di database.</p>
    </div>

    @if ($ci->session->errors)
        @include('installer.partials.errors')
    @else
        <div class="si-summary si-ok" role="status">
            @include('admin.layouts.components.ikon', ['n' => 'check-circle'])
            <div><strong>Koneksi database berhasil</strong>Database siap diisi.</div>
        </div>
    @endif

    <form method="post" action="{{ site_url('install/migrations') }}" data-si-form>
        <input type="hidden" name="<?= $ci->security->get_csrf_token_name() ?>" value="<?= $ci->security->get_csrf_hash() ?>" />

        <div class="si-progress" role="status" aria-live="polite">
            <strong>Sedang memasang data awal…</strong>
            Biasanya kurang dari satu menit. Jangan tutup atau muat ulang halaman ini.
            <div class="si-bar"></div>
        </div>

        <p class="si-p">Cukup sekali. Jika gagal, pesan kesalahan tampil di halaman ini dan Anda bisa mencoba lagi.</p>

        <div class="si-foot-actions">
            <a class="si-link" href="{{ site_url('install/database') }}">Kembali</a>
            <button type="submit" class="sl-btn sl-btn-primary" data-si-busy="Memasang data…">
                <span class="si-spin" aria-hidden="true"></span>
                <span data-si-label>{{ $ci->session->errors ? 'Coba lagi' : 'Pasang data awal' }}</span>
                <span class="si-arrow">@include('admin.layouts.components.ikon', ['n' => 'next'])</span>
            </button>
        </div>
    </form>
@endsection
