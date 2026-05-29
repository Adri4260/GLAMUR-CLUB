# ⚜️ Glamur Club - Guía de Inicialización Local (Grup 04)

Este documento contiene la guía oficial paso a paso para clonar, configurar, arrancar y solucionar problemas del entorno de desarrollo local de **Glamur Club**. Sigue estas instrucciones minuciosamente para garantizar un despliegue limpio en tu máquina local sin conflictos de puertos ni servicios.

---

## 📌 Requisitos Previos

Antes de empezar, asegúrate de tener instalado en tu sistema operativo:

- **Docker** y **Docker Desktop** (versión actualizada).
- **Git** configurado con tu cuenta académica.
- **Node.js** (versión 20+ recomendada de forma opcional para tareas de depuración fuera de Docker).

---

## 🚀 Paso 1: Configuración en GitHub y Clonado

Para evitar errores de estructura, la arquitectura está desacoplada. Asegúrate de clonar el repositorio unificado de la siguiente manera.

La estructura interna del proyecto debe verse así:

```
glamur-club/
├── docker-compose.yml
├── laravel/            # Repositorio/Carpeta del Backend
│   ├── Dockerfile
│   └── .env.example
└── vue/                # Repositorio/Carpeta del Frontend
    ├── Dockerfile
    └── .env.example
```

---

## 🔑 Paso 2: Configuración de Variables de Entorno (.env)

Es obligatorio configurar las credenciales antes de levantar los contenedores para evitar fallos de conexión en la base de datos y la API.

### A) Backend (`laravel/.env`)

Copia el archivo de ejemplo y edita los siguientes valores clave:

```bash
cp laravel/.env.example laravel/.env
```

Asegúrate de que la conexión a la base de datos use el nombre del servicio Docker (`db`) y no `127.0.0.1`, y configura las URLs de cruce de puertos:

```env
APP_NAME="Glamur Club Backend"
APP_ENV=local
APP_URL=http://localhost:8000

# Conexión interna Docker de la Base de Datos
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=glamur_db
DB_USERNAME=root
DB_PASSWORD=root_password

# Control de Sesión obligatorio (si se usa Base de Datos)
SESSION_DRIVER=database

# URLs de Seguridad para evitar errores CORS e Integración OAuth
FRONTEND_URL=http://localhost:5173
GOOGLE_REDIRECT_URI=http://localhost:8000/api/oauth/google/callback
```

### B) Frontend (`vue/.env`)

Copia el archivo de ejemplo en la raíz del frontend:

```bash
cp vue/.env.example vue/.env
```

Configura la URL absoluta apuntando al puerto 8000 del backend de Docker:

```env
VITE_API_BASE_URL=http://localhost:8000/api
```

---

## 🐳 Paso 3: Construcción y Arranque con Docker

### 🛑 Comprobación de Seguridad Anti-Conflictos (CRÍTICO)

Antes de ejecutar Docker, debes asegurarte de que tu máquina local no tenga puertos secuestrados por bases de datos nativas o automatizaciones como n8n. Ejecuta en tu terminal:

```bash
# Detener contenedores zombis que usen el puerto 3306 (ej: mysql-n8n)
docker stop mysql-n8n 2>/dev/null

# Detener el servicio nativo de MySQL en tu Ubuntu local si existe
sudo systemctl stop mysql 2>/dev/null
sudo systemctl stop mariadb 2>/dev/null

# Limpiar procesos de Node colgados en el puerto 5173
sudo killall node 2>/dev/null
```

### ⚡ Lanzamiento de la Arquitectura

Una vez despejada la vía, ejecuta el comando de construcción desde la raíz del proyecto (donde reside el archivo `docker-compose.yml`):

```bash
# Construir y levantar los contenedores en segundo plano
docker compose up -d --build
```

Verifica que los tres servicios estén levantados con el comando:

```bash
docker compose ps
```

Los servicios `glamur_db`, `glamur_backend` y `glamur_frontend` deben mostrar el estado `Up`.

---

## 🛠️ Paso 4: Inicialización Interna del Backend y Frontend

Dado que los volúmenes de Docker nacen vacíos en local, debemos instalar las dependencias de Laravel y poblar la base de datos.

