# 💜 GLAMUR CLUB

![Status](https://img.shields.io/badge/Estat-En%20Desenvolupament-green?style=flat-square) ![Version](https://img.shields.io/badge/Versió-Sprint%202-purple?style=flat-square) ![License](https://img.shields.io/badge/Llicència-Educational-blue?style=flat-square)

## 🧴 Descripció del projecte
**GLAMUR CLUB** és un e-commerce exclusiu dedicat a la venda de **perfums i productes de bellesa i higiene**.
El lloc destaca per la seva funcionalitat innovadora: la possibilitat de **crear el teu propi perfum personalitzat**, combinant aromes segons els gustos de l’usuari o mitjançant suggeriments basats en **Intel·ligència Artificial**.

Aquesta rama forma part de la **Iteració 4: Client SPA amb Vue i control de rols**, desenvolupat dins del curs **DAW 2n – CIPFP Batoi**.

## 👥 Equipo de Desarrollo
* **Pepe** 🧑‍💻
* **Adri** 👨‍💻 

## 📊 Planificación y Seguimiento
Para visualizar la organización temporal y la asignación de tareas de esta fase, puedes consultar nuestros paneles de seguimiento:
* 📅 **[Ver Diagrama de Gantt del Sprint 4](#(https://github.com/Adri4260/GLAMUR-CLUB/blob/sprint4/ganttSprint4.gan))**
* 📋 **[Ver Tablero Kanban del Sprint 4](#(https://github.com/users/Adri4260/projects/8))**

---

## 🎯 Objetivos y Funcionalidades del Sprint

### ⚡ C1. Interfaz de usuario avanzada con Vue.js
Hemos migrado nuestro frontend clásico (HTML/Vanilla JS) a un proyecto moderno impulsado por **Vite** y **Vue 3**.
* **SPA y Enrutamiento:** Implementación de navegación dinámica sin recargas completas de página utilizando `vue-router`.
* **Modularidad:** Refactorización de la interfaz en componentes reutilizables (`Navbar.vue`, `ProductCard.vue`, `Footer.vue`).
* **Conectividad:** Sustitución de llamadas estáticas por peticiones dinámicas a la API REST mediante `Axios`.

### 🔐 C2. Integración de la autenticación mediante API
El sistema de autenticación de sesiones web tradicional se ha transformado en un sistema robusto mediante tokens.
* **Backend:** Configuración de **Laravel Sanctum** para gestionar el login/logout y emitir tokens API (`Bearer token`).
* **Gestión de Estado:** Integración de **Pinia** en el frontend (`authStore`) para almacenar de forma persistente el usuario y el token (`localStorage`).
* **Interceptors:** Configuración global de *Axios* para inyectar el token en las cabeceras de cada petición y desloguear automáticamente al usuario si la API devuelve un error `401 Unauthorized`.
* **Router Guards:** Protección de rutas privadas en Vue para redirigir al login a usuarios no autenticados.

### 👥 C3. Gestión de roles de usuario y permisos
Implementación de un sistema RBAC (Control de Acceso Basado en Roles) granular tanto del lado del cliente como del servidor.
* **Roles soportados:** `Admin` (control total), `Vendor` (gestión de sus productos), `Editor` (moderación de valoraciones) y `User` (acceso básico).
* **Protección Backend:** Creación de migraciones de roles (`roles`, `role_user`) y uso de *Middlewares* / *Policies* en Laravel para denegar accesos no permitidos (`403 Forbidden`).
* **Control Visual Frontend:** Creación del *composable* `useRole.js` en Vue, permitiendo ocultar botones o menús mediante directivas (`v-if="can('delete')"`) según el rol del usuario activo.

---

## 🛠️ Tecnologías y Herramientas

**Frontend:**
* Vue.js 3 (Composition API)
* Vite (Bundler)
* Vue Router (Navegación)
* Pinia (State Management)
* Axios (HTTP Client)

**Backend:**
* PHP 8 / Laravel 11
* Laravel Sanctum (Token Auth)
* MySQL (Base de Datos)
* Docker y Sail (Entorno de despliegue)

---

## 🚀 Guía de Instalación y Despliegue en Desarrollo

Dado que el proyecto ahora tiene una clara separación entre cliente y servidor, es necesario levantar ambos entornos de forma independiente:

### 1. Despliegue del Backend (Laravel API)

1. Entra en el directorio del backend:
   ```bash
   cd laravel
   ```

2. Instala las dependencias de Composer:
   ```bash
   composer install
   ```

3. Prepara tu archivo de entorno y la clave de la app:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Levanta el entorno con Docker (Sail):
   ```bash
   ./vendor/bin/sail up -d
   ```

5. Ejecuta las migraciones y puebla la base de datos (vital para cargar los nuevos roles y usuarios de prueba):
   ```bash
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```

### 2. Despliegue del Frontend (Vue SPA)

1. Navega al directorio raíz del nuevo cliente web:
   ```bash
   cd frontend
   ```

2. Instala las dependencias de Node:
   ```bash
   npm install
   ```

3. Ejecuta el servidor de desarrollo:
   ```bash
   npm run dev
   ```

4. Accede a la aplicación desde tu navegador, generalmente en `http://localhost:5173`.

---
*Glamur Club - Proyecto Intermodular desarrollado por Pepe y Adri.*
