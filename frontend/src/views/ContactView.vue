<script setup>
import { ref } from 'vue';
import { useForm, useField } from 'vee-validate';
import * as yup from 'yup';

const enviado = ref(false);

// 1. Definimos el esquema de validación con Yup
const schema = yup.object({
  nombre: yup
    .string()
    .required('El nombre es obligatorio.')
    .min(3, 'El nombre debe tener al menos 3 caracteres.'),
  email: yup
    .string()
    .required('El correo electrónico es obligatorio.')
    .email('Debe ser un correo válido (ej: nombre@correo.com).'),
  mensaje: yup
    .string()
    .required('El mensaje no puede estar vacío.')
    .min(10, 'El mensaje debe tener al menos 10 caracteres.')
});

// 2. Inicializamos el formulario con vee-validate
const { handleSubmit, errors, isSubmitting, resetForm } = useForm({
  validationSchema: schema,
});

// 3. Conectamos los campos
const { value: nombre } = useField('nombre');
const { value: email } = useField('email');
const { value: mensaje } = useField('mensaje');

// 4. Función de envío que solo se ejecuta si todo es válido
const onSubmit = handleSubmit(async (values) => {
    // Simulamos una petición de red con un pequeño delay
    await new Promise(resolve => setTimeout(resolve, 1500));
    
    enviado.value = true;
    resetForm(); // Vaciamos el formulario
    
    // Ocultamos el mensaje de éxito tras 5 segundos
    setTimeout(() => enviado.value = false, 5000);
});
</script>

<template>
  <div class="glamur-page py-5">
    <div class="container py-4">
      <h1 class="text-center font-playfair text-white mb-5">Ponte en <span class="text-gold">Contacto</span></h1>
      
      <div class="form-container mx-auto">
        
        <!-- MENSAJE DE ÉXITO -->
        <div v-if="enviado" class="alert alert-success text-center">
          ¡Mensaje enviado con éxito! Nos pondremos en contacto contigo pronto.
        </div>
        
        <!-- FORMULARIO CON VALIDACIÓN -->
        <form @submit="onSubmit" v-else>
          
          <div class="mb-4 position-relative">
            <label class="form-label text-light">Nombre</label>
            <input 
              type="text" 
              v-model="nombre" 
              class="form-control glamur-input" 
              :class="{ 'is-invalid': errors.nombre }"
              placeholder="Tu nombre completo"
            >
            <span class="error-text" v-if="errors.nombre">{{ errors.nombre }}</span>
          </div>

          <div class="mb-4 position-relative">
            <label class="form-label text-light">Correo Electrónico</label>
            <input 
              type="email" 
              v-model="email" 
              class="form-control glamur-input" 
              :class="{ 'is-invalid': errors.email }"
              placeholder="ejemplo@correo.com"
            >
            <span class="error-text" v-if="errors.email">{{ errors.email }}</span>
          </div>

          <div class="mb-4 position-relative">
            <label class="form-label text-light">Mensaje</label>
            <textarea 
              v-model="mensaje" 
              class="form-control glamur-input" 
              :class="{ 'is-invalid': errors.mensaje }"
              rows="5" 
              placeholder="¿En qué podemos ayudarte?"
            ></textarea>
            <span class="error-text" v-if="errors.mensaje">{{ errors.mensaje }}</span>
          </div>

          <button type="submit" class="btn-glamur-submit w-100" :disabled="isSubmitting">
            <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
            {{ isSubmitting ? 'Enviando...' : 'Enviar Mensaje' }}
          </button>

        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.glamur-page {
  min-height: calc(100vh - 74px);
  background: radial-gradient(circle at center, #0d291e 0%, #07120c 100%);
}

.font-playfair { 
  font-family: 'Playfair Display', serif; 
  font-weight: 700; 
}

.text-gold { 
  color: #d4af37; 
}

.form-container {
  max-width: 500px; 
  background: rgba(7, 21, 15, 0.75); 
  backdrop-filter: blur(20px);
  border: 1px solid rgba(212, 175, 55, 0.15); 
  border-radius: 24px; 
  padding: 2.5rem;
}

/* =========================
   INPUTS Y PLACEHOLDERS
========================= */
.glamur-input {
  background: rgba(255, 255, 255, 0.03); 
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: #f5f1e8 !important; 
  border-radius: 12px; 
  padding: 0.75rem 1rem;
  transition: all 0.3s ease;
}

/* AQUÍ SE ARREGLAN LOS PLACEHOLDERS PARA QUE SE VEAN */
.glamur-input::placeholder {
  color: rgba(255, 255, 255, 0.45) !important;
}

.glamur-input:focus { 
  background: rgba(255, 255, 255, 0.05); 
  border-color: #27e0a3; 
  box-shadow: 0 0 0 3px rgba(39, 224, 163, 0.15); 
  outline: none; 
}

/* =========================
   ERRORES VEE-VALIDATE
========================= */
.is-invalid {
  border-color: #ff6f85 !important;
  background: rgba(255, 111, 133, 0.05) !important;
}

.is-invalid:focus {
  box-shadow: 0 0 0 3px rgba(255, 111, 133, 0.15) !important;
}

.error-text {
  color: #ff6f85;
  font-size: 0.82rem;
  margin-top: 0.4rem;
  display: block;
  font-weight: 500;
}

/* =========================
   BOTONES
========================= */
.btn-glamur-submit {
  border: none; 
  padding: 0.9rem; 
  border-radius: 12px;
  background: linear-gradient(135deg, #27e0a3, #1fc98f); 
  color: #07150f; 
  font-weight: 600; 
  width: 100%; 
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

.alert-success { 
  background: rgba(39, 224, 163, 0.1); 
  border-color: #27e0a3; 
  color: #27e0a3; 
  border-radius: 12px;
}
</style>