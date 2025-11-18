<?php
require_once "../includes/auth_check.php";

delete_auth_cookie(); // ✅ AÑADIDO: Eliminar la cookie de identificación

session_unset();
session_destroy();

// Eliminar la cookie de sesión de PHP (Buena práctica)
setcookie(session_name(), '', time() - 3600, "/");

header("Location: login.php");
exit;
