<script setup>
import { useAuthStore } from '../modules/auth/store'
const authStore = useAuthStore()
</script>

<template>
    <div class="container py-5 text-center">
        <h1 class="text-danger fw-bold">ZONA DE ADMINISTRACIÓN</h1>
        <p>Bienvenido, {{ authStore.user?.name }}. Tienes permisos de nivel supremo.</p>
        <img src="https://media.giphy.com/media/xT0xeJpnrWC4XWblWQ/giphy.gif" alt="Security"
            class="mt-4 rounded shadow">
    </div>
</template>