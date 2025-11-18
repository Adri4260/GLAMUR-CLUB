<?php
// includes/auth_check.php
// Protección de rutas - verificar autenticación

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/session_store.php';

function require_login() {
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_validated'])) {
        return $_SESSION['user_id'];
    }
    
    if (isset($_COOKIE[COOKIE_NAME])) {
        $token = $_COOKIE[COOKIE_NAME];
        
        $session = find_session_by_token($token);
        
        if ($session) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $session['user_id'];
            $_SESSION['user_validated'] = true;
            return $session['user_id'];
        } else {
            $options = COOKIE_OPTIONS;
            $options['expires'] = time() - 3600;
            setcookie(COOKIE_NAME, '', $options);
        }
    }
    
    header('Location: /auth/login.php');
    exit;
}

function is_logged_in() {
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_validated'])) {
        return true;
    }
    
    if (isset($_COOKIE[COOKIE_NAME])) {
        $token = $_COOKIE[COOKIE_NAME];
        $session = find_session_by_token($token);
        
        if ($session) {
            $_SESSION['user_id'] = $session['user_id'];
            $_SESSION['user_validated'] = true;
            return true;
        }
    }
    
    return false;
}

function get_current_user_id() {
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_validated'])) {
        return $_SESSION['user_id'];
    }
    
    if (isset($_COOKIE[COOKIE_NAME])) {
        $token = $_COOKIE[COOKIE_NAME];
        $session = find_session_by_token($token);
        
        if ($session) {
            return $session['user_id'];
        }
    }
    
    return null;
}
