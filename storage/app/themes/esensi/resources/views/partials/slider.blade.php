<section class="es-slider sliderx" aria-label="Sorotan">
    <div class="owl-carousel es-slider-track">
        @foreach ($slider_gambar['gambar'] as $data)
            @php
                $img = $slider_gambar['lokasi'] . 'sedang_' . $data['gambar'];
            @endphp
            @if (is_file($img))
                <figure class="es-slide">
                    <img src="{{ base_url($img) }}" alt="{{ $data['judul'] }}">
                    @if ($slider_gambar['sumber'] != 3)
                        <figcaption class="es-slide-caption">
                            <a href="{{ site_url('artikel/' . buat_slug($data)) }}">{{ $data['judul'] }}</a>
                        </figcaption>
                    @endif
                </figure>
            @endif
        @endforeach
    </div>
    <div class="slider-nav es-slider-nav">
        <button type="button" class="slider-nav-prev es-icon-btn es-icon-btn-solid" title="Sebelumnya" aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></button>
        <button type="button" class="slider-nav-next es-icon-btn es-icon-btn-solid" title="Selanjutnya" aria-label="Selanjutnya"><i class="fas fa-chevron-right"></i></button>
    </div>
</section>
