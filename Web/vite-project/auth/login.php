<?php
// auth/login.php
require_once "../includes/auth_check.php";

if (is_logged_in()) {
  header("Location: profile.php");
  exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"] ?? "");
  $password = trim($_POST["password"] ?? "");

  $user = get_user_by_username($username);

  if (!$user) {
    $message = "Usuario no encontrado.";
  } else {
    if (password_verify($password, $user["contrasenya"])) {
      session_regenerate_id(true);
      $_SESSION["user_id"] = $user["id"];
      set_auth_cookie($user["id"]);
      header("Location: profile.php");
      exit;
    } else {
      $message = "Contraseña incorrecta.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
  <meta charset="UTF-8">
  <title>Iniciar sesión | GLAMUR CLUB</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../public/css/styles.css">
  <link rel="stylesheet" href="../public/css/auth.css">
</head>

<body>
  <div class="auth-container">
    <h2>Iniciar sesión</h2>

    <?php if (isset($_GET["success"])): ?>
      <div class="alert alert-success shadow-sm mb-3" role="alert">
        ✅ Registro completado. ¡Ya puedes entrar!
      </div>
    <?php endif; ?>

    <?php if ($message): ?>
      <div class="alert alert-danger shadow-sm mb-3" role="alert">
        ⚠️ <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <label for="username">Usuario</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" required>

      <button type="submit" class="btn btn-primary w-100 mt-2">Entrar</button>
    </form>

    <p class="mt-4">
      <a href="register.php">Crear cuenta</a>
    </p>
  </div>
</body>

</html>