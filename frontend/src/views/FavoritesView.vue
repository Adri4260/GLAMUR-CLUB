<script setup>
import { useShopStore } from '../store/shopStore'
import CardProducte from '../components/CardProducte.vue'

const shopStore = useShopStore()
</script>

<template>
  <div class="glamur-page py-5">
    <div class="container py-4">
      
      <div class="text-center mb-5">
        <h1 class="display-4 font-playfair text-white">Tus <span class="text-gold">Favoritos</span></h1>
        <p class="subtitle mt-2">Los productos que te han enamorado</p>
      </div>

      <!-- SI HAY FAVORITOS -->
      <div v-if="shopStore.favorites.length > 0" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <div v-for="product in shopStore.favorites" :key="product.id" class="col">
          <CardProducte :product="product" />
        </div>
      </div>

      <!-- SI ESTÁ VACÍO -->
      <div v-else class="text-center py-5 empty-state">
        <div class="empty-icon mb-3">🖤</div>
        <h4 class="text-white">Aún no tienes favoritos</h4>
        <p class="text-muted mb-4">Explora nuestro catálogo y guarda los que más te gusten.</p>
        <router-link to="/catalogo" class="btn-glamur-gold">Ir al Catálogo</router-link>
      </div>

    </div>
  </div>
</template>

<style scoped>
.glamur-page {
  min-height: calc(100vh - 74px);
  background: radial-gradient(circle at top, #0d291e 0%, #07120c 100%);
}
.font-playfair { font-family: 'Playfair Display', serif; font-weight: 700; }
.text-gold { color: #d4af37; }
.subtitle { color: #a3b8ab; font-family: system-ui, -apple-system, sans-serif; }
.empty-state { background: rgba(255,255,255,0.02); border-radius: 20px; padding: 4rem 2rem; border: 1px dashed rgba(212,175,55,0.2); }
.empty-icon { font-size: 3rem; opacity: 0.5; }
.btn-glamur-gold {
  display: inline-block; padding: 0.8rem 2rem; border-radius: 12px;
  background: transparent; border: 1px solid #d4af37; color: #d4af37;
  font-weight: 600; text-decoration: none; transition: all 0.3s ease;
}
.btn-glamur-gold:hover { background: #d4af37; color: #07150f; transform: translateY(-2px); }
</style>