<?php

/*
 * File ini bagian dari OpenSID edisi Satima.
 *
 * Diturunkan dari array penggantian di Surat_model::surat_rtf() OpenSID v2407.0.0.
 */

namespace App\Libraries\Surat\Rtf;

/**
 * Peta kode isian lama (template RTF gaya 2407/SID CRI) ke kode isian 2607.
 *
 * Template RTF memakai kode seperti [no_ktp], [NAMA_DES], [sex]; mesin surat 2607
 * menyediakan nilainya di bawah nama baru ([nik], [nama_desa], [jenis_kelamin]).
 * RtfEngine memakai peta ini untuk mengisi kode lama dari nilai 2607, tanpa perlu
 * mengubah template desa yang sudah ada.
 *
 * Kunci = kode LAMA (tanpa kurung, huruf kecil). Nilai = kode 2607 (tanpa kurung).
 * Hanya kode skalar; kode struktural (tanda tangan, kop, logo, foto, QR, tabel
 * pengikut) ditangani langsung oleh RtfEngine.
 */
final class AliasKodeLama
{
    /**
     * @return array<string, string> kode lama (lower, tanpa kurung) => kode 2607 (tanpa kurung)
     */
    public static function peta(): array
    {
        return [
            // Identitas penduduk
            'no_ktp'        => 'nik',
            'sex'           => 'jenis_kelamin',
            'status'        => 'status_kawin',
            'dusun'         => 'nama_dusun',
            'rt'            => 'nama_rt',
            'rw'            => 'nama_rw',
            'pendidikan'    => 'pendidikan_kk',
            'warga_negara'  => 'warga_negara',
            'gol_darah'     => 'golongan_darah',
            'alamat'        => 'alamat',
            'ttl'           => 'tempat_tgl_lahir',
            'usia'          => 'usia',
            'tempat_tgl_lahir' => 'tempat_tgl_lahir',

            // Identitas desa / wilayah
            'nama_des'      => 'nama_desa',
            'nama_kab'      => 'nama_kabupaten',
            'nama_kec'      => 'nama_kecamatan',
            'nama_prov'     => 'nama_provinsi',
            'kode_desa'     => 'kode_desa',
            'kode_kecamatan' => 'kode_kecamatan',
            'kode_kabupaten' => 'kode_kabupaten',
            'kode_provinsi' => 'kode_provinsi',
            'kode_pos'      => 'kode_pos',
            'pos'           => 'kode_pos',
            'alamat_des'    => 'alamat_desa',
            'alamat_kantor' => 'alamat_kantor',
            'email_desa'    => 'email_desa',
            'telepon_desa'  => 'telepon_desa',
            'website_desa'  => 'website_desa',
            'nama_kepala_camat' => 'nama_kepala_camat',
            'nama_kepala_desa'  => 'nama_kepala_desa',
            'nip_kepala_camat'  => 'nip_kepala_camat',
            'nip_kepala_desa'   => 'nip_kepala_desa',

            // Orang tua: 2407 memakai [d_<kode>_ayah/ibu] dan [ayah_nik]; 2607 memakai [<kode>_ayah/ibu]
            'ayah_nik'             => 'nik_ayah',
            'ibu_nik'              => 'nik_ibu',
            'd_nama_ayah'          => 'nama_ayah',
            'd_nik_ayah'           => 'nik_ayah',
            'd_tempatlahir_ayah'   => 'tempatlahir_ayah',
            'd_tanggallahir_ayah'  => 'tanggallahir_ayah',
            'd_warganegara_ayah'   => 'warga_negara_ayah',
            'd_agama_ayah'         => 'agama_ayah',
            'd_pekerjaan_ayah'     => 'pekerjaan_ayah',
            'd_alamat_ayah'        => 'alamat_ayah',
            'd_nama_ibu'           => 'nama_ibu',
            'd_nik_ibu'            => 'nik_ibu',
            'd_tempatlahir_ibu'    => 'tempatlahir_ibu',
            'd_tanggallahir_ibu'   => 'tanggallahir_ibu',
            'd_warganegara_ibu'    => 'warga_negara_ibu',
            'd_agama_ibu'          => 'agama_ibu',
            'd_pekerjaan_ibu'      => 'pekerjaan_ibu',
            'd_alamat_ibu'         => 'alamat_ibu',

            // Masa berlaku: 2407 [mulai_berlaku]/[tgl_akhir] dari isian berlaku_dari/berlaku_sampai
            'tgl_akhir'            => 'berlaku_sampai',
            'berlaku_dari'         => 'mulai_berlaku',
            'form_berlaku_dari'    => 'mulai_berlaku',
            'form_berlaku_sampai'  => 'berlaku_sampai',
            'form_mulai_berlaku'   => 'mulai_berlaku',

            // Penandatangan
            'form_pamong_nip' => 'form_nip_pamong',
            'pamong_nip'      => 'nip_pamong',
        ];
    }

    /**
     * Resolusi kode lama → kode 2607, atau null bila tidak ada padanan.
     */
    public static function ke2607(string $kodeLama): ?string
    {
        return self::peta()[strtolower(trim($kodeLama, '[] '))] ?? null;
    }
}
