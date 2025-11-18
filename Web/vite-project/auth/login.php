<?php
require_once "../includes/auth_check.php";

// Si el usuario ya está logueado, redirigir a su perfil
if (is_logged_in()) {
  header("Location: /auth/profile.php");
  exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  $user = get_user_by_username($username);

  if (!$user) {
    $message = "Usuario no encontrado.";
  } else {
    if (password_verify($password, $user["contrasenya"])) {

      session_regenerate_id(true);
      $_SESSION["user_id"] = $user["id"];
      set_auth_cookie($user["id"]); // ✅ AÑADIDO: Guardar la cookie de identificación

      header("Location: /auth/profile.php");
      exit;
    } else {
      $message = "Contraseña incorrecta.";
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Iniciar sesión</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>

  <h2>Iniciar sesión</h2>

  <?php if (isset($_GET["success"])): ?>
    <p style="color:green">Registro completado. ¡Ya puedes iniciar sesión!</p>
  <?php endif; ?>

  <?php if ($message): ?>
    <p style="color:red"><?= $message ?></p>
  <?php endif; ?>

  <form method="POST">
    <label>Usuario</label><br>
    <input type="text" name="username"><br><br>

    <label>Contraseña</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
      </svg>
      Entrar
    </button>
  </form>

  <p><a href="register.php">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
        <circle cx="9" cy="7" r="4" />
      </svg>
      Crear cuenta
    </a></p>
</body>

</html>