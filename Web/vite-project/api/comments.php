<?php
// Web/vite-project/api/comments.php
// Lógica para manejar la API de comentarios/valoraciones usando el JSON Server (vía json_connect.php)

header('Content-Type: application/json');
session_start();

// Incluir configuración de la base de datos (para obtener el username)
require_once '../includes/config.php';
// Incluir la librería de conexión al JSON Server (para json_get y json_patch)
require_once '../includes/json_connect.php';

// Endpoint base para los productos en el JSON Server
// Si tus productos están en http://.../products, esta es la clave
$products_endpoint = '/products';

// --- Funciones de utilidad ---

function send_response($success, $message, $data = [], $status_code = 200)
{
    http_response_code($status_code);
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit();
}

/**
 * Obtiene el nombre de usuario de la base de datos a partir del user_id.
 */
function get_username_from_db($conn, $userId)
{
    if (!isset($conn)) return 'Usuario Desconocido';
    try {
        // Usamos la conexión PDO ($conn) asumida de config.php para buscar en la tabla 'users'
        $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userData['username'] ?? 'Usuario Desconocido';
    } catch (PDOException $e) {
        error_log("Error fetching username from DB: " . $e->getMessage());
        return 'Usuario Desconocido (Error DB)';
    }
}

// ----------------------------------------------------
// Petición GET: Cargar comentarios y estadísticas (Flujo 1)
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
        send_response(false, 'Falta el ID del producto.');
    }

    $productId = (int)$_GET['product_id'];

    // 1. Obtener datos del producto específico desde el JSON Server (ej: /products/1)
    $product_endpoint = $products_endpoint . '/' . $productId;
    $product = json_get($product_endpoint);

    if ($product === null) {
        send_response(false, 'Producto no encontrado o error al contactar con JSON Server.', [], 404);
    }

    // Inicializar el array de comentarios 
    $comments = $product['comentarios'] ?? [];

    // 2. Obtener nombres de usuario de la DB (para mostrar quién comentó)
    $userIds = array_unique(array_column($comments, 'user_id'));
    $usernames = [];
    if (!empty($userIds) && isset($conn)) {
        $placeholders = implode(',', array_fill(0, count($userIds), '?'));
        try {
            $stmt_users = $conn->prepare("SELECT id, username FROM users WHERE id IN ($placeholders)");
            $stmt_users->execute($userIds);
            while ($row = $stmt_users->fetch(PDO::FETCH_ASSOC)) {
                $usernames[$row['id']] = $row['username'];
            }
        } catch (PDOException $e) {
            error_log("Error fetching usernames: " . $e->getMessage());
        }
    }

    // 3. Calcular estadísticas y añadir username a cada comentario
    $totalLikes = 0;
    $totalRatings = 0;
    $sumRatings = 0;
    $userHasCommented = false;
    $currentUserId = $_SESSION['user_id'] ?? null;
    $processedComments = [];

    foreach ($comments as $comment) {
        // Añadir el nombre de usuario
        $comment['username'] = $usernames[$comment['user_id']] ?? 'Usuario Desconocido';
        $processedComments[] = $comment;

        // Cálculo de estadísticas
        if (isset($comment['puntuacion']) && $comment['puntuacion'] !== null && is_numeric($comment['puntuacion'])) {
            $totalRatings++;
            $sumRatings += (int)$comment['puntuacion'];
        }
        if (isset($comment['megusta']) && $comment['megusta'] === true) {
            $totalLikes++;
        }
        // Comprobar si el usuario actual ya ha comentado
        if ($currentUserId !== null && (int)$comment['user_id'] === (int)$currentUserId) {
            $userHasCommented = true;
        }
    }

    $avgRating = ($totalRatings > 0) ? round($sumRatings / $totalRatings, 1) : 0;

    // Ordenar por fecha de creación (más reciente primero)
    usort($processedComments, function ($a, $b) {
        return strtotime($b['fecha_creacion']) - strtotime($a['fecha_creacion']);
    });

    send_response(true, 'Comentarios cargados correctamente.', [
        'comments' => $processedComments,
        'stats' => [
            'total_comments' => count($processedComments),
            'avg_rating' => $avgRating,
            'total_likes' => $totalLikes,
        ],
        'user_has_commented' => $userHasCommented
    ]);
}

// ----------------------------------------------------
// Petición POST: Añadir nuevo comentario/valoración (Flujo 2 y 3)
// ----------------------------------------------------
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 🔐 Comprobar autenticación (Requisito 4)
    if (!isset($_SESSION['user_id'])) {
        send_response(false, 'Debes iniciar sesión para comentar o valorar.', ['redirect' => '/auth/login.php'], 401);
    }

    $userId = (int)$_SESSION['user_id'];
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING) ?? '';
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    // filter_input(INPUT_POST, 'is_like_only') puede ser 'true' o nada.
    $isLikeOnly = filter_input(INPUT_POST, 'is_like_only', FILTER_SANITIZE_STRING) === 'true';

    // 🧱 Validación de datos (Requisito 7)
    if (!$productId) {
        send_response(false, 'ID de producto no válido.');
    }

    if (empty(trim($comment)) && ($rating === false || $rating === null) && !$isLikeOnly) {
        send_response(false, 'El comentario, la puntuación o el "Me gusta" es obligatorio.');
    }

    if ($rating !== false && ($rating < 1 || $rating > 5)) {
        send_response(false, 'La puntuación debe ser entre 1 y 5.');
    }

    // 1. Obtener datos del producto actual desde el JSON Server
    $product_endpoint = $products_endpoint . '/' . $productId;
    $product = json_get($product_endpoint);

    if ($product === null) {
        send_response(false, 'Producto no encontrado o error al obtener datos del JSON Server.', [], 404);
    }

    $comments = $product['comentarios'] ?? [];

    // 🚫 Prevenir doble post (Requisito 7)
    foreach ($comments as $c) {
        if (isset($c['user_id']) && (int)$c['user_id'] === $userId) {
            send_response(false, 'Ya has dejado una opinión o valoración para este producto. Solo se permite una por usuario.');
        }
    }

    // 2. Crear el nuevo objeto comentario (Requisito 7: Registrar fecha y usuario)
    $newComment = [
        'id' => uniqid(),
        'user_id' => $userId,
        'comentario' => trim($comment),
        'puntuacion' => $rating !== false ? $rating : null,
        'megusta' => $isLikeOnly,
        'fecha_creacion' => date('Y-m-d H:i:s')
    ];

    // 3. Añadir el nuevo comentario
    $comments[] = $newComment;

    // 4. Enviar el PATCH al JSON Server (Actualiza solo el array de comentarios)
    $patchData = ['comentarios' => $comments];

    // Usamos json_patch (función de json_connect.php)
    $updatedProduct = json_patch($product_endpoint, $patchData);

    if ($updatedProduct !== null) {

        // Obtener el username para devolver el comentario completo al frontend
        $username = get_username_from_db($conn, $userId);
        $newComment['username'] = $username;

        send_response(true, 'Comentario/Valoración añadido con éxito.', ['new_comment' => $newComment]);
    } else {
        send_response(false, 'Error al guardar el comentario en JSON Server.', [], 500);
    }
} else {
    // Método no permitido
    send_response(false, 'Método no permitido.', [], 405);
}
