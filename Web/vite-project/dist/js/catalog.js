// Catálogo - Lógica específica
let currentCategory = 'all';
let currentSort = 'name';

// Cargar y renderizar productos del catálogo
async function loadCatalog() {
    const products = await loadProducts();
    renderCatalog(products);
}

// Renderizar catálogo
function renderCatalog(products) {
    const container = document.getElementById('catalogProducts');
    const resultsCount = document.getElementById('resultsCount');
    
    // Filtrar por categoría
    let filtered = currentCategory === 'all' 
        ? products 
        : products.filter(p => p.categoria.toLowerCase() === currentCategory);
    
    // Ordenar
    filtered = sortProducts(filtered, currentSort);
    
    // Actualizar contador
    resultsCount.textContent = `${filtered.length} productos encontrados`;
    
    // Renderizar
    if (filtered.length === 0) {
        container.innerHTML = '<p class="loading">No se encontraron productos</p>';
        return;
    }
    
    container.innerHTML = filtered.map(product => createProductCard(product)).join('');
}

// Ordenar productos
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
const categoryButtons = document.querySelectorAll('[data-category]');
categoryButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        // Actualizar botones activos
        categoryButtons.forEach(b => {
            b.classList.remove('btn-primary', 'active');
            b.classList.add('btn-outline');
        });
        btn.classList.remove('btn-outline');
        btn.classList.add('btn-primary', 'active');
        
        // Actualizar categoría y recargar
        currentCategory = btn.dataset.category;
        loadCatalog();
    });
});

// Event listener para ordenar
const sortSelect = document.getElementById('sortSelect');
sortSelect.addEventListener('change', () => {
    currentSort = sortSelect.value;
    loadCatalog();
});

// Inicializar
document.addEventListener('DOMContentLoaded', loadCatalog);
