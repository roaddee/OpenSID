<?php

/*
 * File ini bagian dari OpenSID edisi Satima.
 *
 * Diturunkan dari Surat_model::bersihkan_kode_isian() OpenSID v2407.0.0
 * (GPL V3, Perkumpulan Desa Digital Terbuka).
 */

namespace App\Libraries\Surat\Rtf;

/**
 * Menyambung kode isian yang dipecah Word.
 *
 * Saat template diedit di Word, kode seperti [form_nama_wanita] sering tersimpan sebagai
 * "[}{\rtlch\fcs1 \ab\af1 ... \caps ... form_nama_wanita}" karena riwayat edit atau perubahan
 * format satu huruf. Kelas ini membuang kata kendali RTF di dalam kurung siku sehingga
 * kodenya kembali utuh.
 *
 * Bedanya dengan versi 2407:
 * - kata kendali dibuang utuh (\ab, \caps, \lang1057, \insrsid123 ...), bukan lewat daftar regex
 *   yang meninggalkan sisa seperti "[acapsform_nama_wanitaacaps]";
 * - kurung kurawal yang ikut terbuang diseimbangkan kembali setelah "]", jadi struktur grup
 *   dokumen tidak rusak;
 * - "[" yang bukan kode (tidak ditutup dalam batas wajar, atau isinya bukan nama kode)
 *   dibiarkan apa adanya, tidak menelan teks sampai "]" berikutnya.
 */
final class BersihkanKodeIsian
{
    /** Panjang maksimum potongan RTF di antara "[" dan "]" yang masih dianggap satu kode. */
    private const MAKS_PANJANG = 4000;

    public static function bersihkan(string $rtf): string
    {
        $hasil  = '';
        $posisi = 0;
        $n      = strlen($rtf);

        while (($buka = strpos($rtf, '[', $posisi)) !== false) {
            $hasil .= substr($rtf, $posisi, $buka - $posisi);

            $tutup = strpos($rtf, ']', $buka + 1);
            if ($tutup === false || $tutup - $buka > self::MAKS_PANJANG) {
                $hasil .= '[';
                $posisi = $buka + 1;

                continue;
            }

            // "[" lain sebelum "]" berarti "[" ini bukan awal kode
            $bukaLagi = strpos($rtf, '[', $buka + 1);
            if ($bukaLagi !== false && $bukaLagi < $tutup) {
                $hasil .= '[';
                $posisi = $buka + 1;

                continue;
            }

            $potongan = substr($rtf, $buka + 1, $tutup - $buka - 1);
            $bersih   = self::bersihkanIsi($potongan);

            if ($bersih === null) {
                $hasil .= '[' . $potongan . ']';
            } else {
                $hasil .= '[' . $bersih['kode'] . ']' . $bersih['penyeimbang'];
            }

            $posisi = $tutup + 1;
        }

        return $hasil . substr($rtf, $posisi, max(0, $n - $posisi));
    }

    /**
     * @return array{kode: string, penyeimbang: string}|null null bila isinya bukan nama kode
     */
    private static function bersihkanIsi(string $isi): ?array
    {
        // Sudah bersih: hanya spasi yang dibuang, seperti 2407 ("[nama _pria]" → "[nama_pria]";
        // "[nomor_surat, 3]" → "[nomor_surat,3]", tetap dikenali substitusi nomor surat)
        if (preg_match('/^[A-Za-z0-9_, \-]+$/', $isi)) {
            $kode = str_replace(' ', '', $isi);

            return $kode === '' ? null : ['kode' => $kode, 'penyeimbang' => ''];
        }

        // Teks yang memuat pindah paragraf/sel jelas bukan satu kode
        if (preg_match('/\\\\(par|cell|row|sect|page)\b/', $isi)) {
            return null;
        }

        // Grup tujuan yang diabaikan ({\*\bkmkstart ...}) dibuang utuh; isinya seimbang sendiri
        $tanpaTujuan = (string) preg_replace('/\{\\\\\*[^{}]*\}/', '', $isi);

        // Buang kata kendali (\ab, \caps, \lang1057 ...), kurung kurawal, dan baris baru
        $kode = (string) preg_replace('/\\\\[A-Za-z]+-?\d* ?/', '', $tanpaTujuan);
        $kode = str_replace(['{', '}', "\r", "\n", ' '], '', $kode);

        if ($kode === '' || ! preg_match('/^[A-Za-z][A-Za-z0-9_, \-]*$/', $kode)) {
            return null;
        }

        // Kurung yang terbuang harus diganti setelah "]" agar grup dokumen tetap seimbang:
        // "}" lebih banyak = grup dari sebelum "[" ditutup di dalam kode → tutup setelahnya;
        // "{" lebih banyak = grup dibuka di dalam kode dan ditutup setelah "]" → buka setelahnya.
        $selisih     = substr_count($tanpaTujuan, '}') - substr_count($tanpaTujuan, '{');
        $penyeimbang = $selisih > 0 ? str_repeat('}', $selisih) : str_repeat('{', -$selisih);

        return ['kode' => $kode, 'penyeimbang' => $penyeimbang];
    }
}
