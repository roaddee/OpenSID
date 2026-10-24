@extends('layanan_mandiri.auth.index')

@php
    $kb = jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber');
    $ada_anjungan = in_array(\Modules\Anjungan\Models\Anjungan::ANJUNGAN, $cek_anjungan['tipe'] ?? []);
@endphp

@section('content')
    @include('admin.layouts.components.notifikasi')

    <form id="validasi" autocomplete="off" action="{{ $form_action }}" method="post" class="login-form sl-form">
        <div class="form-group form-login sl-field">
            <label for="nik" class="sl-label">NIK</label>
            <div class="sl-input">
                <i class="fa fa-id-card-o" aria-hidden="true"></i>
                <input type="text" inputmode="numeric" autocomplete="off" id="nik" class="form-control angka required {!! $kb !!}" name="nik" maxlength="16" placeholder="16 digit sesuai KTP">
            </div>
        </div>
        {{-- Hidden input for UUID from local storage --}}
        <input type="hidden" name="anjungan_uuid" id="anjungan_uuid">
        <div class="form-group form-login sl-field">
            <div class="sl-label-row">
                <label for="pin" class="sl-label">PIN</label>
                <a href="{{ site_url('layanan-mandiri/lupa-pin') }}" class="sl-link">Lupa PIN?</a>
            </div>
            <div class="sl-input">
                <i class="fa fa-lock" aria-hidden="true"></i>
                <input
                    type="password"
                    inputmode="numeric"
                    autocomplete="off"
                    class="form-control angka required sl-pin {!! $kb !!}"
                    name="password"
                    placeholder="6 digit"
                    id="pin"
                    maxlength="6"
                >
                <button type="button" class="sl-eye" id="checkbox" aria-label="Tampilkan PIN" aria-pressed="false"><i class="fa fa-eye"></i></button>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn sl-btn sl-btn-primary">Masuk</button>
        </div>

        <div class="sl-divider"><span>atau</span></div>

        <div class="sl-actions {{ $ada_anjungan ? 'sl-actions-2' : '' }}">
            <a href="{{ site_url('layanan-mandiri/masuk-ektp') }}" class="btn sl-btn sl-btn-ghost"><i class="fa fa-credit-card"></i> Masuk dengan e-KTP</a>
            @if ($ada_anjungan)
                <a href="<?= route('anjungan.index') ?>" class="btn sl-btn sl-btn-ghost"><i class="fa fa-desktop"></i> Anjungan</a>
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
            var pass = $("#pin");
            $('#checkbox').click(function() {
                var tampil = pass.attr('type') === 'password';
                pass.attr('type', tampil ? 'text' : 'password');
                $(this).attr('aria-pressed', tampil).attr('aria-label', tampil ? 'Sembunyikan PIN' : 'Tampilkan PIN')
                    .find('i').toggleClass('fa-eye', !tampil).toggleClass('fa-eye-slash', tampil);
            });

            // Get UUID from local storage and set it to the hidden input
            const anjungan_uuid = localStorage.getItem('anjungan_uuid');
            if (anjungan_uuid) {
                $('#anjungan_uuid').val(anjungan_uuid);
            }
        });
    </script>
@endpush
