# Audit kode isian template RTF

Dibuat oleh `bin/satima/audit-kode-rtf.php` pada 2026-10-25.

| Golongan | Jumlah | Artinya |
| --- | --- | --- |
| umum | 1119 | Ditangani alias umum (diganti `Surat_model` 2407) |
| khusus | 484 | Perlu alias per surat (dari `data_rtf_<url>.php` 2407) |
| form | 41 | Isian form surat 2607, aturan `[form_x]`/`[x]` |
| form-2407 | 177 | Isian form 2407 yang tidak ada di form 2607 |
| kotor | 0 | Kode rusak di template |
| asing | 3 | Tidak dikenali, periksa manual |

## Per surat

| Surat 2607 | umum | khusus | form | form-2407 | kotor | asing |
| --- | --- | --- | --- | --- | --- | --- |
| sistem-surat-biodata-penduduk | 41 | 0 | 0 | 0 | 0 | 0 |
| surat_domisili_usaha_non_warga (tanpa padanan) | 17 | 0 | 0 | 19 | 0 | 0 |
| sistem-surat-pengantar-izin-keramaian | 30 | 0 | 1 | 3 | 0 | 0 |
| sistem-surat-keterangan-izin-orang-tua-suami-istri | 22 | 13 | 2 | 2 | 0 | 0 |
| sistem-surat-keterangan-bepergian | 32 | 0 | 0 | 1 | 0 | 0 |
| sistem-surat-keterangan-beda-identitas-kis | 16 | 91 | 0 | 0 | 0 | 0 |
| sistem-surat-keterangan-beda-identitas | 24 | 0 | 6 | 5 | 0 | 0 |
| sistem-surat-pengantar-surat-keterangan-catatan-kepolisian | 30 | 0 | 1 | 0 | 0 | 0 |
| sistem-surat-keterangan-domisili | 31 | 0 | 0 | 0 | 0 | 0 |
| sistem-surat-keterangan-domisili-usaha | 28 | 0 | 0 | 1 | 0 | 0 |
| sistem-surat-keterangan-jamkesos | 27 | 0 | 0 | 2 | 0 | 0 |
| sistem-surat-keterangan-jual-beli | 23 | 0 | 1 | 10 | 0 | 0 |
| sistem-surat-pengantar-laporan-kehilangan | 30 | 0 | 2 | 1 | 0 | 0 |
| sistem-surat-keterangan-kelahiran | 19 | 54 | 1 | 6 | 0 | 0 |
| sistem-surat-keterangan-kematian | 24 | 8 | 0 | 3 | 0 | 0 |
| sistem-surat-keterangan-kepemilikan-kendaraan | 24 | 0 | 4 | 7 | 0 | 0 |
| sistem-surat-keterangan-kepemilikan-tanah | 24 | 0 | 2 | 9 | 0 | 0 |
| sistem-surat-keterangan-ktp-dalam-proses | 26 | 0 | 0 | 0 | 0 | 0 |
| sistem-surat-keterangan-kurang-mampu | 28 | 49 | 0 | 0 | 0 | 0 |
| sistem-surat-keterangan-lahir-mati | 25 | 0 | 4 | 2 | 0 | 0 |
| sistem-surat-keterangan-nikah | 10 | 58 | 0 | 40 | 0 | 1 |
| sistem-surat-keterangan-untuk-nikah-warga-non-muslim | 17 | 19 | 0 | 2 | 0 | 1 |
| sistem-surat-keterangan-penduduk | 31 | 0 | 0 | 0 | 0 | 0 |
| sistem-surat-keterangan-pengantar | 32 | 0 | 0 | 0 | 0 | 0 |
| sistem-surat-keterangan-penghasilan-ayah | 43 | 2 | 0 | 1 | 0 | 0 |
| sistem-surat-keterangan-penghasilan-ibu | 43 | 2 | 0 | 1 | 0 | 0 |
| sistem-surat-keterangan-penghasilan-orang-tua | 35 | 6 | 0 | 4 | 0 | 1 |
| sistem-surat-keterangan-pergi-kawin | 27 | 0 | 1 | 3 | 0 | 0 |
| sistem-surat-keterangan-pindah-penduduk | 26 | 37 | 7 | 1 | 0 | 0 |
| sistem-surat-keterangan-pengantar-rujuk-atau-cerai | 25 | 0 | 0 | 8 | 0 | 0 |
| sistem-surat-keterangan-usaha | 30 | 0 | 0 | 4 | 0 | 0 |
| sistem-surat-keterangan-wali-hakim | 28 | 0 | 0 | 0 | 0 | 0 |
| sistem-surat-kuasa | 14 | 22 | 1 | 0 | 0 | 0 |
| sistem-surat-perintah-perjalanan-dinas | 19 | 0 | 0 | 12 | 0 | 0 |
| sistem-surat-permohonan-akta-lahir | 22 | 0 | 4 | 4 | 0 | 0 |
| sistem-surat-permohonan-cerai | 21 | 7 | 0 | 1 | 0 | 0 |
| sistem-surat-permohonan-duplikat-kelahiran | 32 | 0 | 0 | 10 | 0 | 0 |
| sistem-surat-permohonan-duplikat-surat-nikah | 29 | 0 | 2 | 1 | 0 | 0 |
| sistem-surat-permohonan-kartu-keluarga | 28 | 0 | 0 | 0 | 0 | 0 |
| sistem-surat-pengantar-permohonan-penerbitan-buku-pas-lintas | 26 | 101 | 0 | 0 | 0 | 0 |
| sistem-surat-permohonan-perubahan-kartu-keluarga | 28 | 0 | 0 | 0 | 0 | 0 |
| sistem-surat-pernyataan-belum-memiliki-akta-lahir | 26 | 0 | 0 | 0 | 0 | 0 |
| sistem-surat-pernyataan-penguasaan-fisik-bidang-tanah-sporadik | 6 | 15 | 2 | 14 | 0 | 0 |

