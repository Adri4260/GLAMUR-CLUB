<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from './modules/auth/store'
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'

const authStore = useAuthStore()

// Cuando la app arranca, comprobamos si el token guardado sigue siendo válido
onMounted(() => {
  authStore.fetchUser()
})
</script>

<template>
  <div class="d-flex flex-column min-vh-100">
    <Navbar />

    <main class="flex-grow-1 container py-5">
      <router-view></router-view>
    </main>

    <Footer />
  </div>
</template>

<style>
/* Estilos globales si son necesarios */
</style>