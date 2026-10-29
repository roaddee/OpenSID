<?php

/*
 * File ini bagian dari OpenSID edisi Satima.
 *
 * Mesin surat RTF: backport dari Surat_model::surat_rtf() OpenSID v2407.0.0,
 * tetapi mengambil nilai isian dari mesin kode isian 2607 (TinyMCE::getFormatedKodeIsian)
 * sehingga tidak menduplikasi logika pengambilan data penduduk/desa.
 */

namespace App\Libraries\Surat;

use App\Libraries\Surat\Rtf\AliasKodeLama;
use App\Libraries\Surat\Rtf\BersihkanKodeIsian;
use App\Libraries\Surat\Rtf\RtfEscape;
use App\Models\FormatSurat;

final class RtfEngine
{
    /** Kode yang nilainya potongan RTF mentah (jangan di-escape). */
    private array $kodeStruktural = ['kop_surat', 'logo_desa', 'logo', 'foto_penduduk', 'pas_foto', 'foto', 'qr_code', 'qrcode', 'penandatangan', 'atas_nama'];

    /**
     * Penanda PNG placeholder di template 2407 ([awal hex, akhir hex]): awal = header PNG
     * dengan ukuran khas placeholder, akhir = chunk IEND placeholder.
     */
    public const LOGO = ['89504e470d0a1a0a0000000d4948445200000040000000400806000000aa', 'e33874670000000049454e44ae426082'];
    public const FOTO = ['89504e470d0a1a0a0000000d4948445200000080000000800806000000c3', 'afbe45630000000049454e44ae426082'];
    public const QR   = ['89504e470d0a1a0a0000000d4948445200000084000000840802000000de', '04c5cd360000000049454e44ae426082'];

    /** Sisa kode yang tidak dikenali pada render terakhir (untuk diagnosa/uji). */
    public array $kodeTidakDikenal = [];

    /**
     * @param array<string,mixed> $nilai   peta kode 2607: '[Kode]' => nilai (getFormatedKodeIsian($data, true))
     * @param array<string,mixed> $konteks ['input'=>array, 'qr_path'=>?string, 'foto_path'=>?string, 'logo_path'=>?string]
     */
    public function render(FormatSurat $surat, array $nilai, array $konteks = []): string
    {
        $buffer = BersihkanKodeIsian::bersihkan($this->ambilTemplate($surat));

        // Properti dokumen (judul/penulis/operator) seperti 2407
        $buffer = $this->setProperti($buffer, $surat);

        // Kode struktural (potongan RTF mentah) lebih dulu
        $buffer = $this->sisipkanStruktural($buffer, $nilai, $konteks);

        // Peta skalar: normalkan kunci 2607 ke huruf kecil tanpa kurung, lalu tambah alias kode lama
        $skalar = $this->petaSkalar($nilai, $surat, $konteks);

        // Ganti setiap [kode] yang dikenal (case-insensitive), nilai di-escape RTF
        $this->kodeTidakDikenal = [];
        $buffer                 = preg_replace_callback('/\[([A-Za-z0-9_, \-]{1,60})\]/', function (array $m) use ($skalar): string {
            $kode = strtolower(trim($m[1]));
            // nomor_surat berpanjang: [nomor_surat, 3]
            $kodeDasar = preg_replace('/\s*,.*$/', '', $kode);

            if (array_key_exists($kode, $skalar)) {
                $v = (string) $skalar[$kode];
                // Seperti 2407: kode sebutan_* mengikuti huruf kodenya ([Sebutan_Desa] → "Desa")
                if (str_starts_with($kode, 'sebutan_') && function_exists('caseWord')) {
                    $v = caseWord(str_replace(' ', '', $m[1]), $v);
                }

                return RtfEscape::escape($v);
            }
            if (array_key_exists($kodeDasar, $skalar)) {
                return RtfEscape::escape((string) $skalar[$kodeDasar]);
            }

            $this->kodeTidakDikenal[$m[1]] = true;

            return $m[0]; // biarkan apa adanya supaya kelihatan saat uji
        }, $buffer);

        $this->kodeTidakDikenal = array_keys($this->kodeTidakDikenal);

        return $buffer;
    }

    private function ambilTemplate(FormatSurat $surat): string
    {
        $berkas = $surat->templateRtf();

        return $berkas === null ? '' : (string) file_get_contents($berkas);
    }

