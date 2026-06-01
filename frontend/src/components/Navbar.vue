<script setup>
import { useRouter } from 'vue-router';
import { useAuthStore } from '../modules/auth/store.js';
import { useShopStore } from '../store/shopStore.js';

const router = useRouter();
const authStore = useAuthStore();
const shopStore = useShopStore();

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};
</script>

<template>

  <nav class="glamur-navbar navbar navbar-expand-lg">

    <div class="container">

      <!-- BRAND -->
      <router-link
        to="/"
        class="navbar-brand d-flex align-items-center gap-3"
      >
        <img src="/img/logo.png" alt="Glamur Club Logo" class="brand-icon-img">
        <span class="brand-text">
          GLAMUR <span>CLUB</span>
        </span>
      </router-link>

      <!-- MOBILE -->
      <button
        class="navbar-toggler custom-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarContent"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- CONTENT -->
      <div
        class="collapse navbar-collapse"
        id="navbarContent"
      >

        <!-- LINKS -->
        <ul class="navbar-nav mx-auto gap-lg-4">

          <li class="nav-item">
            <router-link
              to="/"
              class="nav-link glamur-link"
            >
              Inicio
            </router-link>
          </li>

          <li class="nav-item">
            <router-link
              to="/catalogo"
              class="nav-link glamur-link"
            >
              Catálogo
            </router-link>
          </li>

          <!-- Sólo se muestra si es administrador -->
          <li class="nav-item" v-if="authStore.isAdmin">
            <router-link
              to="/admin"
              class="nav-link admin-link"
            >
              Gestión Admin
            </router-link>
          </li>

        </ul>

        <!-- USER & SHOP ACTIONS -->
        <div class="navbar-actions">

          <!-- ICONOS TIENDA (Favoritos y Carrito) -->
          <div class="shop-icons-container">
            <router-link to="/favoritos" class="shop-icon-btn position-relative">
              <i class="bi bi-heart"></i>
              <span v-if="shopStore.favoritesCount > 0" class="shop-badge">
                {{ shopStore.favoritesCount }}
              </span>
            </router-link>

            <router-link to="/carrito" class="shop-icon-btn position-relative">
              <i class="bi bi-cart3"></i>
              <span v-if="shopStore.cartCount > 0" class="shop-badge">
                {{ shopStore.cartCount }}
              </span>
            </router-link>
          </div>

          <!-- SI EL USUARIO ESTÁ LOGUEADO -->
          <template v-if="authStore.isAuthenticated">
            <div class="user-pill">

              <span class="user-greeting">
                Hola, {{ authStore.user?.name || 'Usuario' }}
              </span>

              <!-- El badge de admin sólo se muestra si realmente tiene el rol -->
              <span class="admin-badge" v-if="authStore.isAdmin">
                ADMIN
              </span>

            </div>

            <!-- Botón de Perfil -->
            <router-link to="/perfil" class="btn-profile">
              👤 Perfil
            </router-link>

            <!-- Botón de Salir -->
            <button class="btn-logout" @click="handleLogout">
              Salir
            </button>
          </template>

          <!-- SI EL USUARIO NO ESTÁ LOGUEADO -->
          <template v-else>
            <ul class="navbar-nav">
              <li class="nav-item">
                <router-link 
                  to="/login" 
                  class="nav-link glamur-link"
                >
                  Iniciar Sesión
                </router-link>
              </li>
            </ul>
          </template>

        </div>

      </div>

    </div>

  </nav>

</template>

<style scoped>

/* =========================
   NAVBAR BASE
========================= */

.glamur-navbar {

  background:
    rgba(7, 21, 15, 0.88);

  backdrop-filter: blur(18px);

  border-bottom:
    1px solid rgba(212, 175, 55, 0.12);

  padding:
    0.75rem 0;

  z-index: 1000;
}

/* =========================
   BRAND
========================= */

.navbar-brand {
  text-decoration: none;
}

.brand-icon-img {
  height: 92px;
  width: auto;
  object-fit: contain;
  filter: drop-shadow(0 4px 10px rgba(212, 175, 55, 0.3));
  transition: transform 0.3s ease;
}

.brand-icon-img:hover {
  transform: scale(1.05);
}

.brand-text {
  font-family: 'Playfair Display', serif;
  font-size: 1.75rem;
  font-weight: 700;
  letter-spacing: 1px;
  color: #f5f1e8;
}

