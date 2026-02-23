<script setup>
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'
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
/* Aquí puedes añadir CSS extra si hace falta, pero el grueso ya está en tu public/css */
</style>