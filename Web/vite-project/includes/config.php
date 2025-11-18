<?php
// includes/config.php
// Configuración general del proyecto

// URL del JSON Server - detecta si está en Docker o local
if (getenv('JSON_SERVER_HOST')) {
    define('JSON_SERVER_URL', 'http://' . getenv('JSON_SERVER_HOST') . ':3000');
} else {
    // En Replit/local sin Docker, usa localhost
    define('JSON_SERVER_URL', 'http://localhost:3000');
}

// Configuración de cookies seguras
define('COOKIE_NAME', 'user_session');
define('COOKIE_EXPIRE', 3600);

// Opciones de cookie seguras
$cookie_options = [
    'expires' => time() + COOKIE_EXPIRE,
    'path' => '/',
    'httponly' => true,
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'samesite' => 'Lax'
];
define('COOKIE_OPTIONS', $cookie_options);

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 1 : 0);
ini_set('session.cookie_samesite', 'Lax');

// Zona horaria
date_default_timezone_set('Europe/Madrid');
