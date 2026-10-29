<?php

/*
 * File ini bagian dari OpenSID edisi Satima.
 *
 * Migrasi Satima: gambar tengah QR surat (logo desa / gambar sendiri / tanpa gambar).
 */

use App\Traits\Migrator;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2026102981
{
    use Migrator;

    public function up()
    {
        // '' = logo desa (bawaan), '-' = tanpa gambar, lainnya = nama berkas di LOKASI_MEDIA.
        // Kategori hidden: diatur dari tab "QR Code" di Pengaturan Surat, bukan daftar pengaturan umum.
        $this->createSetting([
            'judul'      => 'Gambar QR Code Surat',
            'key'        => 'satima_qr_gambar',
            'value'      => '',
            'keterangan' => 'Gambar di tengah QR code surat: kosong = logo desa, "-" = tanpa gambar, atau nama berkas gambar sendiri.',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'hidden',
        ]);
    }
}
