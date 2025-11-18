<?php
// auth_check.php

// 1. INICIAR SESIÓN Y RUTA
// Ajustado a la estructura auth/ -> ../includes/
require_once "../includes/json_connect.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// --- 2. GESTIÓN DE COOKIES/SESIÓN (Requisito del proyecto) ---

/**
 * Establece la cookie de identificación
 */
function set_auth_cookie(int $user_id): void
{
    // Duración de la cookie: 1 hora (3600 segundos) en todo el sitio ("/")
    setcookie('user_id', $user_id, time() + 3600, "/");
}

/**
 * Elimina la cookie de identificación
 */
function delete_auth_cookie(): void
{
    // Poner la cookie en el pasado para eliminarla
    setcookie('user_id', '', time() - 3600, "/");
}


/**
 * ¿El usuario está logueado por Sesión o Cookie?
 */
function is_logged_in(): bool
{
    // 1. Comprobar Sesión PHP
    if (isset($_SESSION["user_id"])) {
        return true;
    }

    // 2. Comprobar Cookie de persistencia
    if (isset($_COOKIE['user_id'])) {
        $user_id = (int)$_COOKIE['user_id'];
        $user = get_user_by_id($user_id);

        if ($user) {
            // Cookie válida: inicializamos la sesión PHP
            $_SESSION["user_id"] = $user_id;
            return true;
        } else {
            // Cookie no válida (ej. usuario borrado), la eliminamos
            delete_auth_cookie();
        }
    }

    return false;
}

/**
 * Redirige si NO está logueado
 */
function require_login()
{
    if (!is_logged_in()) {
        header("Location: /auth/login.php");
        exit;
    }
}


// --- 3. FUNCIONES DE INTERACCIÓN CON JSON SERVER (Endpoint Corregido: /usuaris) ---

/**
 * Buscar usuario por nombre de usuario
 */
function get_user_by_username($username)
{
    // Endpoint CORREGIDO: /usuaris
    $result = json_get("/usuaris?nom_usuari=" . urlencode($username));
    return is_array($result) && !empty($result) ? $result[0] : null;
}

/**
 * Buscar usuario por ID
 */
function get_user_by_id($id)
{
    // Endpoint CORREGIDO: /usuaris
    return json_get("/usuaris/" . $id);
}

/**
 * Registrar nuevo usuario
 */
function create_user($data)
{
    // Endpoint CORREGIDO: /usuaris
    return json_post("/usuaris", $data);
}

/**
 * Actualizar usuario existente
 */
function update_user($id, $data)
{
    // Endpoint CORREGIDO: /usuaris
    return json_patch("/usuaris/" . $id, $data);
}
