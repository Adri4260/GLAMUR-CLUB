import { defineStore } from "pinia";
import http from "../../services/http";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: JSON.parse(localStorage.getItem("user")) || null,
    token: localStorage.getItem("token") || null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => {
      if (!state.user || !state.user.roles) return false;
      return state.user.roles.some((role) => role.name === "admin");
    },
  },

  actions: {
    async login(email, password) {
      const response = await http.post("/login", { email, password });
      this.token = response.data.token;
      this.user = response.data.user;
      localStorage.setItem("token", this.token);
      localStorage.setItem("user", JSON.stringify(this.user));
    },

    async logout() {
      if (this.token) {
        try {
          await http.post("/logout");
        } catch (error) {
          console.error(error);
        }
      }
      this.token = null;
      this.user = null;
      localStorage.removeItem("token");
      localStorage.removeItem("user");
    },

    // NUEVO: Pide los datos frescos al servidor usando el token guardado
    async fetchUser() {
      if (!this.token) return;
      try {
        const response = await http.get("/user"); // Ruta de Sanctum en Laravel
        // Actualizamos los datos (por si le cambiaron el rol en la base de datos)
        this.user = response.data;
        localStorage.setItem("user", JSON.stringify(this.user));
      } catch (error) {
        // Si el token caducó o es falso, cerramos sesión
        this.logout();
      }
    },
  },
});
