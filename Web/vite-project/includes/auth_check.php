<?php
// includes/auth_check.php

// Incloem la connexió al JSON Server
require_once "json_connect.php";

// Iniciem sessió PHP si no està iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- GESTIÓ DE COOKIES/SESSIÓ ---

function set_auth_cookie(int $user_id): void
{
    // Cookie vàlida per 1 hora ("/")
    setcookie('user_id', $user_id, time() + 3600, "/");
}

function delete_auth_cookie(): void
{
    // Caducar la cookie
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
        $user_id = (int)$_COOKIE['user_id'];

        // Verifiquem si l'usuari encara existeix
        $user = get_user_by_id($user_id);

        if ($user) {
            // Cookie vàlida: restaurem la sessió PHP
            $_SESSION["user_id"] = $user_id;
            return true;
        } else {
            // Cookie invàlida (usuari esborrat?), l'eliminem
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

// --- FUNCIONS MODEL USUARIS (JSON SERVER) ---

/**
 * Cerca un usuari pel nom. Retorna array o null.
 */
function get_user_by_username(string $username): ?array
{
    // Endpoint amb filtre: /usuaris?nom_usuari=...
    $endpoint = "/usuaris?nom_usuari=" . urlencode($username);
    $results = json_get($endpoint);

    if (!empty($results) && is_array($results)) {
        // Retornem el primer resultat trobat
        return $results[0];
    }
    return null;
}

/**
 * Cerca un usuari per ID.
 */
function get_user_by_id($id)
{
    return json_get("/usuaris/" . $id);
}

/**
 * Crea un nou usuari.
 */
function create_user(array $data): ?array
{
    return json_post("/usuaris", $data);
}

/**
 * Actualitza un usuari.
 */
function update_user($id, $data)
{
    return json_patch("/usuaris/" . $id, $data);
}
