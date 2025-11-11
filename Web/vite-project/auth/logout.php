<?php
// auth/logout.php
session_start();
require_once __DIR__ . '/../includes/config.php';

setcookie(COOKIE_NAME, '', time() - 3600, "/");
session_destroy();

header('Location: /auth/login.php');
exit;
