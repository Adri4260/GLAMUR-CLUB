<?php
// auth/logout.php
require_once "../includes/auth_check.php";

delete_auth_cookie(); // Esborrar la cookie personalitzada

session_unset();
session_destroy();

// Eliminar la cookie de sessió de PHP per neteja total
setcookie(session_name(), '', time() - 3600, "/");

header("Location: login.php");
exit;
