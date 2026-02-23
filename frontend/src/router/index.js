import { createRouter, createWebHistory } from "vue-router";
import CatalogoView from "../views/CatalogoView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "catalogo",
      component: CatalogoView,
    },
    // Más adelante añadiremos /login, /admin, etc.
  ],
});

export default router;
