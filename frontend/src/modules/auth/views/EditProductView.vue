<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
// Ajusta la ruta a tu cliente HTTP dependiendo de dónde esté
import http from '../../../services/http'; 

const route = useRoute();
const router = useRouter();

// Datos del producto a editar
const product = ref({ name: '', sku: '', description: '', price: '' });
const loading = ref(true);
const errorMessage = ref('');

// Al cargar la pantalla, pedimos los datos de ESE producto a tu backend
onMounted(async () => {
    try {
        const response = await http.get(`/products/${route.params.id}`);
        // Según cómo devuelva tu backend, puede ser response.data o response.data.data
        product.value = response.data.data || response.data;
    } catch (error) {
        console.error("Error cargando el producto:", error);
        errorMessage.value = "No se pudieron cargar los datos del producto.";
    } finally {
        loading.value = false;
    }
});

// Guardar los cambios (Petición PUT)
const saveProduct = async () => {
    errorMessage.value = '';
    try {
        await http.put(`/products/${route.params.id}`, product.value);
        // Si guarda correctamente, volvemos a la página del catálogo
        router.push('/catalogo');
    } catch (error) {
        console.error("Error guardando:", error);
        errorMessage.value = "Hubo un error al actualizar el producto.";
    }
};
</script>

<template>
  <div class="auth-wrapper d-flex align-items-center justify-content-center">
    <div class="auth-container" style="max-width: 650px; width: 100%;">
      
      <div class="auth-header text-center mb-4">
        <h2>Editar <span class="gold-text">Producto</span></h2>
        <p class="auth-subtitle">Modifica los detalles en el catálogo general</p>
      </div>

      <div v-if="errorMessage" class="alert alert-danger text-center py-2" role="alert">
        {{ errorMessage }}
      </div>

      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border" style="color: #27e0a3;"></div>
      </div>

      <form v-else @submit.prevent="saveProduct">
        
        <div class="mb-3 position-relative">
          <label class="form-label glamur-label">Nombre del Producto</label>
          <input v-model="product.name" type="text" class="form-control glamur-input" required />
        </div>
        
        <div class="mb-3 position-relative">
          <label class="form-label glamur-label">Código SKU</label>
          <input v-model="product.sku" type="text" class="form-control glamur-input" required />
        </div>

        <div class="mb-3 position-relative">
          <label class="form-label glamur-label">Precio (€)</label>
          <input v-model="product.price" type="number" step="0.01" class="form-control glamur-input" required />
        </div>

        <div class="mb-4 position-relative">
          <label class="form-label glamur-label">Descripción Detallada</label>
          <textarea v-model="product.description" class="form-control glamur-input" rows="4" required></textarea>
        </div>

        <div class="d-flex gap-3 mt-4">
          <button type="submit" class="btn-glamur-submit flex-fill">Guardar Cambios</button>
          <button type="button" @click="router.push('/catalogo')" class="btn-glamur-cancel flex-fill">Cancelar</button>
        </div>

      </form>
      
    </div>
  </div>
</template>

<style scoped>
/* ==========================================================================
   ESTILOS PREMIUM - GLAMUR CLUB ADMIN
   ========================================================================== */

.auth-wrapper {
  min-height: calc(100vh - 74px); /* Descuenta el Navbar */
  background: radial-gradient(circle at center, #0d291e 0%, #07120c 100%);
  padding: 3rem 1rem;
  font-family: 'Playfair Display', serif;
}

.auth-container {
  background: rgba(7, 21, 15, 0.75);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(212, 175, 55, 0.15);
  border-radius: 24px;
  padding: 2.5rem;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
}

h2 {
  font-size: 1.85rem;
  font-weight: 700;
  letter-spacing: 1px;
  color: #f5f1e8;
  margin-bottom: 0.5rem;
}

.gold-text {
  color: #d4af37;
}

.auth-subtitle {
  font-family: system-ui, -apple-system, sans-serif;
  color: #a3b8ab;
  font-size: 0.9rem;
}

.glamur-label {
  font-family: system-ui, -apple-system, sans-serif;
  color: #d7e3db;
  font-size: 0.88rem;
  font-weight: 500;
  margin-bottom: 0.5rem;
}

.glamur-input {
  font-family: system-ui, -apple-system, sans-serif;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 14px;
  color: #f5f1e8 !important;
  padding: 0.75rem 1rem;
  transition: all 0.3s ease;
}

.glamur-input:focus {
  background: rgba(255, 255, 255, 0.05);
  border-color: #27e0a3;
  box-shadow: 0 0 0 4px rgba(39, 224, 163, 0.15);
  outline: none;
}

.btn-glamur-submit {
  border: none;
  padding: 0.9rem;
  border-radius: 14px;
  background: linear-gradient(135deg, #27e0a3, #1fc98f);
  color: #07150f;
  font-weight: 600;
  font-family: system-ui, -apple-system, sans-serif;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.btn-glamur-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(39, 224, 163, 0.25);
}

.btn-glamur-cancel {
  border: 1px solid rgba(255, 100, 124, 0.35);
  padding: 0.9rem;
  border-radius: 14px;
  background: transparent;
  color: #ff647c;
  font-weight: 600;
  font-family: system-ui, -apple-system, sans-serif;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.btn-glamur-cancel:hover {
  background: rgba(255, 100, 124, 0.08);
}

.alert-danger {
  background-color: rgba(255, 111, 133, 0.1);
  border: 1px solid rgba(255, 111, 133, 0.2);
  color: #ff9dad;
  font-family: system-ui, -apple-system, sans-serif;
  font-size: 0.85rem;
  border-radius: 12px;
}
</style>