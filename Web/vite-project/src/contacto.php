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
    <link rel="stylesheet" href="/public/css/contacto.css">
    <script defer src="../public/js/validacion.js"></script>
</head>

<body>
    <header>
        <h1>Contacta con <span>GLAMUR CLUB</span></h1>
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
                    Desactivar validación del servidor
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