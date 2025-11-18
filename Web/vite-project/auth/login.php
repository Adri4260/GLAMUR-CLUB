<?php
session_start();
require_once __DIR__ . '/../includes/json_connect.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_store.php';

cleanup_expired_sessions();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_usuari = trim($_POST['nom_usuari'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';

    if ($nom_usuari === '' || $contrasenya === '') {
        $errors[] = 'Rellena todos los campos.';
    } else {
        $user = find_user_by_username($nom_usuari);
        if ($user && password_verify($contrasenya, $user['contrasenya'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_validated'] = true;
            
            $token = create_session_token($user['id']);
            if ($token) {
                $options = COOKIE_OPTIONS;
                setcookie(COOKIE_NAME, $token, $options);
            }
            
            header('Location: /auth/profile.php');
            exit;
        } else {
            $errors[] = 'Usuario o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - GLAMUR CLUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>Iniciar Sesión</h1>
            <p>Bienvenido de nuevo a GLAMUR CLUB</p>
        </div>

        <div class="auth-body">
            <?php if ($errors): ?>
                <div class="message error">
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="nom_usuari">Nombre de usuario</label>
                    <input type="text" id="nom_usuari" name="nom_usuari" required>
                </div>

                <div class="form-group">
                    <label for="contrasenya">Contraseña</label>
                    <input type="password" id="contrasenya" name="contrasenya" required>
                </div>

                <button type="submit" class="btn">Entrar</button>
            </form>
        </div>

        <div class="auth-footer">
            ¿No tienes cuenta? <a href="register.php">Regístrate</a>
            <br>
            <a href="/" class="back-home">← Volver al inicio</a>
        </div>
    </div>
</body>
</html>
