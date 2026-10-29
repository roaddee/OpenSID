# OpenSID umum — edisi Satima

Branch `satima-umum` = rilis resmi OpenSID umum **Rilis-v2607.0.2** (VERSION tetap `2607.0.1`)
ditambah perbaikan & pengerasan dari PT. Satima Putra Informa. Dipasang oleh Panel Satima bila situs memilih
"repo Satima"; repo resmi `OpenSID/OpenSID` tetap bisa dipilih.

## Isi (di atas Rilis-v2607.0.2)

| Commit | Perubahan | Alasan |
|---|---|---|
| captcha | `Route::get('captcha', 'Securimage@show')` di `donjo-app/Routes/web.php` | Rilis memindahkannya ke `routes/web.php` Laravel yang tidak dimuat edisi umum → captcha /siteman hilang |
| Controller | `app/Http/Controllers/Controller.php` (kelas kosong + `abort()` → `show_404`) | AssetController/ServeFileController fatal → tema tanpa CSS/JS |
| endpoint uji | hapus `PlaywrightController`, `Routes/Web/playwright.php`, `make_obfuscate.php`, `umum_obfuscate.php` | artisan/SQL bebas; skrip build bisa dipanggil tanpa login dan menulis ulang berkas inti (ikut ter-clone lewat git, tidak ada di zip rilis) |
| decrypt | `decrypt()` hanya meng-unserialize skalar/array, `allowed_classes => false` | cegah PHP object injection bila APP_KEY bocor |
| .htaccess | `htaccess.apache.txt`: tolak skrip di folder data/kode, PHP akar selain index.php, tests/, dotfile, kunci & arsip; `!= on` → `!=on` | berlaku juga di OpenLiteSpeed (RewriteRule, bukan FilesMatch); versi resmi membuat Apache 500; rute akhir `index.php?/$1` (bukan `index.php/$1`) supaya CSS/JS termuat di OLS |
| tanpa obfuscate | 16 berkas (core_helper, Admin_Controller, Web/AdminModulController, controller Anjungan/BukuTamu/Pelanggan) dikembalikan ke kode asli | kode bisa dibaca, diperbaiki, dan dipindai; dekode statis, isi tidak diubah |
| login mandiri | `filled('anjungan_uuid')` | field tersembunyi selalu terkirim → semua login warga ditolak "Anjungan tidak ditemukan" |
| cookie | `assets/js/id_browser.js`: elemen `#pengunjung` opsional | tombol "Terima semua cookie" diam & modal menutupi form login |
| wilayah RW/RT | `'true'`/`'false'` untuk `refreshOrder` | `var refreshOrder = ;` mematikan skrip halaman RW/RT |
| header admin | blok lama menu pengguna/Informasi/Pengaturan dibuang, `$is_mobile` didefinisikan | ikon tampil dua kali di semua halaman admin; HTML rusak di HP (bawaan 2607.0.0) |
| surat & pesan mandiri | `fmandiri/Surat::kirim()` & `Pesan::kirim()`: event menerima `Penduduk::find(id_pend)`, bukan `auth('penduduk')->user()`; pemohon dari sesi login; ubah permohonan hanya milik sendiri berstatus 0; kegagalan notifikasi dicatat saja | guard `penduduk` mengembalikan `PendudukMandiri` → TypeError, ajuan surat/pesan warga selalu error setelah "Lanjut"; `nik`/`id` dari form bisa dipakai atas nama warga lain |
| PHP 8.4 | batas versi PHP di installer & Info Sistem: `maxPhpVersion` = `8.4`, dibandingkan dengan versi mayor.minor | versi resmi membandingkan `PHP_VERSION` dengan `8.3.0`, jadi 8.3.1+ dan 8.4 dianggap tidak memenuhi syarat (installer web berhenti di langkah Server). Diuji di PHP 8.4.27: install web, 233 halaman admin, halaman publik, Layanan Mandiri (login, ajukan surat, pesan), Excel (openspout/fast-excel), PDF (html2pdf/tcpdf) — hasil identik dengan PHP 8.3 |
| bug bawaan | `/buku-tamu` (`use` ganda → fatal), Daftar Kontak → Tambah/Ubah & tombol kembali Dokumen/Biodata penduduk (`HTTP_REFERER` kosong → 500/warning; label salah urutan operator), `setting/ambil_foto` tanpa/keliru `pengaturan` (TypeError → kini 404), `AppServiceProvider` tidak menyentuh DB sebelum instalasi ("Database connection [] not configured"), 3 `use` tanpa efek | halaman 500 / log error, juga di PHP 8.3 |
| vendor deprecated | `#[\AllowDynamicProperties]` di 139 kelas CI3 (`vendor/` & `storage/app/vendor/`), 111 parameter implicit nullable → `?Tipe` (13 paket), `E_STRICT` → 2048, `${var}` → `{$var}` | tanpa peringatan deprecated di PHP 8.4 (siap PHP 9); hilang bila `composer update` — terapkan ulang |
| vendor aman | lihat "Pembaruan vendor keamanan" di bawah | `composer audit` 2607 menemukan ±50 advisory di 14 paket |