## Daftar kerja

### surat_domisili_usaha_non_warga (Domisili Usaha Non-Warga)

- **form-2407:** `[form_nama_non_warga]`, `[form_tempatlahir]`, `[form_tanggallahir]`, `[form_nik_non_warga]`, `[form_kk_non_warga]`, `[form_warga_negara]`, `[form_agama]`, `[form_sex]`, `[form_pekerjaan]`, `[form_alamat]`, `[form_nama_usaha]`, `[form_usaha]`, `[form_akta_usaha]`, `[form_akta_tahun]`, `[form_notaris]`, `[form_bangunan]`, `[form_peruntukan_bangunan]`, `[form_status_bangunan]`, `[form_alamat_usaha]`

### sistem-surat-pengantar-izin-keramaian (Pengantar Izin Keramaian)

- **form-2407:** `[form_jenis_keramaian]`, `[form_berlaku_dari]`, `[form_berlaku_sampai]`

### sistem-surat-keterangan-izin-orang-tua-suami-istri (Keterangan Izin Orang Tua/Suami/Istri)

- **form-2407:** `[mengizinkan]`, `[masa_kontrak]`

### sistem-surat-keterangan-bepergian (Keterangan Bepergian / Jalan)

- **form-2407:** `[form_keterangan]`

### sistem-surat-keterangan-beda-identitas (Keterangan Beda Identitas)

- **form-2407:** `[form_kartu]`, `[form_identitas]`, `[form_tempatlahir]`, `[form_tanggallahir]`, `[form_sex]`

### sistem-surat-keterangan-domisili-usaha (Keterangan Domisili Usaha)

- **form-2407:** `[form_usaha]`

### sistem-surat-keterangan-jamkesos (Keterangan JAMKESOS)

- **form-2407:** `[form_keterangan]`, `[form_no_jamkesos]`

### sistem-surat-keterangan-jual-beli (Keterangan Jual Beli)

- **form-2407:** `[form_barang]`, `[form_jenis]`, `[form_nama]`, `[form_identitas]`, `[form_tempatlahir]`, `[form_tanggallahir]`, `[form_sex]`, `[form_alamat]`, `[form_pekerjaan]`, `[form_ketua_adat]`

### sistem-surat-pengantar-laporan-kehilangan (Pengantar Laporan Kehilangan)

- **form-2407:** `[form_barang]`

### sistem-surat-keterangan-kelahiran (Keterangan Kelahiran)

- **form-2407:** `[form_hari]`, `[form_tanggallahir]`, `[form_waktu_lahir]`, `[form_tempatlahir]`, `[lokasi_disdukcapil]`, `[form_kelahiran_anak_ke]`

### sistem-surat-keterangan-kematian (Keterangan Kematian)

- **form-2407:** `[form_hari]`, `[sebab_nama]`, `[hubungan_pelapor]`

### sistem-surat-keterangan-kepemilikan-kendaraan (Keterangan Kepemilikan Kendaraan)

- **form-2407:** `[merk]`, `[warna]`, `[nopol]`, `[nosin]`, `[noka]`, `[bpkb]`, `[peruntukan_surat]`

### sistem-surat-keterangan-kepemilikan-tanah (Keterangan Kepemilikan Tanah)

- **form-2407:** `[atas_nama]`, `[bukti_kepemilikan]`, `[nomor_kepemilikan]`, `[utara]`, `[timur]`, `[selatan]`, `[barat]`, `[asal_tanah]`, `[bukti_pendukung]`

### sistem-surat-keterangan-lahir-mati (Keterangan Lahir Mati)

- **form-2407:** `[form_tanggal_mati]`, `[form_pelapor]`

### sistem-surat-keterangan-nikah (Keterangan Untuk Nikah (N-1 s/d N-7))

