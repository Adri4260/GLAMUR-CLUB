// Estado Global de la Aplicación
const AppState = {
  cart: JSON.parse(localStorage.getItem("cart")) || [],
  favorites: JSON.parse(localStorage.getItem("favorites")) || [],
  products: [],
};

// Actualizar badges
function updateBadges() {
  const cartBadge = document.getElementById("cartBadge");
  const favoritesBadge = document.getElementById("favoritesBadge");

  if (cartBadge) {
    const totalItems = AppState.cart.reduce(
      (sum, item) => sum + item.quantity,
      0
    );
    cartBadge.textContent = totalItems;
    // Volvemos a 'flex' para que respete tu diseño original de styles.css
    cartBadge.style.display = totalItems > 0 ? "flex" : "none";
  }

  if (favoritesBadge) {
    favoritesBadge.textContent = AppState.favorites.length;
    favoritesBadge.style.display =
      AppState.favorites.length > 0 ? "flex" : "none";
  }
}

// Guardar en localStorage
function saveToStorage() {
  localStorage.setItem("cart", JSON.stringify(AppState.cart));
  localStorage.setItem("favorites", JSON.stringify(AppState.favorites));
  updateBadges();
}

// Mobile Menu
const mobileMenuBtn = document.getElementById("mobileMenuBtn");
const navLinks = document.getElementById("navLinks");

if (mobileMenuBtn) {
  mobileMenuBtn.addEventListener("click", () => {
    navLinks.classList.toggle("active");
  });
}

// Cargar productos
async function loadProducts() {
  if (AppState.products.length > 0) return AppState.products;

  try {
    const response = await fetch("/public/data/datos.json");
    const data = await response.json();
    // Soporte para estructura { productes: [...] } o array directo
    AppState.products = data.productes || data.productos || data;
    return AppState.products;
  } catch (error) {
    console.error("Error cargando productos:", error);
    return [];
  }
}
window.loadProducts = loadProducts; // Hacer global

// Renderizar productos destacados
async function renderFeaturedProducts() {
  const container = document.getElementById("featuredProducts");
  if (!container) return;

  const products = await loadProducts();
  const featured = products.filter((p) => p.destacado).slice(0, 4);

  if (featured.length === 0) {
    container.innerHTML = '<p class="loading">No hay productos destacados</p>';
    return;
  }

  container.innerHTML = featured
    .map((product) => createProductCard(product))
    .join("");
  // No necesitamos attachProductEventListeners si usamos onclick en el HTML
}

// Renderizar catálogo con filtro
async function renderCatalog(category = "Todos") {
  const container = document.getElementById("catalogProducts");
  if (!container) return;

  const products = await loadProducts();
  let filteredProducts = products;

  if (category && category !== "Todos") {
    filteredProducts = products.filter((p) => p.categoria === category);
  }

  if (filteredProducts.length === 0) {
    container.innerHTML =
      '<p class="loading">No hay productos para esta categoría</p>';
    return;
  }

  container.innerHTML = filteredProducts.map(createProductCard).join("");
}
window.renderCatalog = renderCatalog; // Hacer global