## Tampilan edisi Satima (24 Okt 2026, branch `satima-tampilan`)

| Bagian | Berkas | Isi |
|---|---|---|
| Tema Esensi | `storage/app/themes/esensi/` (commons, layouts, partials artikel/slider/headline/sidebar), `assets/css/satima.css` & `custom.css` tema | navbar lengket + menu "Lainnya", beranda dengan judul besar & motif garis terasering, layanan cepat (hanya menu yang aktif di Admin Web → Menu), kartu berita, widget, kaki baru; layar muat hilang sendiri (CSS) |
| Tema bawaan | `donjo-app/models/seeders/SettingSeeder.php`, `app/database/seeders/DataAwal/SettingAplikasi.php` | instalasi baru memakai **Esensi** (dulu Natra). Situs lama tidak diubah — aktifkan dari Admin Web → Tema |
| Masuk admin | `resources/views/admin/auth/*`, `assets/css/satima-login.css` | senada login Panel Satima (navy, gelombang, kartu bergaris enam warna); lupa sandi, atur ulang, 2FA, OTP ikut |
| Beranda admin | `resources/views/admin/home/index.blade.php`, `assets/css/satima-beranda.css`, `donjo-app/controllers/Beranda.php` (`ringkasan()`) | banner sambutan + pintasan sesuai hak akses, kartu statistik, permohonan surat warga, berita terbaru, komposisi penduduk; tiap kueri dibungkus try/catch |
| Masuk warga | `resources/views/layanan_mandiri/auth/*` | layar terbelah hijau desa, NIK/PIN berikon, e-KTP, lupa PIN, daftar; modal cookie dirapikan |
| Halaman warga | `resources/views/layanan_mandiri/layouts/index.blade.php`, `beranda/index.blade.php`, `assets/css/satima-mandiri.css`, `donjo-app/controllers/fmandiri/Beranda.php` | bilah atas, menu samping, tab bawah + laci di HP, beranda warga baru (dulu langsung dialihkan); isi halaman lama dirapikan lewat CSS |
| Font | `assets/fonts/plus-jakarta-sans/`, `assets/css/satima-font.css` | Plus Jakarta Sans (OFL) lokal, tanpa Google Fonts |

Diuji di sandbox (Apache + MariaDB + PHP 8.3, desktop 1366 px & HP 390 px): beranda, artikel, kategori, arsip, pencarian,
pengaduan, peta, galeri, lapak, pembangunan, SOTK, 404; /siteman (produksi + captcha, salah sandi, tombol lihat sandi);
beranda admin; Layanan Mandiri masuk (salah PIN, benar), beranda, surat (buat → isi form → kirim → tampil di beranda),
pesan, dokumen, bantuan, profil, ganti PIN, laci menu HP, keluar lewat modal penilaian. Bila memperbarui dari rilis
OpenSID baru: berkas di tabel ini menimpa versi resmi, cek dulu apakah rilis mengubah view yang sama.

## Pembaruan vendor keamanan (24 Okt 2026)

`composer audit` pada rilis 2607 menemukan advisory di 14 paket (beberapa HIGH). Diperbarui di dalam versi mayor yang
sama (tetap PHP ≥ 8.1), `vendor/` + `composer.lock` + `assets/js/tinymce-72` ikut di-commit:

| Paket | Lama → baru | Advisory utama |
|---|---|---|
| guzzlehttp/guzzle | 7.10.0 → 7.16.0 | downgrade proxy HTTPS, kebocoran/injeksi cookie, Proxy-Authorization ke origin, host noncanonical (HIGH) |
| guzzlehttp/psr7 | 2.8.0 → 2.13.2 | CRLF injection & host confusion |
| guzzlehttp/promises | 2.3.0 → 2.6.0 | (dibutuhkan guzzle 7.16) |
| league/commonmark | 2.8.0 → 2.10.4 | XSS `on*` bypass, DisallowedRawHtml bypass, beberapa DoS (HIGH) |
| league/flysystem (+ -local) | 3.30.2 → 3.36.0 / 3.35.3 | bypass pemeriksaan karakter kontrol path |
| phpseclib/phpseclib | 3.0.48 → **dihapus** | padding oracle, DoS OID, SSRF X.509 — google/apiclient 2.20 tidak memerlukannya lagi (tanda tangan lewat OpenSSL) |
| firebase/php-jwt | 6.11.1 → 7.2.1 | kunci lemah diterima (google/auth 1.55 & apiclient 2.20 mendukung ^7) |
| google/auth, google/apiclient | 1.49.0 → 1.55.2, 2.18.4 → 2.20.1 | (agar php-jwt 7 & guzzle baru bisa dipakai) |
| symfony/http-foundation | 6.4.30 → 6.4.48 | SSRF IPv6 transisi, ReDoS Cache-Control |
| symfony/mime, symfony/mailer | 6.4.30/6.4.27 → 6.4.46 | CRLF/SMTP command injection, argument injection sendmail (HIGH) |
| symfony/process | 6.4.26 → 6.4.48 | escaping argumen (Windows) |
| symfony/polyfill-intl-idn | 1.33.0 → 1.43.0 | ekuivalensi punycode |
| tinymce/tinymce | 7.9.1 → 7.9.3 | 3 XSS (HIGH); `plugins/image` tetap versi resmi seperti sebelumnya |
| phpunit/phpunit (dev, hanya lock) | 9.6.31 → 9.6.38 | unsafe deserialization |

