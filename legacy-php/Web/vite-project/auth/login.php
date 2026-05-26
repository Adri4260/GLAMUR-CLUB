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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión | GLAMUR CLUB</title>

  <link rel="stylesheet" href="../public/css/bootstrap.min.css">

  <link rel="stylesheet" href="../public/css/styles.css">
  <link rel="stylesheet" href="../public/css/auth.css">
</head>

<body>
  <div class="auth-container">
    <h2 class="mb-4 text-center">Iniciar sesión</h2>

    <?php if (isset($_GET["success"])): ?>
      <div class="alert alert-success border-0 shadow-sm mb-3 py-2" role="alert" style="font-size: 0.9rem;">
        ✅ Registro completado. ¡Ya puedes entrar!
      </div>
    <?php endif; ?>

    <?php if ($message): ?>
      <div class="alert alert-danger border-0 shadow-sm mb-3 py-2" role="alert" style="font-size: 0.9rem;">
        ⚠️ <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="mb-3">
        <label for="username" class="form-label">Usuario</label>
        <input type="text" id="username" name="username" class="form-control bg-dark text-white border-secondary" required>
      </div>

      <div class="mb-4">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" id="password" name="password" class="form-control bg-dark text-white border-secondary" required>
      </div>

      <button type="submit" class="btn w-100 fw-bold py-2"
        style="background-color: var(--color-primary); color: var(--color-bg-main); border: none;">
        Entrar
      </button>
    </form>

    <p class="mt-4 text-center">
      <a href="register.php" class="text-decoration-none" style="color: var(--color-primary);">Crear cuenta</a>
    </p>
  </div>
</body>

</html>