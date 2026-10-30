<?php

/*
 * File ini bagian dari OpenSID edisi Satima.
 *
 * Migrasi Satima: gambar tengah QR surat (logo desa / gambar sendiri / tanpa gambar).
 */

use App\Traits\Migrator;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2026103081
{
    use Migrator;

    public function up()
    {
        // Satima: pilihan "Navy Satima" untuk Warna Tema Admin, sekaligus jadi bawaan.
        $pilihan = json_encode([
            'skin-satima'       => 'Navy Satima',
            'skin-blue'         => 'Biru',
            'skin-blue-light'   => 'Biru Terang',
            'skin-black'        => 'Hitam',
            'skin-black-light'  => 'Hitam Terang',
            'skin-red'          => 'Merah',
            'skin-red-light'    => 'Merah Terang',
            'skin-yellow'       => 'Kuning',
            'skin-yellow-light' => 'Kuning Terang',
            'skin-purple'       => 'Ungu',
            'skin-purple-light' => 'Ungu Terang',
            'skin-green'        => 'Hijau',
            'skin-green-light'  => 'Hijau Terang',
        ]);

        // Semua desa (multi desa ikut), tanpa menyentuh nilai yang sudah dipilih.
        DB::table('setting_aplikasi')->where('key', 'warna_tema_admin')->update(['option' => $pilihan]);

        // Ungu adalah bawaan lama OpenSID: situs yang belum pernah mengganti warna dipindah ke Navy Satima.
        // Desa yang memang memilih Ungu cukup memilihnya lagi di Pengaturan → Aplikasi.
        DB::table('setting_aplikasi')->where('key', 'warna_tema_admin')
            ->where(static fn ($q) => $q->where('value', 'skin-purple')->orWhereNull('value')->orWhere('value', ''))
            ->update(['value' => 'skin-satima']);

        cache()->flush();
    }
}
