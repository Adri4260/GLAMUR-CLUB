<?php
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - GLAMUR CLUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>Mi Perfil</h1>
            <p>Hola, <?= htmlspecialchars($user['nom_usuari']) ?></p>
        </div>

        <div class="auth-body">
            <?php if ($success): ?>
                <div class="message success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if ($errors): ?>
                <div class="message error">
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="profile-info">
                <h2>Información de Usuario</h2>
                <p><strong>Usuario:</strong> <?= htmlspecialchars($user['nom_usuari']) ?></p>
                <p><strong>Registrado:</strong> <?= date('d/m/Y', strtotime($user['data_registre'])) ?></p>
            </div>

            <form method="POST">
                <div class="form-group">
                    <label for="nom">Nombre</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="cognoms">Apellidos</label>
                    <input type="text" id="cognoms" name="cognoms" value="<?= htmlspecialchars($user['cognoms'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                </div>

                <div class="profile-actions">
                    <button type="submit" class="btn">Guardar Cambios</button>
                    <a href="logout.php" class="btn btn-secondary">Cerrar Sesión</a>
                </div>
            </form>
        </div>

        <div class="auth-footer">
            <a href="/" class="back-home">← Volver al inicio</a>
        </div>
    </div>
</body>
</html>
