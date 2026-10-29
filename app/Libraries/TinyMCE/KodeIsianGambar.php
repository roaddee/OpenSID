<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries\TinyMCE;

use App\Models\LogSurat;
use App\Models\Penduduk;

class KodeIsianGambar
{
    private $urls_id;

    public function __construct(private $request, private $result, private $surat = null, private $lampiran = false, private bool $pratinjau = false)
    {
    }

    /**
     * @param bool $pratinjau Satima: PDF pratinjau (arsip dihapus setelahnya) → pakai QR contoh, bukan QR verifikasi
     */
    public static function set($request, $result, $surat = null, $lampiran = false, bool $pratinjau = false): array
    {
        $result = str_replace(['alt="[logo]"', 'alt="[logo_bsre]"', 'alt="[foto_penduduk]"'], 'alt=""', $result);

        return (new self($request, $result, $surat, $lampiran, $pratinjau))->setKodeIsianGambar();
    }

    public function setKodeIsianGambar(): array
    {
        // Logo Surat
        $file_logo = $this->request['logo_garuda'] ? FCPATH . LOGO_GARUDA : gambar_desa(identitas()->logo, false, true);
        $this->replacePlaceholder('[logo]', $file_logo, 90, 90);

        // Logo BSrE
        if (setting('tte') == 1) {
            $this->replacePlaceholder('[logo_bsre]', FCPATH . LOGO_BSRE, height: 50 );
        }

        // Foto Penduduk
        $fotoPath = FCPATH . LOKASI_USER_PICT . Penduduk::find($this->surat['id_pend'])->foto;
        $this->replacePlaceholder('[foto_penduduk]', $fotoPath, 90, 'auto');

        // QR_Code Surat
        $this->handleQrCode();

        return [
            'result'  => $this->result,
            'urls_id' => $this->urls_id ?? null,
        ];
    }

    /**
     * Mengganti placeholder dengan tag gambar berformat base64 jika file tersedia.
     */
    private function replacePlaceholder(string $placeholder, string $filePath, int $width = 90, mixed $height = 90): void
    {
        $realPath = realpath($filePath);
        $imgTag   = ''; // Placeholder dihapus jika gambar tidak tersedia
        if ($realPath && file_exists($realPath)) {
            $base64   = base64_encode(file_get_contents($realPath));
            $mimeType = mime_content_type($realPath);
            $imgSrc   = "data:{$mimeType};base64,{$base64}";
            $imgTag   = "<img src=\"{$imgSrc}\" width=\"{$width}\" height=\"{$height}\" />";
        }

        $this->result = str_replace($placeholder, $imgTag, $this->result);
    }

    /** Satima: QR contoh di pratinjau (dummy) — diganti QR verifikasi asli saat surat dicetak. */
    public const ALT_QR_CONTOH = 'qrcode-surat-contoh';

    public const ALT_QR = 'qrcode-surat';

    /**
     * Menangani logika kode QR, memastikan kompatibilitas dengan Html2Pdf.
     *
     * Satima: dulu pratinjau mengganti [qr_code] dengan QR dummy ("dummy qrcode OpenSID") yang ikut
     * terkirim ke cetak final, sehingga tag [qr_code] sudah hilang dan QR verifikasi asli tidak
     * pernah dibuat. Kini QR pratinjau diberi penanda ALT_QR_CONTOH dan diganti saat cetak.
     */
    private function handleQrCode(): void
    {
        $polaContoh = '/<img\b[^>]*\balt="' . self::ALT_QR_CONTOH . '"[^>]*>/i';
        $adaTag     = str_contains($this->result, '[qr_code]');
        $adaContoh  = (bool) preg_match($polaContoh, $this->result);
        $adaLama    = (bool) preg_match('/<img[^>]+src="((?!data:)[^"]*qrcode[^"]*temp[^"]*)"/i', (string) $this->result, $lama);

        if (! $adaTag && ! $adaContoh && ! $adaLama) {
            return;
        }

        // Default: jika qr_code tidak diset, anggap true (null coalescing)
        $qr_code_enabled = $this->request['qr_code'] ?? true;

        // Jika QR dimatikan untuk surat ini, hapus tag & QR contoh
        if (! $qr_code_enabled) {
            $this->result = (string) preg_replace($polaContoh, '', str_replace('[qr_code]', '', $this->result));

            return;
        }

        // Pratinjau (belum ada arsip surat, atau PDF pratinjau yang arsipnya dibuang): QR contoh berpenanda
        if (! $this->surat || $this->pratinjau) {
            $cek = dummyQrCode(null);
            if (! empty($cek['viewqr']) && file_exists($cek['viewqr'])) {
                $this->result = str_replace('[qr_code]', $this->imgQr($cek['viewqr'], self::ALT_QR_CONTOH), $this->result);
            }

            return;
        }

        // Belum waktunya (TTE menunggu verifikasi kades): kembalikan QR contoh menjadi tag, diproses nanti
        if (! $this->shouldIncludeQrCode()) {
            $this->result = (string) preg_replace($polaContoh, '[qr_code]', $this->result);

            return;
        }

        // QR berisi gambar QR lama berupa jalur berkas sementara yang sudah hilang: buat ulang
        if ($adaLama && ! file_exists($lama[1]) && ! $adaTag && ! $adaContoh) {
            $cek = LogSurat::buatQrCode($this->surat->nama_surat, null);
            if (! empty($cek['viewqr']) && file_exists($cek['viewqr'])) {
                $this->result = str_replace($lama[1], $this->srcQr($cek['viewqr']), $this->result);
                $this->surat->update(['isi_surat' => $this->result]);
                $this->urls_id = $cek['urls_id'] ?? null;
            }

            return;
        }

        if (! $adaTag && ! $adaContoh) {
            return; // QR asli sudah terpasang
        }

        $cek        = LogSurat::buatQrCode($this->surat->nama_surat, null);
        $qrcodePath = $cek['viewqr'] ?? null;

        // Jika file QR tidak ada, biarkan tag untuk diproses nanti
        if (! $qrcodePath || ! file_exists($qrcodePath)) {
            $this->result  = (string) preg_replace($polaContoh, '[qr_code]', $this->result);
            $this->urls_id = $cek['urls_id'] ?? null;

            return;
        }

        $img          = $this->imgQr($qrcodePath, self::ALT_QR);
        $this->result = str_replace('[qr_code]', $img, (string) preg_replace($polaContoh, $img, $this->result));

        $this->urls_id = $cek['urls_id'] ?? null;
    }

    private function srcQr(string $path): string
    {
        return 'data:' . mime_content_type($path) . ';base64,' . base64_encode((string) file_get_contents($path));
    }

    private function imgQr(string $path, string $alt): string
    {
        return '<img src="' . $this->srcQr($path) . '" width="90" height="90" alt="' . $alt . '" />';
    }

    /**
     * Menentukan apakah kode QR harus dimasukkan dalam hasil.
     */
    private function shouldIncludeQrCode(): bool
    {
        return (setting('tte') == 1 && ($this->surat->verifikasi_kades == LogSurat::TERIMA || $this->lampiran)) || setting('tte') == 0;
    }
}
