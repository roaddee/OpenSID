<?php

/*
 * File ini bagian dari OpenSID edisi Satima.
 */

namespace App\Libraries\Surat\Rtf;

/**
 * Validasi isi template RTF yang diunggah desa.
 *
 * Template ini dirender lalu diunduh dan dibuka di Word/LibreOffice oleh staf desa dan warga, jadi
 * bentuk RTF yang dikenal sebagai jalur serangan dokumen ditolak:
 *  - objek OLE tertanam (\object, \objdata, \objemb, \objlink, \objautlink): dipakai banyak eksploit
 *    RTF (mis. Equation Editor CVE-2017-11882);
 *  - field yang mengambil/menjalankan sesuatu dari luar: DDE/DDEAUTO, INCLUDETEXT, INCLUDEPICTURE /
 *    IMPORT dengan alamat http(s)/UNC, LINK, dan AUTOTEXT/MACROBUTTON.
 * Selain itu berkas harus benar-benar RTF (diawali {\rtf), tanpa byte NUL, dan kurung grupnya seimbang.
 * Kelas murni (tanpa framework) supaya bisa diuji satuan.
 */
final class ValidasiRtf
{
    /** @return list<string> daftar alasan penolakan; kosong = lolos */
    public static function periksa(string $isi): array
    {
        $alasan = [];

        $awal = ltrim(preg_replace('/^\xEF\xBB\xBF/', '', $isi) ?? $isi);
        if (! str_starts_with($awal, '{\rtf')) {
            return ['Berkas bukan dokumen RTF (harus diawali {\rtf). Simpan ulang dari Word/LibreOffice sebagai "Rich Text Format (.rtf)".'];
        }

        if (str_contains($isi, "\0")) {
            $alasan[] = 'Berkas mengandung byte biner (NUL); RTF yang sah berupa teks.';
        }

        // Keseimbangan grup: abaikan \{ \} \\ yang di-escape
        $tanpaEscape = (string) preg_replace('/\\\\[\\\\{}]/', '', $isi);
        $saldo       = 0;
        $minimum     = 0;
        $n           = strlen($tanpaEscape);

        for ($i = 0; $i < $n; $i++) {
            if ($tanpaEscape[$i] === '{') {
                $saldo++;
            } elseif ($tanpaEscape[$i] === '}') {
                $saldo--;
                $minimum = min($minimum, $saldo);
            }
        }

        if ($saldo !== 0 || $minimum < 0) {
            $alasan[] = 'Struktur RTF rusak (kurung kurawal tidak seimbang).';
        }

        if (preg_match('/\\\\(object|objdata|objemb|objlink|objautlink|objupdate)(?![a-z])/i', $isi, $m)) {
            $alasan[] = "Template berisi objek tertanam (\\{$m[1]}). Hapus objek OLE (persamaan, lembar Excel, dsb.) dari template.";
        }

        // Instruksi field: teks dalam {\*\fldinst ...}. Kata kendali & kurung dibuang agar kata kunci terbaca utuh.
        if (preg_match_all('/\\\\fldinst(.{0,2000}?)\\\\fldrslt/is', $isi, $fields)) {
            foreach ($fields[1] as $f) {
                $teks = strtoupper(trim((string) preg_replace(['/\\\\[a-z]+-?\\d* ?/i', '/[{}]/'], ' ', $f)));
                $teks = (string) preg_replace('/\\s+/', ' ', $teks);

                if (preg_match('/^(DDE|DDEAUTO|LINK|AUTOTEXT|AUTOTEXTLIST|MACROBUTTON)\\b/', $teks, $k)) {
                    $alasan[] = "Template berisi field {$k[1]} yang bisa menjalankan/mengambil data dari luar dokumen.";
                } elseif (preg_match('/^(INCLUDETEXT|INCLUDEPICTURE|IMPORT)\\b.*(HTTPS?:|FILE:|\\\\\\\\)/', $teks, $k)) {
                    $alasan[] = "Template berisi field {$k[1]} yang mengambil berkas dari luar dokumen.";
                }
            }
        }

        // Jaring pengaman: DDEAUTO hampir tidak pernah ada di dokumen wajar
        if (preg_match('/\\\\fldinst[^}]{0,300}DDEAUTO/i', $isi) && ! preg_grep('/DDEAUTO/', $alasan)) {
            $alasan[] = 'Template berisi field DDEAUTO yang bisa menjalankan/mengambil data dari luar dokumen.';
        }

        return array_values(array_unique($alasan));
    }
}
