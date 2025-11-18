<?php
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - GLAMUR CLUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>Crear Cuenta</h1>
            <p>Únete a GLAMUR CLUB</p>
        </div>

        <div class="auth-body">
            <?php if ($success): ?>
                <div class="message success">
                    <?= htmlspecialchars($success) ?>
                    <br><br>
                    <a href="login.php" style="color: #155724; font-weight: 600;">Ir a Iniciar Sesión</a>
                </div>
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

            <form method="POST">
                <div class="form-group">
                    <label for="nom_usuari">Nombre de usuario *</label>
                    <input type="text" id="nom_usuari" name="nom_usuari" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="contrasenya">Contraseña *</label>
                    <input type="password" id="contrasenya" name="contrasenya" required>
                </div>

                <div class="form-group">
                    <label for="nom">Nombre</label>
                    <input type="text" id="nom" name="nom">
                </div>

                <div class="form-group">
                    <label for="cognoms">Apellidos</label>
                    <input type="text" id="cognoms" name="cognoms">
                </div>

                <button type="submit" class="btn">Registrarse</button>
            </form>
        </div>

        <div class="auth-footer">
            ¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a>
            <br>
            <a href="/" class="back-home">← Volver al inicio</a>
        </div>
    </div>
</body>
</html>
