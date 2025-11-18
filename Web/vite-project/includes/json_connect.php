<?php
// json_connect.php
// Maneja todas las peticiones desde PHP al JSON Server

const JSON_SERVER_URL = "http://localhost:3000";
// Ajusta el host si tu servicio se llama distinto en docker-compose

function json_get($endpoint)
{
    $url = JSON_SERVER_URL . $endpoint;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200 && $http_code !== 304) {
        return null;
    }

    return json_decode($response, true);
}

function json_post($endpoint, $data)
{
    $url = JSON_SERVER_URL . $endpoint;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 201) { // 201 Created
        return null;
    }

    return json_decode($response, true);
}

function json_patch($endpoint, $data)
{
    $url = JSON_SERVER_URL . $endpoint;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) { // 200 OK
        return null;
    }

    return json_decode($response, true);
}

function json_delete($endpoint)
{
    $url = JSON_SERVER_URL . $endpoint;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // JSON Server devuelve 200 o 204 para una eliminación exitosa
    return $http_code === 200 || $http_code === 204;
}
