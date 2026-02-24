import { useAuthStore } from "../../../modules/auth/store";

export function useRole() {
  const authStore = useAuthStore();

  // NUEVA FUNCIÓN: Comprueba PERMISOS en lugar de roles
  const can = (permission) => {
    if (
      !authStore.user ||
      !authStore.user.roles ||
      authStore.user.roles.length === 0
    ) {
      return false;
    }

    // Leemos el rol actual del usuario
    const role = authStore.user.roles[0].name;

    // Diccionario de permisos (Exactamente como pide el PDF)
    const rules = {
      admin: ["create", "edit", "delete", "moderate"],
      vendor: ["create", "edit", "delete"],
      editor: ["moderate"],
      user: ["read"],
    };

    // Retorna true si el permiso solicitado está dentro del array de su rol
    return rules[role]?.includes(permission) ?? false;
  };

  // Mantenemos esta por si hace falta, pero la importante ahora es can()
  const hasRole = (roleName) => {
    if (!authStore.user || !authStore.user.roles) return false;
    return authStore.user.roles.some((r) => r.name === roleName);
  };

  return { can, hasRole };
}
