<script setup>
import { computed } from 'vue'

const currentYear = computed(() => new Date().getFullYear())

const isAdmin = computed(() => true)
</script>

<template>

  <footer class="glamur-footer text-light">

    <div class="container footer-container">

      <div class="row gy-4">

        <!-- BRAND -->
        <div class="col-12 col-lg-4">

          <div class="d-flex align-items-center mb-3">

            <div class="brand-logo-text me-3">
              GC
            </div>

            <h2 class="brand-title mb-0">
              GLAMUR <span class="text-gold">CLUB</span>
            </h2>

          </div>

          <p class="brand-description">
            Perfumería premium impulsada por elegancia,
            innovación y experiencias exclusivas.
          </p>

          <div class="social-icons d-flex gap-3 mt-4">

            <a href="#" class="social-link">
              <i class="bi bi-instagram"></i>
            </a>

            <a href="#" class="social-link">
              <i class="bi bi-tiktok"></i>
            </a>

            <a href="#" class="social-link">
              <i class="bi bi-pinterest"></i>
            </a>

          </div>

        </div>

        <!-- NAV -->
        <div class="col-6 col-md-4 col-lg-2">

          <h5 class="footer-heading">
            Navegación
          </h5>

          <ul class="footer-links list-unstyled">

            <li>
              <router-link to="/" class="nav-link-custom">
                Inicio
              </router-link>
            </li>

            <li>
              <router-link to="/catalogo" class="nav-link-custom">
                Catálogo
              </router-link>
            </li>

            <li>
              <router-link to="/sostenibilidad" class="nav-link-custom">
                Sostenibilidad
              </router-link>
            </li>

          </ul>

        </div>

        <!-- INFO -->
        <div class="col-6 col-md-4 col-lg-3">

          <h5 class="footer-heading">
            Información
          </h5>

          <ul class="footer-links list-unstyled">

            <li>
              <router-link to="/about" class="nav-link-custom">
                Sobre Nosotros
              </router-link>
            </li>

            <li>
              <router-link to="/contacto" class="nav-link-custom">
                Contacto
              </router-link>
            </li>

            <li>
              <router-link to="/faqs" class="nav-link-custom">
                Preguntas Frecuentes
              </router-link>
            </li>

          </ul>

        </div>

        <!-- ADMIN -->
        <div
          v-if="isAdmin"
          class="col-12 col-md-4 col-lg-3 d-flex flex-column align-items-md-end"
        >

          <h5 class="footer-heading text-md-end">
            Panel Administración
          </h5>

          <router-link
            to="/admin/import-excel"
            class="btn btn-gold mt-2"
          >
            <i class="bi bi-file-earmark-excel-fill me-2"></i>
            Importar Excel
          </router-link>

        </div>

      </div>

      <!-- DIVIDER -->
      <div class="footer-divider"></div>

      <!-- BOTTOM -->
      <div class="row footer-bottom gy-2">

        <div class="col-md-6 text-center text-md-start">
          © {{ currentYear }} GLAMUR CLUB
        </div>

        <div class="col-md-6 text-center text-md-end">
          Proyecto Intermodular DAW
        </div>

      </div>

    </div>

  </footer>

</template>

<style scoped>

/* =========================
   BASE
========================= */

.glamur-footer {

  background:
    linear-gradient(
      180deg,
      #07150f 0%,
      #0b291b 55%,
      #102f20 100%
    );

  border-top:
    1px solid rgba(212, 175, 55, 0.18);

  position: relative;

  overflow: hidden;
}

.footer-container {

  padding-top: 3rem;
  padding-bottom: 1.5rem;
}

/* =========================
   BRAND
========================= */

.brand-logo-text {

  width: 52px;
  height: 52px;

  border-radius: 50%;

  display: flex;
  align-items: center;
  justify-content: center;

  background:
    linear-gradient(
      135deg,
      #d4af37,
      #f3dc87
    );

  color: #07150f;

  font-weight: 700;
}

.brand-title {

  font-family:
    'Playfair Display',
    serif;

  font-size: 1.8rem;

  font-weight: 700;

  color: white;
}

.text-gold {
  color: #d4af37;
}

.brand-description {

  color: #b8c6be;

  line-height: 1.6;

  max-width: 320px;

  font-size: 0.92rem;
}

/* =========================
   HEADINGS
========================= */

.footer-heading {

  color: #d4af37;

  font-size: 0.95rem;

  font-weight: 600;

  margin-bottom: 1rem;

  text-transform: uppercase;
}

/* =========================
   LINKS
========================= */

.footer-links li {
  margin-bottom: 0.45rem;
}

.nav-link-custom {

  color: #b8c6be;

  text-decoration: none;

  transition:
    color 0.25s ease;
}

.nav-link-custom:hover {

  color: #27e0a3;
}

/* =========================
   SOCIAL
========================= */

.social-link {

  width: 40px;
  height: 40px;

  border-radius: 50%;

  display: flex;
  align-items: center;
  justify-content: center;

  border:
    1px solid rgba(212, 175, 55, 0.2);

  color: #d4af37;

  text-decoration: none;

  transition:
    all 0.3s ease;
}

.social-link:hover {

  background: #d4af37;

  color: #07150f;

  transform:
    translateY(-2px);
}

/* =========================
   BUTTON
========================= */

.btn-gold {

  border:
    1px solid #d4af37;

  color: #d4af37;

  background: transparent;

  padding:
    0.7rem 1.1rem;

  border-radius: 12px;

  font-weight: 600;

  transition:
    all 0.3s ease;
}

.btn-gold:hover {

  background: #d4af37;

  color: #07150f;
}

/* =========================
   DIVIDER
========================= */

.footer-divider {

  height: 1px;

  background:
    linear-gradient(
      90deg,
      transparent,
      rgba(212, 175, 55, 0.25),
      transparent
    );

  margin:
    2rem 0
    1.2rem 0;
}

/* =========================
   BOTTOM
========================= */

.footer-bottom {

  color: #8fa297;

  font-size: 0.88rem;
}

</style>