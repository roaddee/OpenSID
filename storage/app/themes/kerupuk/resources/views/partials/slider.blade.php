{{-- Sorotan foto: geser biasa (scroll-snap) tanpa pustaka carousel, sehingga tidak ada JS berat yang harus dimuat. --}}
<section class="kr-sorotan" aria-label="Sorotan foto" data-kr-geser>
    <div class="kr-sorotan-track" tabindex="0">
        @foreach ($slider_gambar['gambar'] as $data)
            @php
                $img = $slider_gambar['lokasi'] . 'sedang_' . $data['gambar'];
            @endphp
            @if (is_file($img))
                <figure class="kr-sorotan-item">
                    <img src="{{ base_url($img) }}" alt="{{ $data['judul'] }}" @if (!$loop->first) loading="lazy" @endif>
                    @if ($slider_gambar['sumber'] != 3)
                        <figcaption><a href="{{ site_url('artikel/' . buat_slug($data)) }}">{{ $data['judul'] }}</a></figcaption>
                    @endif
                </figure>
            @endif
        @endforeach
    </div>
    <div class="kr-geser-nav">
        <button type="button" class="kr-icon-btn" data-kr-geser-prev aria-label="Foto sebelumnya"><i class="fas fa-chevron-left"></i></button>
        <button type="button" class="kr-icon-btn" data-kr-geser-next aria-label="Foto berikutnya"><i class="fas fa-chevron-right"></i></button>
    </div>
</section>
