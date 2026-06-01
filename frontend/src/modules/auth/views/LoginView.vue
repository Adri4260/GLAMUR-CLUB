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
  <div class="auth-wrapper d-flex align-items-center justify-content-center">
    <div class="auth-container">
      
      <!-- CABECERA DEL LOGIN -->
      <div class="auth-header text-center mb-4">
        <!-- AQUÍ SE SUSTITUYE EL CÍRCULO POR TU LOGO MUCHO MÁS GRANDE -->
        <img src="/img/logo.png" alt="Glamur Club Logo" class="auth-brand-icon-img mx-auto mb-3">
        
        <h2>GLAMUR <span class="gold-text">CLUB</span></h2>
        <p class="auth-subtitle">Inicia sesión para acceder a tu experiencia exclusiva</p>
      </div>

      <!-- MENSAGE DE ERROR GLOBAL -->
      <div v-if="errorMessage" class="alert alert-danger text-center py-2" role="alert">
        {{ errorMessage }}
      </div>

      <!-- FORMULARIO CON VEE-VALIDATE -->
      <Form @submit="handleLogin" :validation-schema="schema" v-slot="{ errors }">
        
        <!-- CAMPO: EMAIL -->
        <div class="mb-3 position-relative">
          <label for="email" class="form-label glamur-label">Correo Electrónico</label>
          <Field 
            name="email" 
            type="email" 
            id="email"
            class="form-control glamur-input" 
            :class="{ 'is-invalid': errors.email }"
            placeholder="ejemplo@glamur.club"
          />
          <ErrorMessage name="email" class="invalid-feedback glamur-feedback" />
        </div>

        <!-- CAMPO: PASSWORD -->
        <div class="mb-4 position-relative">
          <label for="password" class="form-label glamur-label">Contraseña</label>
          <Field 
            name="password" 
            type="password" 
            id="password"
            class="form-control glamur-input" 
            :class="{ 'is-invalid': errors.password }"
            placeholder="••••••••"
          />
          <ErrorMessage name="password" class="invalid-feedback glamur-feedback" />
        </div>

        <!-- BOTÓN ENTRAR -->
        <button type="submit" class="btn-glamur-submit w-100 mb-3" :disabled="loading">
          <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
          {{ loading ? 'Autenticando...' : 'Iniciar Sesión' }}
        </button>

        <!-- BOTÓN OAUTH GOOGLE (Sprint 5 - Requisito C1) -->
        <div class="separator d-flex align-items-center my-3">
          <span>O BIEN</span>
        </div>

        <button type="button" @click="loginWithGoogle" class="btn-google-oauth w-100 d-flex align-items-center justify-content-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
          </svg>
          Continuar con Google
        </button>

      </Form>
      
    </div>
  </div>
</template>

<style scoped>
/* ==========================================================================
   ESTILOS PREMIUM - GLAMUR CLUB AUTH
   ========================================================================== */

.auth-wrapper {
  min-height: calc(100vh - 74px); /* Descuenta el tamaño del navbar */
  background: radial-gradient(circle at center, #0d291e 0%, #07120c 100%);
  padding: 2rem 1rem;
  font-family: 'Playfair Display', serif;
}

.auth-container {
  width: 100%;
  max-width: 440px;
  background: rgba(7, 21, 15, 0.75);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(212, 175, 55, 0.15);
  border-radius: 24px;
  padding: 2.5rem;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
}

/* BRAND & TEXTS */

/* ESTILOS DEL NUEVO LOGO - MUCHO MÁS GRANDE */
.auth-brand-icon-img {
  display: block;
  height: 130px; /* ¡Aumentado a 130px para que destaque bien! */
  max-width: 100%; /* Evita que se salga en pantallas enanas */
  width: auto;
  object-fit: contain;
  filter: drop-shadow(0 4px 10px rgba(212, 175, 55, 0.3));
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

/* FORM LABELS & INPUTS */
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

.glamur-input::placeholder {
  color: rgba(245, 241, 232, 0.3);
}

/* DYNAMIC VALIDATION FEEDBACK */
.is-invalid {
  border-color: #ff6f85 !important;
}

.glamur-feedback {
  font-family: system-ui, -apple-system, sans-serif;
  color: #ff6f85;
  font-size: 0.8rem;
  margin-top: 0.35rem;
}

/* BUTTONS */
.btn-glamur-submit {
  border: none;
  padding: 0.8rem;
  border-radius: 14px;
  background: linear-gradient(135deg, #27e0a3, #1fc98f);
  color: #07150f;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.btn-glamur-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(39, 224, 163, 0.25);
}

.btn-glamur-submit:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

/* SEPARATOR */
.separator {
  position: relative;
  text-align: center;
}
.separator::before, .separator::after {
  content: "";
  flex: 1;
  height: 1px;
  background: rgba(255, 255, 255, 0.08);
}
.separator span {
  padding: 0 0.75rem;
  color: rgba(255, 255, 255, 0.3);
  font-size: 0.75rem;
  font-weight: 600;
  font-family: system-ui, -apple-system, sans-serif;
}

/* GOOGLE OAUTH BUTTON */
.btn-google-oauth {
  font-family: system-ui, -apple-system, sans-serif;
  background: #ffffff;
  border: none;
  border-radius: 14px;
  color: #1f1f1f;
  font-weight: 600;
  font-size: 0.95rem;
  padding: 0.75rem;
  transition: all 0.3s ease;
}

.btn-google-oauth:hover {
  background: #f1f3f4;
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(255, 255, 255, 0.1);
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