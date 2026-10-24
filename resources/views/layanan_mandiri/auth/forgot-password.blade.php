@extends('layanan_mandiri.auth.index')

@section('judul', 'Lupa PIN')
@section('keterangan', 'Masukkan NIK Anda, lalu pilih ke mana PIN baru dikirim.')

@section('content')
    <form id="validasi" action="<?= ci_route('layanan-mandiri.cek-pin') ?>" method="post" class="login-form sl-form">
        <div class="form-group form-login sl-field">
            <label for="nik" class="sl-label">NIK</label>
            <div class="sl-input">
                @include('admin.layouts.components.ikon', ['n' => 'id', 'k' => 'sl-input-ikon'])
                <input
                    type="text"
                    inputmode="numeric"
                    id="nik"
                    autocomplete="off"
                    value=""
                    class="form-control required nik <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') ?>"
                    name="nik"
                    placeholder="16 digit sesuai KTP"
                    maxlength="16"
                >
            </div>
        </div>
        <div class="sl-actions sl-actions-2">
            <button type="submit" class="btn sl-btn sl-btn-primary" name="via" value="telegram">@include('admin.layouts.components.ikon', ['n' => 'send']) Telegram</button>
            <button type="submit" class="btn sl-btn sl-btn-primary" name="via" value="email">@include('admin.layouts.components.ikon', ['n' => 'mail']) Email</button>
        </div>
        <div class="sl-divider"><span>sudah ingat?</span></div>
        <div class="sl-actions sl-actions-2">
            <a href="<?= site_url('layanan-mandiri/masuk') ?>" class="btn sl-btn sl-btn-ghost">Masuk dengan NIK</a>
            <a href="<?= site_url('layanan-mandiri/masuk-ektp') ?>" class="btn sl-btn sl-btn-ghost">Masuk dengan e-KTP</a>
        </div>
    </form>
@endsection
