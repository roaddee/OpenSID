<?php

/*
 * File ini bagian dari OpenSID edisi Satima.
 */

namespace App\Libraries\Surat;

/**
 * Menentukan lokasi berkas data.php / view.php sebuah lampiran surat dengan aman.
 *
 * Nama lampiran berasal dari isian form surat (dikendalikan pengguna), lalu di-include oleh
 * mesin lampiran. Tanpa penyaringan, nama seperti "../../.." membuka celah penyertaan berkas
 * (RCE). Kelas ini murni (tidak menyentuh framework) agar bisa diuji satuan:
 *
 *  1. nama wajib berupa nama folder polos — diawali huruf/angka, hanya huruf, angka, titik,
 *     garis bawah, dan strip; tidak boleh mengandung "/", "\\", "..", atau byte NUL;
 *  2. berkas dicari hanya di dalam folder dasar yang diberikan; hasil realpath wajib tetap
 *     berada di dalam folder dasar itu (pertahanan kedua terhadap tautan simbolik/relatif).
 */
final class LampiranPath
{
    /**
     * @param list<string> $baseDirs folder dasar lampiran, diperiksa berurutan (desa lalu sistem)
     *
     * @return array{0: string|null, 1: string}|null [data.php|null, view.php]; null bila tidak sah/tidak ada
     */
    public static function resolve(string $nama, array $baseDirs): ?array
    {
        if (! self::namaSah($nama)) {
            return null;
        }

        $nama  = strtolower(trim($nama));
        $hasil = ['data' => null, 'view' => null];

        foreach ($baseDirs as $folder) {
            $akar = realpath($folder);
            if ($akar === false) {
                continue;
            }
            $akar = rtrim($akar, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            foreach (array_keys($hasil) as $jenis) {
                if ($hasil[$jenis] !== null) {
                    continue;
                }
                $berkas = realpath($folder . DIRECTORY_SEPARATOR . $nama . DIRECTORY_SEPARATOR . $jenis . '.php');
                if ($berkas !== false && str_starts_with($berkas, $akar)) {
                    $hasil[$jenis] = $berkas;
                }
            }
        }

        if ($hasil['view'] === null) {
            return null;
        }

        return [$hasil['data'], $hasil['view']];
    }

    public static function namaSah(string $nama): bool
    {
        // Byte NUL diperiksa pada masukan mentah: trim() membuang NUL di ujung sehingga bisa lolos
        if (str_contains($nama, "\0")) {
            return false;
        }

        $nama = strtolower(trim($nama));

        if ($nama === '' || str_contains($nama, '..')) {
            return false;
        }

        return (bool) preg_match('/^[a-z0-9][a-z0-9._-]*$/', $nama);
    }
}
