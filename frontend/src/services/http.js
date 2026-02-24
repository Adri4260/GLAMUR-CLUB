import axios from "axios";
import router from "../router";

const http = axios.create({
  baseURL: "http://localhost/api",
  headers: {
    Accept: "application/json",
    "Content-Type": "application/json",
  },
});

// 1. Interceptor de PETICIÓN (Envía el Token siempre)
http.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// 2. Interceptor de RESPUESTA (Vigila los errores de seguridad)
http.interceptors.response.use(
  (response) => response, // Si todo va bien, deja pasar la respuesta
  (error) => {
    // Si Laravel dice que el token es inválido (401) o no tienes permisos (403)
    if (
      error.response &&
      (error.response.status === 401 || error.response.status === 403)
    ) {
      console.warn("Sesión caducada o acceso denegado. Cerrando sesión...");

      // Borramos los datos locales
      localStorage.removeItem("token");
      localStorage.removeItem("user");

      // Forzamos al usuario a ir al login (solo si no está ya allí)
      if (window.location.pathname !== "/login") {
        window.location.href = "/login";
      }
    }
    return Promise.reject(error);
  },
);

export default http;
