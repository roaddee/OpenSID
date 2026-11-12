<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

@php
    // Edisi Satima: kartu logo seragam 2 kolom + nama program; bila banyak, sisanya dilipat
    // (tanpa JavaScript, <details>) supaya kotak samping tidak memanjang ke bawah.
    // Pengaturan "gambar per baris" tidak dipakai: kolom samping terlalu sempit untuk 3 logo bernama.
    $sinergi_program = collect(sinergi_program() ?: [])->filter(static fn ($s) => ! empty($s['gambar_url']))->values();
    $tampil_awal = 6;
    $sinergi_awal = $sinergi_program->take($tampil_awal);
    $sinergi_lain = $sinergi_program->slice($tampil_awal);
@endphp

<div class="box box-primary box-solid">
    <div class="box-header">
        <h3 class="box-title"><i class="fas fa-external-link-alt mr-1"></i>{{ $judul_widget }}</h3>
    </div>
    <div id="sinergi_program" class="box-body">
        @if ($sinergi_program->isEmpty())
            <p class="es-sinergi-kosong">Belum ada tautan program.</p>
        @else
            <ul class="es-sinergi">
                @foreach ($sinergi_awal as $item)
                    @include('theme::widgets.sinergi_program_item', ['item' => $item])
                @endforeach
            </ul>

            @if ($sinergi_lain->isNotEmpty())
                <details class="es-sinergi-lain">
                    <summary>
                        <span class="es-sinergi-buka">Lihat {{ $sinergi_lain->count() }} program lainnya</span>
                        <span class="es-sinergi-tutup">Sembunyikan</span>
                        <i class="fas fa-chevron-down"></i>
                    </summary>
                    <ul class="es-sinergi">
                        @foreach ($sinergi_lain as $item)
                            @include('theme::widgets.sinergi_program_item', ['item' => $item])
                        @endforeach
                    </ul>
                </details>
            @endif
        @endif
    </div>
</div>
