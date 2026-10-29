<div class="tab-pane active" id="pengaturan-umum">

    @include('admin.pengaturan_surat.kembali')

    <div class="box-body form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="kode_surat">Kode/Klasifikasi Surat</label>
            <div class="col-sm-7">
                <select class="form-control input-sm required" id="kode_surat" name="kode_surat" data-placeholder="-- Pilih Kode/Klasifikasi Surat --">
                    @if ($klasifikasiSurat)
                        <option value="{{ $klasifikasiSurat->kode }}">
                            {{ $klasifikasiSurat->kode . ' - ' . $klasifikasiSurat->nama }}</option>
                    @elseif ($suratMaster->kode_surat)
                        <option value="{{ $suratMaster->kode_surat }}">
                            {{ $suratMaster->kode_surat }}</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Nama Layanan</label>
            <div class="col-sm-7">
                <div class="input-group">
                    <span class="input-group-addon input-sm">Surat</span>
                    <input type="text" class="form-control input-sm nama_terbatas required" id="nama" name="nama" placeholder="Nama Layanan" value="{{ $suratMaster->nama }}" />
                </div>
            </div>
        </div>
        @if (strpos($form_action, 'insert') !== false && is_null($suratMaster->template))
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nama">Pemohon Surat</label>
                <div class="col-sm-3">
                    <select class="form-control input-sm" id="pemohon_surat" name="pemohon_surat">
                        <option value="warga" selected>Warga</option>
                        <option value="non_warga">Bukan Warga</option>
                    </select>
                </div>
            </div>
        @endif

        <div class="form-group">
            <label class="col-sm-3 control-label" for="nama">Masa Berlaku Default</label>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-2">
                        <input type="number" class="form-control input-sm" id="masa_berlaku" name="masa_berlaku" onchange="masaBerlaku()" value="{{ $suratMaster->masa_berlaku ?? 0 }}">
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control input-sm" id="satuan_masa_berlaku" name="satuan_masa_berlaku">
                            @foreach ($masaBerlaku as $kode_masa => $judul_masa)
                                <option value="{{ $kode_masa }}" @selected($suratMaster->satuan_masa_berlaku === $kode_masa)>{{ $judul_masa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <label class="text-muted text-red">Isi 0 jika tidak digunakan dan maksimal 31.</label>
            </div>
        </div>

        @if ($orientations)
            <div class="form-group">
                <label class="col-sm-3 control-label">Orientasi Kertas</label>
                <div class="col-sm-7">
                    <select class="form-control input-sm select2 required" name="orientasi">
                        @foreach ($orientations as $value)
                            <option value="{{ $value }}" @selected(($suratMaster->orientasi ?? $default_orientations) === $value)>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        @if ($orientations)
            <div class="form-group">
                <label class="col-sm-3 control-label">Ukuran Kertas</label>
                <div class="col-sm-7">
                    <select class="form-control input-sm select2 required" name="ukuran">
                        @foreach ($sizes as $value)
                            <option value="{{ $value }}" @selected(($suratMaster->ukuran ?? $default_sizes) === $value)>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        <div class="form-group">
            <label class="col-sm-3 control-label">Gunakan Margin Kertas Global</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="margin: 0 0 5px 0">
                <label id="lmg1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($margin_global)">
                    <input
                        id="img1"
                        type="radio"
                        name="margin_global"
                        @checked($margin_global)
                        class="form-check-input"
                        type="radio"
                        value="1"
                        autocomplete="off"
                    >Ya
                </label>
                <label id="lmg2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$margin_global)">
                    <input
                        id="img2"
                        type="radio"
                        name="margin_global"
                        class="form-check-input"
                        @checked(!$margin_global)
                        type="radio"
                        value="0"
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
            <div id="manual_margin" style="display: none;">
                <div class="col-sm-7 col-sm-offset-3">
                    <div class="row">
                        @foreach ($margins as $key => $value)
                            <div class="col-sm-6">
                                <div class="input-group" style="margin-top: 3px; margin-bottom: 3px">
                                    <span class="input-group-addon input-sm">{{ ucwords($key) }}</span>
                                    <input
                                        type="number"
                                        class="form-control input-sm required"
                                        min="0"
                                        name="{{ $key }}"
                                        min="0"
                                        max="10"
                                        step="0.01"
                                        style="text-align:right;"
                                        value="{{ $value }}"
                                    >
                                    <span class="input-group-addon input-sm">cm</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Gunakan Penomoran Surat Global</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="margin: 0 0 5px 0">
                <label id="lmg11" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($format_nomor_global)">
                    <input
                        id="img11"
                        type="radio"
                        name="format_nomor_global"
                        @checked($format_nomor_global)
                        class="form-check-input"
                        type="radio"
                        value="1"
                        autocomplete="off"
                    >Ya
                </label>
                <label id="lmg21" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$format_nomor_global)">
                    <input
                        id="img21"
                        type="radio"
                        name="format_nomor_global"
                        class="form-check-input"
                        @checked(!$format_nomor_global)
                        type="radio"
                        value="0"
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
            <div id="manual_nomor_surat" style="display: none;">
                <div class="col-sm-7 col-sm-offset-3">
                    <input type="text" class="form-control input-sm" name="format_nomor" placeholder="[nomor_surat, 3]/PK-TBT/[bulan_romawi]/[tahun]" value="{{ $format_nomor }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Lampiran</label>
            <div class="col-sm-7">
                @if ($viewOnly)
                    @foreach (explode(',', $suratMaster->lampiran) as $item)
                        <input type="hidden" name="lampiran[]" value="{{ $item }}">
                    @endforeach
                @endif
                <select class="form-control input-sm select2" name="lampiran[]" multiple="multiple" data-placeholder="Pilih Lampiran">
                    @foreach ($daftar_lampiran as $value)
                        <option value="{{ $value }}" @selected(in_array($value, explode(',', $suratMaster->lampiran)))>{{ $value }} </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Tampilkan QR Code</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                <label id="lq1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($suratMaster->qr_code)">
                    <input
                        id="iq1"
                        type="radio"
                        name="qr_code"
                        class="form-check-input"
                        type="radio"
                        value="1"
                        @checked($suratMaster->qr_code)
                        autocomplete="off"
                    >Ya
                </label>
                <label id="lq2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$suratMaster->qr_code)">
                    <input
                        id="iq2"
                        type="radio"
                        name="qr_code"
                        class="form-check-input"
                        type="radio"
                        value="0"
                        @checked(!$suratMaster->qr_code)
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Tampilkan QR Code TTE</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                <label id="lq1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($suratMaster->qr_code_tte)">
                    <input
                        id="iq1"
                        type="radio"
                        name="qr_code_tte"
                        class="form-check-input"
                        type="radio"
                        value="1"
                        @checked($suratMaster->qr_code_tte)
                        autocomplete="off"
                    >Ya
                </label>
                <label id="lq2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$suratMaster->qr_code_tte)">
                    <input
                        id="iq2"
                        type="radio"
                        name="qr_code_tte"
                        class="form-check-input"
                        type="radio"
                        value="0"
                        @checked(!$suratMaster->qr_code_tte)
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Tampilkan Header</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                <label id="lh1" for="ih1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($header == 1)">
                    <input
                        id="ih1"
                        type="radio"
                        name="header"
                        class="form-check-input"
                        type="radio"
                        value="1"
                        @checked($header == 1)
                        autocomplete="off"
                    >Semua Halaman
                </label>
                <label id="lh2" for="lh2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($header == 2)">
                    <input
                        id="ih2"
                        type="radio"
                        name="header"
                        class="form-check-input"
                        type="radio"
                        value="2"
                        @checked($header == 2)
                        autocomplete="off"
                    >Hanya Halaman Awal
                </label>
                <label id="lh3" for="lh3" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($header == 0)">
                    <input
                        id="ih3"
                        type="radio"
                        name="header"
                        class="form-check-input"
                        type="radio"
                        value="0"
                        @checked($header == 0)
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Tampilkan Footer</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                <label id="lf1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($footer)">
                    <input
                        id="if1"
                        type="radio"
                        name="footer"
                        class="form-check-input"
                        type="radio"
                        value="1"
                        @checked($footer)
                        autocomplete="off"
                    >Ya
                </label>
                <label id="lf2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$footer)">
                    <input
                        id="if2"
                        type="radio"
                        name="footer"
                        class="form-check-input"
                        type="radio"
                        value="0"
                        @checked(!$footer)
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label" for="logo_garuda">Logo Burung Garuda</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                <label id="lbg1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($suratMaster->logo_garuda)">
                    <input
                        id="ibg1"
                        type="radio"
                        name="logo_garuda"
                        class="form-check-input"
                        type="radio"
                        value="1"
                        @checked($suratMaster->logo_garuda)
                        autocomplete="off"
                    >Ya
                </label>
                <label id="lbg2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$suratMaster->logo_garuda)">
                    <input
                        id="ibg2"
                        type="radio"
                        name="logo_garuda"
                        class="form-check-input"
                        type="radio"
                        value="0"
                        @checked(!$suratMaster->logo_garuda)
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
        </div>

        {{-- Satima: engine render surat --}}
        @if (\Illuminate\Support\Facades\Schema::hasColumn('tweb_surat_format', 'engine'))
            @php($adaRtf = $suratMaster->id ? $suratMaster->templateRtf() !== null : false)
            <div class="form-group">
                <label class="col-sm-3 control-label" for="engine">Engine Surat</label>
                <div class="col-sm-8">
                    <select id="engine" name="engine" class="form-control input-sm">
                        <option value="tinymce" @selected(($suratMaster->engine ?? 'tinymce') === 'tinymce')>TinyMCE (editor di browser)</option>
                        <option value="rtf" @selected(($suratMaster->engine ?? '') === 'rtf') @disabled(! $adaRtf)>RTF (template Word/LibreOffice){{ $adaRtf ? '' : ' — template belum tersedia' }}</option>
                    </select>
                    <p class="help-block">RTF mencetak dari template .rtf: tampilan di layar sama dengan hasil cetak. Isian form tetap memakai pengaturan surat ini.</p>
                    @if ($viewOnly ?? false)
                        <button type="button" id="simpan-engine" class="btn btn-social btn-info btn-sm" data-url="{{ ci_route('surat_master.update', $suratMaster->id) }}">
                            <i class="fa fa-check"></i> Simpan Engine
                        </button>
                        <span id="hasil-engine" class="help-block" style="display:inline-block;margin-left:8px"></span>
                    @endif
                </div>
            </div>
            {{-- Satima: template RTF milik desa --}}
            @if ($suratMaster->id)
                @php($rtfDesaBerkas = FCPATH . 'desa/template-surat/rtf/' . $suratMaster->url_surat . '.rtf')
                @php($rtfDesa = is_file($rtfDesaBerkas))
                <div class="form-group">
                    <label class="col-sm-3 control-label">Template RTF</label>
                    <div class="col-sm-8">
                        <p class="form-control-static">
                            @if ($rtfDesa)
                                <span class="label label-success">Template desa</span> diunggah {{ date('d-m-Y H:i', filemtime($rtfDesaBerkas)) }}
                            @elseif ($adaRtf)
                                <span class="label label-default">Template sistem</span>
                            @else
                                <span class="label label-warning">Belum ada template RTF</span>
                            @endif
                        </p>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;align-items:center">
                            @if ($adaRtf)
                                <a id="template-rtf-unduh" href="{{ ci_route('surat_master.template_rtf_unduh', $suratMaster->id) }}" class="btn btn-social btn-default btn-sm"><i class="fa fa-download"></i> Unduh template</a>
                            @endif
                            <input type="file" id="template-rtf-berkas" accept=".rtf" class="input-sm" style="width:auto;max-width:100%">
                            <button type="button" id="template-rtf-unggah" class="btn btn-social btn-primary btn-sm" data-url="{{ ci_route('surat_master.template_rtf_unggah', $suratMaster->id) }}"><i class="fa fa-upload"></i> Unggah template desa</button>
                            @if ($rtfDesa)
                                <button type="button" id="template-rtf-hapus" class="btn btn-social btn-danger btn-sm" data-url="{{ ci_route('surat_master.template_rtf_hapus', $suratMaster->id) }}"><i class="fa fa-trash"></i> Hapus template desa</button>
                            @endif
                        </div>
                        <span id="hasil-template-rtf" class="help-block"></span>
                        <p class="help-block small">Buat di Word/LibreOffice (bisa mulai dari "Unduh template"), simpan sebagai <b>Rich Text Format (.rtf)</b>.
                            Kode isian sama dengan surat ini ([nama], [nik], [tgl_surat], …), ditambah [qr_code], [logo], [foto_penduduk], dan [penandatangan].
                            Objek tertanam (persamaan, lembar Excel) dan field yang mengambil data dari luar dokumen akan ditolak.</p>
                    </div>
                </div>
                @push('scripts')
                    <script>
                        $(function() {
                            const tampil = (pesan, ok) => $('#hasil-template-rtf').text(pesan).css('color', ok ? 'green' : 'red');
                            $('#template-rtf-unggah').on('click', function() {
                                const berkas = $('#template-rtf-berkas')[0].files[0];
                                if (!berkas) { tampil('Pilih berkas .rtf terlebih dahulu', false); return; }
                                const data = new FormData();
                                data.append('template_rtf', berkas);
                                data.append(csrfParam, getCsrfToken());
                                const $btn = $(this).prop('disabled', true);
                                $.ajax({ url: $btn.data('url'), method: 'POST', data: data, processData: false, contentType: false })
                                    .done(r => { tampil(r.message, true); setTimeout(() => location.reload(), 1200); })
                                    .fail(x => tampil((x.responseJSON && x.responseJSON.message) || 'Gagal mengunggah', false))
                                    .always(() => $btn.prop('disabled', false));
                            });
                            $('#template-rtf-hapus').on('click', function() {
                                if (!confirm('Hapus template RTF desa untuk surat ini?')) return;
                                const $btn = $(this).prop('disabled', true);
                                $.post($btn.data('url'), { [csrfParam]: getCsrfToken() })
                                    .done(r => { tampil(r.message, true); setTimeout(() => location.reload(), 1200); })
                                    .fail(x => tampil((x.responseJSON && x.responseJSON.message) || 'Gagal menghapus', false))
                                    .always(() => $btn.prop('disabled', false));
                            });
                        });
                    </script>
                @endpush
            @endif

            @if ($viewOnly ?? false)
                @push('scripts')
                    <script>
                        $(function() {
                            $('#simpan-engine').on('click', function() {
                                const $btn = $(this).prop('disabled', true);
                                $.post($btn.data('url'), {
                                    engine: $('#engine').val(),
                                    [csrfParam]: getCsrfToken()
                                }).done(function(r) {
                                    $('#hasil-engine').text(r.message || 'Tersimpan').css('color', 'green');
                                }).fail(function(x) {
                                    $('#hasil-engine').text((x.responseJSON && x.responseJSON.message) || 'Gagal menyimpan').css('color', 'red');
                                }).always(function() {
                                    $btn.prop('disabled', false);
                                });
                            });
                        });
                    </script>
                @endpush
            @endif
        @endif

        @if (setting('tte'))
            <div class="form-group">
                <label class="col-sm-3 control-label" for="kecamatan">Kirim ke Kecamatan</label>
                <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                    <label id="lk1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($suratMaster->kecamatan)">
                        <input
                            id="ik1"
                            type="radio"
                            name="kecamatan"
                            class="form-check-input"
                            type="radio"
                            value="1"
                            @checked($suratMaster->kecamatan)
                            autocomplete="off"
                        >Ya
                    </label>
                    <label id="lk2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$suratMaster->kecamatan)">
                        <input
                            id="ik2"
                            type="radio"
                            name="kecamatan"
                            class="form-check-input"
                            type="radio"
                            value="0"
                            @checked(!$suratMaster->kecamatan)
                            autocomplete="off"
                        >Tidak
                    </label>
                </div>
            </div>
        @endif

        <div class="form-group">
            <label class="col-sm-3 control-label">Sediakan di Layanan Mandiri</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                <label id="lm1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($suratMaster->mandiri)">
                    <input
                        id="im1"
                        type="radio"
                        name="mandiri"
                        class="form-check-input"
                        type="radio"
                        value="1"
                        @checked($suratMaster->mandiri)
                        autocomplete="off"
                    >Ya
                </label>
                <label id="lm2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$suratMaster->mandiri)">
                    <input
                        id="im2"
                        type="radio"
                        name="mandiri"
                        class="form-check-input"
                        type="radio"
                        value="0"
                        @checked(!$suratMaster->mandiri)
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
        </div>

        <div class="form-group" id="syarat" {{ jecho($suratMaster->mandiri, false, 'style="display:none;"') }}>
            <label class="col-sm-3 control-label">Syarat Surat</label>
            <div class="col-sm-7">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tabeldata" style="width: 100%;">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="checkall" /></th>
                                <th>NO</th>
                                <th>NAMA DOKUMEN</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            var margin_global = $("[name='margin_global']:checked").val()
            if (margin_global == 0) {
                $('#manual_margin').show()
            }

            format_nomor_surat($("[name='format_nomor_global']:checked").val())
        })

        function format_nomor_surat(params) {
            if (params == 0) {
                $('#manual_nomor_surat').show()
                $('input[name="format_nomor"]').addClass('required')
            } else {
                $('#manual_nomor_surat').hide()
                $('input[name="format_nomor"]').removeClass('required')
            }
        }

        $("[name='margin_global']").change(function() {
            var val = $(this).val()
            if (val == 0) {
                $('#manual_margin').show()
            } else {
                $('#manual_margin').hide()
            }
        })

        $("[name='format_nomor_global']").change(function() {
            format_nomor_surat($(this).val())
        })

        $('#kode_surat').select2({
            tags: true,
            ajax: {
                url: SITE_URL + 'surat_master/apisurat',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term || '',
                        page: params.page || 1,
                    };
                },
                cache: true
            },
            placeholder: function() {
                return $(this).data('placeholder');
            },
            minimumInputLength: 1,
            allowClear: true,
            escapeMarkup: function(markup) {
                return markup;
            },
            createTag: function(params) {
                // batasi hanya 10 karakter
                var term = params.term.substring(0, 10);
                return {
                    id: term,
                    text: term,
                    newOption: true
                };
            },
            templateResult: function(data) {
                var $result = $("<span></span>").text(data.text);
                if (data.newOption) {
                    $result.append(" <em>(Buat Baru, maksimal 10 karakter)</em>");
                }
                return $result;
            },
            insertTag: function(data, tag) {
                data.push(tag);
            }
        });

        var TableData = $('#tabeldata').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            bPaginate: false,
            ajax: "{{ ci_route('surat_master.syaratSuratDatatables', $suratMaster->id) }}",
            drawCallback: function(settings) {
                // Disable all checkbox inputs after the DataTable is rendered
                $('input[type="checkbox"]').prop('disabled', {{ $viewOnly }});
            },
            columns: [{
                    data: 'ceklist',
                    class: 'padat',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'DT_RowIndex',
                    class: 'padat',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'ref_syarat_nama',
                    name: 'ref_syarat_nama',
                    searchable: true,
                    orderable: true
                },
            ],
            order: [
                [2, 'asc']
            ]
        });
    </script>
@endpush
