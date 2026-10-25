<?php

/*
 * Satima — uji LampiranPath tanpa PHPUnit.
 *
 * Membuktikan bahwa nama lampiran bawaan pengguna tidak bisa dipakai menyertakan berkas di luar
 * folder lampiran (celah RCE penyertaan berkas). Pemakaian: php bin/satima/uji-lampiran-path.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);

    exit;
}

$akar = dirname(__DIR__, 2);

require_once $akar . '/app/Libraries/Surat/LampiranPath.php';

use App\Libraries\Surat\LampiranPath;

$gagal = 0;
$uji   = static function (string $nama, bool $lolos, string $info = '') use (&$gagal): void {
    echo($lolos ? 'OK    ' : 'GAGAL ') . $nama . ($lolos || $info === '' ? '' : " — {$info}") . "\n";
    $gagal += $lolos ? 0 : 1;
};

// Fixture: satu folder lampiran "sistem" berisi f-1.01, dan satu folder RAHASIA di luarnya
$tmp     = sys_get_temp_dir() . '/satima-uji-lampiran-' . getmypid();
$sistem  = $tmp . '/sistem/';
$rahasia = $tmp . '/rahasia/';
@mkdir($sistem . 'f-1.01', 0o777, true);
@mkdir($rahasia . 'jahat', 0o777, true);
file_put_contents($sistem . 'f-1.01/view.php', '<?php /* sah */');
file_put_contents($sistem . 'f-1.01/data.php', '<?php /* sah */');
file_put_contents($rahasia . 'jahat/view.php', '<?php /* jahat */');

$base = [$sistem];

// 1. Nama sah menemukan view.php & data.php di dalam folder sistem
$r = LampiranPath::resolve('f-1.01', $base);
$uji('nama sah ditemukan', is_array($r) && $r[1] === realpath($sistem . 'f-1.01/view.php') && $r[0] === realpath($sistem . 'f-1.01/data.php'), json_encode($r));

// 2. Penyertaan via "../": nama dengan traversal ditolak
$tujuan = str_repeat('../', 1) . 'rahasia/jahat';
$uji('traversal ../ ditolak', LampiranPath::resolve($tujuan, $base) === null);

// 3. Nama absolut ditolak (ada "/")
$uji('jalur absolut ditolak', LampiranPath::resolve('/etc/passwd', $base) === null);

// 4. Nama dengan garis miring ditolak
$uji('garis miring ditolak', LampiranPath::resolve('f-1.01/../jahat', $base) === null);
$uji('garis miring balik ditolak', LampiranPath::resolve('f-1.01\\jahat', $base) === null);

// 5. Byte NUL ditolak
$uji('byte NUL ditolak', LampiranPath::resolve("f-1.01\0", $base) === null);

// 6. Nama sah tapi folder tidak ada → null (bukan error)
$uji('lampiran tidak ada → null', LampiranPath::resolve('f-9.99', $base) === null);

// 7. Tautan simbolik yang mengarah keluar folder ditolak oleh pemeriksaan realpath
if (function_exists('symlink') && @symlink($rahasia . 'jahat', $sistem . 'tembus') !== false) {
    $uji('symlink keluar folder ditolak', LampiranPath::resolve('tembus', $base) === null);
} else {
    echo "LEWAT symlink keluar folder ditolak — symlink tidak tersedia\n";
}

// 8. namaSah menolak pola berbahaya, menerima nama lampiran nyata
foreach (['..', '.', '', ' ', 'a/b', 'a..b', '../a', 'f 1.01'] as $buruk) {
    if (LampiranPath::namaSah($buruk)) {
        $uji('namaSah menolak ' . json_encode($buruk), false);
    }
}
foreach (['f-1.01', 'f-2.01-kematian', 'n-1', 'sps'] as $baik) {
    if (! LampiranPath::namaSah($baik)) {
        $uji('namaSah menerima ' . json_encode($baik), false);
    }
}
$uji('namaSah: pola berbahaya ditolak, nama nyata diterima', true);

// Bersihkan fixture
@unlink($sistem . 'tembus');
foreach ([$sistem . 'f-1.01/view.php', $sistem . 'f-1.01/data.php', $rahasia . 'jahat/view.php'] as $f) {
    @unlink($f);
}
foreach ([$sistem . 'f-1.01', $rahasia . 'jahat', $sistem, $rahasia, $tmp] as $d) {
    @rmdir($d);
}

echo $gagal === 0 ? "\nSemua lolos\n" : "\n{$gagal} gagal\n";

exit($gagal > 0 ? 1 : 0);
