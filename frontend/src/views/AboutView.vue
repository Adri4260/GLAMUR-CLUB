<template>
  <div class="glamur-page py-5">
    <div class="container py-5 text-center">
      <div class="brand-icon mx-auto mb-4">GC</div>
      <h1 class="display-4 font-playfair text-white mb-4">Nuestra <span class="text-gold">Esencia</span></h1>
      
      <div class="row justify-content-center mt-5">
        <div class="col-md-8">
          <p class="lead text-light-muted mb-4">
            GLAMUR CLUB nació de la pasión por la alta perfumería y el deseo de democratizar el lujo. 
            No vendemos simples fragancias; ofrecemos experiencias olfativas diseñadas para dejar huella.
          </p>
          <p class="text-light-muted mb-5">
            Cada producto de nuestro catálogo es seleccionado bajo los más estrictos estándares de calidad, 
            asegurando que nuestros socios disfruten de notas exclusivas, packaging sostenible y una atención premium.
          </p>
          
          <router-link to="/catalogo" class="btn-glamur-gold">Explorar Catálogo</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.glamur-page {
  min-height: calc(100vh - 74px);
  background: radial-gradient(circle at top, #0d291e 0%, #07120c 100%);
}
.font-playfair { font-family: 'Playfair Display', serif; font-weight: 700; }
.text-gold { color: #d4af37; }
.text-light-muted { color: #a3b8ab; line-height: 1.8; font-family: system-ui, -apple-system, sans-serif; }
.brand-icon {
  width: 70px; height: 70px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  background: linear-gradient(135deg, #d4af37, #f3dc87);
  color: #07150f; font-weight: 700; font-size: 1.5rem;
  box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2);
}
.btn-glamur-gold {
  display: inline-block; padding: 0.8rem 2rem; border-radius: 12px;
  background: transparent; border: 1px solid #d4af37; color: #d4af37;
  font-weight: 600; text-decoration: none; transition: all 0.3s ease;
}
.btn-glamur-gold:hover { background: #d4af37; color: #07150f; transform: translateY(-2px); }
</style>