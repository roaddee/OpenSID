/* Tema Kerupuk (Satima): perilaku kerangka tanpa Alpine/jQuery, supaya menu tetap jalan walau CDN lambat. */
(function () {
    'use strict';

    var laci = document.getElementById('kr-laci');
    var latar = document.querySelector('.kr-laci-latar');
    var cari = document.getElementById('kr-cari');
    var pemicuTerakhir = null;

    function setExpanded(nama, nilai) {
        document.querySelectorAll('[data-kr-toggle="' + nama + '"]').forEach(function (b) {
            b.setAttribute('aria-expanded', nilai ? 'true' : 'false');
        });
    }

    function bukaLaci(pemicu) {
        if (!laci) return;
        pemicuTerakhir = pemicu || null;
        laci.classList.add('is-open');
        laci.setAttribute('aria-hidden', 'false');
        if (latar) latar.hidden = false;
        document.documentElement.style.overflow = 'hidden';
        setExpanded('laci', true);
        var tutup = laci.querySelector('[data-kr-close="laci"]');
        if (tutup) tutup.focus();
    }

    function tutupLaci() {
        if (!laci || !laci.classList.contains('is-open')) return;
        laci.classList.remove('is-open');
        laci.setAttribute('aria-hidden', 'true');
        if (latar) latar.hidden = true;
        document.documentElement.style.overflow = '';
        setExpanded('laci', false);
        if (pemicuTerakhir) pemicuTerakhir.focus();
    }

    function aturCari(buka) {
        if (!cari) return;
        cari.hidden = !buka;
        setExpanded('cari', buka);
        if (buka) {
            window.scrollTo({ top: 0 });
            var input = cari.querySelector('input');
            if (input) input.focus();
        }
    }

    document.addEventListener('click', function (e) {
        var t = e.target.closest('[data-kr-toggle], [data-kr-close], [data-kr-sub], [data-kr-totop], [data-kr-geser-prev], [data-kr-geser-next]');
        if (!t) return;

        if (t.matches('[data-kr-toggle="laci"]')) {
            laci && laci.classList.contains('is-open') ? tutupLaci() : bukaLaci(t);
        } else if (t.matches('[data-kr-toggle="cari"]')) {
            tutupLaci();
            aturCari(cari && cari.hidden);
        } else if (t.matches('[data-kr-close="laci"]')) {
            tutupLaci();
        } else if (t.matches('[data-kr-sub]')) {
            var sub = t.nextElementSibling;
            var buka = t.getAttribute('aria-expanded') !== 'true';
            t.setAttribute('aria-expanded', buka ? 'true' : 'false');
            if (sub) sub.hidden = !buka;
        } else if (t.matches('[data-kr-totop]')) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else if (t.matches('[data-kr-geser-prev], [data-kr-geser-next]')) {
            var wadah = t.closest('[data-kr-geser]');
            var jalur = wadah && wadah.querySelector('[data-kr-jalur], .kr-sorotan-track, .kr-aparat-track');
            if (jalur) geser(jalur, t.matches('[data-kr-geser-next]') ? 1 : -1);
        }
    });

    // Satu langkah = satu kartu untuk jalur berkartu (perangkat), satu layar untuk sorotan foto.
    function langkah(jalur) {
        if (jalur.hasAttribute('data-kr-jalur') && jalur.children.length > 1) {
            return jalur.children[1].offsetLeft - jalur.children[0].offsetLeft;
        }
        return jalur.clientWidth;
    }

    function geser(jalur, arah) {
        var ujungKanan = jalur.scrollLeft + jalur.clientWidth >= jalur.scrollWidth - 4;
        if (arah > 0 && ujungKanan) {
            jalur.scrollTo({ left: 0 });
        } else if (arah < 0 && jalur.scrollLeft <= 4) {
            jalur.scrollTo({ left: jalur.scrollWidth });
        } else {
            jalur.scrollBy({ left: arah * langkah(jalur) });
        }
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            tutupLaci();
            if (cari && !cari.hidden) aturCari(false);
        }
    });

    // Bayangan navbar & tombol ke atas: satu pengamat gulir yang ringan
    var nav = document.querySelector('[data-kr-nav]');
    var keAtas = document.querySelector('[data-kr-totop]');
    var menunggu = false;
    function cekGulir() {
        menunggu = false;
        var y = window.scrollY;
        if (nav) nav.classList.toggle('is-stuck', y > 40);
        if (keAtas) keAtas.classList.toggle('is-visible', y > 900);
    }
    window.addEventListener('scroll', function () {
        if (!menunggu) {
            menunggu = true;
            window.requestAnimationFrame(cekGulir);
        }
    }, { passive: true });
    cekGulir();

    // Sorotan foto & pita perangkat bergeser sendiri; berhenti saat disentuh/diarahkan, tab tersembunyi,
    // atau bila pengguna memilih gerak minim (prefers-reduced-motion).
    var hemat = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    document.querySelectorAll('.kr-sorotan, [data-kr-otomatis]').forEach(function (s) {
        var jalur = s.querySelector('[data-kr-jalur], .kr-sorotan-track');
        if (hemat || !jalur || jalur.children.length < 2) return;
        var jeda = false;
        ['mouseenter', 'focusin', 'touchstart'].forEach(function (ev) { s.addEventListener(ev, function () { jeda = true; }, { passive: true }); });
        ['mouseleave', 'focusout'].forEach(function (ev) { s.addEventListener(ev, function () { jeda = false; }); });
        setInterval(function () {
            if (jeda || document.hidden || jalur.scrollWidth <= jalur.clientWidth + 4) return;
            geser(jalur, 1);
        }, parseInt(s.getAttribute('data-kr-otomatis'), 10) || 6000);
    });
})();
