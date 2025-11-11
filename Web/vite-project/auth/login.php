<?php
// auth/login.php
session_start();
require_once __DIR__ . '/../includes/json_connect.php';
require_once __DIR__ . '/../includes/config.php';

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
            setcookie(COOKIE_NAME, $user['id'], time() + COOKIE_EXPIRE, "/");
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
  <title>Iniciar sesión</title>
</head>
<body>
  <h1>Iniciar sesión</h1>

  <?php if ($errors): ?>
    <ul style="color:red;">
      <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="POST">
    <label>Usuario: <input type="text" name="nom_usuari" required></label><br>
    <label>Contraseña: <input type="password" name="contrasenya" required></label><br>
    <button type="submit">Entrar</button>
  </form>

  <p>¿No tienes cuenta? <a href="register.php">Regístrate</a></p>
</body>
</html>
