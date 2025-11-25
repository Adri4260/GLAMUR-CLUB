<?php
// auth/profile.php
require_once "../includes/auth_check.php";

// Verifica si l'usuari està loguejat, si no, redirigeix a login.php
require_login();

// Obtenir dades de l'usuari actual
$user = get_user_by_id($_SESSION["user_id"]);

if (!$user) {
  echo "Error: No se han podido cargar los datos del usuario.";
  exit;
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Mi perfil</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <div style="padding: 20px;">
    <h2>Perfil de usuario</h2>

    <p><strong>Usuario:</strong> <?= htmlspecialchars($user["nom_usuari"]) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user["email"]) ?></p>
    <p><strong>Fecha Registro:</strong> <?= htmlspecialchars($user["data_registre"]) ?></p>

    <br>
    <p>
      <a href="logout.php">Cerrar sesión</a> |
      <a href="../index.php">Volver al inicio</a>
    </p>
  </div>
</body>

</html>