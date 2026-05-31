<template>
  <div class="glamur-page py-5">
    <div class="container py-4">
      <h1 class="text-center font-playfair text-white mb-5">Preguntas <span class="text-gold">Frecuentes</span></h1>
      
      <div class="faq-container mx-auto" style="max-width: 700px;">
        <div class="faq-item">
          <h5 class="faq-question">¿Cuánto tardan los envíos?</h5>
          <p class="faq-answer">Todos nuestros pedidos premium se procesan en 24h y llegan a tu domicilio en un plazo de 48 a 72 horas laborables.</p>
        </div>
        <div class="faq-item">
          <h5 class="faq-question">¿Son productos originales?</h5>
          <p class="faq-answer">Absolutamente. Trabajamos directamente con las casas perfumeras y distribuidores oficiales. Todo nuestro catálogo tiene garantía de autenticidad 100%.</p>
        </div>
        <div class="faq-item">
          <h5 class="faq-question">¿Qué significa el sello Eco-Packaging?</h5>
          <p class="faq-answer">Significa que el embalaje utilizado en ese producto en particular proviene de materiales reciclados o es completamente biodegradable, en línea con nuestro compromiso de sostenibilidad.</p>
        </div>
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
.faq-item {
  background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem;
  transition: transform 0.3s ease, border-color 0.3s ease;
}
.faq-item:hover { transform: translateY(-3px); border-color: rgba(212, 175, 55, 0.3); }
.faq-question { color: #27e0a3; font-weight: 600; margin-bottom: 0.8rem; }
.faq-answer { color: #a3b8ab; margin-bottom: 0; line-height: 1.6; }
</style>