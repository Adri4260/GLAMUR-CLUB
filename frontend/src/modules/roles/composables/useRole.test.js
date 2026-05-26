import { describe, it, expect, beforeEach } from "vitest";
import { setActivePinia, createPinia } from "pinia";
import { useAuthStore } from "../../../modules/auth/store";
import { useRole } from "./useRole";

describe("Sistema de Permisos - useRole.js", () => {
  // Antes de cada prueba, reiniciamos la memoria de Vue (Pinia)
  beforeEach(() => {
    setActivePinia(createPinia());
  });

  it("Un ADMINISTRADOR debería poder borrar (delete)", () => {
    const authStore = useAuthStore();
    // Simulamos que ha entrado un admin
    authStore.user = { roles: [{ name: "admin" }] };

    const { can } = useRole();
    expect(can("delete")).toBe(true);
  });

  it("Un VENDEDOR debería poder editar (edit) pero NO moderar (moderate)", () => {
    const authStore = useAuthStore();
    // Simulamos que ha entrado un vendedor
    authStore.user = { roles: [{ name: "vendor" }] };

    const { can } = useRole();
    expect(can("edit")).toBe(true);
    expect(can("moderate")).toBe(false);
  });

  it("Un CLIENTE NORMAL solo debería poder leer (read), NO borrar", () => {
    const authStore = useAuthStore();
    // Simulamos que ha entrado un cliente
    authStore.user = { roles: [{ name: "user" }] };

    const { can } = useRole();
    expect(can("read")).toBe(true);
    expect(can("delete")).toBe(false);
  });

  it("Un INVITADO (sin loguear) NO debería tener permisos", () => {
    const authStore = useAuthStore();
    // Simulamos que nadie ha iniciado sesión
    authStore.user = null;

    const { can } = useRole();
    expect(can("read")).toBe(false);
    expect(can("delete")).toBe(false);
  });
});
