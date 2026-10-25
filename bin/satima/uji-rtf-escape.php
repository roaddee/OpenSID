<?php

/*
 * Satima — uji RtfEscape tanpa PHPUnit. Pemakaian: php bin/satima/uji-rtf-escape.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);

    exit;
}

require_once dirname(__DIR__, 2) . '/app/Libraries/Surat/Rtf/RtfEscape.php';

use App\Libraries\Surat\Rtf\RtfEscape;

$gagal = 0;
$uji   = static function (string $nama, bool $lolos, string $info = '') use (&$gagal): void {
    echo($lolos ? 'OK    ' : 'GAGAL ') . $nama . ($lolos || $info === '' ? '' : " — {$info}") . "\n";
    $gagal += $lolos ? 0 : 1;
};

$kasus = [
    'teks biasa'            => ['Budi Santoso', 'Budi Santoso'],
    'backslash'             => ['a\\b', 'a\\\\b'],
    'kurung kurawal'        => ['Budi }}\\b tebal', 'Budi \\}\\}\\\\b tebal'],
    'buka kurawal'          => ['{x}', '\\{x\\}'],
    'tab'                   => ["a\tb", 'a\\tab b'],
    'baris baru jadi par'   => ["a\nb", 'a\\par b'],
    'cr dibuang'            => ["a\r\nb", 'a\\par b'],
    'aksen é (BMP)'         => ['é', '\\u233?'],
    'huruf arab'            => ['ب', '\\u1576?'],
    'emoji (di luar BMP)'   => ['😀', '\\u-10179?\\u-8704?'], // U+1F600 → surrogate D83D DE00
];

foreach ($kasus as $nama => [$masuk, $harap]) {
    $hasil = RtfEscape::escape($masuk);
    $uji($nama, $hasil === $harap, 'dapat ' . json_encode($hasil) . ', harap ' . json_encode($harap));
}

// escapeInline: baris baru jadi \line
$uji('inline: baris baru jadi line', RtfEscape::escapeInline("a\nb") === 'a\\line b', json_encode(RtfEscape::escapeInline("a\nb")));

// Properti keamanan: hasil tidak boleh memuat "{", "}", atau "\" yang bukan bagian dari escape sah
$jahat    = 'X}}{\\rtlch\\b jadi tebal{\\field}';
$escaped  = RtfEscape::escape($jahat);
// Buang semua escape sah; tidak boleh ada backslash, "{", atau "}" tersisa
$sisa = (string) preg_replace('/\\\\(\\\\|\\{|\\}|tab |par |line |u-?\\d+\\?)/', '', $escaped);
$uji('injeksi RTF: hanya escape sah, tak ada backslash/brace telanjang', ! preg_match('/[\\\\{}]/', $sisa), 'sisa: ' . json_encode($sisa));

// Round-trip lewat LibreOffice bila tersedia: nilai ter-escape tampil sebagai teks apa adanya
$soffice = trim((string) @shell_exec('command -v soffice 2>/dev/null'));
if ($soffice !== '') {
    $tmp  = sys_get_temp_dir() . '/satima-rtf-' . getmypid();
    @mkdir($tmp, 0o777, true);
    $nilai = 'Budi }{\\b CORET';
    $rtf   = '{\\rtf1\\ansi\\deff0{\\fonttbl{\\f0 Arial;}}\\f0 Nama: ' . RtfEscape::escape($nilai) . '\\par}';
    file_put_contents("{$tmp}/uji.rtf", $rtf);
    @shell_exec(escapeshellarg($soffice) . ' -env:UserInstallation=file://' . escapeshellarg($tmp . '/profil') . ' --headless --convert-to txt:Text --outdir ' . escapeshellarg($tmp) . ' ' . escapeshellarg("{$tmp}/uji.rtf") . ' 2>/dev/null');
    $teks = @file_get_contents("{$tmp}/uji.txt");
    $uji('LibreOffice: nilai tampil utuh sebagai teks', is_string($teks) && str_contains($teks, 'Budi }{\\b CORET'), json_encode($teks));
    @system('rm -rf ' . escapeshellarg($tmp));
} else {
    echo "LEWAT round-trip LibreOffice — soffice tidak tersedia\n";
}

echo $gagal === 0 ? "\nSemua lolos\n" : "\n{$gagal} gagal\n";

exit($gagal > 0 ? 1 : 0);
