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

// Función para arreglar la imagen
const getImageUrl = (path) => {
    if (!path) return 'http://localhost/img/prod1.jpg'
    if (path.startsWith('http')) return path
    let cleanPath = path.replace('/public/', '').replace('public/', '')
    if (!cleanPath.startsWith('img/')) cleanPath = 'img/' + cleanPath
    return `http://localhost/${cleanPath}`
}

// Enviar nuevo comentario a Laravel
const submitReview = async () => {
    if (!newReview.value.comment) return
    submitting.value = true
    try {
        const response = await http.post(`/products/${product.value.id}/reviews`, newReview.value)
        // Añadimos el comentario nuevo a la lista visualmente sin recargar la página (¡Magia SPA!)
        product.value.reviews.push(response.data)
        newReview.value.comment = '' // Limpiamos el texto
    } catch (error) {
        alert("Error al enviar comentario")
    } finally {
        submitting.value = false
    }
}

// Borrar comentario (Solo Admins/Editores)
const deleteReview = async (reviewId, index) => {
    if (!confirm("¿Seguro que quieres borrar este comentario?")) return
    try {
        await http.delete(`/reviews/${reviewId}`)
        // Lo quitamos de la lista visualmente
        product.value.reviews.splice(index, 1)
    } catch (error) {
        alert("Error al borrar comentario")
    }
}
</script>

<template>
    <div class="container py-5">
        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border" style="color: var(--color-primary);"></div>
        </div>

        <div v-else-if="product">
            <div class="row mb-5">
                <div class="col-md-6">
                    <img :src="getImageUrl(product.image)" class="img-fluid rounded shadow-lg w-100"
                        style="object-fit: cover; max-height: 500px;" :alt="product.name">
                </div>
                <div class="col-md-6 d-flex flex-column justify-content-center pt-4 pt-md-0">
                    <span class="text-muted text-uppercase tracking-wide">{{ product.category }}</span>
                    <h1 class="display-4 fw-bold"
                        style="font-family: 'Playfair Display', serif; color: var(--color-primary);">{{ product.name }}
                    </h1>
                    <p class="fs-4 fw-bold mb-4">{{ product.price }} €</p>
                    <p class="lead text-muted">{{ product.description }}</p>
                    <p class="small text-secondary">Stock disponible: {{ product.stock }} unidades</p>
                    <button class="btn btn-lg w-100 mt-4 shadow"
                        style="background: var(--color-primary); color: #000; font-weight: bold;">
                        Añadir al Carrito
                    </button>
                </div>
            </div>

            <hr class="my-5 border-secondary">

            <div class="row">
                <div class="col-md-8 mx-auto">
                    <h3 class="mb-4" style="font-family: 'Playfair Display', serif;">Valoraciones de los Socios</h3>

                    <div v-if="authStore.isAuthenticated" class="card border-0 shadow-sm mb-5"
                        style="background: var(--bg-card);">
                        <div class="card-body p-4">
                            <h5 class="mb-3">Escribe tu opinión</h5>
                            <form @submit.prevent="submitReview">
                                <div class="mb-3">
                                    <select v-model="newReview.rating" class="form-select bg-light border-0">
                                        <option value="5">⭐⭐⭐⭐⭐ (5/5) - Excelente</option>
                                        <option value="4">⭐⭐⭐⭐ (4/5) - Muy Bueno</option>
                                        <option value="3">⭐⭐⭐ (3/5) - Bueno</option>
                                        <option value="2">⭐⭐ (2/5) - Regular</option>
                                        <option value="1">⭐ (1/5) - Malo</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <textarea v-model="newReview.comment" class="form-control bg-light border-0"
                                        rows="3" placeholder="¿Qué te ha parecido este producto?" required></textarea>
                                </div>
                                <button type="submit" class="btn text-dark fw-bold px-4"
                                    style="background: var(--color-primary);" :disabled="submitting">
                                    {{ submitting ? 'Enviando...' : 'Publicar Valoración' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div v-else class="alert alert-secondary text-center mb-5">
                        Debes <router-link to="/login" class="fw-bold" style="color: var(--color-primary);">iniciar
                            sesión</router-link> para dejar un comentario.
                    </div>

                    <div v-if="product.reviews && product.reviews.length > 0">
                        <div v-for="(review, index) in product.reviews" :key="review.id"
                            class="card mb-3 border-0 shadow-sm" style="background: var(--bg-card);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0 text-capitalize">{{ review.user?.name || 'Usuario' }}</h6>
                                    <span class="text-warning">{'⭐'.repeat(review.rating)}</span>
                                </div>
                                <p class="text-muted mb-0">{{ review.comment }}</p>

                                <RoleGuard requirePermission="moderate">
                                    <div class="text-end mt-2">
                                        <button @click="deleteReview(review.id, index)"
                                            class="btn btn-sm btn-outline-danger" title="Moderar comentario">
                                            🗑️ Borrar
                                        </button>
                                    </div>
                                </RoleGuard>

                            </div>
                        </div>
                    </div>
                    <p v-else class="text-muted text-center italic">Sé el primero en valorar esta joya.</p>
                </div>
            </div>

        </div>
    </div>
</template>