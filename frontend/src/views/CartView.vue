<script setup>
import { computed } from 'vue'
import { useShopStore } from '../store/shopStore'

const shopStore = useShopStore()

// Calcula el total del carrito
const subtotal = computed(() => {
  return shopStore.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0)
})

// --- FIX PARA IMÁGENES EN EL CARRITO ---
const getImageUrl = (path) => {
    const apiBase = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api';
    const serverUrl = apiBase.replace(/\/api$/, '');

    if (!path) return `${serverUrl}/img/prod1.jpg`
    if (path.startsWith('http')) return path

    let cleanPath = path.replace('/public/', '').replace('public/', '')
    if (!cleanPath.startsWith('img/')) cleanPath = 'img/' + cleanPath

    return `${serverUrl}/${cleanPath}`
}
</script>

<template>
  <div class="glamur-page py-5">
    <div class="container py-4">
      
      <h1 class="font-playfair text-white border-bottom border-secondary pb-3 mb-4">
        Tu <span class="text-gold">Cesta</span>
      </h1>

      <div class="row" v-if="shopStore.cart.length > 0">
        
        <!-- LISTA DE PRODUCTOS -->
        <div class="col-lg-7">
          <div class="cart-items-container">
            <div v-for="item in shopStore.cart" :key="item.id" class="cart-item d-flex align-items-center mb-3 p-3">
              
              <!-- MINIATURA DE IMAGEN -->
              <div class="cart-img-wrapper">
                <img :src="getImageUrl(item.image)" class="cart-item-img" :alt="item.name">
              </div>

              <!-- INFO -->
              <div class="flex-grow-1 ms-3">
                <h5 class="text-white mb-1">{{ item.name }}</h5>
                <p class="text-gold mb-0 fw-bold">{{ item.price }} €</p>
              </div>

              <!-- CONTROLES -->
              <div class="quantity-controls d-flex align-items-center mx-3">
                <button class="btn-qty" @click="shopStore.addToCart(item, -1)" :disabled="item.quantity <= 1">-</button>
                <span class="text-white mx-2 fw-bold" style="min-width: 20px; text-align: center;">{{ item.quantity }}</span>
                <button class="btn-qty" @click="shopStore.addToCart(item, 1)">+</button>
              </div>

              <!-- BORRAR -->
              <button class="btn-delete ms-2" @click="shopStore.removeFromCart(item.id)" title="Eliminar producto">
                <i class="bi bi-trash3-fill"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- RESUMEN DE COMPRA (Detallado y corregido) -->
        <div class="col-lg-5 mt-4 mt-lg-0">
          <div class="summary-box p-4">
            <h4 class="text-white mb-4">Resumen de Compra</h4>
            
            <!-- DESGLOSE DE PRODUCTOS -->
            <div class="summary-details mb-4">
               <div v-for="item in shopStore.cart" :key="'sum-'+item.id" class="d-flex justify-content-between mb-3 align-items-center">
                  
                  <div class="summary-item-name text-white pe-3">
                     {{ item.name }} 
                     <!-- Fix visual: clase text-summary-muted para que se lea perfectamente -->
                     <span class="summary-item-calc d-block text-summary-muted mt-1">
                       {{ item.price }} € &nbsp;x{{ item.quantity }}
                     </span>
                  </div>

                  <div class="summary-item-total text-white fw-bold text-nowrap">
                     {{ (item.price * item.quantity).toFixed(2) }} €
                  </div>

               </div>
            </div>

            <!-- ENVÍO -->
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-secondary">
              <!-- Fix visual: clase text-summary-muted -->
              <span class="text-summary-muted">Envío Premium</span>
              <span class="text-success fw-bold">Gratis</span>
            </div>
            
            <!-- TOTAL -->
            <div class="d-flex justify-content-between mb-4 mt-2">
              <h5 class="text-gold fw-bold mb-0">Total</h5>
              <h5 class="text-gold fw-bold mb-0">{{ subtotal.toFixed(2) }} €</h5>
            </div>
            
            <button class="btn-glamur-submit w-100">Finalizar Compra</button>
          </div>
        </div>

      </div>

      <!-- SI EL CARRITO ESTÁ VACÍO -->
      <div v-else class="text-center py-5 empty-state">
        <div class="empty-icon mb-3">🛒</div>
        <h4 class="text-white">Tu cesta está vacía</h4>
        <p class="text-visible mb-4">Añade productos exclusivos a tu carrito.</p>
        <router-link to="/catalogo" class="btn-glamur-gold">Volver al Catálogo</router-link>
      </div>

    </div>
  </div>
