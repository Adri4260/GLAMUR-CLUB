<?php
// auth/login.php
require_once "../includes/auth_check.php";

// Si ja està loguejat, cap al perfil
if (is_logged_in()) {
  header("Location: profile.php");
  exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Cerca l'usuari al JSON (funció de auth_check.php)
  $user = get_user_by_username($username);

  if (!$user) {
    $message = "Usuario no encontrado.";
  } else {
    // Verificar contrasenya hash
    if (password_verify($password, $user["contrasenya"])) {

      session_regenerate_id(true);
      $_SESSION["user_id"] = $user["id"];

      // Guardar cookie per mantenir la sessió
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
<html>

<head>
  <title>Iniciar sesión</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
  <div class="auth-container">
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

      <button type="submit">Entrar</button>
    </form>

    <p>
      <a href="register.php">Crear cuenta</a>
    </p>
  </div>
</body>

</html>