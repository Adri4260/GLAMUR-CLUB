<?php
// includes/session_store.php
// Almacenamiento seguro de tokens de sesión en servidor

define('SESSION_STORE_FILE', __DIR__ . '/../data/sessions.php');

function init_session_store() {
    if (!file_exists(SESSION_STORE_FILE)) {
        file_put_contents(SESSION_STORE_FILE, '<?php return []; ?>');
        chmod(SESSION_STORE_FILE, 0600);
    }
}

function load_sessions() {
    init_session_store();
    return include SESSION_STORE_FILE;
}

function save_sessions($sessions) {
    $php_content = '<?php return ' . var_export($sessions, true) . '; ?>';
    file_put_contents(SESSION_STORE_FILE, $php_content);
}

function create_session_token($user_id) {
    $token = bin2hex(random_bytes(32));
    $sessions = load_sessions();
    
    $sessions[$token] = [
        'user_id' => $user_id,
        'created_at' => time(),
        'expires_at' => time() + COOKIE_EXPIRE
    ];
    
    save_sessions($sessions);
    
    return $token;
}

function find_session_by_token($token) {
    $sessions = load_sessions();
    
    if (isset($sessions[$token])) {
        $session = $sessions[$token];
        
        if ($session['expires_at'] > time()) {
            return $session;
        } else {
            delete_session_by_token($token);
        }
    }
    
    return null;
}

function delete_session_by_token($token) {
    $sessions = load_sessions();
    
    if (isset($sessions[$token])) {
        unset($sessions[$token]);
        save_sessions($sessions);
    }
}

function cleanup_expired_sessions() {
    $sessions = load_sessions();
    $current_time = time();
    $cleaned = false;
    
    foreach ($sessions as $token => $session) {
        if ($session['expires_at'] < $current_time) {
            unset($sessions[$token]);
            $cleaned = true;
        }
    }
    
    if ($cleaned) {
        save_sessions($sessions);
    }
}