</template>

<style scoped>
.glamur-page { min-height: calc(100vh - 74px); background: #07120c; }
.font-playfair { font-family: 'Playfair Display', serif; font-weight: 700; }
.text-gold { color: #d4af37; }

/* FIX: TEXTOS SECUNDARIOS VISIBLES Y ELEGANTES */
.text-visible {
  color: rgba(255, 255, 255, 0.65);
  font-size: 1.05rem;
}
.text-summary-muted {
  color: #a3b8ab !important; /* Un gris verdoso claro muy legible */
  font-size: 0.85rem;
}

/* =========================
   CART ITEM & IMAGE
========================= */
.cart-item {
  background: rgba(255,255,255,0.03); 
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 16px; 
  transition: all 0.3s ease;
}
.cart-item:hover { 
  border-color: rgba(212,175,55,0.3); 
  background: rgba(255,255,255,0.05); 
}

.cart-img-wrapper {
  width: 75px;
  height: 75px;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.5rem;
  flex-shrink: 0;
}
.cart-item-img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

/* =========================
   CONTROLS
========================= */
.quantity-controls { 
  background: rgba(0,0,0,0.4); 
  border-radius: 10px; 
  padding: 0.25rem; 
  border: 1px solid rgba(255,255,255,0.1); 
}
.btn-qty { 
  background: transparent; 
  border: none; 
  color: #d4af37; 
  font-size: 1.2rem; 
  width: 32px; 
  height: 32px; 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  border-radius: 8px; 
  transition: all 0.2s; 
}
.btn-qty:hover:not(:disabled) { background: rgba(212,175,55,0.2); }
.btn-qty:disabled { opacity: 0.3; cursor: not-allowed; }

.btn-delete { 
  background: rgba(255, 99, 132, 0.1); 
  color: #ff6384; 
  border: 1px solid rgba(255, 99, 132, 0.2); 
  width: 42px; 
  height: 42px; 
  border-radius: 10px; 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  transition: all 0.2s; 
}
.btn-delete:hover { background: #ff6384; color: white; transform: scale(1.05); }

/* =========================
   SUMMARY BOX
========================= */
.summary-box { 
  background: rgba(7, 21, 15, 0.8); 
  border: 1px solid rgba(212, 175, 55, 0.15); 
  border-radius: 20px; 
  box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
}

.summary-item-name {
  font-size: 0.95rem;
  line-height: 1.2;
}

.btn-glamur-submit {
  border: none; padding: 0.9rem; border-radius: 12px;
  background: linear-gradient(135deg, #27e0a3, #1fc98f); color: #07150f; font-weight: 600; transition: all 0.3s ease;
}
.btn-glamur-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(39, 224, 163, 0.25); }

/* =========================
   EMPTY STATE
========================= */
.empty-state { 
  background: rgba(255,255,255,0.02); 
  border-radius: 20px; 
  padding: 4rem 2rem; 
  border: 1px dashed rgba(212,175,55,0.2); 
}
.empty-icon { font-size: 3rem; opacity: 0.5; filter: grayscale(1); }
.btn-glamur-gold {
  display: inline-block; padding: 0.8rem 2rem; border-radius: 12px;
  background: transparent; border: 1px solid #d4af37; color: #d4af37; font-weight: 600; text-decoration: none; transition: all 0.3s ease;
}
.btn-glamur-gold:hover { background: #d4af37; color: #07150f; transform: translateY(-2px); }
</style>