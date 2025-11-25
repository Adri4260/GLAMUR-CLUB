<?php
// auth/profile.php
require_once "../includes/auth_check.php";

// 1. Verificar sessió
require_login();

// 2. Obtenir ID de l'usuari de la sessió
$userId = $_SESSION["user_id"];
$user = get_user_by_id($userId);

// Si per algun motiu no es troba l'usuari (ex: esborrat manualment del json), tanquem sessió
if (!$user) {
  header("Location: logout.php");
  exit;
}

$message = "";
$msgType = ""; // 'success' o 'error'

// 3. Processar el formulari d'edició
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = trim($_POST["nombre"]);
  $apellidos = trim($_POST["apellidos"]);
  $email = trim($_POST["email"]);

  // Validació bàsica
  if (!$nombre || !$apellidos || !$email) {
    $message = "Todos los campos marcados son obligatorios.";
    $msgType = "error";
  } else {
    // Dades a actualitzar
    $updateData = [
      "nom" => $nombre,
      "cognoms" => $apellidos,
      "email" => $email
    ];

    // Cridem a la funció update_user (definida a auth_check.php -> json_patch)
    $result = update_user($userId, $updateData);

    if ($result) {
      $message = "Datos actualizados correctamente.";
      $msgType = "success";
      // Actualitzem la variable $user amb les noves dades retornades
      $user = $result;
    } else {
      $message = "Error al guardar los cambios. Verifica la conexión.";
      $msgType = "error";
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Mi Perfil</title>
  <link rel="stylesheet" href="../public/css/styles.css">
  <link rel="stylesheet" href="../public/css/auth.css">
  <style>
    /* Estil extra específic per al perfil per separar seccions */
    .profile-section {
      margin-bottom: 1.5rem;
      padding-bottom: 1.5rem;
      border-bottom: 1px solid var(--color-border);
    }

    .profile-section:last-of-type {
      border-bottom: none;
    }

    .readonly-field {
      background-color: rgba(0, 0, 0, 0.2) !important;
      /* Més fosc per indicar no editable */
      color: var(--color-text-muted) !important;
      border-color: transparent !important;
      cursor: not-allowed;
    }

    .btn-secondary {
      background-color: transparent;
      border: 1px solid var(--color-border);
      color: var(--color-text-white);
    }

    .btn-secondary:hover {
      border-color: var(--color-primary);
      color: var(--color-primary);
    }

    .actions {
      display: flex;
      gap: 1rem;
      margin-top: 1rem;
    }
  </style>
</head>

<body>

  <div class="auth-container" style="max-width: 600px;">
    <h2>Mi Perfil</h2>

    <?php if ($message): ?>
      <div class="<?= $msgType === 'success' ? 'success-message' : 'error-message' ?>">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <form method="POST">

      <div class="profile-section">
        <h3 style="color: var(--color-primary); margin-bottom: 1rem; font-size: 1.2rem;">Información Personal</h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div>
            <label>Nombre</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($user["nom"] ?? '') ?>" required>
          </div>
          <div>
            <label>Apellidos</label>
            <input type="text" name="apellidos" value="<?= htmlspecialchars($user["cognoms"] ?? '') ?>" required>
          </div>
        </div>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user["email"] ?? '') ?>" required>
      </div>

      <div class="profile-section">
        <h3 style="color: var(--color-text-muted); margin-bottom: 1rem; font-size: 1.2rem;">Datos de Cuenta</h3>

        <label>Nombre de usuario</label>
        <input type="text" value="<?= htmlspecialchars($user["nom_usuari"]) ?>" class="readonly-field" readonly>

        <label>Fecha de registro</label>
        <?php
        $date = new DateTime($user["data_registre"]);
        $fecha_formateada = $date->format('d/m/Y H:i');
        ?>
        <input type="text" value="<?= $fecha_formateada ?>" class="readonly-field" readonly>
      </div>

      <button type="submit" class="btn-primary">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
          <path d="M17 21v-8H7v8" />
          <path d="M7 3v5h8" />
        </svg>
        Guardar Cambios
      </button>
    </form>

    <div class="actions">
      <a href="../index.php" class="btn btn-secondary" style="flex: 1; justify-content: center; text-decoration: none;">
        Volver al inicio
      </a>
      <a href="logout.php" class="btn btn-secondary" style="flex: 1; justify-content: center; text-decoration: none; border-color: #ef4444; color: #ef4444;">
        Cerrar sesión
      </a>
    </div>

  </div>

</body>

</html>