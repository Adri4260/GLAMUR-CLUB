<?php
require_once "../includes/auth_check.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  if (!$username || !$email || !$password) {
    $message = "Todos los campos son obligatorios.";
  } else {

    // ¿Usuario ya existe?
    $existing = get_user_by_username($username);

    if ($existing) {
      $message = "El nombre de usuario ya existe.";
    } else {
      // Crear usuario
      $data = [
        "nom_usuari" => $username,
        "email" => $email,
        "contrasenya" => password_hash($password, PASSWORD_DEFAULT),
        "data_registre" => date("c")
      ];

      create_user($data);

      header("Location: login.php?success=1");
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Registro</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <h2>Crear cuenta</h2>

  <?php if ($message): ?>
    <p style="color:red"><?= $message ?></p>
  <?php endif; ?>

  <form method="POST">
    <label>Nombre de usuario</label><br>
    <input type="text" name="username"><br><br>

    <label>Email</label><br>
    <input type="email" name="email"><br><br>

    <label>Contraseña</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit">Registrarse</button>
  </form>

  <p><a href="login.php">Ya tengo cuenta</a></p>
</body>

</html>