**illuminate/mail & illuminate/validation 10.49 (CVE-2026-48019, CRLF di alamat email):** Laravel 10 sudah EOL, tidak ada
rilis perbaikan. Perbaikan resmi Laravel 12.60 di-backport ke `vendor/illuminate/mail/Message.php`,
`Mailables/Address.php` dan `vendor/illuminate/validation/Concerns/ValidatesAttributes.php`; salinannya di
`storage/app/vendor/illuminate/` supaya `composer install` (skrip copy-file) memasangnya lagi. CVE ini dicatat di
`config.audit.ignore` composer.json beserta alasannya, sehingga `composer audit` bersih.

Diuji di sandbox (Apache 2.4 + MariaDB + PHP 8.3): web installer sampai selesai, login admin, 396 halaman admin dijelajah
(tanpa error baru), aset TinyMCE 200, berkas sensitif 403, 22 uji pustaka (guzzle, CRLF ditolak di mime/Laravel,
validasi email, commonmark, flysystem, process, JWT HS256/RS256, Google Client & tanda tangan service account tanpa
phpseclib). PHP 8.1 tidak tersedia di sandbox — uji satu situs PHP 8.1 dulu sebelum massal.

Cek ulang kapan saja: `composer audit --locked` (butuh akses packagist).

Dekoder obfuscate (tanpa eval) untuk berkas yang mungkin muncul lagi di rilis berikutnya: lihat
handoff `handoff-opensid-satima-umum.md` di project Panel Satima.

## QR surat & lampiran — perbaikan (29 Okt 2026, semua branch Satima)

- **QR surat TinyMCE tidak bisa diverifikasi** (keluhan desa): pratinjau mengganti `[qr_code]` dengan QR contoh
  ("dummy qrcode OpenSID") yang ikut tersimpan dan tercetak, sehingga tautan verifikasi tidak pernah dibuat. Sekarang
  pratinjau menandai QR contoh (`alt="qrcode-surat-contoh"`), dan cetak final menggantinya dengan QR asli
  (`…/v/<alias>` → halaman verifikasi surat). PDF pratinjau tidak membuat tautan (`KodeIsianGambar::set(..., $pratinjau)`).
  Saat TTE menunggu verifikasi kades, `[qr_code]` tetap di arsip tapi tidak tercetak sebagai teks.
- **Gambar tengah QR**: Pengaturan Surat → tab **QR Code**: logo desa (bawaan) / gambar sendiri (diunggah ke
  `desa/upload/media/`) / tanpa gambar, dengan contoh QR. Setting `satima_qr_gambar` (`''` = logo desa, `'-'` = tanpa,
  selain itu nama berkas), dibaca `satima_logo_qr()` di `opensid_helper.php`; dipakai surat layanan, surat dinas, dan
  arsip. Migrasi `Migrasi_2026102981`, `VERSI_DATABASE` = `2026102981`.
- `qrcode_generate()` (`pict_helper.php`): nama berkas unik (tidak saling timpa saat cetak bersamaan), latar putih,
  logo opsional dengan rasio terjaga (maks 22% sisi QR, di atas bantalan putih).
- Lampiran: tanpa lampiran dipilih, `TinyMCE::generateLampiran()` tidak lagi menghasilkan halaman kosong tambahan.

Diuji: QR PDF final dipindai (zbarimg) → halaman verifikasi menampilkan nomor/tanggal/perihal/penandatangan; tiga
mode gambar QR terbaca; pratinjau tidak menambah baris `urls`.

## Merawat saat OpenSID rilis baru

```sh
git fetch upstream                                  # upstream = https://github.com/OpenSID/OpenSID.git
git checkout -b satima-umum-<versi> <tag/branch rilis baru>
git cherry-pick <commit Satima satu per satu>       # lewati yang sudah diperbaiki OpenSID
```
Cek dulu: rute `captcha` & `app/Http/Controllers/Controller.php` sudah ada di rilis baru? Endpoint rentan lama
(`dokumen_web`, `tampil/`, `unduh/`) tidak kembali? `PlaywrightController` masih dibatasi ENVIRONMENT?
Jalankan `composer audit --locked`; bila vendor rilis baru masih rentan, ulangi pembaruan vendor di atas (dan cek apakah
backport `illuminate/mail` masih diperlukan — hapus dari `config.audit.ignore` & `storage/app/vendor/illuminate/` bila sudah tidak).

**Jangan** memakai branch `teknis/rilis-2701-integrasi` untuk produksi: masih tahap integrasi, dibuat dari kode
Premium v2601.0.2 sehingga tidak membawa perbaikan keamanan 2607.0.1 & 2607.0.2, butuh PHP ≥ 8.2, sistem
migrasi baru, dan membuang modul Anjungan/Pelanggan/BukuTamu.
