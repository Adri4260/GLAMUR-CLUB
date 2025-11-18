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

    <button type="submit">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
        <path d="M12 2v20M2 12h20" />
      </svg>
      Registrarse
    </button>
  </form>

  <p><a href="login.php">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
      </svg>
      Ya tengo cuenta
    </a></p>
</body>

</html>