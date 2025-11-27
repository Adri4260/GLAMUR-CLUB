<?php
// Web/vite-project/api/comments.php

// Configuración de errores
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

require_once '../includes/config.php';
require_once '../includes/json_connect.php';

// Endpoints del JSON Server
$valoraciones_endpoint = '/valoracions';
$productos_endpoint = '/productes';

// --- Funciones de utilidad ---

function send_response($success, $message, $data = [], $status_code = 200)
{
    http_response_code($status_code);
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit();
}

// Función auxiliar POST (por si json_connect.php no la tiene)
if (!function_exists('json_post_data')) {
    function json_post_data($endpoint, $data) {
        $url = JSON_SERVER_URL . $endpoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}

// ----------------------------------------------------
// GET: Cargar comentarios (Leemos de /valoracions)
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $productId = filter_input(INPUT_GET, 'product_id', FILTER_DEFAULT);

    if (!$productId) send_response(false, 'Falta ID.');

    // 1. Pedimos al JSON Server SOLO las valoraciones de este producto
    $query = $valoraciones_endpoint . '?product_id=' . urlencode($productId);
    $comments = json_get($query);

    if ($comments === null) {
        $comments = [];
    }

    // 3. Procesar estadísticas y formatear
    $totalLikes = 0;
    $totalRatings = 0;
    $sumRatings = 0;
    $userHasCommented = false;
    $currentUserId = $_SESSION['user_id'] ?? null;
    $processedComments = [];

    foreach ($comments as $comment) {
        // Asignar nombre de usuario (si se guardó en el JSON, sino genérico)
        // Ya no consultamos SQL.
        $comment['username'] = $comment['username'] ?? 'Usuario'; 
        $processedComments[] = $comment;

        // Calcular medias
        if (isset($comment['puntuacion']) && is_numeric($comment['puntuacion']) && $comment['puntuacion'] > 0) {
            $totalRatings++;
            $sumRatings += (int)$comment['puntuacion'];
        }
        // Calcular Likes
        if (isset($comment['megusta']) && ($comment['megusta'] === true || $comment['megusta'] === "true" || $comment['megusta'] === 1)) {
            $totalLikes++;
        }
        // Verificar si el usuario actual ya participó
        if ($currentUserId && isset($comment['user_id']) && (string)$comment['user_id'] === (string)$currentUserId) {
            $userHasCommented = true;
        }
    }

    $avgRating = ($totalRatings > 0) ? round($sumRatings / $totalRatings, 1) : 0;
    
    // Ordenar por fecha (más reciente primero)
    usort($processedComments, function($a, $b) {
        $dateA = isset($a['fecha_creacion']) ? strtotime($a['fecha_creacion']) : 0;
        $dateB = isset($b['fecha_creacion']) ? strtotime($b['fecha_creacion']) : 0;
        return $dateB - $dateA;
    });

    send_response(true, 'Cargado.', [
        'comments' => $processedComments,
        'stats' => [
            'total_comments' => count($processedComments),
            'avg_rating' => $avgRating,
            'total_likes' => $totalLikes
        ],
        'user_has_commented' => $userHasCommented
    ]);
}

// ----------------------------------------------------
// POST: Guardar comentario (Escribimos en /valoracions)
// ----------------------------------------------------
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) send_response(false, 'No logueado.', [], 401);

    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username'] ?? 'Usuario'; // Guardamos el nombre de la sesión
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_DEFAULT);
    
    // Sanitización
    $rawComment = $_POST['comment'] ?? '';
    $comment = htmlspecialchars($rawComment, ENT_QUOTES, 'UTF-8');
    
    $ratingVal = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $rating = ($ratingVal && $ratingVal > 0) ? $ratingVal : null;
    
    $isLikeOnly = ($_POST['is_like_only'] ?? 'false') === 'true';

    if (!$productId) send_response(false, 'ID inválido.');

    // 1. Verificar duplicados (GET previo)
    $checkQuery = $valoraciones_endpoint . '?product_id=' . urlencode($productId) . '&user_id=' . urlencode($userId);
    $existing = json_get($checkQuery);
    
    if (!empty($existing)) {
        send_response(false, 'Ya has opinado sobre este producto.');
    }

    // 2. Crear el objeto para /valoracions
    // AÑADIMOS 'username' AQUÍ para no depender de SQL al leer
    $newReview = [
        'id' => uniqid('V'),
        'user_id' => $userId,
        'username' => $username, 
        'product_id' => $productId,
        'comentario' => $comment,
        'puntuacion' => $rating,
        'megusta' => $isLikeOnly,
        'fecha_creacion' => date('Y-m-d H:i:s')
    ];

    // 3. Enviar POST a /valoracions
    if (function_exists('json_post')) {
        $result = json_post($valoraciones_endpoint, $newReview);
    } else {
        $result = json_post_data($valoraciones_endpoint, $newReview);
    }

    if ($result !== null) {
        send_response(true, 'Guardado correctamente.', ['new_comment' => $newReview]);
    } else {
        send_response(false, 'Error al guardar en JSON Server.');
    }
}