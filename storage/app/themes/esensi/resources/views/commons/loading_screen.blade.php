{{-- Layar muat singkat. Hilang sendiri lewat animasi CSS (tanpa JS), jadi tidak pernah menahan halaman
     walau skrip CDN gagal dimuat. --}}
<div class="es-loader" aria-hidden="true">
    <img src="{{ gambar_desa($desa['logo']) }}" alt="" class="es-loader-logo">
    <span class="es-loader-bar"></span>
</div>
