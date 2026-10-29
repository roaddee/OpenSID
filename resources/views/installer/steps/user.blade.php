@extends('installer.install')

@section('langkah', 'akun')

@section('step')
    <div class="sl-card-head">
        <p class="si-eyebrow">Langkah 6 dari 6</p>
        <h2 class="sl-card-title">Buat akun admin</h2>
        <p class="sl-card-sub">Akun ini dipakai untuk masuk ke halaman admin (<code>/siteman</code>). Simpan baik-baik.</p>
    </div>

    @include('installer.partials.errors')

    <form method="post" action="{{ site_url('install/user') }}" data-si-form>
        <input type="hidden" name="<?= $ci->security->get_csrf_token_name() ?>" value="<?= $ci->security->get_csrf_hash() ?>" />

        <div class="form-group">
            <label for="username" class="sl-label">Nama pengguna <span class="si-req">*</span></label>
            <div class="sl-input">
                <span class="sl-input-ikon">@include('admin.layouts.components.ikon', ['n' => 'user'])</span>
                <input id="username" type="text" name="username" class="form-control" placeholder="mis. admin_desa"
                    value="{{ set_value('username') }}" required autocomplete="username">
            </div>
            @if (form_error('username')) {!! form_error('username') !!} @endif
        </div>

        <div class="si-grid si-grid-2">
            <div class="form-group">
                <label for="password" class="sl-label">Kata sandi <span class="si-req">*</span></label>
                <div class="sl-input">
                    <span class="sl-input-ikon">@include('admin.layouts.components.ikon', ['n' => 'lock'])</span>
                    <input id="password" type="password" name="password" class="form-control" value="{{ set_value('password') }}"
                        required autocomplete="new-password" maxlength="20" aria-describedby="syarat-sandi">
                    <button type="button" class="sl-eye" data-si-eye="password,confirm_password" aria-pressed="false" aria-label="Tampilkan kata sandi">
                        <span class="sl-eye-on">@include('admin.layouts.components.ikon', ['n' => 'eye'])</span>
                        <span class="sl-eye-off">@include('admin.layouts.components.ikon', ['n' => 'eye-off'])</span>
                    </button>
                </div>
                @if (form_error('password')) {!! form_error('password') !!} @endif
            </div>
            <div class="form-group">
                <label for="confirm_password" class="sl-label">Ulangi kata sandi <span class="si-req">*</span></label>
                <div class="sl-input">
                    <span class="sl-input-ikon">@include('admin.layouts.components.ikon', ['n' => 'lock'])</span>
                    <input id="confirm_password" type="password" name="confirm_password" class="form-control" value="{{ set_value('confirm_password') }}"
                        required autocomplete="new-password" maxlength="20">
                </div>
                @if (form_error('confirm_password')) {!! form_error('confirm_password') !!} @endif
            </div>
        </div>

        <ul class="si-rules" id="syarat-sandi" aria-live="polite">
            <li data-rule="len">8–20 karakter, tanpa spasi</li>
            <li data-rule="upper">Huruf besar (A–Z)</li>
            <li data-rule="lower">Huruf kecil (a–z)</li>
            <li data-rule="digit">Angka (0–9)</li>
            <li data-rule="symbol">Simbol, mis. * # ! @</li>
            <li data-rule="same">Kedua kata sandi sama</li>
        </ul>

        <div class="si-foot-actions">
            <span class="si-hint">Bisa diganti nanti di Admin → Pengguna.</span>
            <button type="submit" class="sl-btn sl-btn-primary" data-si-busy="Menyimpan akun…">
                <span class="si-spin" aria-hidden="true"></span>
                <span data-si-label>Simpan &amp; selesai</span>
                <span class="si-arrow">@include('admin.layouts.components.ikon', ['n' => 'next'])</span>
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        (function () {
            var pw = document.getElementById('password');
            var cf = document.getElementById('confirm_password');
            var rules = {
                len: function (v) { return v.length >= 8 && v.length <= 20 && !/\s/.test(v); },
                upper: function (v) { return /[A-Z]/.test(v); },
                lower: function (v) { return /[a-z]/.test(v); },
                digit: function (v) { return /\d/.test(v); },
                symbol: function (v) { return /[^a-zA-Z0-9\s]/.test(v); },
                same: function (v) { return v !== '' && v === cf.value; }
            };
            function cek() {
                Object.keys(rules).forEach(function (k) {
                    var li = document.querySelector('[data-rule="' + k + '"]');
                    if (li) { li.classList.toggle('si-met', rules[k](pw.value)); }
                });
            }
            pw.addEventListener('input', cek);
            cf.addEventListener('input', cek);
            cek();
        })();
    </script>
@endpush
