<?php
// includes/auth_check.php

require_once "json_connect.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- GESTIÓ DE COOKIES/SESSIÓ ---

/**
 * Estableix la cookie. Acceptem string o int perquè JSON Server pot generar IDs de text.
 */
function set_auth_cookie($user_id): void
{
    // Eliminem el tipus 'int' de la definició per permetre strings (ex: "5b2a")
    setcookie('user_id', (string)$user_id, time() + 3600, "/");
}

function delete_auth_cookie(): void
{
    setcookie('user_id', '', time() - 3600, "/");
}

function is_logged_in(): bool
{
    // 1. Comprovar Sessió PHP
    if (isset($_SESSION["user_id"])) {
        return true;
    }

    // 2. Comprovar Cookie de persistència
    if (isset($_COOKIE['user_id'])) {
        // NO forcem (int) aquí, deixem que sigui string
        $user_id = $_COOKIE['user_id'];

        // Verifiquem si l'usuari existeix
        $user = get_user_by_id($user_id);

        if ($user) {
            // Cookie vàlida: restaurem la sessió PHP
            $_SESSION["user_id"] = $user_id;
            return true;
        } else {
            delete_auth_cookie();
        }
    }

    return false;
}

function require_login()
{
    if (!is_logged_in()) {
        header("Location: /auth/login.php");
        exit;
    }
}

// --- FUNCIONS MODEL USUARIS ---

function get_user_by_username(string $username): ?array
{
    $endpoint = "/usuaris?nom_usuari=" . urlencode($username);
    $results = json_get($endpoint);

    if (!empty($results) && is_array($results)) {
        return $results[0];
    }
    return null;
}

function get_user_by_id($id)
{
    // Assegurem que l'ID es tracta com a part de la URL
    return json_get("/usuaris/" . urlencode((string)$id));
}

function create_user(array $data): ?array
{
    return json_post("/usuaris", $data);
}

function update_user($id, $data)
{
    return json_patch("/usuaris/" . urlencode((string)$id), $data);
}
