<script setup>
import { ref, onMounted } from 'vue'
import http from '../services/http'
import CardProducte from '../components/CardProducte.vue'

const featuredProducts = ref([])
const loading = ref(true)

// Calculamos la URL de la imagen del Hero una sola vez
const heroImageUrl = `${(import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api').replace(/\/api$/, '')}/img/hero.jpg`;

onMounted(async () => {
    try {

        const response =
            await http.get('/products/featured')

        featuredProducts.value =
            response.data.data || response.data

    } catch (error) {

        console.error(
            'Error al cargar destacados:',
            error
        )

    } finally {

        loading.value = false
    }
})
</script>

<template>

    <div class="home-page">

        <!-- HERO -->
        <section class="hero-section">

            <img :src="heroImageUrl" alt="Hero" class="hero-image">

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <h1 class="hero-title">
                    Glamur Club
                </h1>

                <p class="hero-subtitle">
                    Exclusividad, elegancia y lujo a tu alcance.
                </p>

                <router-link to="/catalogo" class="hero-btn">
                    Descubrir Colección
                </router-link>

            </div>

        </section>

        <!-- FEATURED -->
        <div class="container py-4">

            <div class="text-center mb-5">

                <h2 class="section-title">
                    Joyas de la Corona
                </h2>

                <p class="section-subtitle">
                    Nuestra selección más exclusiva para ti
                </p>

            </div>

            <div v-if="loading" class="text-center py-5">

                <div class="spinner-border" style="color: #27e0a3;"></div>

            </div>

            <div v-else class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

                <div v-for="product in featuredProducts" :key="'feat-' + product.id" class="col">

                    <CardProducte :product="product" />

                </div>

            </div>

        </div>

    </div>

</template>

<style scoped>
/* =========================
   PAGE
========================= */

.home-page {

    background:
        linear-gradient(180deg,
            #101514 0%,
            #151b19 100%);

    min-height: 100vh;
}

/* =========================
   HERO
========================= */

.hero-section {

    position: relative;

    height: 78vh;

    overflow: hidden;

    border-radius: 28px;

    margin-top: -30px;

    margin-bottom: 5rem;

    box-shadow:
        0 20px 60px rgba(0, 0, 0, 0.35);
}

.hero-image {

    width: 100%;
    height: 100%;

    object-fit: cover;
}

/* Overlay */

.hero-overlay {

    position: absolute;

    inset: 0;

    background:
        linear-gradient(90deg,
            rgba(0, 0, 0, 0.65) 0%,
            rgba(0, 0, 0, 0.35) 40%,
            rgba(0, 0, 0, 0.65) 100%);
}

/* Content */

.hero-content {

    position: absolute;

    top: 50%;
    left: 50%;

    transform: translate(-50%, -50%);

    z-index: 2;

    width: 100%;

    text-align: center;

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;
}

.hero-title {

    font-size: 5rem;

    font-weight: 700;

    line-height: 1.05;

    margin-bottom: 1.5rem;

    color: #27e0a3;

    font-family:
        'Playfair Display',
        serif;

    text-shadow:
        0 10px 40px rgba(0, 0, 0, 0.5);
}

.hero-subtitle {

    font-size: 1.35rem;

    color: rgba(255, 255, 255, 0.92);

    margin-bottom: 2rem;

    line-height: 1.7;
}

/* Button */

.hero-btn {

    display: inline-block;

    background:
        linear-gradient(135deg,
            #27e0a3,
            #1fc98f);

    border: none;

    padding:
        1rem 2.7rem;

    border-radius: 999px;

    color: #07150f;

    text-decoration: none;

    font-weight: 700;

    font-size: 1rem;

    transition: all 0.3s ease;
}

.hero-btn:hover {

    transform:
        translateY(-3px);

    box-shadow:
        0 15px 35px rgba(39, 224, 163, 0.3);

    color: #07150f;
}

/* =========================
   TITLES
========================= */

.section-title {

    color: #27e0a3;

    font-size: 3rem;

    font-family:
        'Playfair Display',
        serif;

    font-weight: 700;

    margin-bottom: 1rem;
}

.section-subtitle {

    color: rgba(255, 255, 255, 0.72);

    font-size: 1.05rem;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 768px) {

    .hero-section {

        height: 65vh;
    }

    .hero-content {

        left: 5%;

        right: 5%;
    }

    .hero-title {

        font-size: 3rem;
    }

    .hero-subtitle {

        font-size: 1rem;
    }

}
</style>