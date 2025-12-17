<?php
// Web/vite-project/src/perfume.php
// Página de detalle de producto.

// 1. Iniciar sesión
session_start();

// 2. Incluir archivos de configuración necesarios
require_once '../includes/config.php';
$is_logged_in = isset($_SESSION['user_id']);

// --- 3. Obtención del ID y Carga del Producto ---
$product_id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);

if (empty($product_id)) {
    http_response_code(400);
    echo "<h1>Error 400: ID de producto no proporcionado.</h1>";
    exit;
}

// 4. Cargar datos.json LOCALMENTE
$json_path = dirname(__DIR__) . '/public/data/datos.json';
$json_content = file_get_contents($json_path);

if ($json_content === false) {
    http_response_code(500);
    echo "<h1>Error 500: No se pudo cargar el archivo de datos local.</h1>";
    exit;
}

$data = json_decode($json_content, true);

// Intenta encontrar el array de productos (busca 'productes', luego 'productos', luego raíz)
$products_array = $data['productes'] ?? $data['productos'] ?? $data;

// Buscar el producto por ID
$product = null;
if (is_array($products_array)) {
    foreach ($products_array as $p) {
        if (isset($p['id']) && (string)$p['id'] === (string)$product_id) {
            $product = $p;
            break;
        }
    }
}

if ($product === null) {
    http_response_code(404);
    echo "<h1>Error 404: Producto no encontrado (ID: " . htmlspecialchars($product_id) . ").</h1>";
    exit;
}

// --- VARIABLES PARA LA VISTA ---
$nombre_producto = htmlspecialchars($product['nombre'] ?? 'Producto Desconocido');
$descripcion_producto = htmlspecialchars($product['descripcion'] ?? 'Sin descripción.');
$precio_producto = number_format($product['precio'] ?? 0, 2, ',', '.') . ' €';
$imagen_producto = htmlspecialchars($product['imagen'] ?? '/public/img/default.jpg');

// *** STOCK: Usamos 'estoc' o 'stock' ***
$raw_stock = $product['estoc'] ?? $product['stock'] ?? 0;
$stock_producto = (int)$raw_stock;

$categoria_producto = htmlspecialchars($product['categoria'] ?? 'General');
$username_display = htmlspecialchars($_SESSION['username'] ?? 'Usuario');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nombre_producto; ?> - GLAMUR-CLUB</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS Global -->
    <link rel="stylesheet" href="/public/css/styles.css">
    <!-- CSS Específico de Detalles (Dark Emerald) -->
    <link rel="stylesheet" href="/public/css/detallesProd.css">
</head>

