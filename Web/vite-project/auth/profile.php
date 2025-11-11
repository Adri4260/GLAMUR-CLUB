<?php
require_once "../includes/auth_check.php";
require_login();

$user = get_user_by_id($_SESSION["user_id"]);
?>
<!DOCTYPE html>
<html>

<head>
  <title>Mi perfil</title>
  <link rel="stylesheet" href="../css/style.css">

</head>

<body>

  <h2>Perfil de usuario</h2>

  <p><strong>Usuario:</strong> <?= $user["nom_usuari"] ?></p>
  <p><strong>Email:</strong> <?= $user["email"] ?></p>
  <p><strong>Registrado:</strong> <?= $user["data_registre"] ?></p>

  <p><a href="logout.php">Cerrar sesión</a></p>

</body>

</html>