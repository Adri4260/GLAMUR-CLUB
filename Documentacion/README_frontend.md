# 💄 Glamur Club — Frontend (Vue.js)

**Desarrollado por:** Adrián Becerra y Jose Juan Alemany

---

## 📖 Descripción del Proyecto

Este repositorio contiene el código fuente del frontend de **Glamur Club**, una Single Page Application (SPA) orientada al comercio electrónico de perfumería y cosmética de lujo. La aplicación proporciona una interfaz de usuario reactiva, rápida e intuitiva para la exploración del catálogo, gestión del carrito de compras y administración de perfiles, consumiendo los datos a través de una API RESTful.

---

## 🛠️ Stack Tecnológico

| Tecnología | Rol |
|---|---|
| **Vue.js 3** (Composition API) | Framework principal de la SPA |
| **Vite** | Empaquetador y servidor de desarrollo |
| **Tailwind CSS** | Estilos y diseño responsivo |
| **Pinia** | Gestión de estado global (carrito y sesión de usuario) |
| **Vue Router** | Enrutamiento del lado del cliente |
| **Axios** | Peticiones HTTP a la API |
| **Docker & Docker Compose** | Contenerización del entorno |
| **Nginx** | Servidor web en producción |

---

## 🏗️ Arquitectura Interna y Estructura de Carpetas

El frontend sigue una arquitectura basada en **componentes modulares**, separando la lógica de negocio, el estado global y la presentación visual.

```
/frontend
├── /public               # Assets estáticos (favicon, imágenes genéricas)
├── /src
│   ├── /assets           # Estilos globales y recursos locales
│   ├── /components       # Componentes Vue reutilizables (Botones, Tarjetas, Navbars)
│   ├── /views            # Vistas de página completa (Home, Catálogo, Login, Checkout)
│   ├── /router           # Configuración de Vue Router y guardias de navegación
│   ├── /stores           # Estado global con Pinia (useCartStore, useAuthStore)
│   └── main.js           # Punto de entrada de la aplicación Vue
├── Dockerfile            # Contenedor para el entorno de desarrollo
├── Dockerfile.prod       # Contenedor para compilar y servir estáticos con Nginx
├── docker-compose.yml    # Orquestación del entorno local
└── vite.config.js        # Configuración del servidor de desarrollo Vite
```

---

## ⚙️ Variables de Entorno

Nunca se suben secretos reales al repositorio. Duplica el archivo `.env.example` y renómbralo a `.env` antes de arrancar el proyecto.

```env
# URL base de la API de Laravel
VITE_API_BASE_URL=http://localhost:8000/api

# Clave pública para servicios de terceros (si aplica)
VITE_GOOGLE_CLIENT_ID=tu_clave_publica_aqui
```

---

## 💻 Ejecución en Desarrollo (Local)

El entorno de desarrollo está completamente contenerizado para evitar problemas de dependencias.

**1.** Clona el repositorio y configura tu `.env` siguiendo el paso anterior.

**2.** Asegúrate de estar en la misma red de Docker que el backend:

```bash
docker network create glamur_network
```

**3.** Levanta el contenedor:

```bash
docker compose up -d --build
```

**4.** La aplicación estará disponible en `http://localhost:5173`.

**5.** Para detener el entorno sin perder datos:

```bash
docker compose down
```

---

## 🚀 Build y Despliegue en Producción

Para desplegar la aplicación no se utiliza el servidor de desarrollo de Vite. En su lugar, se ejecuta un proceso de *build* que empaqueta y minimiza el código JavaScript, CSS y HTML en archivos estáticos dentro de la carpeta `/dist`.

Estos archivos estáticos se montan en un contenedor de producción ligero basado en **Alpine Linux con Nginx**, el cual sirve la web de forma ultrarrápida y segura, aislado del exterior y recibiendo el tráfico únicamente a través del proxy inverso de la instancia EC2.

---

## 🔄 Flujo CI/CD (De Commit a Producción)

El proyecto cuenta con un pipeline de Integración y Despliegue Continuo gestionado por **GitHub Actions**.

| Fase | Descripción |
|---|---|
| **Trigger** | Se activa al hacer un `push` o fusionar un Pull Request en la rama `sprint5-6` |
| **Setup** | El runner de GitHub levanta un entorno con Node.js 20 |
| **Dependencias** | Ejecuta `npm install` |
| **Build** | Ejecuta `npm run build` inyectando la URL pública de producción en `VITE_API_BASE_URL` |
| **Despliegue** | Mediante SSH, el runner se conecta a la EC2, descarga los cambios y reinicia el servicio con `docker compose -f docker-compose.prod.yml up -d --build` |

---

## 📈 Escalabilidad y Disponibilidad

**Stateless:** Al ser una SPA servida mediante Nginx, el frontend no guarda estado en el servidor. Toda la sesión reside en el navegador del cliente (Pinia).

**Caché y CDN:** Los assets estáticos generados por Vite llevan hashes únicos en sus nombres de archivo, lo que permite cachearlos agresivamente en los navegadores y distribuirlos a través de un CDN a nivel global sin riesgo de conflictos.

---

## 🤝 Normas de Contribución

**Estrategia de Ramas:** Se trabaja mediante *Feature Branches* (`feature/nombre-funcionalidad`, `fix/nombre-error`). La rama principal se considera sagrada y siempre debe estar en estado estable.

**Revisión de Código:** No se permite subir código directamente a producción. Todo cambio entra mediante Pull Request y es revisado por otro miembro del equipo.

**Code Style:** Se utilizan **ESLint** y **Prettier** preconfigurados en el proyecto para garantizar un estilo de escritura uniforme (comillas simples, indentación de 2 espacios).

**Distribución de Tareas:** Las tareas están asignadas y monitorizadas mediante un tablero Kanban en **GitHub Projects** para equilibrar la carga de trabajo.

---

## 👥 Usuarios de Prueba

Para la evaluación del sistema, se proporcionan las siguientes credenciales genéricas inyectadas en la base de datos:

| Rol | Email | Contraseña |
|---|---|---|
| **Administrador** | `admin@glamurclub.com` | `admin123` |
| **Cliente Estándar** | `cliente@glamurclub.com` | `password` |