    private function setProperti(string $buffer, FormatSurat $surat): string
    {
        $nama = strtoupper('surat ' . $surat->nama);

        return (string) preg_replace(
            ['/\{\\\\title\s.+?\}/', '/\{\\\\author\s.+?\}/', '/\{\\\\operator\s.+?\}/'],
            ['{\\title ' . $nama . '}', '{\\author ' . RtfEscape::escape((string) setting('sebutan_desa') . ' ' . identitas('nama_desa')) . '}', '{\\operator OpenSID}'],
            $buffer
        );
    }

    /**
     * Peta kode skalar huruf kecil => nilai, sudah termasuk alias kode lama & kode terhitung.
     *
     * @return array<string,string>
     */
    private function petaSkalar(array $nilai, FormatSurat $surat, array $konteks): array
    {
        $skalar = [];

        foreach ($nilai as $kode => $v) {
            $k = strtolower(trim((string) $kode, '[] '));
            if ($k === '' || in_array($k, $this->kodeStruktural, true)) {
                continue;
            }
            $skalar[$k] = $this->bersihkanNilai($v);
        }

        // Isian form: [form_x] dan [x]
        foreach (($konteks['input'] ?? []) as $key => $val) {
            if (is_array($val)) {
                continue;
            }
            $skalar['form_' . strtolower($key)] ??= $this->bersihkanNilai($val);
            $skalar[strtolower($key)]           ??= $this->bersihkanNilai($val);
        }

        // Alias kode lama → ambil dari kode 2607
        foreach (AliasKodeLama::peta() as $lama => $baru) {
            if (! array_key_exists($lama, $skalar) && array_key_exists($baru, $skalar)) {
                $skalar[$lama] = $skalar[$baru];
            }
        }

        // Seperti 2407: nama pemohon selalu huruf besar
        if (isset($skalar['nama'])) {
            $skalar['nama'] = mb_strtoupper($skalar['nama']);
        }

        // Kode terhitung yang tidak selalu ada di peta 2607
        $skalar['tgl_surat']    ??= tgl_indo(date('Y m d'));
        $skalar['tahun']        ??= date('Y');
        $skalar['bulan_romawi'] ??= bulan_romawi((int) date('m'));
        $skalar['kode_surat']   ??= (string) $surat->kode_surat;
        $skalar['kode_desa']    ??= (string) identitas('kode_desa');
        $skalar['judul_surat']  ??= strtoupper('surat ' . $surat->nama);

        // Seperti 2407: tanggal masa berlaku ditulis lengkap ("29 Oktober 2026"), bukan dd-mm-yyyy
        foreach (['mulai_berlaku', 'berlaku_sampai', 'tgl_akhir', 'berlaku_dari', 'form_berlaku_dari', 'form_berlaku_sampai', 'form_mulai_berlaku'] as $k) {
            if (isset($skalar[$k]) && preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', trim($skalar[$k])) && ($t = strtotime(trim($skalar[$k]))) !== false) {
                $skalar[$k] = tgl_indo(date('Y m d', $t));
            }
        }

        // Nilai format nomor surat dari 2607 masih memuat kode ([kode_surat]/[tahun]/...): uraikan
        foreach (['format_nomor_surat', 'nomor_surat_lengkap'] as $k) {
            if (isset($skalar[$k])) {
                $skalar[$k] = (string) preg_replace_callback('/\[([a-z_]+)\]/i', static fn ($m) => $skalar[strtolower($m[1])] ?? $m[0], $skalar[$k]);
            }
        }

        return $skalar;
    }

    /** Nilai skalar: ubah <br> jadi baris baru (nanti RtfEscape → \par), buang tag lain. */
    private function bersihkanNilai(mixed $v): string
    {
        $s = is_scalar($v) ? (string) $v : '';
        $s = preg_replace('/<br\s*\/?>/i', "\n", $s) ?? $s;

        return trim(strip_tags($s));
    }

