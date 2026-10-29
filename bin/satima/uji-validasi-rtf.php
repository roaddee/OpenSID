<?php

/*
 * Satima — uji ValidasiRtf (template RTF unggahan desa). Pemakaian: php bin/satima/uji-validasi-rtf.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);

    exit;
}

$akar = dirname(__DIR__, 2);

require_once $akar . '/app/Libraries/Surat/Rtf/ValidasiRtf.php';

use App\Libraries\Surat\Rtf\ValidasiRtf;

$gagal = 0;
$uji   = static function (string $nama, bool $lolos, string $info = '') use (&$gagal): void {
    echo($lolos ? 'OK    ' : 'GAGAL ') . $nama . ($lolos || $info === '' ? '' : " — {$info}") . "\n";
    $gagal += $lolos ? 0 : 1;
};

// Semua template sistem harus lolos
$sistem = glob($akar . '/storage/app/template/rtf/sistem/*.rtf');
$tolak  = [];
foreach ($sistem as $f) {
    if ($a = ValidasiRtf::periksa((string) file_get_contents($f))) {
        $tolak[basename($f)] = $a;
    }
}
$uji(count($sistem) . ' template sistem lolos', $tolak === [], json_encode($tolak, JSON_UNESCAPED_UNICODE));

$dasar = '{\rtf1\ansi\deff0{\fonttbl{\f0 Arial;}}\f0 Nama: [nama]\par ';
$kasus = [
    'RTF sederhana lolos'                => [$dasar . '}', true],
    'BOM + spasi di awal lolos'          => ["\xEF\xBB\xBF \n" . $dasar . '}', true],
    'kurung di-escape lolos'             => [$dasar . 'Kode \{x\} dan \\\\ }', true],
    'field biasa (PAGE) lolos'           => [$dasar . '{\field{\*\fldinst { PAGE }}{\fldrslt {1}}}}', true],
    'INCLUDEPICTURE lokal lolos'         => [$dasar . '{\field{\*\fldinst { INCLUDEPICTURE "logo.png" }}{\fldrslt {}}}}', true],
    'bukan RTF ditolak'                  => ['<?php echo 1; ?>', false],
    'HTML berekstensi rtf ditolak'       => ['<html><body>x</body></html>', false],
    'kurung tidak seimbang ditolak'      => [$dasar, false],
    'byte NUL ditolak'                   => [$dasar . "\0}", false],
    'objek OLE ditolak'                  => [$dasar . '{\object\objemb{\*\objclass Equation.3}{\*\objdata 0105}}}', false],
    'objdata saja ditolak'               => [$dasar . '{\*\objdata 01050000}}', false],
    'field DDEAUTO ditolak'              => [$dasar . '{\field{\*\fldinst { DDEAUTO c:\\\\windows\\\\system32\\\\cmd.exe "/k calc" }}{\fldrslt {}}}}', false],
    'field DDEAUTO terpecah ditolak'     => [$dasar . '{\field{\*\fldinst {\rtlch DDE}{\rtlch AUTO x}}{\fldrslt {}}}}', false],
    'INCLUDEPICTURE http ditolak'        => [$dasar . '{\field{\*\fldinst { INCLUDEPICTURE "http://contoh.test/a.png" \\\\d }}{\fldrslt {}}}}', false],
    'INCLUDETEXT UNC ditolak'            => [$dasar . '{\field{\*\fldinst { INCLUDETEXT "\\\\\\\\server\\\\share\\\\a.doc" }}{\fldrslt {}}}}', false],
    'DDEAUTO tanpa fldrslt ditolak'      => [$dasar . '{\field{\*\fldinst { DDEAUTO cmd }}}}', false],
    'kata "object" di teks biasa lolos'  => [$dasar . 'object dan objdata sebagai teks}', true],
];

foreach ($kasus as $nama => [$isi, $harapLolos]) {
    $a = ValidasiRtf::periksa($isi);
    $uji($nama, ($a === []) === $harapLolos, json_encode($a, JSON_UNESCAPED_UNICODE));
}

echo $gagal === 0 ? "\nSemua lolos\n" : "\n{$gagal} gagal\n";

exit($gagal > 0 ? 1 : 0);
