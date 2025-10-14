<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $missatge = trim($_POST["missatge"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Correu electrònic invàlid.");
    }

    if (empty($nom) || empty($missatge)) {
        die("Falten camps obligatoris.");
    }

    // Enviament o registre
    echo "Missatge rebut. Gràcies per contactar amb Glamur Club!";
}
?>

