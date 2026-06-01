# 🏗️ Glamur Club — Arquitectura y Stack Tecnológico

**Desarrollado por:** Adrián Becerra y Jose Juan Alemany
**Identificador:** Grupo 04

---

## 1. Tecnologías Utilizadas

El proyecto Glamur Club se ha desarrollado utilizando un stack moderno, separando completamente el frontend del backend para garantizar la escalabilidad y el mantenimiento independiente.

### Frontend (Interfaz de Usuario)

| Tecnología | Versión | Rol |
|---|---|---|
| **Vue.js** | 3 | Framework progresivo de JavaScript para la construcción de una Single Page Application (SPA) reactiva |
| **Pinia** | — | Gestor de estado global para centralizar la información del carrito de compras y la sesión del usuario |
| **Tailwind CSS** | — | Framework de CSS basado en utilidades para el diseño visual responsivo |
| **Vite** | — | Herramienta de empaquetado y servidor de desarrollo ultrarrápido |

### Backend (API REST)

| Tecnología | Versión | Rol |
|---|---|---|
| **Laravel** | 12 | Framework de PHP para construir una API RESTful robusta y segura |
| **PHP** | 8.4 | Lenguaje de programación del servidor |
| **MySQL** | — | Motor de base de datos relacional |

### Infraestructura y DevOps

| Tecnología | Rol |
|---|---|
| **Docker & Docker Compose** | Contenerización de las aplicaciones para estandarizar los entornos de desarrollo y producción |
| **Nginx** | Servidor web actuando como proxy inverso |
| **AWS (EC2 + RDS)** | Proveedor cloud: EC2 para cómputo, RDS para base de datos gestionada |
| **GitHub Actions** | Plataforma de Integración y Despliegue Continuo (CI/CD) |
| **Certbot (Let's Encrypt)** | Generación y renovación automática de certificados SSL/TLS |

---

## 2. Relación Global de Componentes

La arquitectura global del sistema sigue un modelo **cliente-servidor desacoplado**. El flujo de comunicación se estructura en cuatro etapas:

### 1. Resolución DNS y Entrada (Edge)

El usuario introduce `www.projecte04.ddaw.es` en su navegador. El DNS dirige la petición a la IP pública de la instancia EC2 en AWS.

### 2. Proxy Inverso (Nginx)

Nginx recibe la petición por el puerto `443` (HTTPS), descifra el tráfico de forma segura y evalúa el subdominio:

- Si la petición es para la web estática → sirve los archivos de Vue compilados.
- Si la petición va dirigida a `api.projecte04.ddaw.es` → realiza un `proxy_pass` y envía el tráfico internamente al puerto `8000` del contenedor de Laravel.

### 3. Interacción del Cliente

Una vez el navegador carga la aplicación Vue, esta realiza peticiones HTTP asíncronas (mediante `axios`) hacia la API de Laravel para solicitar el catálogo de productos o enviar formularios de inicio de sesión.

### 4. Capa de Datos

Laravel procesa la lógica de negocio y, cuando necesita leer o escribir datos, se conecta exclusivamente de forma interna a la base de datos MySQL alojada en **Amazon RDS**. La respuesta se devuelve al frontend en formato **JSON**.

---

## 3. Arquitectura Interna de las Aplicaciones

### Frontend (Vue.js)

Se ha adoptado una arquitectura basada en **Componentes**. La interfaz de usuario se divide en piezas reutilizables como `ProductCard`, `Navbar` o `ReviewList`.

El **enrutamiento** se gestiona en el lado del cliente (Vue Router), lo que permite navegar entre las distintas secciones del catálogo sin recargar la página. La persistencia de datos temporales (token de sesión, carrito) se gestiona de forma reactiva con **Pinia**.

### Backend (Laravel)

El backend expone una **API RESTful estricta y sin estado (Stateless)**, devolviendo únicamente respuestas JSON. Sigue el patrón de diseño **MVC** adaptado para APIs:

| Capa | Archivo | Responsabilidad |
|---|---|---|
| **Rutas** | `routes/api.php` | Definen los endpoints disponibles (`GET /products`, `POST /login`) |
| **Controladores** | `app/Http/Controllers/` | Reciben la petición HTTP, validan los datos de entrada y orquestan la lógica de negocio |
| **Modelos** | `app/Models/` | Representan las tablas de la base de datos y gestionan las relaciones (ej. un `Producto` tiene muchas `Reseñas`) mediante Eloquent ORM |
| **Resources** | `app/Http/Resources/` | Capa de transformación que da formato a los datos JSON antes de enviarlos al cliente, ocultando información sensible |
