# 🚀 Glamur Club — Entorno de Producción y Despliegue (CI/CD)

**Desarrollado por:** Adrián Becerra y Jose Juan Alemany
**Identificador:** Grupo 04

---

## 1. Entorno de Producción

> **Objetivo:** Proveer una infraestructura altamente disponible, segura y escalable que sirva la aplicación final a los usuarios, garantizando el aislamiento de los datos y el rendimiento óptimo del sistema.

### Infraestructura Desplegada

El entorno de producción se aloja íntegramente en **AWS** bajo una arquitectura de contenedores estructurada en tres capas:

**Edge Layer (EC2 + Nginx):** Una instancia EC2 actúa como punto único de entrada. Nginx funciona como proxy inverso, gestionando la terminación HTTPS mediante certificados automáticos de **Let's Encrypt (Certbot)** y derivando el tráfico internamente.

**Application Layer (Docker):** El Backend (Laravel 12) y el Frontend (Vue.js) se ejecutan en contenedores Docker independientes orquestados con `docker-compose.prod.yml`. Están aislados del exterior y solo reciben peticiones desde el proxy Nginx a través del `localhost` de la máquina.

**Data Layer (Amazon RDS):** La base de datos MySQL de producción está externalizada en un servicio gestionado (RDS) dentro de una subred privada, aislada de Internet y configurada para aceptar únicamente conexiones originadas desde la capa de aplicación.

### Acceso y Gestión de Secretos

El acceso al servidor de producción está restringido exclusivamente mediante **autenticación por clave asimétrica (SSH Key)**. Las credenciales de la base de datos y de la API no se versionan en el repositorio; se inyectan a través de variables de entorno (archivos `.env` protegidos físicamente en la instancia EC2).

---

## 2. Flujos de Integración y Despliegue Continuo (CI/CD)

Para garantizar un ciclo de vida del software ágil y libre de errores humanos, el proyecto implementa **dos pipelines de CI/CD totalmente independientes** en GitHub Actions. Esta arquitectura garantiza que una actualización en la interfaz visual no afecte ni reinicie la API, y viceversa.

El despliegue se dispara automáticamente cuando un desarrollador realiza un `push` o fusiona un Pull Request hacia la rama de despliegue principal (`sprint5-6`).

### Pipeline del Backend (Laravel)

| Fase | Descripción |
|---|---|
| **Checkout y Setup** | El runner clona el repositorio y configura el entorno con PHP 8.4 |
| **Instalación de Dependencias** | Se ejecuta `composer install --no-dev` para instalar únicamente paquetes de producción, omitiendo herramientas de testing o depuración |
| **Testing Automático** | Se ejecutan las baterías de pruebas unitarias y funcionales (`php artisan test`). Si un test falla, el pipeline aborta para proteger el servidor de producción |
| **Despliegue (SSH)** | Mediante una conexión SSH segura (utilizando secrets de GitHub), el runner actualiza el código en la instancia EC2 |
| **Migración (Crítico)** | Se reinicia el contenedor de producción y se ejecuta `php artisan migrate --force` de forma desatendida para inyectar cualquier cambio en la estructura de la base de datos |

### Pipeline del Frontend (Vue)

| Fase | Descripción |
|---|---|
| **Checkout y Setup** | El runner configura el entorno con Node.js 20 |
| **Instalación** | Se descargan los paquetes definidos ejecutando `npm install` |
| **Build de Producción** | Vite compila, minimiza y empaqueta los assets estáticos (HTML, JS, CSS Tailwind). Durante esta fase se inyecta la URL pública de la API (`https://api.projecte04.ddaw.es`) en las variables de entorno de Vue |
| **Despliegue** | Se envía la versión compilada a la EC2 y se levanta el contenedor Nginx del frontend estático |

---

## 3. Normas de Contribución del Equipo

Para mantener la integridad del código en un entorno colaborativo, el equipo sigue las siguientes políticas estrictas:

**Estrategia de Ramas:** Se utiliza un modelo basado en sprints y funcionalidades. La rama principal (`main` / `sprint5-6`) refleja el estado de producción y es sagrada. El desarrollo de nuevas características se realiza en ramas separadas (ej. `feature/catalogo`, `fix/login`).

**Proceso de Revisión de Código:** No se permiten commits directos a la rama de producción. Toda integración requiere un **Pull Request (PR)** que debe ser validado por el equipo.

**Criterios de Aceptación:** Antes de fusionar un PR, el código debe superar el escáner de los tests automatizados (CI) y no contener dependencias de desarrollo no resueltas.

---

## 4. Usuarios de Prueba (Evaluación)

Para la revisión de la aplicación por parte del equipo docente, se ha habilitado un seeder que inyecta cuentas de prueba con roles predefinidos:

| Rol | Email | Contraseña |
|---|---|---|
| **Administrador** | `admin@glamurclub.com` | `admin123` |
| **Cliente Estándar** | `cliente@glamurclub.com` | `password` |
