<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '../modules/auth/store'
import http from '../services/http'
import RoleGuard from '../modules/roles/components/RoleGuard.vue'

const route = useRoute()
const authStore = useAuthStore()

const product = ref(null)
const loading = ref(true)

// Formulario del nuevo comentario
const newReview = ref({ rating: 5, comment: '' })
const submitting = ref(false)

// Cargar el producto al entrar
onMounted(async () => {
    try {
        const response = await http.get(`/products/${route.params.id}`)
        product.value = response.data
    } catch (error) {
        console.error("Error al cargar detalle:", error)
    } finally {
        loading.value = false
    }
})

// --- FIX PARA IMÁGENES EN PRODUCCIÓN Y LOCAL ---
const getImageUrl = (path) => {
    const apiBase = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api';
    const serverUrl = apiBase.replace(/\/api$/, '');

    if (!path) return `${serverUrl}/img/prod1.jpg`
    if (path.startsWith('http')) return path

    let cleanPath = path.replace('/public/', '').replace('public/', '')
    if (!cleanPath.startsWith('img/')) cleanPath = 'img/' + cleanPath

    return `${serverUrl}/${cleanPath}`
}

// Enviar nuevo comentario a Laravel
const submitReview = async () => {
    if (!newReview.value.comment) return

    submitting.value = true

    try {

        const response = await http.post(
            `/products/${product.value.id}/reviews`,
            newReview.value
        )

        product.value.reviews.push(response.data)

        newReview.value.comment = ''

    } catch (error) {

        alert("Error al enviar comentario")

    } finally {

        submitting.value = false
    }
}

// Borrar comentario
const deleteReview = async (reviewId, index) => {

    if (!confirm("¿Seguro que quieres borrar este comentario?")) return

    try {

        await http.delete(`/reviews/${reviewId}`)

        product.value.reviews.splice(index, 1)

    } catch (error) {

        alert("Error al borrar comentario")
    }
}
</script>

<template>

    <div class="product-detail-page">

        <div class="container py-5">

            <!-- LOADING -->
            <div v-if="loading" class="text-center py-5">

                <div class="spinner-border" style="color: #27e0a3;"></div>

            </div>

            <!-- PRODUCT -->
            <div v-else-if="product">

                <div class="row mb-5 align-items-center">

                    <!-- IMAGE -->
                    <div class="col-md-6 mb-4 mb-md-0">

                        <div class="product-image-wrapper">

                            <img :src="getImageUrl(product.image)" class="product-image" :alt="product.name">

                        </div>

                    </div>

                    <!-- INFO -->
                    <div class="col-md-6">

                        <span class="product-category">
                            {{ product.category }}
                        </span>

                        <h1 class="product-title">
                            {{ product.name }}
                        </h1>

                        <p class="product-price">
                            {{ product.price }} €
                        </p>

                        <p class="product-description">
                            {{ product.description }}
                        </p>

                        <p class="product-stock">
                            Stock disponible:
                            {{ product.stock }} unidades
                        </p>

                        <button class="add-cart-btn">
                            Añadir al Carrito
                        </button>

                    </div>

                </div>

                <!-- DIVIDER -->
                <div class="divider"></div>

                <!-- REVIEWS -->
                <div class="row">

                    <div class="col-md-8 mx-auto">

                        <h3 class="reviews-title">
                            Valoraciones de los Socios
                        </h3>

                        <!-- FORM -->
                        <div v-if="authStore.isAuthenticated" class="review-form-card">

                            <h5 class="mb-3">
                                Escribe tu opinión
                            </h5>

                            <form @submit.prevent="submitReview">

                                <div class="mb-3">

                                    <select v-model="newReview.rating" class="custom-select">

                                        <option value="5">
                                            ⭐⭐⭐⭐⭐ (5/5) - Excelente
                                        </option>

                                        <option value="4">
                                            ⭐⭐⭐⭐ (4/5) - Muy Bueno
                                        </option>

                                        <option value="3">
                                            ⭐⭐⭐ (3/5) - Bueno
                                        </option>

                                        <option value="2">
                                            ⭐⭐ (2/5) - Regular
                                        </option>

                                        <option value="1">
                                            ⭐ (1/5) - Malo
                                        </option>

                                    </select>

                                </div>

                                <div class="mb-3">

                                    <textarea v-model="newReview.comment" class="custom-textarea" rows="4"
                                        placeholder="¿Qué te ha parecido este producto?" required></textarea>

                                </div>

                                <button type="submit" class="publish-btn" :disabled="submitting">

                                    {{
                                        submitting
                                            ? 'Enviando...'
                                            : 'Publicar Valoración'
                                    }}

                                </button>

                            </form>

                        </div>

                        <!-- LOGIN -->
                        <div v-else class="login-alert">

                            Debes

                            <router-link to="/login" class="login-link">
                                iniciar sesión
                            </router-link>

                            para dejar un comentario.

                        </div>

                        <!-- REVIEWS LIST -->
                        <div v-if="product.reviews && product.reviews.length > 0">

                            <div v-for="(review, index) in product.reviews" :key="review.id" class="review-card">

                                <div class="d-flex justify-content-between align-items-center mb-2">

                                    <h6 class="review-user">
                                        {{
                                            review.user?.name || 'Usuario'
                                        }}
                                    </h6>

                                    <span class="review-stars">
                                        {{ '⭐'.repeat(review.rating) }}
                                    </span>

                                </div>

                                <p class="review-comment">
                                    {{ review.comment }}
                                </p>

                                <RoleGuard requirePermission="moderate">

                                    <div class="text-end mt-3">

                                        <button @click="deleteReview(review.id, index)"
                                            class="btn btn-sm btn-outline-danger">
                                            🗑️ Borrar
                                        </button>

                                    </div>

                                </RoleGuard>

                            </div>

                        </div>

                        <p v-else class="empty-review-text">

                            Sé el primero en valorar esta joya.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</template>

