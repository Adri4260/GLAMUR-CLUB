# 💜 GLAMUR CLUB - Arquitectura Cloud & SPA

## 🧴 Descripción del Proyecto

**GLAMUR CLUB** es un e-commerce exclusivo dedicado a la venta de perfumes, cosmética y maquillaje premium.

Esta versión final culmina el **Proyecto Intermodular (Sprints 1 al 6)**, evolucionando de una aplicación monolítica tradicional a una **Arquitectura Desacoplada** profesional y altamente escalable.

El proyecto consta de una **API REST (Backend)** securizada y documentada, consumida por una **Single Page Application (Frontend)** reactiva y sostenible, con todo el entorno orquestado e independizado mediante **Docker** para su despliegue automatizado en infraestructuras **Cloud (AWS)**.

> Desarrollado dentro del curso **DAW 2n – CIPFP Batoi**.

---

## 👥 Equipo de Desarrollo y Gestión

| Miembro | Rol |
|---------|------|
| **Adrián Becerra** 👨‍💻 | Full-Stack, DevOps & Cloud Architecture |
| **Jose Juan Alemany (Pepe)** 🧑‍💻 | UI/UX Design & Frontend |

📊 **Tablero Kanban del Proyecto:**  
Sigue nuestra planificación y progreso en [GitHub Projects](https://github.com/projects)

---

## 🎯 Mejoras Clave (Sprints 5 y 6)

### ☁️ 1. Infraestructura Cloud y DevOps (C7)

- **Dockerización Independiente:** Creación de `Dockerfile` específicos para Vue y Laravel, garantizando aislamiento total.
- **Integración y Despliegue Continuo (CI/CD):** Implementación de dos pipelines independientes en GitHub Actions. Compilan código, ejecutan tests y despliegan automáticamente en producción (incluyendo ejecución de migraciones en servidor).
- **Arquitectura AWS:** Despliegue real en producción utilizando una instancia **EC2** configurada como proxy inverso con **Nginx**, tráfico cifrado (**HTTPS con Let's Encrypt**) y una base de datos externa privada y segura en **Amazon RDS**.

### 🔗 2. Integraciones y Documentación API (C1 y C2)

- **OAuth2 con Google:** Integración de Laravel Socialite en el backend para permitir un inicio de sesión seguro, rápido y stateless a través de Google, sin exponer el `client_secret` en el cliente.
- **Swagger / OpenAPI:** Documentación interactiva de todos los endpoints de la API (Catálogo, Auth, Reviews) generada automáticamente y disponible para pruebas en vivo.

### 🧪 3. Mejora Digital: "Crea tu Perfume" (C5)

- **Configurador Interactivo:** Desarrollo de una herramienta interactiva donde el usuario puede seleccionar sus propias notas olfativas (salida, corazón y fondo). El sistema calcula dinámicamente el perfil y genera un producto a medida listo para añadir al carrito.

### 🌱 4. Sostenibilidad ASG y Ecodiseño (C6)

- **Optimización Extrema:** Implementación de compresión **Gzip** en Nginx, minificación de assets con **Vite** y uso de **Lazy Loading** para reducir drásticamente la transferencia de datos y el consumo energético.
- **Política ASG:** Inclusión de una vista dedicada en la web que justifica la eficiencia digital de la SPA y el compromiso ambiental de la marca.

### 🤖 5. Frontend Avanzado y Filtros Reactivos (C3)

- **Filtros en Tiempo Real:** Catálogo impulsado por `watchers` de Vue 3 que filtran productos instantáneamente sin recargar la página.
- **Validaciones Robustas:** Integración de **Vee-Validate** y **Yup** en los formularios para feedback instantáneo, accesible y seguro.

---

## 🛠️ Stack Tecnológico

| Capa | Tecnologías |
|------|--------------|
| **Frontend (Client SPA)** | Vue.js 3 (Composition API), Vite, Pinia, Tailwind CSS, Vee-Validate / Yup |
| **Backend (REST API)** | PHP 8.4 / Laravel 12, MySQL 8.0, Laravel Sanctum, Laravel Socialite, L5-Swagger (OpenAPI) |
| **Infraestructura & DevOps** | Docker & Docker Compose, GitHub Actions, AWS (EC2, VPC, RDS Privado), Nginx & Certbot (Let's Encrypt) |

---

## 🚀 Guía de Puesta en Marcha (Entorno Local Dockerizado)

Gracias a la orquestación con **Docker Compose**, levantar toda la infraestructura del proyecto para desarrollo es extremadamente sencillo.

### Requisitos previos

- Tener **Docker** y **Docker Desktop** instalados y ejecutándose.
- Asegurarte de que los puertos `8000`, `5173` y `3306` están libres en tu máquina.

### Pasos de ejecución

#### 1. Clonar repositorios y configurar variables de entorno

Deberás configurar el archivo `.env` tanto en la carpeta del frontend como en la del backend duplicando el archivo `.env.example`.

```bash
# Ejemplo en el Backend:
cd laravel
cp .env.example .env
```

Asegúrate de que las credenciales de BD en el `.env` apuntan al host `mysql`, tal y como está configurado en Docker.

#### 2. Levantar la infraestructura

Vuelve a la raíz principal del proyecto y ejecuta el comando maestro:

```bash
docker compose up -d --build
```

Docker descargará las imágenes oficiales, instalará las dependencias (NPM y Composer) y levantará la Base de Datos, la API y la Web.

#### 3. Configurar Laravel y poblar la Base de Datos

Una vez los contenedores estén activos (espera unos 15 segundos a que arranque MySQL), entra al contenedor del backend para inyectar los datos semilla:

```bash
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate:fresh --seed
```

## 🌐 Accesos del Sistema

### Entorno de Producción (AWS)

| Servicio | URL Pública |
|----------|--------------|
| 🖥️ Tienda Oficial (Frontend) | `https://www.projecte04.ddaw.es` |
| ⚙️ API REST (Backend) | `https://api.projecte04.ddaw.es/api` |
| 📖 Documentación Swagger | `https://api.projecte04.ddaw.es/api/documentation` |

### Entorno de Desarrollo (Local)

| Servicio | URL Local |
|----------|-----------|
| 🖥️ Frontend SPA | `http://localhost:5173` |
| ⚙️ Backend API | `http://localhost:8000/api` |

---

## 👤 Usuarios de Prueba (Evaluación)

Para revisar la plataforma sin necesidad de registrar cuentas nuevas o usar correos personales, puedes acceder con los siguientes usuarios pre-sembrados:

| Perfil | Correo de Acceso | Contraseña | Permisos |
|--------|------------------|------------|----------|
| 👑 Administrador | `admin@glamurclub.com` | `admin123` | Control total del sistema, catálogo y moderación |
| 🛍️ Cliente | `cliente2@glamurclub.com` | `password` | Navegación, carrito, compras y publicación de reseñas |
