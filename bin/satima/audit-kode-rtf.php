<?php

/*
 * Satima — audit kode isian template surat RTF (tahap 2 backport RTF).
 *
 * Membaca template RTF di storage/app/template/rtf/sistem/, lalu menggolongkan setiap
 * kode isian [..] menurut dari mana nilainya akan datang setelah di-backport ke 2607:
 *
 *   umum      kode yang diganti oleh Surat_model::surat_rtf()/atas_nama() di 2407
 *             → akan ditangani alias umum (App\Libraries\Surat\Rtf\AliasKodeLama)
 *   khusus    kode yang diganti oleh data_rtf_<url>.php surat itu di 2407
 *             → perlu alias per surat (sumber nilainya terlihat di file itu)
 *   form      isian form surat padanan di 2607 (kode_isian), dipakai lewat aturan
 *             [form_x] / [x] seperti di 2407
 *   form-2407 isian form 2407 yang TIDAK ditanyakan form 2607 → form 2607 perlu ditambah
 *             isian, atau kode dipetakan ke data lain
 *   kotor     kode yang tidak bisa dibaca setelah dibersihkan (rusak di template)
 *   asing     tidak ditemukan di mana pun → diperiksa manual
 *
 * Tidak memerlukan database. Kode 2407 hanya dibaca sebagai teks; tidak ada yang di-include.
 *
 * Pemakaian (dari akar repo):
 *   php bin/satima/audit-kode-rtf.php --opensid-2407=/jalur/ke/OpenSID-v2407.0.0 [--json=hasil.json] [--md=hasil.md]
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);

    exit;
}

$opsi = getopt('', ['opensid-2407:', 'json:', 'md:']);
$akar = dirname(__DIR__, 2);
$lama = rtrim((string) ($opsi['opensid-2407'] ?? ''), '/');

if ($lama === '' || ! is_file($lama . '/donjo-app/models/Surat_model.php')) {
    fwrite(STDERR, "Berikan --opensid-2407=<folder checkout OpenSID v2407.0.0>\n");

    exit(2);
}

require_once $akar . '/app/Libraries/Surat/Rtf/BersihkanKodeIsian.php';

use App\Libraries\Surat\Rtf\BersihkanKodeIsian;

$peta  = json_decode((string) file_get_contents($akar . '/storage/app/template/rtf/peta-url-2407.json'), true, 512, JSON_THROW_ON_ERROR);
$tiny  = json_decode((string) file_get_contents($akar . '/storage/app/template/impor/template-surat-tinymce.json'), true, 512, JSON_THROW_ON_ERROR);
$tinyByUrl = [];

foreach ($tiny as $t) {
    $tinyByUrl[$t['url_surat']] = $t;
}

/**
 * Kode [..] dan awalan kode dinamis ("[anggota_nama_" . $i . "]") dari teks sumber PHP.
 *
 * @return array{0: array<string, true>, 1: list<string>}
 */
function kode_dari_php(string $isi): array
{
    preg_match_all('/\[([A-Za-z][A-Za-z0-9_]*)\]/', $isi, $m);
    $kode = array_fill_keys(array_map('strtolower', $m[1]), true);

    preg_match_all('/["\']\[([A-Za-z][A-Za-z0-9_]*)["\']\s*\./', $isi, $a);
    preg_match_all('/["\']\[([A-Za-z][A-Za-z0-9_]*)\{?\$/', $isi, $b);

    return [$kode, array_values(array_unique(array_map('strtolower', array_merge($a[1], $b[1]))))];
}

/** Nama field form (name="...") di file form PHP 2407. */
function field_form(string $isi): array
{
    preg_match_all('/name\s*=\s*["\']([A-Za-z_][A-Za-z0-9_]*)(\[\])?["\']/', $isi, $m);

    return array_fill_keys(array_map('strtolower', $m[1]), true);
}

function cocok_awalan(string $kode, array $awalan): bool
{
    foreach ($awalan as $a) {
        if (str_starts_with($kode, $a) && preg_match('/^\d+$/', substr($kode, strlen($a)))) {
            return true;
        }
    }

    return false;
}

// Kode umum 2407: semua yang diganti di Surat_model (surat_rtf, atas_nama, substitusi_nomor_surat, ...)
[$umum, $umumAwalan] = kode_dari_php((string) file_get_contents($lama . '/donjo-app/models/Surat_model.php'));
$umum['nomor_surat'] = true; // [nomor_surat, 3] ditangani substitusi_nomor_surat()

// Field form umum 2407 (partial yang di-include setiap form surat)
$fieldUmum = [];

foreach (glob($lama . '/donjo-app/views/surat/form/*.php') as $f) {
    $fieldUmum += field_form((string) file_get_contents($f));
}

$hasil = [];
$total = ['umum' => 0, 'khusus' => 0, 'form' => 0, 'form-2407' => 0, 'kotor' => 0, 'asing' => 0];

