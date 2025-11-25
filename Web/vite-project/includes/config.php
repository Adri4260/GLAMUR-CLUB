<?php
// includes/config.php

// Dins de Docker, "localhost" no funciona per comunicar contenidors.
// Hem de fer servir el nom del servei definit al docker-compose.yml: "jsonserver"
define("JSON_SERVER_URL", "http://jsonserver:3000");
