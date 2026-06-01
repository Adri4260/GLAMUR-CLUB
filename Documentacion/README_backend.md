# ⚙️ Glamur Club — Backend API (Laravel)

**Desarrollado por:** Adrián Becerra y Jose Juan Alemany

---

## 📖 Descripción del Proyecto

Este repositorio contiene el código fuente del backend de **Glamur Club**, una API RESTful robusta y segura diseñada para dar soporte a la plataforma de e-commerce de perfumería y cosmética. Proporciona todos los servicios necesarios para la gestión del catálogo de productos, autenticación de usuarios, procesamiento de reseñas y control de roles, operando de manera totalmente desacoplada del frontend.

---

## 🛠️ Stack Tecnológico

| Tecnología | Rol |
|---|---|
| **Laravel 12** | Framework principal de la API REST |
| **PHP 8.4** | Lenguaje de programación del servidor |
| **MySQL** (Amazon RDS en producción) | Motor de base de datos relacional |
| **Laravel Sanctum** | Autenticación mediante tokens API |
| **Laravel Socialite** | Autenticación OAuth2 (Google) |
| **Docker & Docker Compose** | Contenerización del entorno |
| **PHPUnit / Pest** | Testing unitario y funcional |
| **L5-Swagger (OpenAPI)** | Documentación interactiva de la API |

---

## 🏗️ Arquitectura Interna

La API sigue un patrón **MVC adaptado para servicios REST**, garantizando un enfoque completamente Stateless (sin estado).

| Capa | Ubicación | Responsabilidad |
|---|---|---|
| **Rutas** | `routes/api.php` | Punto de entrada de las peticiones HTTP |
| **Controladores** | `app/Http/Controllers/` | Procesan la lógica de negocio y validan los Form Requests |
| **Modelos (Eloquent)** | `app/Models/` | Gestionan la interacción con la base de datos y definen las relaciones entre entidades |
| **Resources** | `app/Http/Resources/` | Formatean las respuestas Eloquent a JSON estructurado, ocultando campos sensibles |

---

## ⚙️ Variables de Entorno

Las credenciales reales nunca se versionan en Git. Duplica el archivo `.env.example` y renómbralo a `.env` antes de arrancar el proyecto.

```env
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=glamur_db
DB_USERNAME=root
DB_PASSWORD=secret
```

---

## 💻 Ejecución en Desarrollo (Local)

El entorno de desarrollo local está completamente contenerizado para evitar conflictos de dependencias.

**1.** Clona el repositorio y duplica el archivo de entorno:

```bash
cp .env.example .env
```

**2.** Levanta los contenedores y construye la imagen:

```bash
docker compose up -d --build
```

**3.** Genera la clave de la aplicación:

```bash
docker compose exec backend php artisan key:generate
```

**4.** Ejecuta las migraciones y puebla la base de datos:

```bash
docker compose exec backend php artisan migrate:fresh --seed
```

**5.** La API estará disponible en `http://localhost:8000/api`.

---

## 🚀 Build y Despliegue en Producción

En producción, el contenedor de Laravel se construye utilizando un `Dockerfile.prod` optimizado:

**Build:** Se ejecuta `composer install --no-dev --optimize-autoloader` para instalar solo las dependencias esenciales. Las rutas y configuraciones se almacenan en caché (`php artisan config:cache`, `route:cache`) para mejorar el rendimiento.

**Despliegue:** El contenedor se despliega en una instancia EC2 de AWS. A diferencia del entorno local, no levanta un contenedor de base de datos interno; el `.env` de producción apunta directamente al endpoint de un **Amazon RDS privado**.

---

## 🔄 Flujo CI/CD (De Commit a Producción)

| Fase | Descripción |
|---|---|
| **Trigger** | `push` o Pull Request fusionado en la rama `sprint5-6` |
| **Setup** | GitHub configura PHP 8.4 e instala dependencias con Composer |
| **Tests** | Se ejecuta `php artisan test`. Si algún test falla, el despliegue se cancela |
| **Despliegue** | Los cambios se transfieren al servidor EC2 mediante SSH seguro |
| **Migración (Crítico)** | Tras levantar el nuevo contenedor, se ejecuta obligatoriamente `php artisan migrate --force` para actualizar la base de datos de producción sin intervención manual |