<style scoped>
/* =========================
   PAGE
========================= */

.product-detail-page {

    background:
        linear-gradient(180deg,
            #101514 0%,
            #151b19 100%);

    min-height: 100vh;

    color: white;
}

/* =========================
   IMAGE
========================= */

.product-image-wrapper {

    background:
        linear-gradient(180deg,
            #1a2220 0%,
            #202927 100%);

    border:
        1px solid rgba(255, 255, 255, 0.05);

    border-radius: 24px;

    padding: 2rem;

    box-shadow:
        0 15px 40px rgba(0, 0, 0, 0.25);
}

.product-image {

    width: 100%;

    max-height: 520px;

    object-fit: contain;
}

/* =========================
   INFO
========================= */

.product-category {

    color: rgba(255, 255, 255, 0.55);

    text-transform: uppercase;

    letter-spacing: 2px;

    font-size: 0.8rem;
}

.product-title {

    font-size: 4rem;

    font-weight: 700;

    color: #27e0a3;

    font-family:
        'Playfair Display',
        serif;

    margin:
        1rem 0;
}

.product-price {

    font-size: 2rem;

    font-weight: 700;

    color: white;

    margin-bottom: 2rem;
}

.product-description {

    color: rgba(255, 255, 255, 0.82);

    line-height: 1.9;

    font-size: 1.05rem;
}

.product-stock {

    color: rgba(255, 255, 255, 0.6);

    margin-top: 1.5rem;
}

/* =========================
   BUTTON
========================= */

.add-cart-btn {

    margin-top: 2rem;

    width: 100%;

    border: none;

    background:
        linear-gradient(135deg,
            #27e0a3,
            #1fc98f);

    color: #07150f;

    font-weight: 700;

    padding:
        1rem 2rem;

    border-radius: 16px;

    transition: all 0.3s ease;
}

.add-cart-btn:hover {

    transform:
        translateY(-3px);

    box-shadow:
        0 15px 35px rgba(39, 224, 163, 0.25);
}

/* =========================
   DIVIDER
========================= */

.divider {

    height: 1px;

    background:
        linear-gradient(90deg,
            transparent,
            rgba(255, 255, 255, 0.12),
            transparent);

    margin:
        4rem 0;
}

/* =========================
   REVIEWS
========================= */

.reviews-title {

    font-size: 2.2rem;

    color: white;

    margin-bottom: 2rem;

    font-family:
        'Playfair Display',
        serif;
}

.review-form-card,
.review-card {

    background:
        linear-gradient(180deg,
            #1a2220 0%,
            #202927 100%);

    border:
        1px solid rgba(255, 255, 255, 0.06);

    border-radius: 20px;

    padding: 2rem;

    margin-bottom: 1.5rem;

    box-shadow:
        0 10px 30px rgba(0, 0, 0, 0.18);
}

/* =========================
   INPUTS
========================= */

.custom-select,
.custom-textarea {

    width: 100%;

    border: none;

    outline: none;

    background: #0f1413;

    color: white;

    border-radius: 14px;

    padding: 1rem;
}

.custom-textarea {

    resize: none;
}

/* =========================
   PUBLISH BTN
========================= */

.publish-btn {

    border: none;

    background:
        linear-gradient(135deg,
            #27e0a3,
            #1fc98f);

    color: #07150f;

    font-weight: 700;

    padding:
        0.9rem 1.8rem;

    border-radius: 14px;

    transition: all 0.3s ease;
}

.publish-btn:hover {

    transform:
        translateY(-2px);

    box-shadow:
        0 12px 24px rgba(39, 224, 163, 0.25);
}

/* =========================
   REVIEWS
========================= */

.review-user {

    color: white;

    font-weight: 700;

    margin: 0;
}

.review-stars {

    font-size: 1rem;
}

.review-comment {

    color: rgba(255, 255, 255, 0.78);

    margin: 0;
}

/* =========================
   LOGIN ALERT
========================= */

.login-alert {

    background:
        rgba(255, 255, 255, 0.05);

    border:
        1px solid rgba(255, 255, 255, 0.08);

    padding: 1.5rem;

    border-radius: 16px;

    text-align: center;

    color: rgba(255, 255, 255, 0.8);

    margin-bottom: 2rem;
}

.login-link {

    color: #27e0a3;

    text-decoration: none;

    font-weight: 700;
}

/* =========================
   EMPTY
========================= */

.empty-review-text {

    color: rgba(255, 255, 255, 0.55);

    text-align: center;

    margin-top: 2rem;
}

/* =========================
   MOBILE
========================= */

@media (max-width: 768px) {

    .product-title {

        font-size: 2.8rem;
    }

}
</style>