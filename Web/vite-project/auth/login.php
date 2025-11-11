<?php
require_once "../includes/auth_check.php";

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

    <button type="submit">Entrar</button>
  </form>

  <p><a href="register.php">Crear cuenta</a></p>
</body>

</html>