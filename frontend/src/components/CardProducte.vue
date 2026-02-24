<script setup>
import RoleGuard from '../modules/roles/components/RoleGuard.vue'

// Definimos que este componente recibe un "product" como parámetro
const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})

// Nos traemos la función de las imágenes aquí, que es donde se usa
const getImageUrl = (path) => {
    if (!path) return 'http://localhost/img/prod1.jpg';
    if (path.startsWith('http')) return path;

    let cleanPath = path.replace('/public/', '').replace('public/', '');
    if (!cleanPath.startsWith('img/')) cleanPath = 'img/' + cleanPath;

    return `http://localhost/${cleanPath}`;
}
</script>

<template>
    <div class="card h-100 border-0 shadow-sm product-card"
        style="background: var(--bg-card); transition: transform 0.3s ease;">

        <div class="position-relative" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;">
            <img :src="getImageUrl(product.image)" class="w-100 h-100" style="object-fit: cover;" :alt="product.name"
                @error="$event.target.src = 'http://localhost/img/prod1.jpg'">

            <span class="badge bg-dark position-absolute top-0 start-0 m-2 text-uppercase"
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

            <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                <span class="fs-5 fw-bold" style="color: var(--color-primary);">{{ product.price }}€</span>
                <span class="text-muted small">Stock: {{ product.stock }}</span>
            </div>

            <div class="mt-auto d-grid gap-2">

                <router-link :to="'/producto/' + product.id" class="btn fw-bold shadow-sm"
                    style="background: var(--color-primary); color: #000; border: none; border-radius: 4px; padding: 0.5rem; text-decoration: none; text-align: center;">
                    Ver Detalles
                </router-link>

                <div class="d-flex gap-2 mt-2">
                    <RoleGuard requirePermission="edit">
                        <button class="btn btn-sm btn-outline-secondary flex-fill">✏️ Editar</button>
                    </RoleGuard>

                    <RoleGuard requirePermission="delete">
                        <button class="btn btn-sm btn-outline-danger flex-fill">🗑️ Borrar</button>
                    </RoleGuard>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
}
</style>