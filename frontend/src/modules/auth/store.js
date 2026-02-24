import { defineStore } from "pinia";
import http from "../../services/http";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    // Intentamos recuperar los datos si ya se había logueado antes
    user: JSON.parse(localStorage.getItem("user")) || null,
    token: localStorage.getItem("token") || null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.email === "admin@glamur.com", // Validaremos rols más adelante
  },

  actions: {
    async login(email, password) {
      const response = await http.post("/login", { email, password });

      this.token = response.data.token;
      this.user = response.data.user;

      // Guardamos en el navegador
      localStorage.setItem("token", this.token);
      localStorage.setItem("user", JSON.stringify(this.user));
    },

    async logout() {
      if (this.token) {
        try {
          await http.post("/logout");
        } catch (error) {
          console.error("Error al desloguear en el servidor");
        }
      }
      this.token = null;
      this.user = null;
      localStorage.removeItem("token");
      localStorage.removeItem("user");
    },
  },
});
