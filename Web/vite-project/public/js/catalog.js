// Web/vite-project/public/js/catalog.js
// Lógica que orquesta el catálogo, ahora dependiendo de script.js para datos y cards.

// --- Variables Globales y del DOM ---
let currentCategory = 'all';
let currentSort = 'name';

const catalogContainer = document.getElementById('catalogProducts'); 
const resultsCount = document.getElementById('resultsCount');
const sortSelect = document.getElementById('sortSelect');
const categoryButtons = document.querySelectorAll('[data-category]');


/**
 * Cargar y renderizar productos del catálogo
 */
async function loadCatalog() {
    if (catalogContainer) {
        catalogContainer.innerHTML = '<p class="loading text-center">Carregant productes...</p>';
    }
    
    // *** CLAVE: Usar la función global loadProducts de script.js ***
    const products = await window.loadProducts(); 
    
    renderCatalog(products);
}

/**
 * Renderizar catálogo (Tu lógica de filtrado y ordenación original)
 */
function renderCatalog(products) {
    const container = catalogContainer;
    if (!container || typeof window.createProductCard !== 'function') return; // Asegura que la función de card existe
    
    // Filtrar por categoría
    let filtered = currentCategory === 'all' 
        ? products 
        : products.filter(p => p.categoria.toLowerCase() === currentCategory.toLowerCase());
    
    // Ordenar
    filtered = sortProducts(filtered, currentSort);
    
    // Actualizar contador
    if (resultsCount) {
        resultsCount.textContent = `${filtered.length} productos encontrados`;
    }
    
    // Renderizar
    if (filtered.length === 0) {
        container.innerHTML = '<p class="loading">No se encontraron productos</p>';
        return;
    }
    
    // *** CLAVE: Usar la función global createProductCard de script.js ***
    container.innerHTML = filtered.map(product => window.createProductCard(product)).join('');
    
    // Asegurar el layout de cuadrícula
    if (!container.classList.contains('row')) {
        container.classList.add('row', 'g-4'); 
    }
}

/**
 * Ordenar productos (Tu lógica original)
 */
function sortProducts(products, sortBy) {
    const sorted = [...products];
    
    switch(sortBy) {
        case 'price-asc':
            return sorted.sort((a, b) => parseFloat(a.precio) - parseFloat(b.precio));
        case 'price-desc':
            return sorted.sort((a, b) => parseFloat(b.precio) - parseFloat(a.precio));
        case 'name':
        default:
            return sorted.sort((a, b) => a.nombre.localeCompare(b.nombre));
    }
}

// Event listeners para categorías
if (categoryButtons.length > 0) {
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            categoryButtons.forEach(b => {
                b.classList.remove('btn-primary', 'active');
                b.classList.add('btn-outline');
            });
            btn.classList.remove('btn-outline');
            btn.classList.add('btn-primary', 'active');
            
            currentCategory = btn.dataset.category;
            loadCatalog();
        });
    });
}

// Event listener para ordenar
if (sortSelect) {
    sortSelect.addEventListener('change', () => {
        currentSort = sortSelect.value;
        loadCatalog();
    });
}

// Inicializar
document.addEventListener('DOMContentLoaded', loadCatalog);