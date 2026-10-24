@extends('layanan_mandiri.auth.index')

@section('judul', 'Masuk dengan e-KTP')
@section('keterangan', $cek_anjungan ? 'Tempelkan e-KTP pada pembaca kartu di anjungan.' : 'Tempelkan e-KTP pada pembaca kartu, lalu masukkan PIN.')

@section('content')
    <form id="validasi" autocomplete="off" action="{{ $form_action }}" method="post" class="login-form">
        <div class="sl-scan">
            <img src="{{ asset('images/camera-scan.gif') }}" alt="Ilustrasi pembaca kartu e-KTP">
        </div>
        <input type="hidden" name="anjungan_uuid" id="anjungan_uuid">
        <div class="form-group form-login" style="{{ jecho($cek_anjungan == 0 || ENVIRONMENT == 'development', false, 'width: 0; height: 0; overflow: hidden;') }}">
            <input
                name="tag_id_card"
                id="tag"
                autocomplete="off"
                placeholder="Tempelkan e-KTP Pada Card Reader"
                class="form-control required number"
                type="password"
                onkeypress="if (event.keyCode == 13){$('#'+'validasi').attr('action', '{{ $form_action }}');$('#'+'validasi').submit();}"
            >
        </div>
        @if (!$cek_anjungan)
            <div class="form-group form-login sl-field">
                <label for="pin" class="sl-label">PIN</label>
                <div class="sl-input">
                    @include('admin.layouts.components.ikon', ['n' => 'lock', 'k' => 'sl-input-ikon'])
                    <input type="password" inputmode="numeric" id="pin" class="form-control required number sl-pin" name="password" placeholder="6 digit" autocomplete="off" maxlength="6">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn sl-btn sl-btn-primary">Masuk</button>
            </div>
        @endif
        <div class="sl-divider"><span>atau</span></div>
        <div class="sl-actions">
            <a href="{{ site_url('layanan-mandiri/masuk') }}" class="btn sl-btn sl-btn-ghost">@include('admin.layouts.components.ikon', ['n' => 'id']) Masuk dengan NIK</a>
            <a href="{{ site_url('layanan-mandiri/lupa-pin') }}" class="btn sl-btn sl-btn-ghost">@include('admin.layouts.components.ikon', ['n' => 'help']) Lupa PIN</a>
            @if (in_array(\Modules\Anjungan\Models\Anjungan::ANJUNGAN, $cek_anjungan['tipe'] ?? []))
                <a href="{{ route('anjungan.index') }}" class="btn sl-btn sl-btn-ghost">@include('admin.layouts.components.ikon', ['n' => 'monitor']) Anjungan</a>
            @endif
        </div>
        @if (setting('tampilkan_pendaftaran'))
            <p class="sl-switch">Belum punya akun? <a href="{{ site_url('layanan-mandiri/daftar') }}">Daftar di sini</a></p>
        @endif
    </form>
@endsection
@push('script')
    <script type="text/javascript">
        $('document').ready(function() {
            // Get UUID from local storage and set it to the hidden input
            const anjungan_uuid = localStorage.getItem('anjungan_uuid');
            if (anjungan_uuid) {
                $('#anjungan_uuid').val(anjungan_uuid);
            }
        });
    </script>
@endpush
