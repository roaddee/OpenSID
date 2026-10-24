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

use App\Libraries\Release;
use App\Libraries\Saas;
use App\Models\Artikel;
use App\Models\Penduduk;
use App\Models\PermohonanSurat;
use App\Models\Shortcut;
use Modules\Pelanggan\Services\CekService;
use Modules\Pelanggan\Services\PelangganService;

defined('BASEPATH') || exit('No direct script access allowed');

class Beranda extends Admin_Controller
{
    public $isAdmin;
    public $modul_ini           = 'beranda';
    public $kategori_pengaturan = 'Beranda';

    public function __construct()
    {
        parent::__construct();
        $this->isAdmin = $this->session->isAdmin->pamong;
    }

    public function index()
    {
        get_pesan_opendk(); // ambil pesan baru di opendk

        $notif_langganan = PelangganService::statusLangganan();
        $notif_percobaan = null;

        // hanya cek percobaan kalau premium kosong
        if (empty($notif_langganan)) {
            $notif_percobaan = PelangganService::statusPercobaan();
        }

        $data = [
            'rilis'           => $this->getUpdate(),
            'shortcut'        => Shortcut::querys()['data'],
            'saas'            => Saas::peringatan(),
            'notif_langganan' => $notif_langganan,
            'notif_percobaan' => $notif_percobaan,
            // disimpan 2 menit agar beranda tetap cepat; permohonan baru tampil paling lambat 2 menit kemudian
            'ringkasan'       => cache()->remember('satima_ringkasan_beranda_' . identitas('id'), 120, fn (): array => $this->ringkasan()),
        ];

        return view('admin.home.index', $data);
    }

    /**
     * Data tambahan untuk tampilan beranda edisi Satima. Setiap bagian dibungkus try/catch:
     * bila satu kueri gagal (mis. tabel berbeda versi), beranda tetap tampil tanpa bagian itu.
     */
    private function ringkasan(): array
    {
        $data = ['penduduk' => null, 'umur' => null, 'permohonan' => collect(), 'permohonan_baru' => 0, 'artikel' => collect()];

        try {
            $jenis = Penduduk::status(1)->selectRaw('sex, COUNT(*) as jumlah')->groupBy('sex')->pluck('jumlah', 'sex');
            $data['penduduk'] = [
                'laki'      => (int) ($jenis[1] ?? 0),
                'perempuan' => (int) ($jenis[2] ?? 0),
                'total'     => (int) $jenis->sum(),
            ];

            $umur = Penduduk::status(1)->whereNotNull('tanggallahir')
                ->selectRaw('SUM(TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) < 17) as anak')
                ->selectRaw('SUM(TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN 17 AND 59) as produktif')
                ->selectRaw('SUM(TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) >= 60) as lansia')
                ->first();
            $data['umur'] = [
                'Anak (0–16)'       => (int) $umur?->anak,
                'Usia kerja (17–59)' => (int) $umur?->produktif,
                'Lansia (60+)'      => (int) $umur?->lansia,
            ];
        } catch (Throwable $e) {
            log_message('error', 'Beranda ringkasan penduduk: ' . $e->getMessage());
        }

        try {
            $data['permohonan'] = PermohonanSurat::with(['penduduk:id,nama', 'surat:id,nama'])
                ->latest('created_at')->limit(5)->get(['id', 'id_pemohon', 'id_surat', 'status', 'created_at']);
            $data['permohonan_baru'] = PermohonanSurat::whereIn('status', [PermohonanSurat::BELUM_LENGKAP, PermohonanSurat::SEDANG_DIPERIKSA])->count();
        } catch (Throwable $e) {
            log_message('error', 'Beranda ringkasan permohonan: ' . $e->getMessage());
        }

        try {
            $data['artikel'] = Artikel::whereNotIn('tipe', Artikel::TIPE_NOT_IN_ARTIKEL)->latest('tgl_upload')->limit(4)->get(['id', 'judul', 'tgl_upload', 'hit', 'enabled']);
        } catch (Throwable $e) {
            log_message('error', 'Beranda ringkasan artikel: ' . $e->getMessage());
        }

        return $data;
    }

    private function getUpdate(): array
    {
        $info = [];

        if (cek_koneksi_internet() && !config_item('demo_mode')) {
            $url_rilis = config_item('rilis_umum');

            $release = new Release();
            $release->setApiUrl($url_rilis)->setCurrentVersion();

            if ($release->isAvailable()) {
                $info['update_available'] = $release->isAvailable();
                $info['current_version'] = 'v' . AmbilVersi();
                $info['latest_version'] = $release->getLatestVersion();
                $info['release_name'] = $release->getReleaseName();
                $info['release_body'] = $release->getReleaseBody();
                $info['url_download'] = $release->getReleaseDownload();
            } else {
                $info['update_available'] = false;
            }
        }

        return $info;
    }
}