foreach ($peta as $url2407 => $info) {
    $url2607 = $info['url_2607'] ?? $url2407;
    $berkas  = $akar . '/storage/app/template/rtf/sistem/' . $url2607 . '.rtf';
    if (! is_file($berkas)) {
        continue;
    }

    $rtf = BersihkanKodeIsian::bersihkan((string) file_get_contents($berkas));
    preg_match_all('/\[([^\[\]\\\\{}]{1,80})\]/', $rtf, $m);
    $kodeRtf = array_values(array_unique($m[1]));

    $folder = $lama . '/template-surat/' . $url2407 . '/';
    [$khusus, $khususAwalan] = is_file($folder . "data_rtf_{$url2407}.php")
        ? kode_dari_php((string) file_get_contents($folder . "data_rtf_{$url2407}.php"))
        : [[], []];

    $field2407 = $fieldUmum;

    foreach (["{$url2407}.php", "data_form_{$url2407}.php"] as $f) {
        if (is_file($folder . $f)) {
            $field2407 += field_form((string) file_get_contents($folder . $f));
        }
    }

    $form2607 = [];
    $surat    = $tinyByUrl[$url2607] ?? null;
    $kodeIsian = $surat ? (is_string($surat['kode_isian']) ? json_decode($surat['kode_isian'], true) : $surat['kode_isian']) : [];

    foreach ((array) $kodeIsian as $item) {
        $form2607[strtolower(str_replace(['[form_', ']'], '', (string) ($item['kode'] ?? '')))] = true;
    }

    $baris = ['url_2407' => $url2407, 'url_2607' => $info['url_2607'], 'nama' => $info['nama'], 'kode' => []];

    foreach ($kodeRtf as $kode) {
        $k    = strtolower(trim($kode));
        $dasar = preg_replace('/^form_/', '', $k);

        if (! preg_match('/^[a-z][a-z0-9_, -]*$/', $k) || str_contains($k, 'caps')) {
            $gol = 'kotor';
        } elseif (isset($umum[$k]) || cocok_awalan($k, $umumAwalan) || str_starts_with($k, 'nomor_surat')) {
            $gol = 'umum';
        } elseif (isset($khusus[$k]) || cocok_awalan($k, $khususAwalan)) {
            $gol = 'khusus';
        } elseif (isset($form2607[$dasar]) || isset($form2607[$k])) {
            $gol = 'form';
        } elseif (isset($field2407[$dasar]) || isset($field2407[$k]) || isset($field2407[preg_replace('/\d+$/', '', $dasar)])) {
            $gol = 'form-2407';
        } else {
            $gol = 'asing';
        }

        $baris['kode'][$gol][] = $kode;
        $total[$gol]++;
    }

    $hasil[] = $baris;
}

// Ringkasan ke layar
printf("%d template diaudit\n", count($hasil));

foreach ($total as $gol => $n) {
    printf("  %-10s %4d\n", $gol, $n);
}

if (isset($opsi['json'])) {
    file_put_contents($opsi['json'], json_encode(['total' => $total, 'surat' => $hasil], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

if (isset($opsi['md'])) {
    $md = "# Audit kode isian template RTF\n\nDibuat oleh `bin/satima/audit-kode-rtf.php` pada " . date('Y-m-d') . ".\n\n";
    $md .= "| Golongan | Jumlah | Artinya |\n| --- | --- | --- |\n";
    $arti = [
        'umum'      => 'Ditangani alias umum (diganti `Surat_model` 2407)',
        'khusus'    => 'Perlu alias per surat (dari `data_rtf_<url>.php` 2407)',
        'form'      => 'Isian form surat 2607, aturan `[form_x]`/`[x]`',
        'form-2407' => 'Isian form 2407 yang tidak ada di form 2607',
        'kotor'     => 'Kode rusak di template',
        'asing'     => 'Tidak dikenali, periksa manual',
    ];

    foreach ($total as $gol => $n) {
        $md .= "| {$gol} | {$n} | {$arti[$gol]} |\n";
    }

    $md .= "\n## Per surat\n\n| Surat 2607 | umum | khusus | form | form-2407 | kotor | asing |\n| --- | --- | --- | --- | --- | --- | --- |\n";

    foreach ($hasil as $b) {
        $c = static fn ($g) => count($b['kode'][$g] ?? []);
        $md .= sprintf("| %s | %d | %d | %d | %d | %d | %d |\n", $b['url_2607'] ?? ($b['url_2407'] . ' (tanpa padanan)'), $c('umum'), $c('khusus'), $c('form'), $c('form-2407'), $c('kotor'), $c('asing'));
    }

    $md .= "\n## Daftar kerja\n";

    foreach ($hasil as $b) {
        $perlu = array_intersect_key($b['kode'], array_flip(['form-2407', 'kotor', 'asing']));
        if ($perlu === []) {
            continue;
        }
        $md .= "\n### " . ($b['url_2607'] ?? $b['url_2407']) . " ({$b['nama']})\n\n";

        foreach ($perlu as $gol => $daftar) {
            $md .= "- **{$gol}:** `[" . implode(']`, `[', $daftar) . "]`\n";
        }
    }

    file_put_contents($opsi['md'], $md);
}
