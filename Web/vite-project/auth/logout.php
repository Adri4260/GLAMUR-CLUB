<?php
// auth/logout.php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_store.php';

if (isset($_COOKIE[COOKIE_NAME])) {
    $token = $_COOKIE[COOKIE_NAME];
    delete_session_by_token($token);
}

$options = COOKIE_OPTIONS;
$options['expires'] = time() - 3600;
setcookie(COOKIE_NAME, '', $options);

session_destroy();

header('Location: /auth/login.php');
exit;
