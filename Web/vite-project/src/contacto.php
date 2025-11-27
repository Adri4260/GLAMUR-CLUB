<?php
// contacto.php

$errors = [];
$success = "";

// Detectar envío
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger y limpiar datos
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");
    $acceptTerms = isset($_POST["acceptTerms"]) ? true : false;
    $skipValidation = isset($_POST["skipValidation"]) ? true : false;

    // VALIDACIÓN SERVIDOR
    if (!$skipValidation && strlen($name) < 2) {
        $errors[] = "⚠️ El nombre debe tener al menos 2 caracteres.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "⚠️ El correo electrónico no es válido.";
    }

    if (strlen($subject) < 3) {
        $errors[] = "⚠️ El asunto es demasiado corto.";
    }

    if (strlen($message) < 5) {
        $errors[] = "⚠️ El mensaje es demasiado corto.";
    }

    // Comprobar aceptación de términos
    if (!$acceptTerms) {
        $errors[] = "⚠️ Debes aceptar los términos y condiciones.";
    }

    // Si no hay errores → simulación de envío
    if (empty($errors)) {
        $success = "✅ Tu mensaje se ha enviado correctamente. ¡Gracias por contactarnos!";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contacto | GLAMUR CLUB</title>
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/contacto.css">

    <script defer src="../public/js/validacion.js"></script>
</head>

<body>
    <header>
        <h1>Contacta con <span>GLAMUR CLUB</span></h1>
        <a href="/" aria-label="Home"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="50" viewBox="0 0 50 50">
                <path d="M 24.962891 1.0546875 A 1.0001 1.0001 0 0 0 24.384766 1.2636719 L 1.3847656 19.210938 A 1.0005659 1.0005659 0 0 0 2.6152344 20.789062 L 4 19.708984 L 4 46 A 1.0001 1.0001 0 0 0 5 47 L 18.832031 47 A 1.0001 1.0001 0 0 0 19.158203 47 L 30.832031 47 A 1.0001 1.0001 0 0 0 31.158203 47 L 45 47 A 1.0001 1.0001 0 0 0 46 46 L 46 19.708984 L 47.384766 20.789062 A 1.0005657 1.0005657 0 1 0 48.615234 19.210938 L 41 13.269531 L 41 6 L 35 6 L 35 8.5859375 L 25.615234 1.2636719 A 1.0001 1.0001 0 0 0 24.962891 1.0546875 z M 25 3.3222656 L 44 18.148438 L 44 45 L 32 45 L 32 26 L 18 26 L 18 45 L 6 45 L 6 18.148438 L 25 3.3222656 z M 37 8 L 39 8 L 39 11.708984 L 37 10.146484 L 37 8 z M 20 28 L 30 28 L 30 45 L 20 45 L 20 28 z"></path>
            </svg></a>
    </header>

    <main class="contact-container">
        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="contactForm" method="post" action="contacto.php" novalidate>
            <label for="name">Nombre completo</label>
            <input type="text" id="name" name="name" placeholder="Escribe tu nombre"
                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>

            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" placeholder="ejemplo@correo.com"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

            <label for="subject">Asunto</label>
            <input type="text" id="subject" name="subject" placeholder="Motivo del mensaje"
                value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required>

            <label for="message">Mensaje</label>
            <textarea id="message" name="message" rows="5" placeholder="Escribe tu mensaje..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

            <div class="checkbox-group">
                <label>
                    <input type="checkbox" id="acceptTerms" name="acceptTerms" required
                        <?= isset($_POST['acceptTerms']) ? 'checked' : '' ?>>
                    Acepto las <a href="#">leyes y términos de uso</a>.
                </label>
            </div>

            <div class="checkbox-group">
                <label>
                    <input type="checkbox" id="skipValidation" name="skipValidation"
                        <?= isset($_POST['skipValidation']) ? 'checked' : '' ?>>
                    Desactivar validación del cliente de prueba
                </label>
            </div>

            <button type="submit">Enviar mensaje</button>
        </form>
    </main>

    <footer>
        <p>© 2025 GLAMUR CLUB — Perfumes y Belleza</p>
    </footer>
</body>

</html>