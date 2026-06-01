# 🚀 Guía de Arranque Rápido: Glamur Club (Entorno Local)

**Desarrollado por:** Adrián Becerra y Jose Juan Alemany

Bienvenido al entorno de desarrollo local de **Glamur Club**. Sigue estos pasos en orden estricto para levantar la infraestructura en tu máquina. Solo tendrás que hacer la configuración inicial completa la primera vez.

---

## 🛠️ Requisitos Previos

Asegúrate de tener instalados en tu ordenador:

- **Docker Desktop** (encendido y funcionando)
- **Git** (para clonar el código)

---

## 🟢 Paso 1: Descargar el Código

Abre tu terminal y clona los repositorios del proyecto. Si ya los tienes, asegúrate de hacer un `git pull` para tener la última versión de las ramas.

```bash
git clone [URL_DEL_REPOSITORIO_BACKEND] backend
git clone [URL_DEL_REPOSITORIO_FRONTEND] frontend
```

---

## 🔐 Paso 2: Variables de Entorno

> ⚠️ Antes de arrancar nada, necesitamos configurar las variables secretas en ambos proyectos.

**1. En el Backend:** Entra en la carpeta del backend y duplica el archivo de ejemplo:

```bash
cd backend
cp .env.example .env
```

**2. En el Frontend:** Abre una nueva terminal, entra en la carpeta del frontend y repite el proceso:

```bash
cd frontend
cp .env.example .env
```

---

## 🌉 Paso 3: Crear el Puente de Red *(Solo la primera vez)*

Para que el Frontend y el Backend puedan comunicarse entre ellos en tu ordenador (por ejemplo, para peticiones a la API), necesitamos crear una red externa en Docker:

```bash
docker network create glamur_network
```

> ℹ️ Si el comando devuelve un mensaje indicando que la red ya existe, ignóralo y continúa con el siguiente paso.

---

## 🐘 Paso 4: Arrancar el Backend (Laravel + BD)

El Backend siempre va primero, porque levanta el contenedor de MySQL que necesita el Frontend.

**1.** Entra en la carpeta del backend:

```bash
cd backend
```

**2.** Levanta los contenedores en segundo plano:

```bash
docker compose up -d --build
```

**3.** Genera la clave de encriptación de Laravel *(solo la primera vez)*:

```bash
docker compose exec backend php artisan key:generate
```

**4.** Espera unos **15-20 segundos**. MySQL tarda un momento en inicializarse la primera vez.

**5.** Inyecta los datos de prueba (rellena la base de datos con los productos, usuarios y categorías iniciales):

```bash
docker compose exec backend php artisan migrate:fresh --seed
```

> ✅ **Resultado:** Tu API está viva y respondiendo en `http://localhost:8000`

---

## 💻 Paso 5: Arrancar el Frontend (Vue)

Ahora le toca el turno a la web interactiva que verán los usuarios.

**1.** Abre una nueva pestaña en tu terminal y entra en la carpeta del frontend:

```bash
cd frontend
```

**2.** Levanta el contenedor en segundo plano:

```bash
docker compose up -d --build
```

> ✅ **Resultado:** Tu web está viva y respondiendo en `http://localhost:5173`

---

## 🛑 Cómo Apagar el Proyecto al Terminar

Cuando acabes tu jornada, **nunca cierres la terminal bruscamente ni apagues el ordenador directamente**. Debes detener los contenedores correctamente para no corromper la base de datos.

**1.** Ve a la terminal del Frontend y ejecuta:

```bash
docker compose down
```

**2.** Ve a la terminal del Backend y ejecuta:

```bash
docker compose down
```

> 💾 Tranquilo, tus datos no se borrarán porque hemos configurado un **volumen persistente** en Docker.

---

## 🆘 Solución de Problemas Frecuentes

### Error 500 en Vue o "Network Error"

El Frontend no encuentra la API. Comprueba en tu navegador que `http://localhost:8000/api` responde. Si no lo hace, el Backend no está bien arrancado o los dos contenedores no están en la misma red de Docker.

---

### `Table 'glamur_db.sessions' doesn't exist`

Te has saltado el Paso 4.5. Ejecuta las migraciones para crear las tablas:

```bash
docker compose exec backend php artisan migrate:fresh --seed
```

---

### Error de encriptación: `No application encryption key`

Te has saltado el Paso 4.3. Genera la clave con:

```bash
docker compose exec backend php artisan key:generate
```

---

### Los cambios en el código Vue no se ven (Hot Reload)

Refresca la página manualmente con `F5`. Si sigues sin verlo, asegúrate de que estás modificando los archivos dentro de la carpeta local correcta. Gracias a los *bind mounts*, la sincronización de archivos debería ser inmediata.
