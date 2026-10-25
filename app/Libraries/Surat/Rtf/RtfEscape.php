<?php

/*
 * File ini bagian dari OpenSID edisi Satima.
 */

namespace App\Libraries\Surat\Rtf;

/**
 * Meng-escape nilai teks sebelum dimasukkan ke dokumen RTF.
 *
 * Nilai isian surat berasal dari data penduduk dan isian form (dikendalikan pengguna). RTF memberi
 * makna khusus pada "\", "{", dan "}", jadi tanpa escape sebuah nama seperti "Budi }}\b" bisa
 * merusak struktur dokumen atau menyisipkan kata kendali (mis. menebalkan sisa surat). Karakter non
 * ASCII juga harus ditulis sebagai escape unicode "\uN?" agar tampil benar di semua pembaca.
 *
 * Yang di-escape HANYA nilai isian; potongan RTF yang memang struktur (kop, logo, foto, QR, tabel
 * pengikut) tidak boleh dilewatkan ke sini.
 */
final class RtfEscape
{
    public static function escape(string $teks): string
    {
        $keluar = '';

        // Buang urutan UTF-8 tidak valid agar pemecahan per titik kode tidak gagal
        $teks      = function_exists('mb_scrub') ? mb_scrub($teks, 'UTF-8') : (string) mb_convert_encoding($teks, 'UTF-8', 'UTF-8');
        $kodepoint = preg_split('//u', $teks, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        foreach ($kodepoint as $ch) {
            $kode = self::ordUtf8($ch);

            $keluar .= match (true) {
                $ch === '\\'          => '\\\\',
                $ch === '{'           => '\\{',
                $ch === '}'           => '\\}',
                $ch === "\t"          => '\\tab ',
                $ch === "\n"          => '\\par ',
                $ch === "\r"          => '',
                $kode < 0x20          => '',           // kontrol lain dibuang
                $kode < 0x80          => $ch,          // ASCII tercetak apa adanya
                default               => self::escapeUnicode($kode),
            };
        }

        return $keluar;
    }

    /**
     * Baris baru di dalam nilai jadi "\line " (pindah baris dalam paragraf), bukan "\par ".
     * Dipakai untuk isian yang disisipkan ke tengah kalimat (mis. daftar yang dipisah baris).
     */
    public static function escapeInline(string $teks): string
    {
        return str_replace('\\par ', '\\line ', self::escape($teks));
    }

    private static function escapeUnicode(int $kode): string
    {
        // RTF \uN memakai integer 16-bit bertanda; titik kode di luar BMP jadi pasangan surrogate.
        // "?" adalah karakter pengganti untuk pembaca lama (RTF menghitungnya sebagai 1 byte: \ucN=1).
        if ($kode > 0xFFFF) {
            $kode -= 0x10000;
            $hi = 0xD800 + ($kode >> 10);
            $lo = 0xDC00 + ($kode & 0x3FF);

            return self::ganda($hi) . self::ganda($lo);
        }

        return self::ganda($kode);
    }

    private static function ganda(int $unit16): string
    {
        $bertanda = $unit16 >= 0x8000 ? $unit16 - 0x10000 : $unit16;

        return '\\u' . $bertanda . '?';
    }

    private static function ordUtf8(string $ch): int
    {
        $kode = mb_ord($ch, 'UTF-8');

        return $kode === false ? 0x3F : $kode; // '?' bila gagal
    }
}
