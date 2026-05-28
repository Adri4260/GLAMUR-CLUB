<script setup>
import { useRouter } from 'vue-router';
import { useAuthStore } from '../modules/auth/store.js'; // Ajusta esta ruta si es diferente en tu proyecto

const router = useRouter();
const authStore = useAuthStore();

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login'); // O redírigelo a '/' si prefieres que vaya a Inicio al salir
};
</script>

<template>

  <nav class="glamur-navbar navbar navbar-expand-lg">

    <div class="container">

      <router-link
        to="/"
        class="navbar-brand d-flex align-items-center gap-3"
      >

        <div class="brand-icon">
          GC
        </div>

        <span class="brand-text">
          GLAMUR <span>CLUB</span>
        </span>

      </router-link>

      <button
        class="navbar-toggler custom-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarContent"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div
        class="collapse navbar-collapse"
        id="navbarContent"
      >

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

          <li class="nav-item" v-if="authStore.isAdmin">
            <router-link
              to="/admin"
              class="nav-link admin-link"
            >
              Gestión Admin
            </router-link>
          </li>

        </ul>

        <div class="navbar-actions">

          <template v-if="authStore.isAuthenticated">
            <div class="user-pill">

              <span class="user-greeting">
                Hola, {{ authStore.user?.name || 'Usuario' }}
              </span>

              <span class="admin-badge" v-if="authStore.isAdmin">
                ADMIN
              </span>

            </div>

            <button class="btn-logout" @click="handleLogout">
              Salir
            </button>
          </template>

          <template v-else>
            <router-link to="/login" class="btn-logout" style="text-decoration: none; display: inline-block; text-align: center;">
              Iniciar Sesión
            </router-link>
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

.brand-icon {

  width: 44px;
  height: 44px;

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

  font-size: 0.95rem;

  box-shadow:
    0 8px 20px rgba(212, 175, 55, 0.18);
}

.brand-text {

  font-family:
    'Playfair Display',
    serif;

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
   RIGHT
========================= */

.navbar-actions {

  display: flex;
  align-items: center;
  gap: 1rem;
}

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

  .brand-text {

    font-size: 1.4rem;
  }

}

</style>