<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../modules/auth/store'
import http from '../services/http'

const authStore = useAuthStore()
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
})

onMounted(() => {
    // Rellenamos el formulario con los datos guardados en la sesión actual
    if (authStore.user) {
        form.value.name = authStore.user.name
        form.value.email = authStore.user.email
    }
})

const updateProfile = async () => {
    loading.value = true
    successMessage.value = ''
    errorMessage.value = ''

    try {
        await http.put('/user', {
            name: form.value.name,
            password: form.value.password,
            password_confirmation: form.value.password_confirmation
        })

        successMessage.value = 'Perfil actualizado con éxito.'
        
        // Actualizamos el estado visual de Vue y el navegador para que el cambio de nombre se note al instante
        authStore.user.name = form.value.name
        localStorage.setItem('user', JSON.stringify(authStore.user))

        // Vaciamos los campos de contraseñas
        form.value.password = ''
        form.value.password_confirmation = ''

    } catch (error) {
        if (error.response?.status === 422) {
            errorMessage.value = 'Revisa que las contraseñas coincidan y tengan mínimo 8 caracteres.'
        } else {
            errorMessage.value = error.response?.data?.message || 'Error al actualizar el perfil.'
        }
    } finally {
        loading.value = false
    }
}
</script>

<template>
  <div class="profile-wrapper d-flex align-items-center justify-content-center">
    <div class="profile-container w-100 mx-3">
      
      <div class="text-center mb-4">
        <div class="profile-icon mx-auto mb-3">👤</div>
        <h2>Mi <span class="gold-text">Perfil</span></h2>
        <p class="subtitle">Gestiona tus credenciales de Glamur Club</p>
      </div>

      <div v-if="successMessage" class="alert alert-success text-center py-2">{{ successMessage }}</div>
      <div v-if="errorMessage" class="alert alert-danger text-center py-2">{{ errorMessage }}</div>

      <form @submit.prevent="updateProfile">
        
        <div class="mb-3 position-relative">
          <label class="form-label glamur-label">Correo Electrónico</label>
          <input type="email" v-model="form.email" class="form-control glamur-input disabled-input" disabled />
          <small class="text-muted d-block mt-1">Tu email de acceso es permanente y no se puede alterar.</small>
        </div>

        <div class="mb-4 position-relative">
          <label class="form-label glamur-label">Nombre de Usuario</label>
          <input type="text" v-model="form.name" class="form-control glamur-input" required />
        </div>

        <div class="password-section p-3 mb-4">
          <h5 class="mb-2 text-white" style="font-size: 1rem;">Cambiar Contraseña</h5>
          <p class="text-muted small mb-3">Deja estos campos en blanco si deseas mantener tu clave actual.</p>

          <div class="mb-3">
            <input type="password" v-model="form.password" class="form-control glamur-input" placeholder="Nueva Contraseña (mín 8)" />
          </div>
          <div>
            <input type="password" v-model="form.password_confirmation" class="form-control glamur-input" placeholder="Repite la nueva contraseña" />
          </div>
        </div>

        <button type="submit" class="btn-glamur-submit w-100" :disabled="loading">
          <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
          {{ loading ? 'Actualizando...' : 'Guardar Cambios' }}
        </button>

      </form>
      
    </div>
  </div>
</template>

<style scoped>
.profile-wrapper {
  min-height: calc(100vh - 74px);
  background: radial-gradient(circle at center, #0d291e 0%, #07120c 100%);
  font-family: 'Playfair Display', serif;
}

.profile-container {
  max-width: 500px;
  background: rgba(7, 21, 15, 0.75);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(212, 175, 55, 0.15);
  border-radius: 24px;
  padding: 2.5rem;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
}

.profile-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #d4af37, #f3dc87);
  font-size: 1.5rem;
  box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2);
}

h2 { font-size: 2rem; font-weight: 700; color: #f5f1e8; }
.gold-text { color: #d4af37; }
.subtitle { font-family: system-ui, -apple-system, sans-serif; color: #a3b8ab; font-size: 0.95rem; }
.glamur-label { font-family: system-ui, -apple-system, sans-serif; color: #d7e3db; font-size: 0.88rem; font-weight: 500; }
.text-muted { color: #8e9e95 !important; font-family: system-ui, -apple-system, sans-serif;}

.glamur-input {
  font-family: system-ui, -apple-system, sans-serif;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: #f5f1e8 !important;
  border-radius: 14px;
  padding: 0.75rem 1rem;
  transition: all 0.3s ease;
}

.glamur-input:focus {
  background: rgba(255, 255, 255, 0.05);
  border-color: #27e0a3;
  box-shadow: 0 0 0 4px rgba(39, 224, 163, 0.15);
  outline: none;
}

.disabled-input {
  background-color: rgba(255, 255, 255, 0.02) !important;
  color: #8e9e95 !important;
  border: 1px dashed rgba(255, 255, 255, 0.1) !important;
  cursor: not-allowed;
  opacity: 1 !important;
  -webkit-text-fill-color: #8e9e95 !important; /* Para forzar el color en Safari/Chrome */
}

.password-section {
  background: rgba(255, 255, 255, 0.015);
  border: 1px dashed rgba(255, 255, 255, 0.08);
  border-radius: 16px;
}

.btn-glamur-submit {
  border: none;
  padding: 0.85rem;
  border-radius: 14px;
  background: linear-gradient(135deg, #27e0a3, #1fc98f);
  color: #07150f;
  font-weight: 600;
  font-family: system-ui, -apple-system, sans-serif;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.btn-glamur-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(39, 224, 163, 0.25);
}

.alert-success { background-color: rgba(39, 224, 163, 0.1); border: 1px solid rgba(39, 224, 163, 0.3); color: #27e0a3; border-radius: 12px; font-family: system-ui, -apple-system, sans-serif; font-size: 0.9rem;}
.alert-danger { background-color: rgba(255, 111, 133, 0.1); border: 1px solid rgba(255, 111, 133, 0.2); color: #ff9dad; border-radius: 12px; font-family: system-ui, -apple-system, sans-serif; font-size: 0.9rem;}
</style>