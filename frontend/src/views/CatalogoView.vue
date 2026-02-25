<script setup>
import { ref, onMounted, watch } from 'vue'
import http from '../services/http'
import CardProducte from '../components/CardProducte.vue'

const products = ref([])
const filteredProducts = ref([])
const loading = ref(true)

const searchQuery = ref('')
const selectedCategory = ref('') // Guardará 'PER', 'CRE' o 'MAQ'

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

// REQUISITO C3: Watcher arreglado usando el SKU
watch([searchQuery, selectedCategory], () => {
    filteredProducts.value = products.value.filter(p => {
        const matchesSearch = p.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (p.description && p.description.toLowerCase().includes(searchQuery.value.toLowerCase()))

        // Filtramos por el inicio del SKU (PER, CRE, MAQ)
        const matchesCategory = selectedCategory.value ? p.sku.startsWith(selectedCategory.value) : true

        return matchesSearch && matchesCategory
    })
})
</script>

<template>
    <section class="container py-4">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--color-primary);">
                Catálogo Completo</h1>
        </div>

        <div class="row mb-5 bg-light p-3 rounded shadow-sm mx-0">
            <div class="col-md-8 mb-2 mb-md-0">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">🔍</span>
                    <input type="text" v-model="searchQuery" class="form-control border-start-0 ps-0"
                        placeholder="Buscar producto...">
                </div>
            </div>
            <div class="col-md-4">
                <select v-model="selectedCategory" class="form-select border-0 shadow-sm"
                    aria-label="Filtrar por categoría">
                    <option value="">Todas las categorías</option>
                    <option value="PER">Perfumes</option>
                    <option value="CRE">Cosmética y Cremas</option>
                    <option value="MAQ">Maquillaje</option>
                </select>
            </div>
        </div>

        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border"></div>
        </div>

        <div v-else>
            <div v-if="filteredProducts.length > 0" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <div v-for="product in filteredProducts" :key="product.id" class="col">
                    <CardProducte :product="product" />
                </div>
            </div>
            <div v-else class="text-center py-5">
                <h4>No hemos encontrado nada. 😢</h4>
            </div>
        </div>
    </section>
</template>