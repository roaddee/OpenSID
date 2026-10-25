<div class="es-totop" x-data="{ show: false }" @scroll.window="show = window.scrollY > 900">
    <a href="#" class="es-icon-btn es-icon-btn-solid" :class="{ 'is-visible': show }" aria-label="Kembali ke atas" @click.prevent="window.scrollTo({ top: 0, behavior: 'smooth' })">
        <i class="fas fa-arrow-up"></i>
    </a>
</div>
