<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../store'
import http from '../../../services/http'
// Importamos Vee-Validate y Yup para el Requisito C3
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'

const authStore = useAuthStore()
const router = useRouter()
const route = useRoute()
const errorMessage = ref('')
const loading = ref(false)

// Esquema de validación estricto (Yup)
const schema = yup.object({
    email: yup.string().email('Debe ser un email válido').required('El email es obligatorio'),
    password: yup.string().min(6, 'La contraseña debe tener al menos 6 caracteres').required('La contraseña es obligatoria')
})

// Checkeamos si venimos rebotados de Google con un token en la URL
onMounted(() => {
    if (route.query.token) {
        localStorage.setItem('token', route.query.token)
        authStore.token = route.query.token
        authStore.fetchUser().then(() => router.push('/'))
    } else if (route.query.error) {
        errorMessage.value = 'Error al iniciar sesión con Google.'
    }
})

// Función de login normal
const handleLogin = async (values) => {
    loading.value = true
    errorMessage.value = ''
    try {
        await authStore.login(values.email, values.password)
        router.push('/')
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'Credenciales incorrectas'
    } finally {
        loading.value = false
    }
}

// Función para Login con Google (OAuth2 - Requisito C1)
const loginWithGoogle = async () => {
    try {
        const response = await http.get('/oauth/google/redirect')
        window.location.href = response.data.url // Redirigimos a la pantalla de Google
    } catch (error) {
        errorMessage.value = 'El servicio de Google no está disponible ahora mismo.'
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

                <Form @submit="handleLogin" :validation-schema="schema" v-slot="{ errors }">

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Correo Electrónico</label>
                        <Field name="email" type="email" class="form-control bg-light border-0"
                            :class="{ 'is-invalid': errors.email }" placeholder="ejemplo@glamur.com" />
                        <ErrorMessage name="email" class="text-danger small mt-1 d-block" />
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Contraseña</label>
                        <Field name="password" type="password" class="form-control bg-light border-0"
                            :class="{ 'is-invalid': errors.password }" placeholder="••••••••" />
                        <ErrorMessage name="password" class="text-danger small mt-1 d-block" />
                    </div>

                    <button type="submit" class="btn w-100 fw-bold shadow-sm mb-3"
                        style="background: var(--color-primary); color: #000;" :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                        {{ loading ? 'Entrando...' : 'Iniciar Sesión' }}
                    </button>

                    <div class="text-center mb-3 text-muted small">O accede con</div>

                    <button type="button" @click="loginWithGoogle"
                        class="btn btn-outline-dark w-100 fw-bold d-flex align-items-center justify-content-center gap-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg"
                            alt="Google" style="width: 18px;">
                        Continuar con Google
                    </button>

                </Form>
            </div>
        </div>
    </div>
</template>