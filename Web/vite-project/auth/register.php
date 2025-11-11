<?php
// auth/register.php
session_start();
require_once __DIR__ . '/../includes/json_connect.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_usuari = trim($_POST['nom_usuari'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';
    $nom = trim($_POST['nom'] ?? '');
    $cognoms = trim($_POST['cognoms'] ?? '');

    if ($nom_usuari === '' || $email === '' || $contrasenya === '') {
        $errors[] = 'Completa todos los campos obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email no válido.';
    } else {
        $existing = find_user_by_username($nom_usuari);
        if ($existing) {
            $errors[] = 'El usuario ya existe.';
        } else {
            $hashed = password_hash($contrasenya, PASSWORD_DEFAULT);
            $data = [
                "nom_usuari" => $nom_usuari,
                "contrasenya" => $hashed,
                "email" => $email,
                "nom" => $nom,
                "cognoms" => $cognoms,
                "data_registre" => date('c')
            ];
            $resp = create_user($data);
            if (in_array($resp['status'], [201, 200])) {
                $success = 'Usuario registrado correctamente. Puedes iniciar sesión.';
            } else {
                $errors[] = 'Error en el registro (' . $resp['status'] . ')';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de usuario</title>
</head>
<body>
  <h1>Registro</h1>

  <?php if ($success): ?>
    <p style="color:green"><?= htmlspecialchars($success) ?></p>
    <a href="login.php">Iniciar sesión</a>
  <?php endif; ?>

  <?php if ($errors): ?>
    <ul style="color:red;">
      <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="POST">
    <label>Nombre de usuario: <input type="text" name="nom_usuari" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Contraseña: <input type="password" name="contrasenya" required></label><br>
    <label>Nombre: <input type="text" name="nom"></label><br>
    <label>Apellidos: <input type="text" name="cognoms"></label><br>
    <button type="submit">Registrar</button>
  </form>
</body>
</html>
