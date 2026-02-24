<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../../../modules/auth/store'

const authStore = useAuthStore()

// Calculamos el nombre a mostrar según el rol
const roleDisplay = computed(() => {
    if (!authStore.user || !authStore.user.roles || authStore.user.roles.length === 0) return 'Invitado'
    const roleName = authStore.user.roles[0].name
    if (roleName === 'admin') return 'ADMINISTRADOR'
    if (roleName === 'vendor') return 'VENDEDOR'
    return 'CLIENTE VIP'
})

// Calculamos el color según el rol
const badgeClass = computed(() => {
    if (roleDisplay.value === 'ADMINISTRADOR') return 'bg-danger'
    if (roleDisplay.value === 'VENDEDOR') return 'bg-warning text-dark'
    if (roleDisplay.value === 'CLIENTE VIP') return 'bg-primary text-dark'
    return 'bg-secondary'
})
</script>

<template>
    <span class="badge ms-2 rounded-pill" :class="badgeClass" style="font-size: 0.7rem; letter-spacing: 1px;">
        {{ roleDisplay }}
    </span>
</template>