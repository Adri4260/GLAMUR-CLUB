// Favoritos - Lógica específica
async function renderFavorites() {
    const container = document.getElementById('favoritesContent');
    const products = await loadProducts();

    if (AppState.favorites.length === 0) {
        container.innerHTML = `
            <div class="empty-cart">
                <svg width="96" height="96" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <h2>No tienes favoritos todavía</h2>
                <p>Agrega productos que te gusten al hacer clic en el corazón ❤️</p>
                <a href="/src/catalogo.html" class="btn btn-primary">Ver Catálogo</a>
            </div>
        `;
        return;
    }

    const favoriteItems = AppState.favorites.map(id => {
        const product = products.find(p => p.id === id);
        return product ? `
            <div class="cart-item">
                <div class="cart-item-image">
                    <img src="${product.imagen}" alt="${product.nombre}">
                </div>
                <div class="cart-item-info">
                    <h3 class="cart-item-name">${product.nombre}</h3>
                    <p class="cart-item-price">€${parseFloat(product.precio).toFixed(2)}</p>
                    <div class="cart-item-controls">
                        <button class="btn btn-primary" onclick="addToCart('${product.id}')">Añadir al Carrito</button>
                        <button class="remove-btn" onclick="removeFromFavorites('${product.id}')">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                            Quitar
                        </button>
                    </div>
                </div>
            </div>
        ` : '';
    }).join('');

    container.innerHTML = `
        <div class="cart-items">
            ${favoriteItems}
        </div>
    `;
}

function removeFromFavorites(productId) {
    AppState.favorites = AppState.favorites.filter(id => id !== productId);
    saveToStorage();
    renderFavorites();
    showNotification('Producto eliminado de favoritos');
}

document.addEventListener('DOMContentLoaded', renderFavorites);
