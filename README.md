# 💜 GLAMUR CLUB - Arquitectura Cloud & SPA

![Status](https://img.shields.io/badge/Estat-Finalitzat-success?style=flat-square) ![Version](https://img.shields.io/badge/Versió-v1.0.0 (Sprint 6)-purple?style=flat-square) ![License](https://img.shields.io/badge/Llicència-MIT-blue?style=flat-square)

## 🧴 Descripción del Proyecto
**GLAMUR CLUB** es un e-commerce exclusivo dedicado a la venta de **perfumes, cosmética y maquillaje premium**. 
Esta versión final culmina el Proyecto Intermodular (Sprints 1 al 6), evolucionando de una aplicación monolítica tradicional a una **Arquitectura Desacoplada** profesional. 

El proyecto consta de una **API REST (Backend)** securizada y documentada, consumida por una **Single Page Application (Frontend)** reactiva y sostenible, con todo el entorno orquestado e independizado mediante **Docker** para su fácil despliegue en infraestructuras Cloud (AWS).

Desarrollado dentro del curso **DAW 2n – CIPFP Batoi**.

## 👥 Equipo de Desarrollo
* **Adrián** 👨‍💻 (Full-Stack, DevOps & Cloud Architecture)
* **Pepe** 🧑‍💻 (UI/UX Design)

---

## 🎯 Mejoras Clave (Sprints 5 y 6)

### ☁️ 1. Orquestación DevOps y Cloud (C7)
* **Dockerización Independiente:** Creación de `Dockerfile` específicos para Vue y Laravel.
* **Orquestador Maestro:** Implementación de `docker-compose.yml` para levantar Backend, Frontend y Base de Datos (con volúmenes de persistencia) en contenedores aislados.
* **Integración Continua (CI/CD):** Creación de *Pipelines* en GitHub Actions para automatizar el testeo de Laravel y la compilación de Vue ante nuevos *commits* en las ramas principales.
* **Arquitectura AWS:** Diseño documentado de infraestructura de Alta Disponibilidad (Load Balancer, Auto Scaling, RDS Multi-AZ y Subredes Privadas).

### 🔗 2. Integraciones y Documentación API (C1 y C2)
* **OAuth2 con Google:** Integración de Laravel Socialite en el backend para permitir un inicio de sesión seguro, rápido y *stateless* a través de Google.
* **Swagger / OpenAPI:** Documentación interactiva de todos los endpoints de la API (Catálogo, Auth, Reviews) generada automáticamente en `/api/documentation`.

### 🌱 3. Sostenibilidad ASG y Ecodiseño (C6)
* **Distintivo Ecológico:** Implementación de etiquetas visuales (`🌱 Eco-Packaging`) en el catálogo para destacar productos responsables.
* **Política ASG:** Inclusión de una vista dedicada que justifica la eficiencia digital de la SPA (carga asíncrona, menor consumo energético) y las políticas ambientales de la marca.

### 🤖 4. Frontend Avanzado y Filtros Reactivos (C3 y C5)
* **Filtros en Tiempo Real:** Catálogo impulsado por `watchers` de Vue 3 que filtran productos por búsqueda de texto y prefijos de categoría (SKU) sin retardo.
* **Algoritmo de Recomendación:** Endpoint en Laravel que suministra los productos "Prèmium" más destacados de forma inteligente para la página de inicio.
* **Validaciones Robustas:** Integración de `Vee-Validate` y `Yup` en los formularios para feedback instantáneo y seguro.

---

## 🛠️ Stack Tecnológico

**Frontend (Client SPA):**
* Vue.js 3 (Composition API)
* Vite
* Pinia (State Management)
* Vue Router
* Vee-Validate / Yup

**Backend (REST API):**
* PHP 8.2 / Laravel 11
* MySQL 8.0
* Laravel Sanctum (Token Auth)
* Laravel Socialite (OAuth2)
* L5-Swagger (OpenAPI)

**Infraestructura & DevOps:**
* Docker & Docker Compose
* GitHub Actions (CI/CD)
* AWS (EC2, VPC, RDS, ALB)

---

## 🚀 Guía de Puesta en Marcha (Entorno Dockerizado)

Gracias a la orquestación con Docker Compose, levantar toda la infraestructura del proyecto (Frontend, Backend y Base de datos) ahora es extremadamente sencillo. 

### Requisitos previos:
* Tener **Docker** y **Docker Desktop** instalados y ejecutándose en tu máquina.
* Asegurarte de que los puertos `8000`, `5173` y `3306` están libres.

### Pasos de ejecución:

**1. Preparar las variables de entorno del Backend**
Navega a la carpeta de Laravel y crea tu archivo `.env`:
```bash
cd laravel
cp .env.example .env
> **Nota:** Asegúrate de que las credenciales de BD en el `.env` apuntan al host `db`, tal y como se configuró para Docker.

**2. Levantar los contenedores**

Vuelve a la raíz principal del proyecto (donde se encuentra el archivo `docker-compose.yml`) y ejecuta el comando maestro:

```bash
docker compose up -d --build
```

Docker descargará las imágenes oficiales, instalará las dependencias (NPM y Composer) y levantará los 3 servicios.

**3. Generar la clave de Laravel y poblar la Base de Datos**

Una vez los contenedores estén en verde, entra al contenedor del backend para configurar Laravel e inyectar los datos semilla (Productos y Usuarios de prueba):

```bash
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate:fresh --seed
```

---

## 🌐 Accesos del Sistema

| Servicio | URL |
|---|---|
| 🖥️ Frontend SPA (Catálogo y Tienda) | http://localhost:5173 |
| ⚙️ Backend API | http://localhost:8000 |
| 📖 Documentación Interactiva (Swagger) | http://localhost:8000/api/documentation |

---

## 👤 Usuarios de Prueba

Puedes probar el sistema de control de accesos (RBAC) utilizando los siguientes usuarios semilla:

| Perfil | Correo de Acceso | Contraseña | Permisos |
|---|---|---|---|
| 👑 Administrador | admin@glamur.com | password123 | Control total, edición y borrado de catálogo y reseñas. |
| 🛡️ Moderador | moderador@glamur.com | password123 | Puede moderar y borrar comentarios de usuarios. |
| 🛍️ Cliente | cliente@glamur.com | password123 | Navegación, carrito y publicación de reseñas. |

---

*Glamur Club – Entrega Final v1.0.0 (Sprint 6)*