.brand-text span {
  color: #d4af37;
}

/* =========================
   LINKS
========================= */

.glamur-link,
.admin-link {

  font-weight: 500;

  transition:
    all 0.25s ease;

  position: relative;
}

.glamur-link {

  color: #d7e3db;
}

.glamur-link:hover {

  color: #27e0a3;
}

.admin-link {

  color: #ff6f85;

  font-weight: 600;
}

.admin-link:hover {

  color: #ff9dad;
}

.glamur-link::after,
.admin-link::after {

  content: '';

  position: absolute;

  left: 0;
  bottom: -4px;

  width: 0;
  height: 2px;

  border-radius: 999px;

  background:
    linear-gradient(
      90deg,
      #27e0a3,
      #d4af37
    );

  transition:
    width 0.3s ease;
}

.glamur-link:hover::after,
.admin-link:hover::after {

  width: 100%;
}

/* =========================
   ACTIVE ROUTE
========================= */

.router-link-active.glamur-link {

  color: #27e0a3 !important;

  font-weight: 700;
}

.router-link-active.admin-link {

  color: #ff8ea1 !important;

  font-weight: 700;
}

.router-link-active::after {

  width: 100% !important;
}

/* =========================
   RIGHT ACTIONS
========================= */

.navbar-actions {

  display: flex;
  align-items: center;
  gap: 1rem;
}

/* --- ICONOS TIENDA --- */
.shop-icons-container {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  padding-right: 0.5rem;
  border-right: 1px solid rgba(255, 255, 255, 0.1);
}

.shop-icon-btn {
  color: #d8e3dc;
  font-size: 1.3rem;
  text-decoration: none;
  transition: color 0.3s ease, transform 0.2s ease;
}

.shop-icon-btn:hover {
  color: #27e0a3;
  transform: translateY(-2px);
}

.shop-badge {
  position: absolute;
  top: -5px;
  right: -8px;
  background: linear-gradient(135deg, #d4af37, #f3dc87);
  color: #07150f;
  font-size: 0.65rem;
  font-weight: 800;
  padding: 0.15em 0.45em;
  border-radius: 50px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.5);
}

/* --- USER PILL --- */
.user-pill {

  display: flex;
  align-items: center;
  gap: 0.6rem;

  padding:
    0.55rem 1rem;

  border-radius: 999px;

  background:
    rgba(255,255,255,0.04);

  border:
    1px solid rgba(255,255,255,0.06);
}

.user-greeting {

  color: #d8e3dc;

  font-size: 0.9rem;
}

.admin-badge {

  background: #ff647c;

  color: white;

  padding:
    0.25rem 0.65rem;

  border-radius: 999px;

  font-size: 0.72rem;

  font-weight: 700;
}

/* --- BOTONES --- */
.btn-profile {
  border: 1px solid rgba(212, 175, 55, 0.4);
  padding: 0.7rem 1.25rem;
  border-radius: 14px;
  background: rgba(255, 255, 255, 0.03);
  color: #d4af37;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.btn-profile:hover {
  background: rgba(212, 175, 55, 0.15);
  color: #f3dc87;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(212, 175, 55, 0.15);
}

.btn-logout {

  border: none;

  padding:
    0.7rem 1.25rem;

  border-radius: 14px;

  background:
    linear-gradient(
      135deg,
      #27e0a3,
      #1fc98f
    );

  color: #07150f;

  font-weight: 600;

  transition:
    all 0.3s ease;
}

.btn-logout:hover {

  transform:
    translateY(-2px);

  box-shadow:
    0 10px 25px rgba(39, 224, 163, 0.22);
}

/* =========================
   TOGGLER
========================= */

.custom-toggler {

  border:
    1px solid rgba(212, 175, 55, 0.2);

  background:
    rgba(255,255,255,0.03);
}

.navbar-toggler-icon {
  filter: invert(1);
}

/* =========================
   MOBILE
========================= */

@media (max-width: 991px) {

  .navbar-collapse {

    padding-top: 1.2rem;
  }

  .navbar-actions {

    margin-top: 1rem;

    flex-direction: column;

    align-items: flex-start;
  }

  .shop-icons-container {
    border-right: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    width: 100%;
  }

}

</style>