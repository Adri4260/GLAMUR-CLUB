<?php
// Web/vite-project/src/perfume.php

// 1. Iniciar sesión
session_start();

// 2. Incluir archivos de configuración necesarios
// 'config.php' se usa para la conexión a la DB (para obtener el nombre de usuario)
require_once '../includes/config.php';
// 'json_connect.php' DEBE contener ahora las funciones json_get(), json_post(), etc.
require_once '../includes/json_connect.php';

// --- 3. Obtención del ID y Carga del Producto ---

// Obtener ID del producto de la URL (ej: /src/perfume.php?id=2)
$product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$product_id) {
    http_response_code(400);
    echo "<h1>Error 400: ID de producto no proporcionado.</h1>";
    exit;
}

// *** CORRECCIÓN CLAVE: Usar json_get para obtener UN solo producto por ID ***
// Asumimos que el endpoint para un producto específico es /products/{id}
$product_endpoint = '/products/' . $product_id;
$product = json_get($product_endpoint);

if ($product === null) {
    // Manejo de error si el producto no existe o el JSON Server falla
    http_response_code(404);
    echo "<h1>Error 404: Producto no encontrado (ID: " . htmlspecialchars($product_id) . ").</h1>";
    exit;
}

// Variables del producto para mostrar en la vista
$nombre_producto = htmlspecialchars($product['nombre'] ?? 'Producto Desconocido');
$descripcion_producto = htmlspecialchars($product['descripcion'] ?? 'Sin descripción.');
$precio_producto = number_format($product['precio'] ?? 0, 2, ',', '.') . ' €';
$imagen_producto = htmlspecialchars($product['imagen'] ?? '/public/img/default.jpg');
?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nombre_producto; ?> - GLAMUR-CLUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/styles.css">
    <link rel="stylesheet" href="/public/css/perfume.css">
</head>

<body>

    <header class="bg-dark text-white p-3">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h3"><a href="/index.php" class="text-white text-decoration-none">GLAMUR-CLUB</a></h1>
            <nav>
                <a href="/index.php" class="text-white mx-2">Inici</a>
                <a href="/src/catalogo.html" class="text-white mx-2">Catàleg</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="mx-2">Hola, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Usuari'); ?></span>
                    <a href="/auth/profile.php" class="btn btn-sm btn-outline-light">Perfil</a>
                    <a href="/auth/logout.php" class="btn btn-sm btn-danger">Tancar Sessió</a>
                <?php else: ?>
                    <a href="/auth/login.php" class="btn btn-sm btn-primary mx-2">Iniciar Sessió</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container my-5">

        <div class="row product-detail-section">
            <div class="col-md-5">
                <img src="<?php echo $imagen_producto; ?>" alt="<?php echo $nombre_producto; ?>" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-7">
                <h1 class="display-4"><?php echo $nombre_producto; ?></h1>
                <p class="lead text-muted"><?php echo $descripcion_producto; ?></p>
                <h2 class="price text-primary mb-4"><?php echo $precio_producto; ?></h2>

                <p>Categoría: <?php echo htmlspecialchars($product['categoria'] ?? 'Sense categoria'); ?></p>
                <p>Stock: <span class="badge bg-<?php echo ($product['stock'] ?? 0) > 0 ? 'success' : 'danger'; ?>"><?php echo htmlspecialchars($product['stock'] ?? 0); ?> Unitats</span></p>

                <div class="mt-4">
                    <button class="btn btn-success btn-lg">Afegir a la Cistella</button>
                    <button class="btn btn-outline-danger btn-lg">❤️ Favorits</button>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <section id="product-reviews" class="product-reviews">
            <h2>Comentaris i Valoracions</h2>

            <input type="hidden" id="product-id" value="<?php echo htmlspecialchars($product_id); ?>">

            <div id="comment-stats" class="mb-4 p-3 border rounded bg-light">
                <p>Carregant estadístiques...</p>
            </div>

            <div class="mb-4">
                <button id="like-button" class="btn btn-outline-success">
                    <span role="img" aria-label="Me gusta">👍</span> M’agrada el producte
                </button>
            </div>

            <?php if (isset($_SESSION['user_id'])): // Solo para usuarios autenticados 
            ?>
                <h3 class="mt-4">Deixa la teva opinió</h3>

                <form id="comment-form" method="POST" action="/api/comments.php" class="mb-5 p-4 border rounded">
                    <div class="mb-3">
                        <label for="comment-text" class="form-label">Comentari (màx. 500 caràcters)</label>
                        <textarea class="form-control" id="comment-text" name="comment" rows="3" maxlength="500"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="comment-rating" class="form-label">Puntuació (1-5)</label>
                        <select class="form-select" id="comment-rating" name="rating">
                            <option value="">Sense puntuació</option>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> Estrelles</option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar Comentari</button>
                </form>
            <?php else: ?>
                <div id="auth-warning" class="alert alert-warning mt-4" role="alert">
                    Per poder deixar un comentari o valoració, si us plau, <a href="/auth/login.php">inicia sessió</a>.
                </div>
            <?php endif; ?>

            <hr class="my-5">

            <h3 class="mt-4">Comentaris (Més recents primer)</h3>
            <div id="comments-list">
                <p>Carregant comentaris...</p>
            </div>

        </section>
    </main>

    <footer class="bg-dark text-white mt-5 p-4 text-center">
        <p>&copy; <?php echo date('Y'); ?> GLAMUR-CLUB. Tots els drets reservats.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/comments.js" defer></script>
</body>

</html>