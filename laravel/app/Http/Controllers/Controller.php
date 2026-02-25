<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "API de Glamur Club",
    description: "Documentación interactiva de la API REST para el Frontend SPA."
)]
#[OA\Server(url: "/")]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Introduce el token devuelto por el /login"
)]
#[OA\Post(
    path: "/api/login",
    operationId: "loginUser",
    tags: ["Autenticación"],
    summary: "Iniciar sesión",
    description: "Autentica al usuario y devuelve un token Sanctum y los roles.",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "admin@glamur.com"),
                    new OA\Property(property: "password", type: "string", example: "admin123")
                ]
            )
        )
    ),
    responses: [
        new OA\Response(response: 200, description: "Login exitoso"),
        new OA\Response(response: 401, description: "Credenciales incorrectas o no autorizado")
    ]
)]
#[OA\Post(
    path: "/api/logout",
    operationId: "logoutUser",
    tags: ["Autenticación"],
    summary: "Cerrar sesión",
    description: "Invalida el token actual del usuario.",
    security: [["bearerAuth" => []]],
    responses: [
        new OA\Response(response: 200, description: "Sesión cerrada correctamente"),
        new OA\Response(response: 401, description: "Token inválido o ausente")
    ]
)]
abstract class Controller
{
    //
}
