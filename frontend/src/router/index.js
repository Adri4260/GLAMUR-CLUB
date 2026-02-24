import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../modules/auth/store";
import CatalogoView from "../views/CatalogoView.vue";
import LoginView from "../modules/auth/views/LoginView.vue";
import AdminView from "../views/AdminView.vue"; // Importamos la vista

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "catalogo",
      component: CatalogoView,
    },
    {
      path: "/login",
      name: "login",
      component: LoginView,
    },
    {
      path: "/admin",
      name: "admin",
      component: AdminView,
      meta: { requiresAuth: true, role: "admin" }, // <--- ETIQUETA DE SEGURIDAD
    },
  ],
});

// EL GUARDIÁN GLOBAL (Se ejecuta ANTES de cada cambio de página)
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  // 1. Si la ruta requiere estar logueado y no lo está -> al Login
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return next("/login");
  }

  // 2. Si la ruta requiere un ROL específico y no lo tiene -> al Catálogo (o página 403)
  if (to.meta.role && !authStore.isAdmin) {
    alert("Acceso denegado. No tienes permisos suficientes.");
    return next("/");
  }

  // 3. Si todo está bien, le dejamos pasar
  next();
});

export default router;
