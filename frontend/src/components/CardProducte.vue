<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../modules/auth/store'
import { useShopStore } from '../store/shopStore'
import RoleGuard from '../modules/roles/components/RoleGuard.vue'
import http from '../services/http' 

const router = useRouter()
const authStore = useAuthStore()
const shopStore = useShopStore()

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})

// Declaramos el evento que enviaremos a CatalogoView cuando se borre un producto
const emit = defineEmits(['product-deleted'])

const isDeleting = ref(false)

// Comprueba en tiempo real si el producto actual ya está en favoritos
const isFavorite = computed(() => {
    return shopStore.favorites.some(item => item.id === props.product.id)
})

// --- FUNCIONES DE TIENDA ---

const toggleFavorite = () => {
    if (!authStore.isAuthenticated) {
        alert("Debes iniciar sesión para añadir a favoritos.")
        router.push('/login')
        return
    }
    shopStore.toggleFavorite(props.product)
}

const addToCart = () => {
    if (!authStore.isAuthenticated) {
        alert("Debes iniciar sesión para añadir al carrito.")
        router.push('/login')
        return
    }
    shopStore.addToCart(props.product, 1)
}

// --- FIX PARA IMÁGENES EN PRODUCCIÓN Y LOCAL ---
const getImageUrl = (path) => {
    const apiBase = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api';

    const serverUrl = apiBase.replace(/\/api$/, '');

    if (!path)
        return `${serverUrl}/img/prod1.jpg`

    if (path.startsWith('http'))
        return path

    let cleanPath = path.replace('/public/', '').replace('public/', '')
    if (!cleanPath.startsWith('img/'))
        cleanPath = 'img/' + cleanPath

    return `${serverUrl}/${cleanPath}`
}

// --- FUNCIONES DE ADMIN ---

const handleEdit = () => {
    router.push(`/admin/products/${props.product.id}/edit`);
}

const handleDelete = async () => {
    if (confirm(`¿Estás seguro de que deseas eliminar "${props.product.name}"? Esta acción es irreversible.`)) {
        isDeleting.value = true
        try {
            await http.delete(`/products/${props.product.id}`)
            emit('product-deleted', props.product.id)

        } catch (error) {
            console.error("Error DETALLADO:", error.response || error);

            let alertMsg = "Error desconocido.";
            if (error.response) {
                const status = error.response.status;
                const serverMessage = error.response.data?.message || '';

                if (status === 404) alertMsg = `Error 404: La ruta DELETE /products/${props.product.id} no existe en api.php.`;
                else if (status === 401 || status === 403) alertMsg = "Error 401/403: No tienes permisos o el token no es válido.";
                else if (status === 500) alertMsg = "Error 500: Fallo en la base de datos (Probablemente el producto tenga valoraciones asociadas y no se pueda borrar).";
                else alertMsg = `Error ${status}: ${serverMessage}`;
            }

            alert(alertMsg);
        } finally {
            isDeleting.value = false
        }
    }
}
</script>

<template>

    <div class="card product-card h-100 position-relative">

        <button class="fav-badge" @click="toggleFavorite" :title="isFavorite ? 'Quitar de favoritos' : 'Añadir a favoritos'">
            <i class="bi" :class="isFavorite ? 'bi-heart-fill text-danger' : 'bi-heart'"></i>
        </button>

        <span class="eco-badge">
            🌱 Eco-Packaging
        </span>

        <div class="image-wrapper">

            <img :src="getImageUrl(product.image)" class="product-image"
                :alt="'Fotografía del producto ' + product.name">

        </div>

        <div class="card-body d-flex flex-column text-center">

            <span class="sku-badge">
                {{ product.sku }}
            </span>

            <h5 class="card-title product-title">
                {{ product.name }}
            </h5>

            <p class="product-description flex-grow-1">
                {{ product.description?.substring(0, 60) }}...
            </p>

            <div class="product-price">
                {{ product.price }} €
            </div>

            <div class="mt-auto d-grid gap-2">

                <div class="d-flex gap-2">
                    <router-link :to="'/producto/' + product.id" class="details-btn flex-grow-1">
                        Ver Detalles
                    </router-link>

                    <button class="cart-btn" @click="addToCart" title="Añadir al carrito">
                        <i class="bi bi-cart-plus"></i>
                    </button>
                </div>

                <div class="d-flex gap-2 mt-2">

                    <RoleGuard requirePermission="edit">

                        <button class="edit-btn flex-fill" aria-label="Editar producto" @click="handleEdit">
                            ✏️ Editar
                        </button>

                    </RoleGuard>

                    <RoleGuard requirePermission="delete">

                        <button class="delete-btn flex-fill" aria-label="Borrar producto" @click="handleDelete"
                            :disabled="isDeleting">
                            <span v-if="isDeleting" class="spinner-border spinner-border-sm me-1"></span>
                            {{ isDeleting ? 'Borrando...' : '🗑️ Borrar' }}
                        </button>

                    </RoleGuard>

                </div>

            </div>

        </div>

    </div>

