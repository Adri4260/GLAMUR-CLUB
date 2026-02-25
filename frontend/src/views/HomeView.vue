<script setup>
import { ref, onMounted } from 'vue'
import http from '../services/http'
import CardProducte from '../components/CardProducte.vue'

const featuredProducts = ref([])
const loading = ref(true)

onMounted(async () => {
    try {
        const response = await http.get('/products/featured')
        featuredProducts.value = response.data.data || response.data
    } catch (error) {
        console.error("Error al cargar destacados:", error)
    } finally {
        loading.value = false
    }
})
</script>

<template>
    <div>
        <div class="hero-section mb-5 position-relative rounded-4 overflow-hidden shadow-lg" style="height: 450px;">
            <img src="http://localhost/img/hero.jpg" class="w-100 h-100"
                style="object-fit: cover; filter: brightness(0.5);" alt="Glamur Club Hero">

            <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-3">
                <h1 class="display-3 fw-bold mb-3"
                    style="font-family: 'Playfair Display', serif; color: var(--color-primary);">Glamur Club</h1>
                <p class="lead mb-4 fs-4">Exclusividad, elegancia y lujo a tu alcance.</p>
                <router-link to="/catalogo" class="btn btn-lg fw-bold shadow px-5 py-3"
                    style="background: var(--color-primary); color: #000; border-radius: 50px;">
                    Descubrir Colección
                </router-link>
            </div>
        </div>

        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold"
                    style="font-family: 'Playfair Display', serif; color: var(--color-primary);">Joyas de la Corona</h2>
                <p class="text-muted">Nuestra selección más exclusiva para ti</p>
            </div>

            <div v-if="loading" class="text-center py-5">
                <div class="spinner-border" style="color: var(--color-primary);"></div>
            </div>

            <div v-else class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <div v-for="product in featuredProducts" :key="'feat-' + product.id" class="col">
                    <CardProducte :product="product" />
                </div>
            </div>
        </div>
    </div>
</template>