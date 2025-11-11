<?php
// auth/profile.php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/json_connect.php';

$user_id = require_login();
$user = find_user_by_id($user_id);

if (!$user) {
    echo "Error: usuario no encontrado.";
    exit;
}

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $cognoms = trim($_POST['cognoms'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email no válido.';
    } else {
        $update = [
            "nom" => $nom,
            "cognoms" => $cognoms,
            "email" => $email
        ];
        $resp = update_user($user_id, $update);
        if (in_array($resp['status'], [200, 201])) {
            $success = 'Perfil actualizado correctamente.';
            $user = find_user_by_id($user_id);
        } else {
            $errors[] = 'Error al actualizar (' . $resp['status'] . ')';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil de usuario</title>
</head>
<body>
  <h1>Perfil de <?= htmlspecialchars($user['nom_usuari']) ?></h1>

  <?php if ($success): ?><p style="color:green;"><?= htmlspecialchars($success) ?></p><?php endif; ?>
  <?php if ($errors): ?>
    <ul style="color:red;">
      <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="POST">
    <label>Nombre: <input type="text" name="nom" value="<?= htmlspecialchars($user['nom'] ?? '') ?>"></label><br>
    <label>Apellidos: <input type="text" name="cognoms" value="<?= htmlspecialchars($user['cognoms'] ?? '') ?>"></label><br>
    <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>"></label><br>
    <button type="submit">Guardar cambios</button>
  </form>

  <p><a href="logout.php">Cerrar sesión</a></p>
</body>
</html>