</template>

<style scoped>
/* =========================
   CARD
========================= */

.product-card {

    background:
        linear-gradient(180deg,
            #18201d 0%,
            #111715 100%) !important;

    border:
        1px solid rgba(255, 255, 255, 0.06) !important;

    border-radius: 22px;

    overflow: hidden;

    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease,
        border-color 0.3s ease;

    box-shadow:
        0 12px 35px rgba(0, 0, 0, 0.28);
}

.product-card:hover {

    transform:
        translateY(-8px);

    border-color:
        rgba(39, 224, 163, 0.25) !important;

    box-shadow:
        0 18px 45px rgba(0, 0, 0, 0.38);
}

/* =========================
   BADGES
========================= */

.eco-badge {

    position: absolute;

    top: 14px;
    right: 14px;

    z-index: 10;

    background: #1fc98f;

    color: #07150f;

    font-weight: 700;

    padding:
        0.45rem 0.8rem;

    border-radius: 999px;

    font-size: 0.75rem;

    box-shadow:
        0 8px 20px rgba(39, 224, 163, 0.25);
}

.fav-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    z-index: 10;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    padding: 0;
    border-radius: 50%;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.fav-badge:hover {
    background: rgba(0, 0, 0, 0.7);
    transform: scale(1.1);
}

.fav-badge .text-danger {
    color: #ff647c !important;
}

/* =========================
   IMAGE
========================= */

.image-wrapper {

    height: 280px;

    padding: 1.2rem;

    display: flex;
    align-items: center;
    justify-content: center;

    overflow: hidden;
}

.product-image {

    width: 100%;
    height: 100%;

    object-fit: contain;

    transition:
        transform 0.4s ease;
}

.product-card:hover .product-image {

    transform:
        scale(1.05);
}

/* =========================
   BODY
========================= */

.card-body {

    padding:
        1.5rem;
}

/* SKU */

.sku-badge {

    align-self: center;

    background:
        rgba(255, 255, 255, 0.06);

    border:
        1px solid rgba(255, 255, 255, 0.08);

    color:
        rgba(255, 255, 255, 0.75);

    padding:
        0.4rem 0.9rem;

    border-radius: 999px;

    font-size: 0.75rem;

    margin-bottom: 1rem;
}

/* TITLE */

.product-title {

    color: white;

    font-size: 1.7rem;

    font-weight: 700;

    margin-bottom: 1rem;

    font-family:
        'Playfair Display',
        serif;
}

/* DESCRIPTION */

.product-description {

    color:
        rgba(255, 255, 255, 0.68);

    font-size: 0.95rem;

    line-height: 1.7;

    margin-bottom: 1.5rem;
}

/* PRICE */

.product-price {

    color: #27e0a3;

    font-size: 2rem;

    font-weight: 700;

    margin-bottom: 1.5rem;
}

/* =========================
   BUTTONS
========================= */

.details-btn {

    display: inline-block;

    width: 100%;

    border: none;

    background:
        linear-gradient(135deg,
            #27e0a3,
            #1fc98f);

    color: #07150f;

    text-decoration: none;

    font-weight: 700;

    padding: 1rem;

    border-radius: 14px;

    transition:
        all 0.3s ease;
}

.details-btn:hover {

    transform:
        translateY(-2px);

    box-shadow:
        0 12px 24px rgba(39, 224, 163, 0.25);

    color: #07150f;
}

/* CART BTN CARD */
.cart-btn {
    background: rgba(39, 224, 163, 0.1);
    border: 1px solid rgba(39, 224, 163, 0.3);
    color: #27e0a3;
    border-radius: 14px;
    width: 54px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.cart-btn:hover {
    background: #27e0a3;
    color: #07150f;
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(39, 224, 163, 0.2);
}

/* EDIT */

.edit-btn {

    border:
        1px solid rgba(255, 255, 255, 0.12);

    background:
        rgba(255, 255, 255, 0.03);

    color: white;

    padding: 0.75rem;

    border-radius: 12px;

    font-weight: 600;

    transition: all 0.3s ease;
}

.edit-btn:hover {

    background:
        rgba(255, 255, 255, 0.08);
}

/* DELETE */

.delete-btn {

    border:
        1px solid rgba(255, 100, 124, 0.35);

    background: transparent;

    color: #ff647c;

    padding: 0.75rem;

    border-radius: 12px;

    font-weight: 600;

    transition: all 0.3s ease;
}

.delete-btn:hover:not(:disabled) {

    background:
        rgba(255, 100, 124, 0.08);
}

.delete-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 768px) {

    .image-wrapper {

        height: 240px;
    }

    .product-title {

        font-size: 1.4rem;
    }

}
</style>