---

## 🔗 Endpoints Clave de la API

| Método | Endpoint | Descripción | Auth |
|---|---|---|---|
| `POST` | `/api/register` | Registro de nuevo usuario | No |
| `POST` | `/api/login` | Inicio de sesión y entrega de token Sanctum | No |
| `GET` | `/api/products` | Recupera el catálogo completo (paginado) | No |
| `GET` | `/api/products/{id}` | Detalles específicos de un producto | No |
| `POST` | `/api/reviews` | Creación de una reseña | ✅ Requerida |

La documentación interactiva completa con OpenAPI/Swagger está disponible en `/api/documentation`.

---

## 📚 Documentación de la API (Swagger / OpenAPI)

La API está completamente documentada utilizando el estándar **OpenAPI** con la interfaz interactiva de **Swagger UI**, permitiendo explorar y probar todos los endpoints directamente desde el navegador.

### Acceso a la Documentación

La interfaz está desplegada públicamente en producción:

👉 **https://api.projecte04.ddaw.es/api/documentation**

### Contenido Documentado

La documentación cubre la totalidad de la lógica de negocio: endpoints de autenticación, CRUD del catálogo, rutas protegidas de reseñas, códigos de estado HTTP (`200`, `201`, `401`, `403`, `422`, `500`) y esquemas JSON de los modelos de Usuario, Producto y Reseña.

### Probar Endpoints Protegidos (Bearer Token)

Para probar rutas privadas directamente desde Swagger UI:

**1.** Despliega `POST /api/login`, pulsa **"Try it out"**, introduce las credenciales y ejecuta la petición.

**2.** Copia el valor del campo `token` de la respuesta.

**3.** Haz clic en el botón **"Authorize"** (icono de candado) en la parte superior de la página.

**4.** Pega el token y confirma. Swagger inyectará automáticamente la cabecera `Authorization: Bearer <token>` en todas las peticiones posteriores.

### Regenerar la Documentación

Si se modifican controladores o se añaden nuevos endpoints, recompila el archivo con:

```bash
docker compose exec backend php artisan l5-swagger:generate
```

---

## 🔐 Roles y Permisos del Sistema

El sistema cuenta con **autorización basada en roles** implementada mediante Middleware:

| Rol | Permisos |
|---|---|
| **Cliente** | Navegar por el catálogo, dejar reseñas y consultar su historial |
| **Administrador** | Acceso total: CRUD del catálogo, moderación de reseñas y gestión de usuarios |

---

## 🧪 Testing

El backend cuenta con tests unitarios y Feature Tests para garantizar la estabilidad del sistema.

**Qué se testea:** Validaciones de registro y login, protección de rutas mediante Middleware (acceso sin token) y correcto funcionamiento del CRUD de productos.

**Cómo ejecutar los tests:**

```bash
docker compose exec backend php artisan test
```

---

## 📈 Escalabilidad y Disponibilidad

**Stateless Auth:** Al utilizar tokens Sanctum, el backend no almacena sesiones en memoria. Cualquier instancia del contenedor puede resolver la autenticación.

**Desacoplamiento de Datos:** La base de datos no vive en el contenedor. Al usar Amazon RDS externo (con posibilidad de configuración Multi-AZ y réplicas de lectura), el servicio de base de datos escala independientemente del tráfico web.

---

## 🤝 Normas de Contribución

**Estrategia de Ramas:** Uso de Feature Branches (`feature/api-reviews`, `fix/login`). La rama principal se mantiene siempre estable.

**Revisión y CI:** Todo código se integra mediante Pull Requests. GitHub Actions verificará que todos los tests pasen antes de permitir la fusión.

**Code Style:** Se sigue el estándar **PSR-12** para código PHP.

**Distribución de Tareas:** División por endpoints y features mediante un panel Kanban en GitHub Projects.

---

## 👥 Usuarios de Prueba

Para la revisión por parte del equipo docente, la base de datos contiene los siguientes usuarios pre-sembrados:

| Rol | Email | Contraseña |
|---|---|---|
| **Administrador** | `admin@glamurclub.com` | `admin123` |
| **Cliente Estándar** | `cliente@glamurclub.com` | `password` |
