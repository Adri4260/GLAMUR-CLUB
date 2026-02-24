<script setup>
import { ref, onMounted } from 'vue'
import http from '../services/http'
import CardProducte from '../components/CardProducte.vue' // 1. IMPORTAMOS EL COMPONENTE

const products = ref([])
const loading = ref(true)

onMounted(async () => {
    try {
        const response = await http.get('/products')
        products.value = response.data.data || response.data
    } catch (error) {
        console.error("Error al cargar productos:", error)
    } finally {
        loading.value = false
    }
})
</script>

<template>
    <section class="container py-4">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--color-primary);">
                Catálogo de Productos</h1>
            <p class="text-muted">Nuestra colección cargada dinámicamente desde Laravel API</p>
        </div>

        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border" style="color: var(--color-primary); width: 3rem; height: 3rem;" role="status">
            </div>
            <p class="mt-3 text-muted">Cargando joyas...</p>
        </div>

        <div v-else class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            <div v-for="product in products" :key="product.id" class="col">
                <CardProducte :product="product" />
            </div>
        </div>
    </section>
</template>