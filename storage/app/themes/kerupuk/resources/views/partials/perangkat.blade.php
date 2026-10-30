{{-- Pita perangkat desa: satu baris foto selebar layar, bergeser sendiri pelan; tombol panah untuk maju/mundur. --}}
@php
    $perangkat = $aparatur_desa['daftar_perangkat'] ?? [];
    // Tautan "Lihat semua" hanya bila halaman Pemerintahan aktif (Admin Web → Menu); bila tidak, halamannya "Menu Belum Aktif".
    $ada_halaman_pemerintah = in_array('pemerintah', \App\Models\Menu::active()->pluck('link')->all(), true);
@endphp
@if (count($perangkat))
    <section class="kr-perangkat" aria-labelledby="kr-perangkat-judul" data-kr-geser data-kr-otomatis="4000">
        <div class="kr-wrap kr-perangkat-head">
            <h2 class="kr-pita-judul" id="kr-perangkat-judul">Perangkat {{ strtolower(setting('sebutan_desa')) }}</h2>
            <div class="kr-perangkat-aksi">
                @if ($ada_halaman_pemerintah)
                    <a href="{{ site_url('pemerintah') }}" class="kr-link-more">Lihat semua</a>
                @endif
                <button type="button" class="kr-icon-btn" data-kr-geser-prev aria-label="Perangkat sebelumnya"><i class="fas fa-chevron-left"></i></button>
                <button type="button" class="kr-icon-btn" data-kr-geser-next aria-label="Perangkat berikutnya"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
        <ul class="kr-perangkat-track" data-kr-jalur>
            @foreach ($perangkat as $p)
                @php
                    $nama = trim(implode(' ', array_filter([$p['gelar_depan'] ?? null, $p['nama']]))) . (!empty($p['gelar_belakang']) ? ', ' . $p['gelar_belakang'] : '');
                    $hadir = ($p['kehadiran'] ?? null) == 1 && ($p['status_kehadiran'] ?? null) === 'hadir' && ($p['tanggal'] ?? null) == date('Y-m-d');
                @endphp
                <li class="kr-perangkat-item">
                    <figure class="kr-perangkat-kartu">
                        <img src="{{ $p['foto'] }}" alt="Foto {{ $nama }}" loading="lazy" width="240" height="320">
                        @if ($hadir)
                            <span class="kr-perangkat-hadir">Hadir</span>
                        @endif
                        <figcaption>
                            <span class="kr-perangkat-nama">{{ $nama }}</span>
                            <span class="kr-perangkat-jabatan">{{ $p['jabatan'] }}</span>
                        </figcaption>
                    </figure>
                </li>
            @endforeach
        </ul>
    </section>
@endif
