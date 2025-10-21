// Estado Global de la Aplicación
const AppState = {
    cart: JSON.parse(localStorage.getItem('cart')) || [],
    favorites: JSON.parse(localStorage.getItem('favorites')) || [],
    products: []
};

// Actualizar badges
function updateBadges() {
    const cartBadge = document.getElementById('cartBadge');
    const favoritesBadge = document.getElementById('favoritesBadge');
    
    if (cartBadge) {
        const totalItems = AppState.cart.reduce((sum, item) => sum + item.quantity, 0);
        cartBadge.textContent = totalItems;
        cartBadge.style.display = totalItems > 0 ? 'flex' : 'none';
    }
    
    if (favoritesBadge) {
        favoritesBadge.textContent = AppState.favorites.length;
        favoritesBadge.style.display = AppState.favorites.length > 0 ? 'flex' : 'none';
    }
}

// Guardar en localStorage
function saveToStorage() {
    localStorage.setItem('cart', JSON.stringify(AppState.cart));
    localStorage.setItem('favorites', JSON.stringify(AppState.favorites));
    updateBadges();
}

// Mobile Menu
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const navLinks = document.getElementById('navLinks');

if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });
}

// Cargar productos
async function loadProducts() {
    try {
        const response = await fetch('/public/data/productos.json');
        const data = await response.json();
        AppState.products = data;
        return data;
    } catch (error) {
        console.error('Error cargando productos:', error);
        return [];
    }
}

// Renderizar productos destacados
async function renderFeaturedProducts() {
    const container = document.getElementById('featuredProducts');
    if (!container) return;
    
    const products = await loadProducts();
    const featured = products.filter(p => p.destacado).slice(0, 4);
    
    if (featured.length === 0) {
        container.innerHTML = '<p class="loading">No hay productos destacados</p>';
        return;
    }
    
    container.innerHTML = featured.map(product => createProductCard(product)).join('');
    attachProductEventListeners();
}

// Renderizar catálogo con filtro por categoría
async function renderCatalog(category = 'Todos') {
    const container = document.getElementById('catalogProducts');
    if (!container) return;

    if (AppState.products.length === 0) {
        await loadProducts();
    }

    let filteredProducts = AppState.products;

    if (category && category !== 'Todos') {
        filteredProducts = AppState.products.filter(p => p.categoria === category);
    }

    if (filteredProducts.length === 0) {
        container.innerHTML = '<p class="loading">No hay productos para esta categoría</p>';
        return;
    }

    container.innerHTML = filteredProducts.map(createProductCard).join('');
    attachProductEventListeners();
}

// Crear card de producto
function createProductCard(product) {
    const isFavorite = AppState.favorites.some(f => f === product.id);
    
    return `
        <div class="product-card">
            <div class="product-image">
                <a href="/producto.html?id=${product.id}">
                    <img src="${product.imagen}" alt="${product.nombre}">
                </a>
                <button class="product-favorite ${isFavorite ? 'active' : ''}" data-id="${product.id}" onclick="toggleFavorite('${product.id}')">
                    <svg width="20" height="20" fill="${isFavorite ? 'currentColor' : 'none'}" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </button>
                ${product.destacado ? '<span class="product-badge">Destacado</span>' : ''}
            </div>
            <div class="product-info">
                <h3 class="product-name">${product.nombre}</h3>
                <p class="product-description">${product.descripcion}</p>
                <div class="product-footer">
                    <span class="product-price">€${parseFloat(product.precio).toFixed(2)}</span>
                    <button class="product-add-btn" onclick="addToCart('${product.id}')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        Añadir
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Toggle favorito
function toggleFavorite(productId) {
    const index = AppState.favorites.indexOf(productId);
    
    if (index > -1) {
        AppState.favorites.splice(index, 1);
    } else {
        AppState.favorites.push(productId);
    }
    
    saveToStorage();
    
    // Actualizar UI
    const btn = document.querySelector(`[data-id="${productId}"]`);
    if (btn) {
        btn.classList.toggle('active');
        const svg = btn.querySelector('svg');
        const isFavorite = AppState.favorites.includes(productId);
        svg.setAttribute('fill', isFavorite ? 'currentColor' : 'none');
    }
}

// Añadir al carrito
function addToCart(productId) {
    const existingItem = AppState.cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        AppState.cart.push({ id: productId, quantity: 1 });
    }
    
    saveToStorage();
    showNotification('Producto añadido al carrito');
}

// Mostrar notificación
function showNotification(message) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--color-gold);
        color: var(--color-black);
        padding: 1rem 1.5rem;
        border-radius: var(--radius);
        font-weight: 500;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}

// Añadir estilos de animación
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Event listeners para productos
function attachProductEventListeners() {
    // Ya están manejados por onclick en el HTML generado
}

// Newsletter form
const newsletterForm = document.getElementById('newsletterForm');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = newsletterForm.querySelector('input[type="email"]').value;
        
        if (!email || !email.includes('@')) {
            showNotification('Por favor, ingresa un email válido');
            return;
        }
        
        try {
            const response = await fetch('/api/newsletter', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email })
            });
            
            if (response.ok) {
                showNotification('¡Suscripción exitosa!');
                newsletterForm.reset();
            } else {
                showNotification('Error al suscribirse');
            }
        } catch (error) {
            showNotification('Error al suscribirse');
        }
    });
}

// Inicializar
document.addEventListener('DOMContentLoaded', () => {
    updateBadges();

    if (document.getElementById('featuredProducts')) {
        renderFeaturedProducts();
    }

    if (document.getElementById('catalogProducts')) {
        renderCatalog('Todos'); // Carga inicial de todos los productos

        // Listener para filtro de categoría, asumiendo select con id categorySelect
        const categorySelect = document.getElementById('categorySelect');
        if (categorySelect) {
            categorySelect.addEventListener('change', (e) => {
                renderCatalog(e.target.value);
            });
        }
    }
});
