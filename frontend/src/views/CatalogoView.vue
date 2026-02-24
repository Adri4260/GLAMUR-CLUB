<script setup>
import { ref, onMounted } from 'vue'
import http from '../services/http'
import RoleGuard from '../modules/roles/components/RoleGuard.vue'

const products = ref([])
const loading = ref(true)

onMounted(async () => {
    try {
        const response = await http.get('/products')
        // Soportamos si los datos vienen envueltos en "data" (por el Resource) o directos
        products.value = response.data.data || response.data
    } catch (error) {
        console.error("Error al cargar productos:", error)
    } finally {
        loading.value = false
    }
})

// Función inteligente para arreglar la ruta de las imágenes
const getImageUrl = (path) => {
    if (!path) return 'http://localhost/img/prod1.jpg'; // Imagen por defecto
    if (path.startsWith('http')) return path; // Si ya es una URL completa, la dejamos igual

    // Limpiamos la ruta por si viene con 'public/' desde el Excel
    let cleanPath = path.replace('/public/', '').replace('public/', '');
    if (!cleanPath.startsWith('img/')) cleanPath = 'img/' + cleanPath;

    // Le forzamos a que pida la imagen al puerto 80 (Laravel)
    return `http://localhost/${cleanPath}`;
}
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
                <div class="card h-100 border-0 shadow-sm product-card"
                    style="background: var(--bg-card); transition: transform 0.3s ease;">

                    <div class="position-relative" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;">
                        <img :src="getImageUrl(product.image)" class="w-100 h-100" style="object-fit: cover;"
                            :alt="product.name" @error="$event.target.src = 'http://localhost/img/prod1.jpg'"> <span
                            class="badge bg-dark position-absolute top-0 start-0 m-2 text-uppercase"
                            style="letter-spacing: 1px; font-size: 0.7rem;">
                            {{ product.category }}
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column p-4">
                        <h5 class="card-title fw-bold mb-2"
                            style="font-family: 'Playfair Display', serif; font-size: 1.25rem; color: var(--text-primary);">
                            {{ product.name }}
                        </h5>

                        <p class="card-text text-muted small flex-grow-1"
                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                            {{ product.description }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fs-5 fw-bold" style="color: var(--color-primary);">{{ product.price }}€</span>
                            <span class="text-muted small">Stock: {{ product.stock }}</span>
                        </div>

                        <div class="mt-3 d-grid gap-2">
                            <button class="btn fw-bold shadow-sm"
                                style="background: var(--color-primary); color: #000; border: none;">
                                Añadir al Carrito
                            </button>

                            <RoleGuard requireRole="admin">
                                <div class="d-flex gap-2 mt-2">
                                    <button class="btn btn-sm btn-outline-secondary w-50">✏️ Editar</button>
                                    <button class="btn btn-sm btn-outline-danger w-50">🗑️ Borrar</button>
                                </div>
                            </RoleGuard>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</template>

<style scoped>
/* Efecto hover elegante para las tarjetas */
.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
}
</style>