<?php
// contacte.php

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Netejar dades
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    // VALIDACIÓ SERVIDOR
    if (strlen($name) < 2) {
        $errors[] = "⚠️ El nom ha de tindre almenys 2 caràcters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "⚠️ El correu electrònic no és vàlid.";
    }

    if (strlen($subject) < 3) {
        $errors[] = "⚠️ L'assumpte és massa curt.";
    }

    if (strlen($message) < 5) {
        $errors[] = "⚠️ El missatge és massa curt.";
    }

    // Si no hi ha errors → simulació enviament
    if (empty($errors)) {

        $success = "✅ El teu missatge s'ha enviat correctament. Gràcies per contactar-nos!";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contacto | GLAMUR CLUB</title>
    <link rel="stylesheet" href="css/style.css">
    <script defer src="js/validacion.js"></script>
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

        <form id="contactForm" method="post" action="contacte.php" novalidate>
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

            <button type="submit">Enviar mensaje</button>
        </form>
    </main>

    <footer>
        <p>© 2025 GLAMUR CLUB — Perfumes y Belleza</p>
    </footer>
</body>

</html>