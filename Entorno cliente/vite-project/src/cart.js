// Carrito - Lógica específica
async function renderCart() {
    const container = document.getElementById('cartContent');
    const products = await loadProducts();
    
    if (AppState.cart.length === 0) {
        container.innerHTML = `
            <div class="empty-cart">
                <svg width="96" height="96" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                <h2>Tu carrito está vacío</h2>
                <p>Añade productos para comenzar tu compra</p>
                <a href="/src/catalogo.html" class="btn btn-primary">Ir al Catálogo</a>
            </div>
        `;
        return;
    }
    
    // Calcular totales
    let subtotal = 0;
    const cartItems = AppState.cart.map(item => {
        const product = products.find(p => p.id === item.id);
        const itemTotal = parseFloat(product.precio) * item.quantity;
        subtotal += itemTotal;
        return { ...item, product };
    });
    
    const shipping = subtotal >= 50 ? 0 : 5.99;
    const total = subtotal + shipping;
    
    // Renderizar
    container.innerHTML = `
        <div class="cart-items">
            ${cartItems.map(item => `
                <div class="cart-item">
                    <div class="cart-item-image">
                        <img src="${item.product.imagen}" alt="${item.product.nombre}">
                    </div>
                    <div class="cart-item-info">
                        <h3 class="cart-item-name">${item.product.nombre}</h3>
                        <p class="cart-item-price">€${parseFloat(item.product.precio).toFixed(2)}</p>
                        <div class="cart-item-controls">
                            <div class="quantity-control">
                                <button class="quantity-btn" onclick="updateQuantity('${item.id}', ${item.quantity - 1})">−</button>
                                <span class="quantity-value">${item.quantity}</span>
                                <button class="quantity-btn" onclick="updateQuantity('${item.id}', ${item.quantity + 1})">+</button>
                            </div>
                            <button class="remove-btn" onclick="removeFromCart('${item.id}')">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>a
                            </button>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
        
        <div class="cart-summary">
            <h2>Resumen del Pedido</h2>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>€${subtotal.toFixed(2)}</span>
            </div>
            <div class="summary-row">
                <span>Envío</span>
                <span>${shipping === 0 ? 'Gratis' : '€' + shipping.toFixed(2)}</span>
            </div>
            ${subtotal < 50 && shipping > 0 ? `
                <p style="font-size: 0.75rem; color: var(--color-gray-lighter); margin-top: var(--spacing-sm);">
                    Añade €${(50 - subtotal).toFixed(2)} más para envío gratis
                </p>
            ` : ''}
            <div class="summary-divider"></div>
            <div class="summary-total">
                <span>Total</span>
                <span class="price">€${total.toFixed(2)}</span>
            </div>
            <div class="cart-actions">
                <button class="btn btn-primary" onclick="checkout()">Proceder al Pago</button>
                <a href="/src/catalogo.html" class="btn btn-outline">Seguir Comprando</a>
            </div>
        </div>
    `;
}

// Actualizar cantidad
function updateQuantity(productId, newQuantity) {
    if (newQuantity < 1) return;
    
    const item = AppState.cart.find(item => item.id === productId);
    if (item) {
        item.quantity = newQuantity;
        saveToStorage();
        renderCart();
    }
}

// Eliminar del carrito
function removeFromCart(productId) {
    AppState.cart = AppState.cart.filter(item => item.id !== productId);
    saveToStorage();
    renderCart();
    showNotification('Producto eliminado del carrito');
}

// Checkout (simulado)
function checkout() {
    showNotification('Función de pago en desarrollo');
    // Aquí iría la integración con pasarela de pago
}

// Inicializar
document.addEventListener('DOMContentLoaded', renderCart);
