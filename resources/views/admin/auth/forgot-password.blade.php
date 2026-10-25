@extends('admin.auth.index')

@section('judul', 'Lupa kata sandi')
@section('keterangan', 'Masukkan email akun Anda. Tautan untuk mengatur ulang kata sandi akan dikirim ke email atau Telegram.')

@section('content')
    @if (empty(setting('email_notifikasi')) && empty(setting('telegram_notifikasi')))
        <br>
        <div class="alert alert-warning mt-4 text-left">
            <h5 class="mb-3"><strong>Tautan lupa password tidak dapat digunakan karena email atau telegram belum diatur di sistem.</strong></h5>
            <p>
                Jika Anda mempunyai akses ke server, ikuti petunjuk <a href="https://panduan.opendesa.id/opensid/halaman-administrasi#siteman" target="_blank">Panduan Lupa Sandi</a>.
                <br><br>Jika tidak mempunyai akses ke server, Anda perlu menghubungi pihak yang mengelola server. Apabila pelanggan SiapPakai atau Pengguna Diskomino yang PKS dengan OpenDesa, dapat menghubungi DevOps OpenDesa untuk dibantu.
            </p>
        </div>
    @else
        <form id="validasi" class="login-form sl-form" action="<?= site_url('siteman/kirim_lupa_sandi') ?>" method="post">
            <div class="form-group sl-field">
                <label for="email" class="sl-label">Email pengguna</label>
                <div class="sl-input">
                    @include('admin.layouts.components.ikon', ['n' => 'mail', 'k' => 'sl-input-ikon'])
                    <input id="email" name="email" type="email" autocomplete="email" placeholder="nama@contoh.id" class="form-control required">
                </div>
            </div>
            <div class="form-group sl-field">
                <label for="captcha_code" class="sl-label">Kode keamanan</label>
                <div class="sl-captcha">
                    <span class="sl-captcha-img"><img id="captcha" src="<?= site_url('captcha') ?>" alt="Kode keamanan" width="150" height="44" /></span>
                    <button type="button" id="b-captcha" class="sl-captcha-btn" title="Ganti gambar kode" aria-label="Ganti gambar kode" onclick="document.getElementById('captcha').src = '<?= site_url('captcha') ?>?' + Math.random();">@include('admin.layouts.components.ikon', ['n' => 'refresh'])</button>
                    <div class="sl-input captcha">
                        <input
                            id="captcha_code"
                            name="captcha_code"
                            type="text"
                            class="form-control"
                            maxlength="6"
                            placeholder="Ketik kode"
                            autocomplete="off"
                            required
                        />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn sl-btn sl-btn-primary">Kirim tautan</button>
            </div>
            <div class="form-group">
                <a href="<?= site_url('siteman') ?>" class="btn sl-btn sl-btn-ghost">@include('admin.layouts.components.ikon', ['n' => 'back']) Kembali ke halaman masuk</a>
            </div>
        </form>
    @endif
@endsection

@push('js')
    <script>
        $('#b-captcha').click();
    </script>
@endpush
