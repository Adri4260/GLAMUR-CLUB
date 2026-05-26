<script setup>
import { ref, onMounted, watch } from 'vue'
import http from '../services/http'
import CardProducte from '../components/CardProducte.vue'

const products = ref([])
const filteredProducts = ref([])
const loading = ref(true)

const searchQuery = ref('')
const selectedCategory = ref('')

onMounted(async () => {
    try {
        const response = await http.get('/products')

        products.value = response.data.data || response.data

        filteredProducts.value = products.value

    } catch (error) {

        console.error("Error al cargar:", error)

    } finally {

        loading.value = false
    }
})

watch([searchQuery, selectedCategory], () => {

    filteredProducts.value = products.value.filter(p => {

        const matchesSearch =
            p.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||

            (
                p.description &&
                p.description.toLowerCase().includes(searchQuery.value.toLowerCase())
            )

        const matchesCategory =
            selectedCategory.value
                ? p.sku.startsWith(selectedCategory.value)
                : true

        return matchesSearch && matchesCategory
    })

})
</script>

<template>

    <section class="catalog-page container py-4">

        <div class="text-center mb-5">

            <h1
                class="display-4 fw-bold catalog-title"
            >
                Catálogo Completo
            </h1>

        </div>

        <!-- SEARCH BAR -->

        <div class="search-container row mb-5 mx-0">

            <div class="col-md-8 mb-3 mb-md-0">

                <div class="custom-search">

                    <span class="search-icon">
                        🔍
                    </span>

                    <input
                        type="text"
                        v-model="searchQuery"
                        class="search-input"
                        placeholder="Buscar producto..."
                    >

                </div>

            </div>

            <div class="col-md-4">

                <select
                    v-model="selectedCategory"
                    class="custom-select"
                    aria-label="Filtrar por categoría"
                >
                    <option value="">
                        Todas las categorías
                    </option>

                    <option value="PER">
                        Perfumes
                    </option>

                    <option value="CRE">
                        Cosmética y Cremas
                    </option>

                    <option value="MAQ">
                        Maquillaje
                    </option>

                </select>

            </div>

        </div>

        <!-- LOADING -->

        <div
            v-if="loading"
            class="text-center py-5"
        >

            <div
                class="spinner-border"
                style="color: #27e0a3;"
            ></div>

        </div>

        <!-- PRODUCTS -->

        <div v-else>

            <div
                v-if="filteredProducts.length > 0"
                class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4"
            >

                <div
                    v-for="product in filteredProducts"
                    :key="product.id"
                    class="col"
                >

                    <CardProducte :product="product" />

                </div>

            </div>

            <div
                v-else
                class="text-center py-5"
            >

                <h4 class="text-white">
                    No hemos encontrado nada 😢
                </h4>

            </div>

        </div>

    </section>

</template>

<style scoped>

/* =========================
   PAGE
========================= */

.catalog-page {

    min-height: 100vh;
}

/* =========================
   TITLE
========================= */

.catalog-title {

    font-family:
        'Playfair Display',
        serif;

    color: #27e0a3;
}

/* =========================
   SEARCH CONTAINER
========================= */

.search-container {

    background:
        rgba(10, 15, 14, 0.95);

    border:
        1px solid rgba(39,224,163,0.12);

    border-radius: 20px;

    padding: 1.5rem;

    box-shadow:
        0 10px 35px rgba(0,0,0,0.28);
}

/* =========================
   SEARCH
========================= */

.custom-search {

    display: flex;

    align-items: center;

    background:
        #0b1110;

    border:
        1px solid rgba(39,224,163,0.25);

    border-radius: 14px;

    overflow: hidden;

    transition:
        all 0.25s ease;
}

.custom-search:focus-within {

    border-color:
        #27e0a3;

    box-shadow:
        0 0 0 3px rgba(39,224,163,0.12);
}

.search-icon {

    padding:
        0 1rem;

    color:
        #27e0a3;

    font-size:
        1rem;
}

.search-input {

    width: 100%;

    background:
        transparent;

    border:
        none;

    outline:
        none;

    color:
        white;

    padding:
        1rem 1rem 1rem 0;

    font-size:
        1rem;
}

.search-input::placeholder {

    color:
        rgba(255,255,255,0.45);
}

/* =========================
   SELECT
========================= */

.custom-select {

    width: 100%;

    background:
        #0b1110;

    color:
        white;

    border:
        1px solid rgba(39,224,163,0.25);

    border-radius: 14px;

    padding:
        1rem;

    outline:
        none;

    transition:
        all 0.25s ease;
}

.custom-select:focus {

    border-color:
        #27e0a3;

    box-shadow:
        0 0 0 3px rgba(39,224,163,0.12);
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 768px) {

    .search-container {

        padding: 1rem;
    }

}

</style>