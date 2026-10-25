<?php

/*
 * File ini bagian dari OpenSID edisi Satima.
 *
 * Migrasi Satima: dasar engine surat berbasis berkas (RTF, lalu DOCX).
 * Nomor berakhiran 81 = migrasi Satima, tidak bentrok dengan pola 01/51/71 upstream.
 */

use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2026102581
{
    use Migrator;

    public function up()
    {
        $this->tambahKolomEngineSurat();
        $this->tambahPengaturanLibreoffice();
        $this->hapusBarisSuratHantu();
    }

    /**
     * Engine yang merender surat: 'tinymce' (bawaan), 'rtf', atau 'docx'.
     *
     * Dipisah dari kolom `jenis` karena `jenis` 1/2 (RTF lama) dipakai upstream untuk baris
     * yang disembunyikan RemoveRtfScope; engine dipilih per surat TinyMCE (jenis 3/4) yang
     * form isian dan kode isiannya tetap dipakai.
     */
    protected function tambahKolomEngineSurat(): void
    {
        if (Schema::hasColumn('tweb_surat_format', 'engine')) {
            return;
        }

        Schema::table('tweb_surat_format', static function (Blueprint $table): void {
            $table->string('engine', 10)->default('tinymce')->after('jenis');
        });
    }

    /**
     * Kunci diberi awalan satima_ karena `libreoffice_path` termasuk unusedKeys() upstream
     * dan dihapus Migrasi_required setiap kali migrasi berjalan.
     */
    protected function tambahPengaturanLibreoffice(): void
    {
        $this->createSettings([
            [
                'judul'      => 'Path LibreOffice',
                'key'        => 'satima_libreoffice_path',
                'value'      => '',
                'keterangan' => 'Program soffice untuk mengubah surat RTF/DOCX menjadi PDF, misalnya /usr/bin/soffice. Kosongkan bila tidak ada; surat RTF/DOCX diarsipkan tanpa PDF.',
                'jenis'      => 'input-text',
                'option'     => null,
                'attribute'  => null,
                'kategori'   => 'sistem',
            ],
            [
                'judul'      => 'Batas Proses LibreOffice',
                'key'        => 'satima_libreoffice_maks_proses',
                'value'      => 1,
                'keterangan' => 'Jumlah konversi PDF yang boleh berjalan bersamaan. Naikkan hanya bila server punya RAM cukup (±300 MB per proses).',
                'jenis'      => 'input-number',
                'option'     => null,
                'attribute'  => json_encode(['class' => 'required', 'min' => 1, 'max' => 4, 'step' => 1]),
                'kategori'   => 'sistem',
            ],
        ]);
    }

    /**
     * Migrator::tambah_surat_tinymce() (dipakai DataAwalSeeder) menyimpan surat bawaan dengan
     * jenis = 1 walau komentarnya TINYMCE_SISTEM. Setiap instalasi baru jadi punya 43 salinan
     * surat TinyMCE berlabel RTF (url tanpa awalan "sistem-") yang tersembunyi RemoveRtfScope.
     *
     * Surat RTF asli dari 2407 ke bawah tidak punya template HTML, jadi baris yang punya
     * template di jenis 1 pasti salinan ini. Yang pernah dipakai (log/permohonan) tidak disentuh.
     */
    protected function hapusBarisSuratHantu(): void
    {
        $ids = DB::table('tweb_surat_format')
            ->where('jenis', 1)
            ->whereNotNull('template')
            ->where('url_surat', 'not like', 'sistem-%')
            ->whereNotIn('id', DB::table('log_surat')->whereNotNull('id_format_surat')->select('id_format_surat'))
            ->whereNotIn('id', DB::table('permohonan_surat')->whereNotNull('id_surat')->select('id_surat'))
            ->pluck('id');

        if ($ids->isNotEmpty()) {
            DB::table('tweb_surat_format')->whereIn('id', $ids)->delete();
        }
    }
}
