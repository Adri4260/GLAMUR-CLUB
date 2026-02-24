import { useAuthStore } from "../../../modules/auth/store";

export function useRole() {
  const authStore = useAuthStore();

  const hasRole = (roleName) => {
    // Si no hay usuario logueado o no tiene roles, devolvemos falso
    if (!authStore.user || !authStore.user.roles) return false;

    // Buscamos si tiene el rol que pedimos
    return authStore.user.roles.some((role) => role.name === roleName);
  };

  // Aquí podríamos añadir lógica más compleja, como can('edit') si quisiéramos

  return { hasRole };
}
