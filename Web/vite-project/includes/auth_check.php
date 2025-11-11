<?php
// auth_check.php
session_start();
require_once "json_connect.php";

/**
 * ¿El usuario está logueado?
 */
function is_logged_in()
{
    return isset($_SESSION["user_id"]);
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

/**
 * Buscar usuario por nombre de usuario
 */
function get_user_by_username($username)
{
    $result = json_get("/usuarios?nom_usuari=" . urlencode($username));
    return $result ? $result[0] : null;
}

/**
 * Buscar usuario por ID
 */
function get_user_by_id($id)
{
    return json_get("/usuarios/" . $id);
}

/**
 * Registrar nuevo usuario
 */
function create_user($data)
{
    return json_post("/usuarios", $data);
}

/**
 * Actualizar usuario existente
 */
function update_user($id, $data)
{
    return json_patch("/usuarios/" . $id, $data);
}