<body>

    <nav class="navbar">
        <div class="container">
            <div class="nav-content">
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <span></span><span></span><span></span>
                </button>

                <a href="/" class="logo">GLAMUR CLUB</a>

                <div class="nav-links" id="navLinks">
                    <a href="../src/catalogo.html">Catálogo</a>
                    <a href="../src/crear-perfume.html">Crea tu Perfume</a>
                </div>

                <div class="nav-actions">
                    <?php if ($is_logged_in): ?>
                        <a href="../auth/profile.php" class="nav-icon profile-btn" title="Mi Perfil">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <a href="../auth/login.php" class="btn btn-ghost login-btn">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
                            </svg>
                            Entrar
                        </a>
                    <?php endif; ?>
                    <a href="../src/favoritos.html" class="nav-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                        <span class="badge" id="favoritesBadge">0</span>
                    </a>
                    <a href="../src/carrito.html" class="nav-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1" />
                            <circle cx="20" cy="21" r="1" />
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                        </svg>
                        <span class="badge" id="cartBadge">0</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container my-5">

        <div class="row product-detail-section">
            <!-- Columna Imagen -->
            <div class="col-md-5">
                <img src="<?php echo $imagen_producto; ?>" alt="<?php echo $nombre_producto; ?>" class="img-fluid rounded shadow">
            </div>

            <!-- Columna Info -->
            <div class="col-md-7 product-info-col">
                <h1 class="display-4"><?php echo $nombre_producto; ?></h1>
                <p class="lead text-muted"><?php echo $descripcion_producto; ?></p>
                <h2 class="price"><?php echo $precio_producto; ?></h2>

                <div class="product-meta">
                    <p>Categoría: <strong><?php echo $categoria_producto; ?></strong></p>

                    <!-- VISUALIZACIÓN DE STOCK -->
                    <p>Stock:
                        <?php if ($stock_producto > 0): ?>
                            <span class="badge bg-success"><?php echo $stock_producto; ?> Unidades disponibles</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Agotado</span>
                        <?php endif; ?>
                    </p>
                </div>

                <div class="mt-4 actions-container">
                    <button class="btn btn-success btn-lg" onclick="window.addToCart('<?php echo $product_id; ?>', '<?php echo $nombre_producto; ?>')">
                        Añadir a la Cesta
                    </button>
                    <button id="fav-btn-detail" class="btn btn-outline-danger btn-lg" onclick="window.toggleFavorite('<?php echo $product_id; ?>', this)">
                        ❤️ Favoritos
                    </button>
                </div>
            </div>
        </div>

        <hr class="my-5" style="border-color: var(--c-border);">

        <section id="product-reviews" class="product-reviews">
            <h2>Comentarios y Valoraciones</h2>

            <input type="hidden" id="product-id" value="<?php echo htmlspecialchars($product_id); ?>">

            <div id="comment-stats" class="mb-4">
                <p>Cargando estadísticas...</p>
            </div>

            <div class="mb-4 text-center">
                <button id="like-button" class="btn btn-outline-success">
                    <span role="img" aria-label="Me gusta">👍</span> Me gusta el producto
                </button>
            </div>

            <?php if ($is_logged_in): ?>
                <h3 class="mt-4">Deja tu opinión</h3>

                <form id="comment-form" method="POST" action="/api/comments.php" class="mb-5">
                    <div class="mb-3">
                        <label for="comment-text" class="form-label">Comentario (máx. 500 caracteres)</label>
                        <textarea class="form-control" id="comment-text" name="comment" rows="3" maxlength="500"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="comment-rating" class="form-label">Puntuación (1-5)</label>
                        <select class="form-select" id="comment-rating" name="rating">
                            <option value="">Sin puntuación</option>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> Estrellas</option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar Comentario</button>
                </form>
            <?php else: ?>
                <div id="auth-warning" class="alert alert-warning mt-4" role="alert">
                    Para poder dejar un comentario o valoración, por favor, <a href="/auth/login.php">inicia sesión</a>.
                </div>
            <?php endif; ?>

            <hr class="my-5" style="border-color: var(--c-border);">

            <h3 class="mt-4">Comentarios</h3>
            <div id="comments-list">
                <p>Cargando comentarios...</p>
            </div>

        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>GLAMUR CLUB</h3>
                    <p>Tu destino de lujo para perfumes y productos de belleza exclusivos.</p>
                </div>
                <div class="footer-col">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="/src/catalogo.html">Catálogo</a></li>
                        <li><a href="/src/crear-perfume.html">Crear Perfume</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contacto</h4>
                    <ul class="contact-info">
                        <li>📍 Calle Elegancia 123, Madrid</li>
                        <li>📞 +34 900 123 456</li>
                    </ul>
                </div>
                <div class="footer-col">
                    <p>© 2024 GLAMUR CLUB</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts Funcionales -->
    <script src="/public/js/script.js"></script>
    <script src="/public/js/comments.js" defer></script>

    <!-- Script Inline para comprobar favoritos al cargar -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const prodId = '<?php echo $product_id; ?>';
            const btn = document.getElementById('fav-btn-detail');

            if (typeof AppState !== 'undefined' && AppState.favorites && AppState.favorites.includes(prodId)) {
                btn.classList.add('active');
                btn.style.backgroundColor = '#ff6b6b';
                btn.style.color = 'white';
                btn.innerHTML = '❤️ Guardado';
            }
        });
    </script>
</body>

</html>