<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../modules/auth/store.js'

const email = ref('')
const password = ref('')
const errorMessage = ref('')
const loading = ref(false)

const authStore = useAuthStore()
const router = useRouter()

const handleLogin = async () => {
    loading.value = true
    errorMessage.value = ''

    try {
        await authStore.login(email.value, password.value)
        router.push('/') // Redirigimos al catálogo tras el login exitoso
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'Error de conexión'
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="card shadow-lg border-0" style="width: 100%; max-width: 450px; background: var(--bg-card);">
            <div class="card-body p-5">
                <h2 class="text-center mb-4 fw-bold"
                    style="font-family: 'Playfair Display', serif; color: var(--color-primary);">Acceso Socios</h2>

                <div v-if="errorMessage" class="alert alert-danger text-center small py-2">
                    {{ errorMessage }}
                </div>

                <form @submit.prevent="handleLogin">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Correo Electrónico</label>
                        <input type="email" v-model="email" class="form-control bg-light border-0"
                            placeholder="admin@glamur.com" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Contraseña</label>
                        <input type="password" v-model="password" class="form-control bg-light border-0"
                            placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn w-100 fw-bold shadow-sm"
                        style="background: var(--color-primary); color: #000;" :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                        {{ loading ? 'Iniciando sesión...' : 'Entrar' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>