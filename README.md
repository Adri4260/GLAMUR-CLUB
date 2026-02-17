# 💎 GLAMUR CLUB - Sprint 3 (Backend Laravel & API Base)

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)

Bienvenido a la **Iteración 3** de Glamur Club. En este sprint hemos dado el salto de una arquitectura nativa en PHP a un framework profesional (**Laravel 12**), migrando nuestros datos a **MySQL** mediante Eloquent, implementando seguridad avanzada y dejando preparada una API REST para el futuro cliente SPA.

---

## 🚀 Novedades de esta Versión (v2.0)

- **Arquitectura MVC:** Refactorización completa del proyecto utilizando Controladores, Modelos y Vistas (Blade).
- **Base de Datos Relacional:** Transición de `JSON Server` a **MySQL** con Migraciones y Seeders.
- **Autenticación Integrada:** Implementación de **Laravel Breeze** (registro, login, encriptación de contraseñas).
- **Roles y Seguridad:** Sistema de protección de rutas mediante Middleware personalizado (`AdminMiddleware`).
- **Importación Masiva (Excel):** Nuevo motor de importación para cargar productos y valoraciones directamente a la base de datos validando la información.
- **Panel de Administración (CRUD):** Zona restringida para visualizar, editar y eliminar productos del catálogo.
- **API REST Base:** Endpoints normalizados mediante `API Resources`.
- **Testing Automatizado:** Batería de Feature Tests (PHPUnit).

---

## 🛠️ Estructura del Proyecto

El repositorio se divide ahora en dos áreas principales para preservar el historial:
* 📁 `legacy-php/` -> Contiene el código de la v1 (Sprint 1 y 2) intacto. No se utiliza en esta fase.
* 📁 `laravel/` -> Contiene la **nueva aplicación profesional v2**. Todo el backend y frontend actual reside aquí.

---

## 🔐 Evolución de la Seguridad: Breeze vs Legacy

Uno de los objetivos clave de este Sprint (DWES/Seguridad) ha sido reemplazar la autenticación manual por un sistema robusto. Aquí las diferencias principales implementadas:

| Característica | ❌ Autenticación Manual (Sprint 2 - Legacy) | ✅ Laravel Breeze (Sprint 3 - Actual) |
| :--- | :--- | :--- |
| **Gestión de Sesión** | `session_start()` y `$_SESSION` nativos de PHP. | Facade `Auth` y driver de sesión seguro de Laravel. |
| **Protección de Rutas** | `if (!isset(...))` repetido en cada archivo PHP. | **Middleware** (`auth`, `admin`) centralizado en rutas. |
| **Vulnerabilidades** | Propenso a ataques CSRF y Session Hijacking. | **Token CSRF** automático y encriptación robusta. |
| **Contraseñas** | Gestión manual (riesgo de error humano). | Hashing automático con **Bcrypt** y validaciones estrictas. |
| **Mantenimiento** | Código disperso y difícil de escalar. | Controladores estandarizados (`AuthenticatedSessionController`). |

---

## ⚙️ Instalación y Puesta en Marcha

Para arrancar el proyecto en un entorno local utilizando **Laravel Sail (Docker)**, sigue estos pasos:

1. Levantar los contenedores de Docker (en segundo plano):
   ```bash
   ./vendor/bin/sail up -d
   ```
2. Instalar dependencias de frontend y compilar los assets (CSS/JS):
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run build
   ```
3. Ejecutar las migraciones y volcar los datos de prueba (Seeders):
   ```bash
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```
4. *Opcional:* Ejecutar los tests automatizados para verificar la integridad del sistema:
   ```bash
   ./vendor/bin/sail artisan test
   ```

---

## 👑 Acceso de Administración

El sistema cuenta con cuentas de prueba pre-generadas por los Seeders. Para probar el panel de gestión de productos y la importación de Excel, utiliza estas credenciales:

| Rol | Email | Contraseña | Permisos |
| :--- | :--- | :--- | :--- |
| **Administrador** | `admin@glamur.com` | `admin123` | CRUD Productos, Importar Excel, Ver Catálogo |
| **Cliente** | `cliente2@glamur.com` | `password` | Ver Catálogo, Publicar Valoraciones |

👉 **Ruta del Panel Admin:** `/admin/products` *(Redirigirá al login si no estás autenticado como administrador).*

---

## 📡 Documentación de la API REST

Se ha preparado una API que será consumida en el Sprint 4. Las respuestas están estandarizadas usando `ProductResource`.

| Método | Endpoint | Descripción | Autenticación |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/products` | Devuelve la lista completa de productos. | ❌ No |
| `GET` | `/api/products/{id}` | Devuelve los detalles de un producto concreto. | ❌ No |
| `GET` | `/api/reviews/{id}` | Devuelve las valoraciones de un producto. | ❌ No |
| `POST` | `/api/reviews` | Crea una nueva valoración para un producto. | ✅ Sí (Sanctum/Session) |

---

## 👥 Equipo y División de Tareas

Este proyecto ha sido desarrollado colaborativamente. A continuación se detalla la distribución de responsabilidades durante este Sprint para cubrir los resultados de aprendizaje (DWES / DIW / DAW):

| Miembro del Equipo | Tareas Principales (Sprint 3) |
| :--- | :--- |
| 🧑‍💻 **Adrián** | • Configuración inicial del entorno Laravel y Docker Sail.<br>• Creación de la API REST y estandarización con `API Resources`.<br>• Desarrollo de Controladores (`ProductController`, middlewares de seguridad).<br>• Desarrollo de la batería de pruebas automatizadas (PHPUnit). |
| 🧑‍💻 **Pepe** | • Diseño de la BBDD: Migraciones, Factory y Seeders (Eloquent).<br>• Integración y adaptación del diseño del Sprint 2 a componentes **Blade**.<br>• Implementación de **Laravel Breeze** (Autenticación).<br>• Lógica del comando/controlador de importación masiva de Excel. |

---

## 📊 Gestión del Proyecto (RA3 y RA4)

El seguimiento de tareas, control de versiones e historias de usuario se ha gestionado mediante metodologías ágiles.

🔗 **[Ver Tablero Kanban del Proyecto (GitHub Projects)](https://github.com/users/Adri4260/projects/6)**

> **Nota para la evaluación:** Toda la planificación, checklist de estado y revisión final se encuentra documentada y evidenciada en nuestro tablero Kanban enlazado arriba.
