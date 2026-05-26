<script setup>
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'
</script>

<template>
  <div class="app-layout">

    <Navbar />

    <main class="main-content">
      <router-view />
    </main>

    <Footer />

  </div>
</template>

<style>

html,
body,
#app {
  height: 100%;
  margin: 0;
}

body {

  background:
    linear-gradient(
      180deg,
      #101514 0%,
      #151b19 100%
    );

  color: white;

  font-family:
    'Inter',
    sans-serif;
}

.app-layout {

  min-height: 100vh;

  display: flex;

  flex-direction: column;
}

.main-content {

  flex: 1;

  padding-top: 20px;
}

</style>