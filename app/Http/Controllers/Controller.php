<?php

/*
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 */

/*
 * Satima: kelas dasar yang hilang di edisi umum Rilis-v2607.0.2.
 *
 * App\Http\Controllers\AssetController (theme_asset, module_asset) dan ServeFileController (storage-desa)
 * mewarisi App\Http\Controllers\Controller dan memanggil abort(); keduanya hanya ada di edisi premium
 * (illuminate/foundation). Tanpa berkas ini rute tersebut fatal dan tema tampil tanpa CSS/JS.
 *
 * abort() di sini diarahkan ke show_404()/show_error() CodeIgniter supaya respons tetap halaman OpenSID.
 */

namespace App\Http\Controllers {
    abstract class Controller
    {
    }
}

namespace {
    if (! function_exists('abort')) {
        function abort($code = 404, $message = '', array $headers = [])
        {
            if ((int) $code === 404 && function_exists('show_404')) {
                show_404();
            }

            if (function_exists('show_error')) {
                show_error($message ?: 'Terjadi kesalahan.', (int) $code);
            }

            http_response_code((int) $code);

            exit;
        }
    }
}
