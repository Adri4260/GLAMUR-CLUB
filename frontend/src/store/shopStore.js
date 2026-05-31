import { defineStore } from 'pinia';

export const useShopStore = defineStore('shop', {
  state: () => ({
    // Recuperamos lo que haya guardado en el navegador, o empezamos de cero
    cart: JSON.parse(localStorage.getItem('glamur_cart')) || [],
    favorites: JSON.parse(localStorage.getItem('glamur_favorites')) || []
  }),
  
  getters: {
    // Cuenta el total de unidades en el carrito
    cartCount: (state) => state.cart.reduce((total, item) => total + item.quantity, 0),
    // Cuenta cuántos productos únicos hay en favoritos
    favoritesCount: (state) => state.favorites.length,
  },
  
  actions: {
    addToCart(product, quantity = 1) {
      const existingItem = this.cart.find(item => item.id === product.id);
      if (existingItem) {
        existingItem.quantity += quantity;
      } else {
        this.cart.push({ ...product, quantity });
      }
      this.saveCart();
    },
    
    removeFromCart(productId) {
      this.cart = this.cart.filter(item => item.id !== productId);
      this.saveCart();
    },

    toggleFavorite(product) {
      const index = this.favorites.findIndex(item => item.id === product.id);
      if (index >= 0) {
        this.favorites.splice(index, 1); // Si ya estaba, lo quitamos
      } else {
        this.favorites.push(product); // Si no estaba, lo añadimos
      }
      this.saveFavorites();
    },

    saveCart() {
      localStorage.setItem('glamur_cart', JSON.stringify(this.cart));
    },
    
    saveFavorites() {
      localStorage.setItem('glamur_favorites', JSON.stringify(this.favorites));
    }
  }
});