// Crear card de producto
function createProductCard(product) {
  const isFavorite = AppState.favorites.includes(product.id);

  return `
        <div class="product-card">
            <div class="product-image">
                <a href="/src/perfume.php?id=${product.id}">
                    <img src="${product.imagen}" alt="${product.nombre}">
                </a>
                <button class="product-favorite ${isFavorite ? "active" : ""}" 
                        onclick="toggleFavorite('${product.id}', this)"
                        aria-label="Añadir a favoritos">
                    <svg width="20" height="20" fill="${
                      isFavorite ? "currentColor" : "none"
                    }" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </button>
                ${
                  product.destacado
                    ? '<span class="product-badge">Destacado</span>'
                    : ""
                }
            </div>
            <div class="product-info">
                <h3 class="product-name">${product.nombre}</h3>
                <p class="product-description">${product.descripcion}</p>
                <div class="product-footer">
                    <span class="product-price">€${parseFloat(
                      product.precio
                    ).toFixed(2)}</span>
                    <button class="product-add-btn" onclick="addToCart('${
                      product.id
                    }', '${product.nombre}')">
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
window.createProductCard = createProductCard; // Hacer global

// Toggle favorito (Corregido para recibir el botón)
function toggleFavorite(productId, btnElement) {
  const index = AppState.favorites.indexOf(productId);

  if (index > -1) {
    AppState.favorites.splice(index, 1);
    showNotification("Eliminado de favoritos");
  } else {
    AppState.favorites.push(productId);
    showNotification("Añadido a favoritos");
  }

  saveToStorage();

  // Actualizar UI del botón clicado inmediatamente
  if (btnElement) {
    btnElement.classList.toggle("active");
    const svg = btnElement.querySelector("svg");
    const isFav = AppState.favorites.includes(productId);
    svg.setAttribute("fill", isFav ? "currentColor" : "none");
  }

  // Si hay otros botones para el mismo producto (ej: en otra lista), actualizarlos
  const otherBtns = document.querySelectorAll(
    `.product-favorite[onclick*="'${productId}'"]`
  );
  otherBtns.forEach((btn) => {
    if (btn !== btnElement) {
      btn.classList.toggle("active");
      const svg = btn.querySelector("svg");
      const isFav = AppState.favorites.includes(productId);
      svg.setAttribute("fill", isFav ? "currentColor" : "none");
    }
  });
}
window.toggleFavorite = toggleFavorite; // Hacer global

// Añadir al carrito
function addToCart(productId, productName) {
  const existingItem = AppState.cart.find((item) => item.id === productId);

  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    AppState.cart.push({ id: productId, quantity: 1 });
  }

  saveToStorage();
  const nameDisplay = productName ? `"${productName}"` : "Producto";
  showNotification(`${nameDisplay} añadido al carrito`);
}
window.addToCart = addToCart; // Hacer global

// Mostrar notificación (Corregido y estilos inyectados)
function showNotification(message) {
  // Eliminar notificaciones previas para evitar acumulación
  const existingNotif = document.querySelector(".app-notification");
  if (existingNotif) existingNotif.remove();

  const notification = document.createElement("div");
  notification.className = "app-notification";
  notification.textContent = message;

  // Estilos inline para asegurar visibilidad sin depender de CSS externo
  Object.assign(notification.style, {
    position: "fixed",
    top: "80px", // Debajo del navbar
    right: "20px",
    backgroundColor: "#0fe8a7", // Tu color primario (verde esmeralda)
    color: "#0a0f0a", // Color de fondo oscuro para contraste
    padding: "12px 24px",
    borderRadius: "6px",
    fontWeight: "600",
    zIndex: "9999",
    boxShadow: "0 4px 12px rgba(0,0,0,0.3)",
    transform: "translateX(100%)",
    transition: "transform 0.3s ease-out",
  });

  document.body.appendChild(notification);

  // Forzar reflow para activar transición
  requestAnimationFrame(() => {
    notification.style.transform = "translateX(0)";
  });

  setTimeout(() => {
    notification.style.transform = "translateX(120%)"; // Sacar de pantalla
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}
window.showNotification = showNotification; // Hacer global

// Inicializar
document.addEventListener("DOMContentLoaded", () => {
  updateBadges();

  if (document.getElementById("featuredProducts")) {
    renderFeaturedProducts();
  }

  if (document.getElementById("catalogProducts")) {
    renderCatalog("Todos");

    const categorySelect = document.getElementById("categorySelect");
    if (categorySelect) {
      categorySelect.addEventListener("change", (e) => {
        renderCatalog(e.target.value);
      });
    }
  }
});
function updateBadges() {
  const cartBadge = document.getElementById("cartBadge");
  const favoritesBadge = document.getElementById("favoritesBadge");

  if (cartBadge) {
    const totalItems = AppState.cart.reduce(
      (sum, item) => sum + item.quantity,
      0
    );
    cartBadge.textContent = totalItems;
    // Siempre visible pero con opacidad si es 0 (opcional) para un look más fino
    cartBadge.style.display = "inline-block";
    cartBadge.style.opacity = totalItems > 0 ? "1" : "0.5";
  }

  if (favoritesBadge) {
    favoritesBadge.textContent = AppState.favorites.length;
    favoritesBadge.style.display = "inline-block";
    favoritesBadge.style.opacity = AppState.favorites.length > 0 ? "1" : "0.5";
  }
}
