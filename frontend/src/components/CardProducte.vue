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
    <div class="card h-100 border-0 shadow-sm product-card position-relative">

        <span class="badge bg-success position-absolute top-0 end-0 m-2 shadow-sm" style="z-index: 10;">
            🌱 Eco-Packaging
        </span>

        <img :src="getImageUrl(product.image)" class="card-img-top p-3" :alt="'Fotografía del producto ' + product.name"
            style="height: 250px; object-fit: contain;">

        <div class="card-body d-flex flex-column text-center">
            <span class="badge bg-light text-dark mb-2 align-self-center border">{{ product.sku }}</span>
            <h5 class="card-title fw-bold" style="font-family: 'Playfair Display', serif;">{{ product.name }}</h5>
            <p class="card-text text-muted small flex-grow-1">{{ product.description?.substring(0, 60) }}...</p>
            <div class="fs-5 fw-bold mb-3" style="color: var(--color-primary);">{{ product.price }} €</div>

            <div class="mt-auto d-grid gap-2">
                <router-link :to="'/producto/' + product.id" class="btn fw-bold shadow-sm"
                    style="background: var(--color-primary); color: #000; text-decoration: none;">
                    Ver Detalles
                </router-link>
                <div class="d-flex gap-2 mt-2">
                    <RoleGuard requirePermission="edit">
                        <button class="btn btn-sm btn-outline-secondary flex-fill" aria-label="Editar producto">✏️
                            Editar</button>
                    </RoleGuard>
                    <RoleGuard requirePermission="delete">
                        <button class="btn btn-sm btn-outline-danger flex-fill" aria-label="Borrar producto">🗑️
                            Borrar</button>
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