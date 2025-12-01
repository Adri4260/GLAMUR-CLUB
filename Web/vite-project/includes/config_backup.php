<?php
<<<<<<< HEAD
define("JSON_SERVER_URL", "http://localhost:3000");
=======
// includes/config.php

// Dins de Docker, "localhost" no funciona per comunicar contenidors.
// Hem de fer servir el nom del servei definit al docker-compose.yml: "jsonserver"
define("JSON_SERVER_URL", "http://jsonserver:3000");
>>>>>>> f4ead6c6b774f5c0c9b2f47052026524f7269e8d
