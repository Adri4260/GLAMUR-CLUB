<script setup>
import { ref } from 'vue';

const enviado = ref(false);

const enviarMensaje = () => {
    // Aquí iría la lógica de Axios para enviar el correo si tuvieras backend para ello
    enviado.value = true;
    setTimeout(() => enviado.value = false, 5000);
};
</script>

<template>
  <div class="glamur-page py-5">
    <div class="container py-4">
      <h1 class="text-center font-playfair text-white mb-5">Ponte en <span class="text-gold">Contacto</span></h1>
      
      <div class="form-container mx-auto">
        <div v-if="enviado" class="alert alert-success text-center">
          ¡Mensaje enviado con éxito! Nos pondremos en contacto contigo pronto.
        </div>
        
        <form @submit.prevent="enviarMensaje" v-else>
          <div class="mb-3">
            <label class="form-label text-light">Nombre</label>
            <input type="text" class="form-control glamur-input" required placeholder="Tu nombre completo">
          </div>
          <div class="mb-3">
            <label class="form-label text-light">Correo Electrónico</label>
            <input type="email" class="form-control glamur-input" required placeholder="ejemplo@correo.com">
          </div>
          <div class="mb-4">
            <label class="form-label text-light">Mensaje</label>
            <textarea class="form-control glamur-input" rows="5" required placeholder="¿En qué podemos ayudarte?"></textarea>
          </div>
          <button type="submit" class="btn-glamur-submit w-100">Enviar Mensaje</button>
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
.font-playfair { font-family: 'Playfair Display', serif; font-weight: 700; }
.text-gold { color: #d4af37; }
.form-container {
  max-width: 500px; background: rgba(7, 21, 15, 0.75); backdrop-filter: blur(20px);
  border: 1px solid rgba(212, 175, 55, 0.15); border-radius: 24px; padding: 2.5rem;
}
.glamur-input {
  background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1);
  color: #f5f1e8 !important; border-radius: 12px; padding: 0.75rem 1rem;
}
.glamur-input:focus { background: rgba(255, 255, 255, 0.05); border-color: #27e0a3; box-shadow: none; outline: none; }
.btn-glamur-submit {
  border: none; padding: 0.9rem; border-radius: 12px;
  background: linear-gradient(135deg, #27e0a3, #1fc98f); color: #07150f; font-weight: 600; width: 100%; transition: all 0.3s ease;
}
.btn-glamur-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(39, 224, 163, 0.25); }
.alert-success { background: rgba(39, 224, 163, 0.1); border-color: #27e0a3; color: #27e0a3; border-radius: 12px;}
</style>