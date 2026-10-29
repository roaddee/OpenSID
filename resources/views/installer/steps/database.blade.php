@extends('installer.install')

@section('langkah', 'database')

@section('step')
    <div class="sl-card-head">
        <p class="si-eyebrow">Langkah 4 dari 6</p>
        <h2 class="sl-card-title">Sambungkan database</h2>
        <p class="sl-card-sub">Database harus sudah dibuat dan kosong. Data ini disimpan di <code>desa/config/database.php</code>.</p>
    </div>

    @include('installer.partials.errors')

    <form method="post" action="{{ site_url('install/database') }}" data-si-form>
        <input type="hidden" name="<?= $ci->security->get_csrf_token_name() ?>" value="<?= $ci->security->get_csrf_hash() ?>" />

        <div class="si-grid">
            <div class="form-group">
                <label for="database_hostname" class="sl-label">Host database <span class="si-req">*</span></label>
                <input id="database_hostname" type="text" name="database_hostname" class="form-control" placeholder="localhost"
                    value="{{ set_value('database_hostname', 'localhost') }}" required autocomplete="off">
                @if (form_error('database_hostname')) {!! form_error('database_hostname') !!} @endif
            </div>
            <div class="form-group">
                <label for="database_port" class="sl-label">Port <span class="si-req">*</span></label>
                <input id="database_port" type="text" name="database_port" class="form-control" inputmode="numeric" placeholder="3306"
                    value="{{ set_value('database_port', '3306') }}" required>
                @if (form_error('database_port')) {!! form_error('database_port') !!} @endif
            </div>
        </div>

        <div class="form-group">
            <label for="database_name" class="sl-label">Nama database <span class="si-req">*</span></label>
            <input id="database_name" type="text" name="database_name" class="form-control" placeholder="mis. desa_opensid"
                value="{{ set_value('database_name') }}" required autocomplete="off">
            @if (form_error('database_name')) {!! form_error('database_name') !!} @endif
        </div>

        <div class="si-grid si-grid-2">
            <div class="form-group">
                <label for="database_username" class="sl-label">Pengguna database <span class="si-req">*</span></label>
                <input id="database_username" type="text" name="database_username" class="form-control" placeholder="pengguna database"
                    value="{{ set_value('database_username') }}" required autocomplete="off">
                @if (form_error('database_username')) {!! form_error('database_username') !!} @endif
            </div>
            <div class="form-group">
                <label for="database_password" class="sl-label">Kata sandi database</label>
                <div class="sl-input">
                    <input id="database_password" type="password" name="database_password" class="form-control" placeholder="kata sandi"
                        value="{{ set_value('database_password') }}" autocomplete="new-password">
                    <button type="button" class="sl-eye" data-si-eye="database_password" aria-pressed="false" aria-label="Tampilkan kata sandi">
                        <span class="sl-eye-on">@include('admin.layouts.components.ikon', ['n' => 'eye'])</span>
                        <span class="sl-eye-off">@include('admin.layouts.components.ikon', ['n' => 'eye-off'])</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="si-foot-actions">
            <a class="si-link" href="{{ site_url('install/folders') }}">Kembali</a>
            <button type="submit" class="sl-btn sl-btn-primary" data-si-busy="Menguji koneksi…">
                <span class="si-spin" aria-hidden="true"></span>
                <span data-si-label>Uji &amp; simpan koneksi</span>
                <span class="si-arrow">@include('admin.layouts.components.ikon', ['n' => 'next'])</span>
            </button>
        </div>
    </form>
@endsection