- **form-2407:** `[form_istri_ke]`, `[form_status_kawin_pria]`, `[hari_nikah]`, `[tanggal_nikah]`, `[jam_nikah]`, `[tempat_nikah]`, `[mas_kawin]`, `[istri_dulu]`, `[binti]`, `[noktp_istri_dulu]`, `[tempatlahir_istri_dulu]`, `[tanggallahir_istri_dulu]`, `[wn_istri_dulu]`, `[agama_istri_dulu]`, `[pek_istri_dulu]`, `[alamat_istri_dulu]`, `[tanggalmeninggal_istri_dulu]`, `[tempatmeninggal_istri_dulu]`, `[form_pendidikan_wanita]`, `[form_status_kawin_wanita]`, `[nama_suami_dulu]`, `[bin_suami_dulu]`, `[noktp_suami_dulu]`, `[tempatlahir_suami_dulu]`, `[tanggallahir_suami_dulu]`, `[wn_suami_dulu]`, `[agama_suami_dulu]`, `[pek_suami_dulu]`, `[alamat_suami_dulu]`, `[tanggalmeninggal_suami_dulu]`, `[tempatmeninggal_suami_dulu]`, `[nama_wali]`, `[bin_wali]`, `[noktp_wali]`, `[tempatlahir_wali]`, `[tanggallahir_wali]`, `[agama_wali]`, `[pek_wali]`, `[alamat_wali]`, `[hub_wali]`
- **asing:** `[nip_pamong]`

### sistem-surat-keterangan-untuk-nikah-warga-non-muslim (Keterangan Untuk Nikah Warga Non Muslim)

- **form-2407:** `[form_sex_pria]`, `[form_tanggal_nikah]`
- **asing:** `[form_tempat_nikah]`

### sistem-surat-keterangan-penghasilan-ayah (Keterangan Penghasilan Ayah)

- **form-2407:** `[sekolah]`

### sistem-surat-keterangan-penghasilan-ibu (Keterangan Penghasilan Ibu)

- **form-2407:** `[sekolah]`

### sistem-surat-keterangan-penghasilan-orang-tua (Keterangan Penghasilan Orangtua)

- **form-2407:** `[nomor_induk]`, `[jurusan]`, `[sekolah_pt]`, `[kelas_semester]`
- **asing:** `[pamong]`

### sistem-surat-keterangan-pergi-kawin (Keterangan Pergi Kawin)

- **form-2407:** `[form_keterangan]`, `[form_berlaku_dari]`, `[form_berlaku_sampai]`

### sistem-surat-keterangan-pindah-penduduk (Keterangan Pindah Penduduk)

- **form-2407:** `[form_desa_tujuan]`

### sistem-surat-keterangan-pengantar-rujuk-atau-cerai (Keterangan Pengantar Rujuk/Cerai)

- **form-2407:** `[form_nama_pasangan]`, `[form_nama_ayah_pasangan]`, `[form_tempatlahir_pasangan]`, `[form_tanggallahir_pasangan]`, `[form_wn_pasangan]`, `[form_agama_pasangan]`, `[form_pekerjaan_pasangan]`, `[form_alamat_pasangan]`

### sistem-surat-keterangan-usaha (Keterangan Usaha)

- **form-2407:** `[form_keterangan]`, `[form_berlaku_dari]`, `[form_berlaku_sampai]`, `[form_usaha]`

### sistem-surat-perintah-perjalanan-dinas (Perintah Perjalanan Dinas)

- **form-2407:** `[pangkat_gol]`, `[jabatan1]`, `[biaya]`, `[tujuan]`, `[alat_angkut]`, `[anggota_i]`, `[anggota_ii]`, `[anggota_iii]`, `[anggota_iv]`, `[anggota_v]`, `[anggota_vi]`, `[ket_lain]`

### sistem-surat-permohonan-akta-lahir (Permohonan Akta Lahir)

- **form-2407:** `[form_tempatlahir_anak]`, `[form_tanggallahir_anak]`, `[form_harilahir_anak]`, `[form_nama_ortu]`

### sistem-surat-permohonan-cerai (Permohonan Cerai)

- **form-2407:** `[form_sebab]`

### sistem-surat-permohonan-duplikat-kelahiran (Permohonan Duplikat Kelahiran)

- **form-2407:** `[form_hari_bayi]`, `[form_jam_bayi]`, `[form_tempatlahir_bayi]`, `[form_nama_pelapor]`, `[form_nik_pelapor]`, `[form_sex_pelapor]`, `[form_tempatlahir_pelapor]`, `[form_tanggallahir_pelapor]`, `[form_pek_pelapor]`, `[form_alamat_pelapor]`

### sistem-surat-permohonan-duplikat-surat-nikah (Permohonan Duplikat Surat Nikah)

- **form-2407:** `[form_tgl_nikah]`

### sistem-surat-pernyataan-penguasaan-fisik-bidang-tanah-sporadik (Pernyataan Penguasaan Fisik Bidang Tanah (SPORADIK))

- **form-2407:** `[rtrw]`, `[desalurah]`, `[camatt]`, `[kabb]`, `[statustanah]`, `[tanahuntuk]`, `[luashak]`, `[utara]`, `[timur]`, `[selatan]`, `[barat]`, `[peroleh]`, `[perolehtahun]`, `[denganjalan]`
