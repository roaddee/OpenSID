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
| .htaccess | `htaccess.apache.txt`: tolak skrip di folder data/kode, PHP akar selain index.php, tests/, dotfile, kunci & arsip; `!= on` → `!=on` | berlaku juga di OpenLiteSpeed (RewriteRule, bukan FilesMatch); versi resmi membuat Apache 500 |

## Merawat saat OpenSID rilis baru

```sh
git fetch upstream                                  # upstream = https://github.com/OpenSID/OpenSID.git
git checkout -b satima-umum-<versi> <tag/branch rilis baru>
git cherry-pick <commit Satima satu per satu>       # lewati yang sudah diperbaiki OpenSID
```
Cek dulu: rute `captcha` & `app/Http/Controllers/Controller.php` sudah ada di rilis baru? Endpoint rentan lama
(`dokumen_web`, `tampil/`, `unduh/`) tidak kembali? `PlaywrightController` masih dibatasi ENVIRONMENT?

**Jangan** memakai branch `teknis/rilis-2701-integrasi` untuk produksi: masih tahap integrasi, dibuat dari kode
Premium v2601.0.2 sehingga tidak membawa perbaikan keamanan 2607.0.1 & 2607.0.2, butuh PHP ≥ 8.2, sistem
migrasi baru, dan membuang modul Anjungan/Pelanggan/BukuTamu.
