<?php
// includes/config.php

/**
 * Configuración del servidor JSON.
 * Nota: Dentro de Docker, "localhost" no funciona para comunicar contenedores.
 * Se utiliza el nombre del servicio definido en docker-compose.yml: "jsonserver"
 */
define("JSON_SERVER_URL", "http://jsonserver:3000");

// Puedes añadir aquí otras configuraciones de base de datos o constantes globales si las necesitas.