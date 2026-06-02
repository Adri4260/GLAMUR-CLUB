# 💜 Glamur Club — Arquitectura Cloud & SPA

**Desarrollado en:** DAW 2n – CIPFP Batoi
**Identificador:** Grupo 04

---

## 🧴 Descripción del Proyecto

**Glamur Club** es un e-commerce exclusivo dedicado a la venta de perfumes, cosmética y maquillaje premium.

Esta versión final culmina el Proyecto Intermodular (Sprints 1 al 6), evolucionando de una aplicación monolítica tradicional a una **arquitectura desacoplada** profesional y altamente escalable. El proyecto consta de una API REST (Backend) securizada y documentada, consumida por una Single Page Application (Frontend) reactiva y sostenible, con todo el entorno orquestado mediante Docker para su despliegue automatizado en infraestructuras Cloud (AWS).

---

## 👥 Equipo de Desarrollo

| Miembro | Rol |
|---|---|
| **Adrián Becerra** 👨‍💻 | Full-Stack, DevOps, Cloud Architecture & CI/CD |
| **Jose Juan Alemany (Pepe)** 🧑‍💻 | UI/UX Design & Frontend Development |

📊 [Tablero Kanban del Proyecto en GitHub Projects](https://github.com/users/Adri4260/projects/9)

---

## 🧠 Arquitectura del Sistema

Uno de los mayores retos técnicos del proyecto ha sido la separación total de responsabilidades entre cliente y servidor.

### Comunicación Frontend ↔ Backend

**API RESTful y CORS:** El Frontend (Vue 3) realiza peticiones asíncronas HTTP (Axios) hacia los endpoints del Backend (Laravel). El sistema está protegido por políticas CORS estrictas, configuradas en Laravel para aceptar únicamente tráfico originado desde el dominio oficial (`www.projecte04.ddaw.es`).

**Autenticación Stateless (Sanctum):** Las sesiones no se guardan en el servidor. Al hacer login (nativo o vía Google OAuth2), Laravel emite un Bearer Token. Vue lo almacena de forma segura y lo inyecta en las cabeceras de todas las peticiones posteriores que requieran autorización.

**Proxy Inverso en Producción:** En AWS, un único servidor Nginx actúa como director de orquesta. Si un usuario navega por la tienda, Nginx sirve el contenedor estático de Vue. Si la petición incluye el prefijo `/api`, Nginx intercepta la llamada y hace un `proxy_pass` interno hacia el contenedor de Laravel.

---

## ☁️ Infraestructura en AWS y DevOps

El despliegue en producción refleja un entorno empresarial real bajo el siguiente esquema:

| Servicio | Descripción |
|---|---|
| **EC2 (Computación)** | Instancia Ubuntu con IP Elástica. Actúa como host de Docker, ejecutando los contenedores de Frontend, Backend y Chatbot |
| **Amazon RDS** | Motor MySQL 8.0 alojado fuera de la EC2 para garantizar persistencia e integridad. Blindado mediante Security Groups para que únicamente la EC2 pueda conectarse al puerto 3306 |
| **Route 53** | Gestión del dominio y resolución DNS |
| **Certbot (Let's Encrypt)** | Certificados SSL/TLS auto-renovables que garantizan tráfico cifrado de extremo a extremo |

Tráfico de entrada restringido a los puertos `80` (HTTP), `443` (HTTPS) y `22` (SSH).

---

## 🔄 Integración y Despliegue Continuo (CI/CD)

El proyecto utiliza flujos de trabajo de **GitHub Actions**. Ante cada nuevo `push` a la rama de producción (`sprint5-6`):

1. Los runners de GitHub acceden vía SSH a la instancia EC2.
2. Descargan el código actualizado de ambos repositorios.
3. Reconstruyen las imágenes de Docker (`--build`) de forma desatendida.
4. Ejecutan las migraciones de Laravel para mantener la base de datos sincronizada sin intervención humana.

---

## 🎯 Mejoras Clave y Funcionalidades (Sprints 5 y 6)

### 🔗 1. Integraciones y Documentación API

**OAuth2 con Google:** Integración de Laravel Socialite para un inicio de sesión seguro y rápido. El flujo redirige el callback al SPA frontend inyectando el token Sanctum sin exponer credenciales.

**Swagger / OpenAPI:** Documentación interactiva de todos los endpoints (Catálogo, Auth, Reviews) generada automáticamente en `/api/documentation`.

### 🧪 2. Mejora Digital: "Crea tu Perfume"

Configurador interactivo donde el usuario selecciona sus propias notas olfativas (salida, corazón y fondo). El sistema calcula dinámicamente el perfil y genera un producto a medida listo para añadir al carrito.

### 🤖 3. Chatbot de Atención al Cliente (n8n)

Flujo de trabajo automatizado orquestado mediante un contenedor de **n8n**, inyectado globalmente en el SPA de Vue para proporcionar asistencia sobre el catálogo de perfumes.

### 🌱 4. Sostenibilidad ASG y Ecodiseño

Implementación de compresión **Gzip** en Nginx, minificación de assets con Vite y **Lazy Loading** para reducir drásticamente la transferencia de datos y el consumo energético (Green IT).

### ⚡ 5. Frontend Avanzado y Filtros Reactivos

**Filtros en Tiempo Real:** Catálogo impulsado por watchers de Vue 3 que filtran productos instantáneamente sin recargar la página.

**Control de Rutas (SPA):** Nginx configurado con `try_files` para delegar el enrutamiento a Vue, evitando errores `404 Not Found` en accesos directos a rutas internas.

---

## 🛠️ Stack Tecnológico

| Capa | Tecnologías |
|---|---|
| **Frontend (SPA)** | Vue.js 3 (Composition API), Vite, Pinia, Tailwind CSS, Vee-Validate / Yup |
| **Backend (REST API)** | PHP 8.4 / Laravel 12, MySQL 8.0, Laravel Sanctum, Socialite, L5-Swagger |
| **Infraestructura & DevOps** | Docker & Docker Compose, GitHub Actions, n8n, AWS (EC2, RDS, Route 53) |
| **Servidor Web** | Nginx & Certbot (Let's Encrypt) |

---

## 🚀 Guía de Puesta en Marcha (Entorno Local)

Gracias a la orquestación con Docker Compose, levantar toda la infraestructura para desarrollo es extremadamente sencillo.

### Requisitos Previos

- **Docker Desktop** instalado y en ejecución.
- Puertos `8000` (API), `5173` (Web), `3306` (BD) y `5678` (n8n) libres en tu máquina.

### Pasos de Ejecución

**1. Configurar variables de entorno**

Duplica el archivo `.env.example` en las carpetas de ambos microservicios:

```bash
# En el Backend:
cd laravel
cp .env.example .env
```

> Asegúrate de que `DB_HOST` apunta a `mysql` para que conecte con el contenedor.

**2. Levantar la infraestructura**

Vuelve a la raíz principal del proyecto y ejecuta:

```bash
docker compose up -d --build
```

Docker descargará las imágenes oficiales e instalará todas las dependencias (NPM y Composer).

**3. Configurar Laravel y poblar la base de datos**

Una vez los contenedores estén en verde (espera ~15 segundos a que MySQL arranque):

```bash
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate:fresh --seed
```

---

## 🌐 Accesos del Sistema

### Producción (AWS)

| Servicio | URL |
|---|---|
| 🖥️ Tienda Oficial (Frontend) | https://www.projecte04.ddaw.es |
| ⚙️ API REST (Backend) | https://api.projecte04.ddaw.es |
| 📖 Documentación Swagger | https://api.projecte04.ddaw.es/api/documentation |

### Desarrollo (Local)

| Servicio | URL |
|---|---|
| 🖥️ Frontend SPA | http://localhost:5173 |
| ⚙️ Backend API | http://localhost:8000 |
| 📖 Documentación Swagger | http://localhost:8000/api/documentation |

---

## 👤 Usuarios de Prueba

Para revisar la plataforma y probar el Control de Acceso Basado en Roles (RBAC):

| Perfil | Correo | Contraseña | Permisos |
|---|---|---|---|
| 👑 **Administrador** | `admin@glamurclub.com` | `admin123` | Panel de administración, gestión de catálogo, roles y reseñas |
| 🛍️ **Cliente** | `cliente2@glamurclub.com` | `password` | Navegación, carrito persistente y publicación de reseñas |
