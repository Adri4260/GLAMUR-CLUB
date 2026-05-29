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

# 🚀 Guía de Arranque Rápido: Glamur Club (Local)

Sigue estos pasos en orden estricto. Solo tendrás que hacer la configuración inicial la primera vez.

---

## 🛠️ Requisitos Previos

Asegúrate de tener instalados en tu ordenador:

- **Docker Desktop** (encendido y funcionando)
- **Git** (para clonar el código)

## 🟢 PASO 0: Limpieza

```bash
docker compose down -v
docker system prune -f
```

## 🟢 PASO 1: Descargar el Código

Abre tu terminal y clona los repositorios del proyecto. Si ya los tienes, asegúrate de hacer un `git pull` para tener la última versión.

```bash
# Ejemplo:
git clone https://github.com/Adri4260/GLAMUR-CLUB
```

---

## 🌉 PASO 2: Crear el Puente de Red *(Solo la primera vez)*

Para que el Frontend y el Backend puedan "hablar" entre ellos en tu ordenador, necesitamos crear una red en Docker. Abre tu terminal (da igual en qué carpeta) y lanza:

```bash
docker network create glamur_network
```

> ℹ️ Si te dice que ya existe, ignóralo y sigue.

---

## 🐘 PASO 3: Arrancar el Backend (Laravel + BD)

El Backend siempre va primero, porque levanta la Base de Datos que necesita el Frontend.

**1.** Abre una terminal y entra en la carpeta del backend:

```bash
cd backend
```

**2.** Levanta los contenedores en segundo plano:

```bash
docker compose up -d --build
```

**3.** Espera unos **15 segundos**. MySQL tarda un poco en arrancar por primera vez.

**4.** Inyecta los datos de prueba — rellena la base de datos con los productos, usuarios y categorías iniciales:

```bash
docker compose exec backend php artisan migrate:fresh --seed
```

✅ **Resultado:** Tu API está viva y respondiendo en `http://localhost:8000`.

---

## 💻 PASO 4: Arrancar el Frontend (Vue)

Ahora le toca el turno a la web que verán los usuarios.

**1.** Abre una **nueva pestaña** en tu terminal y entra en la carpeta del frontend:

```bash
cd frontend
```

**2.** Levanta el contenedor en segundo plano:

```bash
docker compose up -d --build
```

✅ **Resultado:** Tu web está viva y respondiendo en `http://localhost:5173`.

---

## 🛑 ¿Cómo apagar el proyecto al terminar de trabajar?

Cuando acabes tu jornada, **NUNCA** cierres la terminal a lo bruto. Debes apagar los contenedores correctamente para no corromper la base de datos.

**1.** Ve a la terminal del **Frontend** y ejecuta:

```bash
docker compose down
```

**2.** Ve a la terminal del **Backend** y ejecuta:

```bash
docker compose down
```

> 💾 Tranquilo, tus datos no se borrarán porque hemos configurado un volumen persistente.

---

## 🆘 Solución de Problemas Frecuentes

### Error 500 en Vue
Significa que el Frontend no encuentra la API. Comprueba en tu navegador que `http://localhost:8000/api` responde algo (aunque sea un error de Laravel). Si no responde, tu Backend no está bien arrancado.

### `Table 'glamur_db.sessions' doesn't exist`
Te has saltado el **Paso 3.4**. Ejecuta:

```bash
docker compose exec backend php artisan migrate:fresh --seed
```

### Cambios en el código no se ven (Hot Reload)
Refresca la página manualmente con `Ctrl+F5`. Si sigues sin verlo, asegúrate de que estás modificando los archivos dentro de la carpeta local correcta, porque los bind mounts sincronizan al instante.

*Glamur Club – Entrega Final v1.0.0 (Sprint 6)*
