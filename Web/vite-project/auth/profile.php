<?php
require_once "../includes/auth_check.php";
require_login(); // ✅ Usa la función require_login() que ahora comprueba sesión y cookie.

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

  <p><a href="logout.php">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
        <path d="M10 20H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h6M17 16l4-4-4-4M21 12H9" />
      </svg>
      Cerrar sesión
    </a></p>

</body>

</html>