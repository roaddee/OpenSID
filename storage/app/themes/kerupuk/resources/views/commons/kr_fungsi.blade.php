@php
    // Fungsi bantu tema Kerupuk. Aman di-include berkali-kali.
    if (! function_exists('kr_online')) {
        /**
         * cek_koneksi_internet() membuka koneksi ke google.com tiap kali dipanggil (bila notifikasi koneksi aktif),
         * dan tema memanggilnya beberapa kali per halaman. Di sini hasilnya diingat sekali per permintaan.
         */
        function kr_online(): bool
        {
            static $online = null;

            return $online ??= (bool) cek_koneksi_internet();
        }
    }
    if (! function_exists('kr_asset')) {
        /**
         * URL aset tema. Tema di desa/themes dilayani langsung oleh server web (berkas statis, di-cache browser),
         * jauh lebih cepat daripada theme_asset() yang menyalakan PHP untuk tiap berkas. Lokasi lain tetap theme_asset().
         */
        function kr_asset(string $file): string
        {
            $tema = theme_active();
            if (str_starts_with($tema->path, 'desa/') && is_file(FCPATH . $tema->path . '/assets/' . $file)) {
                return base_url($tema->path . '/assets/' . $file) . '?v=2610.2.0';
            }

            return theme_asset($file, ['themeVersion' => 'v2610.2.0']);
        }
    }
@endphp
