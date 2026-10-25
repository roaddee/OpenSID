<?php

/*
 * Satima — uji BersihkanKodeIsian tanpa PHPUnit (vendor produksi tidak memuat paket dev).
 *
 * Pemakaian (dari akar repo): php bin/satima/uji-bersihkan-kode.php
 * Keluar dengan kode 1 bila ada yang gagal.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);

    exit;
}

$akar = dirname(__DIR__, 2);

require_once $akar . '/app/Libraries/Surat/Rtf/BersihkanKodeIsian.php';

use App\Libraries\Surat\Rtf\BersihkanKodeIsian;

$gagal = 0;
$uji   = static function (string $nama, bool $lolos, string $info = '') use (&$gagal): void {
    echo($lolos ? 'OK    ' : 'GAGAL ') . $nama . ($lolos || $info === '' ? '' : " — {$info}") . "\n";
    $gagal += $lolos ? 0 : 1;
};

// Kasus satuan
$kasus = [
    'kode dipecah Word'          => ['{\b [}{\rtlch\fcs1 \ab\af1 \ltrch\fcs0 \caps\f43\lang1057 form_nama_wanita}]', '{\b [form_nama_wanita]}'],
    'kode sudah bersih'          => ['Nama: [nama].', 'Nama: [nama].'],
    'spasi dibuang seperti 2407' => ['[nama _pria]', '[nama_pria]'],
    'nomor surat berpanjang'     => ['No. [nomor_surat, 3]', 'No. [nomor_surat,3]'],
    'kotak kosong bukan kode'    => ['[ ] Ya', '[ ] Ya'],
    'teks berparagraf bukan kode' => ['a [ bukan \par kode ] b', 'a [ bukan \par kode ] b'],
    '"[" tanpa pasangan'         => ['a [x [nama] b', 'a [x [nama] b'],
    'grup tujuan di dalam kode'  => ['{[na{\*\bkmkstart z}ma]}', '{[nama]}'],
    'grup dibuka di dalam kode'  => ['[na{\i ma]}', '[nama]{}'],
];

foreach ($kasus as $nama => [$masuk, $harap]) {
    $hasil = BersihkanKodeIsian::bersihkan($masuk);
    $uji($nama, $hasil === $harap, "dapat {$hasil}, harap {$harap}");
}

// Semua template sistem: keseimbangan grup RTF tidak berubah, tidak ada sisa kata kendali di kode
$saldo = static function (string $rtf): int {
    $rtf = (string) preg_replace('/\\\\[\\\\{}]/', '', $rtf);

    return substr_count($rtf, '{') - substr_count($rtf, '}');
};

$berkas = glob($akar . '/storage/app/template/rtf/sistem/*.rtf');
$uji('template sistem ditemukan', count($berkas) === 43, count($berkas) . ' berkas');

foreach ($berkas as $f) {
    $asli   = (string) file_get_contents($f);
    $bersih = BersihkanKodeIsian::bersihkan($asli);
    preg_match_all('/\[([^\[\]\\\\{}]{1,80})\]/', $bersih, $m);
    $sampah = array_filter($m[1], static fn ($k) => preg_match('/caps|insrsid|rtlch|ltrch|hich|loch|charrsid/', $k));

    if ($saldo($asli) !== $saldo($bersih) || $sampah !== []) {
        $uji(basename($f), false, 'saldo ' . $saldo($asli) . ' → ' . $saldo($bersih) . '; sisa: ' . implode(' ', $sampah));
    }
}

$uji('saldo grup & kode bersih di semua template', $gagal === 0);

exit($gagal > 0 ? 1 : 0);
