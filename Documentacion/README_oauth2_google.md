# 🔐 Glamur Club — Integración OAuth2: Inicio de Sesión con Google

**Desarrollado por:** Adrián Becerra y Jose Juan Alemany

---

## 1. Flujo Completo de Autenticación

La autenticación externa sigue el **Authorization Code Flow** del protocolo OAuth 2.0, involucrando a las tres partes: Frontend (Vue), Backend (Laravel) y el proveedor de identidad (Google).

```
[Usuario] → clic en "Iniciar sesión con Google"
    │
    ▼
[Frontend] → GET /api/auth/google/redirect
    │
    ▼
[Backend] → genera URL de autorización → redirige al usuario
    │
    ▼
[Google] → pantalla de consentimiento (Email + Nombre)
    │
    ▼
[Google] → redirige a GET /api/auth/google/callback + Authorization Code
    │
    ▼
[Backend] → intercambia Code + client_secret → obtiene Token de Google
    │
    ▼
[Backend] → extrae datos del usuario
    ├── Si NO existe en BD → lo crea
    └── Si YA existe      → actualiza su información
    │
    ▼
[Backend] → genera token Sanctum → redirige al Frontend con el token
    │
    ▼
[Frontend] → inicia sesión nativa en Vue (Pinia)
```

---

## 2. Seguridad: Ocultación del `client_secret`

Es crítico destacar que el `client_secret` de Google **nunca está expuesto en el código del Frontend**.

La aplicación Vue solo recibe las URLs de redirección y el token Sanctum final. Todo el intercambio criptográfico con Google ocurre exclusivamente de **servidor a servidor** (Backend de Laravel hacia la API de Google). Las credenciales de Google residen de forma segura en las variables de entorno (`.env`) de la instancia EC2, fuera del control de versiones.

---

## 3. Gestión Segura de Tokens (Laravel Sanctum)

| Política | Descripción |
|---|---|
| **Expiración** | Los tokens generados tienen un tiempo de vida limitado (TTL configurable) |
| **Renovación** | El usuario debe volver a autenticarse si el token caduca |
| **Revocación (Logout)** | Al cerrar sesión, el Backend destruye físicamente el token activo en base de datos (`$request->user()->currentAccessToken()->delete()`), invalidando la sesión de inmediato |

---

## 4. Migración de Base de Datos

Para soportar la autenticación híbrida (contraseña tradicional + Google), se creó una migración específica que modifica la tabla `users`, añadiendo el campo `google_id` y permitiendo que la contraseña sea nula (los usuarios que se registran con Google no tienen contraseña local).

```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('google_id')->nullable()->unique()->after('email');
        $table->string('password')->nullable()->change();
    });
}
```

---

## 5. Resolución de Errores Típicos (Troubleshooting)

### `redirect_uri_mismatch`

Ocurre si la URL de callback configurada en el `.env` del servidor de producción no coincide exactamente (incluyendo el `https://`) con la URI autorizada en la consola de Google Cloud. Ambas deben ser idénticas carácter por carácter.

### `invalid_grant`

Se produce si el Backend intenta intercambiar con Google un Authorization Code que ya ha sido usado o que ha expirado. Los códigos de autorización tienen una vida útil de apenas unos segundos, por lo que no pueden reutilizarse.

### Token Caducado/Inválido (Sanctum `401 Unauthorized`)

Si el token propio del usuario caduca, el Backend devuelve un código HTTP `401 Unauthorized`. El Frontend intercepta este error automáticamente, limpia el estado de Pinia y redirige al usuario a la pantalla de Login.
