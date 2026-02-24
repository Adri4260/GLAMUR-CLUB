import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../modules/auth/store";
import CatalogoView from "../views/CatalogoView.vue";
import LoginView from "../modules/auth/views/LoginView.vue";
import AdminView from "../views/AdminView.vue"; // Importamos la vista
import DetalleView from "../views/DetalleView.vue";

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
    { path: "/producto/:id", name: "producto-detalle", component: DetalleView },
    {
      path: "/admin",
      name: "admin",
      component: AdminView,
      meta: { requiresAuth: true, role: "admin" }, // <--- ETIQUETA DE SEGURIDAD
    },
  ],
});

// EL GUARDIÁN GLOBAL (Modernizado para Vue Router 4)
router.beforeEach((to, from) => {
  const authStore = useAuthStore();

  // 1. Si la ruta requiere estar logueado y no lo está -> al Login
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return "/login";
  }

  // 2. Si la ruta requiere un ROL específico y no lo tiene -> al Catálogo (o página 403)
  if (to.meta.role && !authStore.isAdmin) {
    alert("Acceso denegado. No tienes permisos suficientes.");
    return "/";
  }

  // 3. Si todo está bien, no devolvemos nada (equivale a dejarle pasar)
  return true;
});

export default router;
