{{-- Satima: gambar di tengah QR code surat (TinyMCE, RTF, surat dinas, tombol QR arsip) --}}
<div class="tab-pane" id="qr-code">
    <div class="box-body">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>Gambar di Tengah QR Code</label>
                    <div class="radio"><label>
                        <input type="radio" name="satima_qr_mode" value="desa" @checked($qr_mode === 'desa')> Logo desa
                    </label></div>
                    <div class="radio"><label>
                        <input type="radio" name="satima_qr_mode" value="sendiri" @checked($qr_mode === 'sendiri')> Gambar sendiri
                    </label></div>
                    <div class="radio"><label>
                        <input type="radio" name="satima_qr_mode" value="tanpa" @checked($qr_mode === 'tanpa')> Tanpa gambar
                    </label></div>
                </div>
                <div class="form-group" id="qr-berkas-grup" @if ($qr_mode !== 'sendiri') style="display:none" @endif>
                    <label for="satima_qr_berkas">Berkas Gambar</label>
                    @if ($qr_berkas)
                        <p><img src="{{ $qr_berkas }}" alt="Gambar QR saat ini" style="max-width:80px;max-height:80px;border:1px solid #ddd;padding:2px"></p>
                    @endif
                    <input type="file" id="satima_qr_berkas" name="satima_qr_berkas" class="form-control input-sm" accept=".png,.jpg,.jpeg,.gif,.webp">
                    <p class="help-block small">PNG/JPG/GIF/WebP. Gambar dipusatkan, maksimal 22% lebar QR, di atas kotak putih supaya QR tetap terbaca.
                        @if ($qr_berkas) Kosongkan bila tidak ingin mengganti gambar. @endif</p>
                </div>
                <p class="help-block small">Berlaku untuk QR surat TinyMCE, surat RTF, surat dinas, dan tombol QR di Arsip Layanan. Simpan, lalu pindai contoh di samping untuk memastikan QR masih terbaca.</p>
            </div>
            <div class="col-md-4 text-center">
                <label>Contoh QR Saat Ini</label>
                <p><img src="{{ $qr_contoh }}" alt="Contoh QR code surat" style="width:160px;height:160px;border:1px solid #ddd"></p>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(function() {
            $('input[name="satima_qr_mode"]').on('change', function() {
                $('#qr-berkas-grup').toggle($(this).val() === 'sendiri');
            });
        });
    </script>
@endpush