    /**
     * Kode struktural: nilai berupa potongan RTF mentah (tanda tangan ber-\par, kop, logo,
     * foto, QR). Untuk sekarang: tanda tangan dari nilai 2607 (atas_nama) dengan <br> → \par;
     * kop/logo/foto/QR disisipkan bila placeholdernya ada.
     */
    private function sisipkanStruktural(string $buffer, array $nilai, array $konteks): string
    {
        // Tanda tangan: [penandatangan] = atas_nama (baris dipisah \par), [atas_nama] sama
        $atasNama = $this->ambilNilai($nilai, 'atas_nama');
        if ($atasNama !== null) {
            $rtf    = $this->keRtfBaris($atasNama);
            $buffer = $this->gantiKodeMentah($buffer, ['penandatangan', 'atas_nama'], $rtf);
        }

        // Gambar: ganti PNG placeholder bawaan template (logo, pas foto, QR) seperti 2407
        // Foto & QR tanpa berkas → gambar kosong, agar siluet/QR palsu bawaan template tidak tercetak
        $buffer = self::gantiGambar($buffer, self::LOGO, $konteks['logo_path'] ?? null);
        $buffer = self::gantiGambar($buffer, self::FOTO, $konteks['foto_path'] ?? null, true);
        $buffer = self::gantiGambar($buffer, self::QR, $konteks['qr_path'] ?? null, true);

        // Kop surat otomatis (bila dipakai)
        if (stripos($buffer, '[kop_surat]') !== false) {
            $kop    = FCPATH . 'storage/app/template/rtf/raw/kop_surat_auto.rtf';
            $isiKop = is_file($kop) ? (string) file_get_contents($kop) : '';
            $buffer = $this->gantiKodeMentah($buffer, ['kop_surat'], $isiKop);
        }

        return $buffer;
    }

    /**
     * Ganti setiap PNG placeholder (heksadesimal di \\pngblip) dengan gambar PNG lain.
     * Berbeda dari 2407 yang memakai regex rakus: awal dicari mundur dari akhir yang ketemu,
     * jadi gambar yang sudah diganti tidak ikut terpotong. Hanya PNG yang disisipkan.
     *
     * @param array{0:string,1:string} $penanda
     */
    public static function gantiGambar(string $buffer, array $penanda, ?string $berkas, bool $kosongBilaTiada = false): string
    {
        if ($berkas !== null && is_file($berkas)) {
            $bytes = self::bytesPng((string) file_get_contents($berkas));
        } elseif ($kosongBilaTiada) {
            $bytes = self::pngKosong();
        } else {
            return $buffer;
        }
        if ($bytes === null) {
            return $buffer;
        }
        $hex = bin2hex($bytes);

        [$awal, $akhir] = $penanda;
        $mulaiCari      = 0;

        while (($posAkhir = strpos($buffer, $akhir, $mulaiCari)) !== false) {
            $posAwal = strrpos(substr($buffer, 0, $posAkhir), $awal);
            if ($posAwal === false) {
                $mulaiCari = $posAkhir + strlen($akhir);

                continue;
            }
            $panjang   = $posAkhir + strlen($akhir) - $posAwal;
            $buffer    = substr_replace($buffer, $hex, $posAwal, $panjang);
            $mulaiCari = $posAwal + strlen($hex);
        }

        return $buffer;
    }

    /** \\pngblip hanya menerima PNG: JPG/GIF/WebP diubah ke PNG lewat GD. */
    private static function bytesPng(string $bytes): ?string
    {
        if (str_starts_with($bytes, "\x89PNG\r\n\x1a\n")) {
            return $bytes;
        }
        if (! function_exists('imagecreatefromstring')) {
            return null;
        }
        $img = @imagecreatefromstring($bytes);
        if ($img === false) {
            return null;
        }
        imagesavealpha($img, true);
        ob_start();
        imagepng($img);
        imagedestroy($img);

        return (string) ob_get_clean();
    }

    /** PNG 1x1 transparan. */
    private static function pngKosong(): string
    {
        return (string) base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==', true);
    }

    private function ambilNilai(array $nilai, string $kodeLower): ?string
    {
        foreach ($nilai as $k => $v) {
            if (strtolower(trim((string) $k, '[] ')) === $kodeLower) {
                return is_scalar($v) ? (string) $v : null;
            }
        }

        return null;
    }

    /** Ubah teks ber-<br>/baris-baru menjadi RTF (tiap bagian di-escape, dipisah \par). */
    private function keRtfBaris(string $teks): string
    {
        $teks  = preg_replace('/<br\s*\/?>/i', "\n", $teks) ?? $teks;
        $baris = preg_split('/\r\n|\r|\n/', strip_tags($teks)) ?: [];

        return implode('\\par ', array_map(static fn ($b) => RtfEscape::escape(trim($b)), $baris));
    }

    private function gantiKodeMentah(string $buffer, array $kodeLower, string $rtf): string
    {
        return (string) preg_replace_callback('/\[([A-Za-z0-9_ ]{1,40})\]/', static function (array $m) use ($kodeLower, $rtf): string {
            return in_array(strtolower(trim($m[1])), $kodeLower, true) ? $rtf : $m[0];
        }, $buffer);
    }
}
