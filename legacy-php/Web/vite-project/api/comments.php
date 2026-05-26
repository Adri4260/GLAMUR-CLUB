<?php
// Web/vite-project/api/comments.php

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

require_once '../includes/config.php';
require_once '../includes/json_connect.php';

$valoraciones_endpoint = '/valoracions';
$productos_endpoint = '/productes';

function send_response($success, $message, $data = [], $status_code = 200) {
    http_response_code($status_code);
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit();
}

/**
 * Función para obtener el mapa de usuarios desde datos.json local
 * Devuelve un array asociativo: [id_usuario => nombre_usuario]
 */
function get_users_map() {
    $json_path = dirname(__DIR__) . '/public/data/datos.json';
    $json_content = @file_get_contents($json_path);
    $users_map = [];
    
    if ($json_content) {
        $data = json_decode($json_content, true);
        $usuarios = $data['usuaris'] ?? [];
        foreach ($usuarios as $u) {
            // Guardamos tanto por ID numérico como string para asegurar
            $name = $u['nom_usuari'] ?? 'Usuario';
            $users_map[(string)$u['id']] = $name;
        }
    }
    return $users_map;
}

// GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $productId = filter_input(INPUT_GET, 'product_id', FILTER_DEFAULT);
    if (!$productId) send_response(false, 'Falta ID.');

    $query = $valoraciones_endpoint . '?product_id=' . urlencode($productId);
    $comments = json_get($query) ?? [];

    // Cargamos el mapa de usuarios solo si hay comentarios
    $usersMap = !empty($comments) ? get_users_map() : [];

    $stats = ['total_comments' => 0, 'avg_rating' => 0, 'total_likes' => 0];
    $sumRatings = 0;
    $totalRatings = 0;
    $userHasCommented = false;
    $currentUserId = $_SESSION['user_id'] ?? null;
    $processedComments = [];

    foreach ($comments as $c) {
        // Lógica de nombre de usuario:
        // 1. Si ya viene grabado en el comentario (nuevos), úsalo.
        // 2. Si no, búscalo en el mapa de usuarios del JSON (antiguos).
        // 3. Si no, pon 'Usuario'.
        if (!empty($c['username'])) {
            $username = $c['username'];
        } else {
            $uid = (string)($c['user_id'] ?? '');
            $username = $usersMap[$uid] ?? 'Usuario';
        }
        
        $c['username'] = $username;
        $processedComments[] = $c;

        if (!empty($c['puntuacion']) && is_numeric($c['puntuacion'])) {
            $totalRatings++;
            $sumRatings += (int)$c['puntuacion'];
        }
        if (!empty($c['megusta']) && $c['megusta'] !== 'false') {
            $stats['total_likes']++;
        }
        if ($currentUserId && isset($c['user_id']) && (string)$c['user_id'] === (string)$currentUserId) {
            $userHasCommented = true;
        }
    }

    if ($totalRatings > 0) {
        $stats['avg_rating'] = round($sumRatings / $totalRatings, 1);
    }
    $stats['total_comments'] = count($processedComments);

    // Ordenar por fecha desc
    usort($processedComments, fn($a, $b) => strtotime($b['fecha_creacion'] ?? 'now') - strtotime($a['fecha_creacion'] ?? 'now'));

    send_response(true, 'Cargado', [
        'comments' => $processedComments,
        'stats' => $stats,
        'user_has_commented' => $userHasCommented
    ]);
}

// POST
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) send_response(false, 'No logueado.', [], 401);

    $userId = $_SESSION['user_id'];
    // Intentamos obtener el nombre de usuario de la sesión, si no, del mapa JSON
    $username = $_SESSION['username'] ?? null;
    
    if (!$username) {
        $usersMap = get_users_map();
        $username = $usersMap[(string)$userId] ?? 'Usuario';
    }

    $productId = filter_input(INPUT_POST, 'product_id', FILTER_DEFAULT);
    $comment = htmlspecialchars($_POST['comment'] ?? '', ENT_QUOTES, 'UTF-8');
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT) ?: null;
    $isLike = ($_POST['is_like_only'] ?? 'false') === 'true';

    // Verificar duplicados
    $check = json_get($valoraciones_endpoint . '?product_id=' . urlencode($productId) . '&user_id=' . $userId);
    if (!empty($check)) send_response(false, 'Ya has valorado este producto.');

    $newReview = [
        'id' => uniqid('v'),
        'user_id' => $userId,
        'username' => $username, // Guardamos el nombre para el futuro
        'product_id' => $productId,
        'comentario' => $comment,
        'puntuacion' => $rating,
        'megusta' => $isLike,
        'fecha_creacion' => date('Y-m-d H:i:s')
    ];

    // POST al JSON Server
    $url = JSON_SERVER_URL . $valoraciones_endpoint;
    $opts = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($newReview)
        ]
    ];
    $context = stream_context_create($opts);
    $result = file_get_contents($url, false, $context);

    if ($result) {
        send_response(true, 'Guardado.', ['new_comment' => $newReview]);
    } else {
        send_response(false, 'Error al conectar con JSON Server.');
    }
}