<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import http from '../services/http';

const router = useRouter();

// Datos del formulario
const form = ref({
    name: '',
    sku: '',
    category: '',
    price: 0,
    stock: 0,
    description: '',
    image: 'img/prod1.jpg' // Imagen por defecto mientras no tengamos subida de archivos
});

const isSubmitting = ref(false);
const errorMessage = ref('');

const submitProduct = async () => {
    isSubmitting.value = true;
    errorMessage.value = '';

    try {
        await http.post('/products', form.value);
        // Si sale bien, volvemos al catálogo a ver nuestra creación
        router.push('/catalogo');
    } catch (error) {
        console.error("Error al crear:", error);
        errorMessage.value = error.response?.data?.message || 'Hubo un error al crear el producto. Revisa los datos.';
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
  <div class="glamur-page py-5">
    <div class="container py-4">
      <div class="d-flex align-items-center mb-5">
        <router-link to="/admin" class="btn-back me-4">
            <i class="bi bi-arrow-left"></i> Volver
        </router-link>
        <h1 class="font-playfair text-white mb-0">Crear <span class="text-gold">Nuevo Producto</span></h1>
      </div>

      <div class="form-container mx-auto" style="max-width: 800px;">
        <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

        <form @submit.prevent="submitProduct">
          <div class="row">
              <div class="col-md-8 mb-4">
                <label class="form-label text-light">Nombre del Producto</label>
                <input type="text" v-model="form.name" class="form-control glamur-input" required placeholder="Ej. Creed Aventus">
              </div>
              <div class="col-md-4 mb-4">
                <label class="form-label text-light">Código SKU</label>
                <input type="text" v-model="form.sku" class="form-control glamur-input" placeholder="Ej. PER-001">
              </div>
          </div>

          <div class="row">
              <div class="col-md-4 mb-4">
                <label class="form-label text-light">Precio (€)</label>
                <input type="number" step="0.01" v-model="form.price" class="form-control glamur-input" required min="0">
              </div>
              <div class="col-md-4 mb-4">
                <label class="form-label text-light">Stock Inicial</label>
                <input type="number" v-model="form.stock" class="form-control glamur-input" required min="0">
              </div>
              <div class="col-md-4 mb-4">
                <label class="form-label text-light">Categoría (Opcional)</label>
                <input type="text" v-model="form.category" class="form-control glamur-input" placeholder="Perfumería">
              </div>
          </div>

          <div class="mb-5">
            <label class="form-label text-light">Descripción Detallada</label>
            <textarea v-model="form.description" class="form-control glamur-input" rows="5" placeholder="Escribe aquí las notas olfativas y características..."></textarea>
          </div>

          <button type="submit" class="btn-glamur-submit w-100 py-3" :disabled="isSubmitting">
            <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
            {{ isSubmitting ? 'Guardando en la base de datos...' : 'Publicar Producto en el Catálogo' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.glamur-page { min-height: calc(100vh - 74px); background: #07120c; }
.font-playfair { font-family: 'Playfair Display', serif; font-weight: 700; }
.text-gold { color: #d4af37; }

.btn-back {
  background: rgba(255,255,255,0.05); color: #d8e3dc; text-decoration: none;
  padding: 0.5rem 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);
  transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;
}
.btn-back:hover { background: rgba(39, 224, 163, 0.1); color: #27e0a3; border-color: rgba(39, 224, 163, 0.3); }

.form-container {
  background: rgba(255, 255, 255, 0.02); backdrop-filter: blur(20px);
  border: 1px solid rgba(212, 175, 55, 0.15); border-radius: 24px; padding: 3rem;
}

.glamur-input {
  background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1);
  color: #f5f1e8 !important; border-radius: 12px; padding: 0.8rem 1rem;
}
.glamur-input:focus { background: rgba(0, 0, 0, 0.4); border-color: #27e0a3; box-shadow: 0 0 0 3px rgba(39, 224, 163, 0.15); outline: none; }
.glamur-input::placeholder { color: rgba(255, 255, 255, 0.3) !important; }

.btn-glamur-submit {
  border: none; border-radius: 14px;
  background: linear-gradient(135deg, #27e0a3, #1fc98f); color: #07150f; font-weight: 700; transition: all 0.3s ease; font-size: 1.1rem;
}
.btn-glamur-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(39, 224, 163, 0.25); }
.btn-glamur-submit:disabled { opacity: 0.7; cursor: not-allowed; }
</style>