### 1. Instalación de dependencias de PHP (Composer) e inicialización de Laravel

```bash
# Acceder al contenedor del backend para ejecutar comandos artisan
docker compose exec backend composer install
docker compose exec backend php artisan key:generate
```

### 2. Ejecución de Migraciones y Seeders (Catálogo de Productos)

Si accedes a la web y recibes un error `SQLSTATE[42S02]: Table 'glamur_db.sessions' doesn't exist`, ejecuta este comando para crear las tablas del sistema e inyectar el lote de productos de prueba:

```bash
docker compose exec backend php artisan migrate:fresh --seed
```

### 3. Enlace simbólico de Imágenes (Storage Link)

Para que las imágenes almacenadas en el backend se puedan servir de manera pública hacia el frontend de Vue, conecta el almacenamiento ejecutando:

```bash
docker compose exec backend php artisan storage:link
```

---

## 🎨 Paso 5: Configuración de Axios e Imágenes en el Frontend (Vue)

### Conexión de la API (Axios)

Asegúrate de que tu archivo de configuración de Axios (ej. `src/services/axios.js` o `src/main.js`) concatene explícitamente el puerto del contenedor del backend (`:8000`):

```javascript
import axios from "axios";

const http = axios.create({
  baseURL: "http://localhost:8000/api", // Obligatorio puerto 8000
  headers: {
    Accept: "application/json",
    "Content-Type": "application/json",
  },
});
```

### Renderizado de Fotos de Productos

Dado que Vue corre en `localhost:5173` y las imágenes residen físicamente en el servidor de Laravel (`localhost:8000`), todas tus etiquetas `<img>` deben incluir el prefijo absoluto del backend.

**❌ Mal hecho** (busca la foto dentro de Vue):

```html
<img :src="producto.image" alt="Producto">
```

**✅ Bien hecho** (llama al contenedor correcto):

```html
<img :src="'http://localhost:8000/' + producto.image" alt="Producto">
```

---

## 🧯 Guía de Resolución de Errores Comunes

### 1. `Composer detected issues in your platform: Your Composer dependencies require a PHP version ">= 8.4.0"`

**Causa:** El archivo `Dockerfile` del backend está usando una versión obsoleta de PHP (ej. 8.2).

**Solución:** Abre `laravel/Dockerfile`, cambia la primera línea a `FROM php:8.4-cli` y regenera la máquina con:

```bash
docker compose up -d --build backend
```

### 2. `ports are not available: listen tcp 0.0.0.0:3306: bind: address already in use`

**Causa:** Tienes el servicio MySQL nativo de tu PC encendido o un contenedor antiguo activo.

**Solución:** Corre `sudo systemctl stop mysql` y comprueba con `sudo lsof -i :3306` quién está usando el puerto para matarlo con `sudo kill -9 <PID>`.

### 3. `Network "glamur-club_default" needs to be recreated`

**Causa:** Se interrumpió abruptamente el comando Docker Compose y los cables virtuales de red quedaron corruptos.

**Solución:** Ejecuta `docker network prune -f` para sanear los canales virtuales de Docker y vuelve a lanzar el comando de arranque.

### 4. `Error while fetching server API version: Connection refused`

**Causa:** Forzaste el cierre de procesos y mataste el motor ("demonio") general de Docker en tu sistema.

**Solución:** Reinicia los servicios de Docker nativos corriendo `sudo systemctl start docker` o levanta de nuevo la aplicación gráfica de Docker Desktop.

---

## 🛑 Protocolo de Apagado Seguro y Limpio

Nunca cierres la terminal ni apagues tu PC a la fuerza con Docker activo. Sigue estas buenas prácticas:

**Para pausas cortas de desarrollo:**

```bash
docker compose stop
```

Apaga los motores consumiendo 0% de CPU pero mantiene la infraestructura lista para un rápido `docker compose start`.

**Para cerrar el día por completo (Recomendado):**

```bash
docker compose down
```

Desmonta todo el entorno local de forma educada y libera los puertos 3306, 8000 y 5173 por completo para que tu máquina rinda al 100%. No perderás tus datos, ya que están protegidos en el volumen local.
