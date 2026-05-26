<?php
require_once "../includes/auth_check.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  // 1. Recollim els nous camps del formulari
  $nombre = trim($_POST["nombre"]);
  $apellidos = trim($_POST["apellidos"]);
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // 2. Validem que no estiguin buits
  if (!$username || !$email || !$password || !$nombre || !$apellidos) {
    $message = "Todos los campos son obligatorios.";
  } else {

    // Comprovar si l'usuari ja existeix
    $existing = get_user_by_username($username);

    if ($existing) {
      $message = "El nombre de usuario ya existe.";
    } else {
      // 3. Construïm l'array de dades amb 'nom' i 'cognoms'
      $data = [
        "nom_usuari" => $username,
        "nom" => $nombre,           // Nou camp
        "cognoms" => $apellidos,    // Nou camp
        "email" => $email,
        "contrasenya" => password_hash($password, PASSWORD_DEFAULT),
        "data_registre" => date("c")
      ];

      // Guardar al JSON Server
      $result = create_user($data);

      if ($result) {
        header("Location: login.php?success=1");
        exit;
      } else {
        $message = "Error al conectar con el servidor. Revisa que 'jsonserver' estigui funcionant i la URL a config.php sigui correcta.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Registro</title>
  <link rel="stylesheet" href="../public/css/styles.css">
  <link rel="stylesheet" href="../public/css/auth.css">
</head>

<body>
  <div class="auth-container">
    <h2>Crear cuenta</h2>

    <?php if ($message): ?>
      <p style="color:red; font-weight:bold;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
      <label>Nombre</label><br>
      <input type="text" name="nombre" required placeholder="Ej. Adrián"><br><br>

      <label>Apellidos</label><br>
      <input type="text" name="apellidos" required placeholder="Ej. Becerra Pérez"><br><br>

      <label>Nombre de usuario</label><br>
      <input type="text" name="username" required placeholder="Ej. adriu"><br><br>

      <label>Email</label><br>
      <input type="email" name="email" required placeholder="ejemplo@email.com"><br><br>

      <label>Contraseña</label><br>
      <input type="password" name="password" required><br><br>

      <button type="submit">Registrarse</button>
    </form>

    <p>
      <a href="login.php">Ya tengo cuenta</a>
    </p>
  </div>
</body>

</html>