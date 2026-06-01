# ☁️ Glamur Club — Arquitectura Cloud en AWS

**Integrantes:** Adrián Becerra y Jose Juan Alemany
**Documento:** Diseño de Infraestructura y Alta Disponibilidad
**Identificador:** Grupo 04

> **Objetivo de la Arquitectura:** Garantizar la alta disponibilidad, seguridad, rendimiento y tolerancia a fallos del e-commerce Glamur Club, separando estrictamente las responsabilidades entre la capa pública, la capa de aplicación y la capa de base de datos, siguiendo las mejores prácticas del **AWS Well-Architected Framework**.

---

## 1. Diseño de Red (VPC y Subredes)

El proyecto se despliega sobre una **VPC propia** (Virtual Private Cloud) con el bloque CIDR `10.0.0.0/16`, distribuida a través de dos Zonas de Disponibilidad (`eu-west-1a` y `eu-west-1b`) para asegurar la tolerancia a fallos.

El particionado de subredes se estructura en tres capas diferenciadas:

### Capa Edge — Subredes Públicas

**Rangos:** `10.0.1.0/24` y `10.0.2.0/24`

Contienen la instancia EC2 que actúa como balanceador y proxy inverso (Nginx). Tienen acceso directo a Internet a través de un **Internet Gateway (IGW)** configurado en su tabla de rutas (`0.0.0.0/0 → IGW`).

### Capa de Aplicación — Subredes Privadas

En la implementación actual, esta capa está virtualizada dentro de la propia EC2 mediante la red interna de Docker (`127.0.0.1`). Los contenedores de Vue y Laravel están aislados del exterior y no disponen de IP pública.

Si la aplicación escalase a múltiples servidores, se ubicarían en subredes privadas `10.0.3.0/24`, necesitando un **NAT Gateway** en la subred pública para gestionar las descargas de actualizaciones desde Internet.

### Capa de Datos — Subredes Privadas de Base de Datos

**Rangos:** `10.0.5.0/24` y `10.0.6.0/24`

Aquí reside el servicio de base de datos relacional (**Amazon RDS**). Estas subredes no tienen ninguna ruta hacia el Internet Gateway, haciéndolas completamente invisibles y bloqueadas desde el exterior.

---

## 2. Aislamiento y Seguridad (Security Groups)

La seguridad perimetral se gestiona mediante reglas estrictas de firewall aplicando el principio de **mínimo privilegio**.

### `SG_Edge` — Instancia EC2

Único punto de entrada a la aplicación.

| Dirección | Puerto(s) | Protocolo | Origen | Propósito |
|---|---|---|---|---|
| Inbound | 80 | HTTP | `0.0.0.0/0` | Tráfico público de usuarios |
| Inbound | 443 | HTTPS | `0.0.0.0/0` | Tráfico seguro de usuarios |
| Inbound | 22 | SSH | IPs de administración | Acceso restringido al equipo |

### `SG_Backend` — Contenedor Laravel (Docker)

El contenedor de Laravel expone el puerto `8000`, pero está vinculado estrictamente a `127.0.0.1`. Esto asegura que **solo el proxy Nginx local** puede enviarle peticiones. Ningún escaneo de puertos externo puede detectar ni alcanzar la API directamente.

### `SG_Database` — Amazon RDS

Aislado totalmente de Internet. Solo tiene una regla activa:

| Dirección | Puerto | Protocolo | Origen | Propósito |
|---|---|---|---|---|
| Inbound | 3306 | MySQL | `sg-0369b9c8227f61ada` (SG de la EC2) | Conexiones exclusivas desde la capa de aplicación |

Queda estrictamente denegado cualquier intento de acceso directo desde máquinas o administradores ajenos a la capa de aplicación.

---

## 3. Alta Disponibilidad y Escalabilidad en Base de Datos

Para un entorno de producción real de e-commerce, la arquitectura de base de datos implementa los siguientes mecanismos avanzados:

### Despliegue Multi-AZ

RDS está configurado con arquitectura **Multi-AZ**. AWS mantiene una instancia principal en `eu-west-1a` y una réplica síncrona exacta en `eu-west-1b`. Si el hardware principal falla, el sistema realiza un **failover automático** cambiando el DNS interno en menos de 35 segundos, sin pérdida de datos.

### Réplicas de Lectura (Read Replicas)

Dado que Glamur Club es un catálogo online, aproximadamente el **80% del tráfico son consultas de lectura** (`SELECT` de productos). Se habilita una **Réplica de Lectura asíncrona** para que la API derive las consultas del catálogo hacia la réplica, liberando a la base de datos principal para que gestione únicamente las escrituras (creación de usuarios, compras, reseñas).

---

## 4. Esquema de Backup y Recuperación

La protección de la información vital de los clientes se garantiza mediante políticas automatizadas en RDS:

### Backups Automatizados

Activados con un **periodo de retención de 7 días**. RDS realiza una copia de seguridad incremental diaria durante la ventana de mantenimiento nocturna configurada.

### Point-in-Time Recovery (PITR)

Gracias a la retención de los *transaction logs* cada 5 minutos, es posible restaurar la base de datos a cualquier segundo exacto dentro de los últimos 7 días. Por ejemplo, si un despliegue defectuoso corrompe la base de datos a las 14:32, se puede restaurar una instancia idéntica con el estado de las 14:31.

### Snapshots Manuales

Se genera una imagen completa estática (*Snapshot*) de forma manual **antes de cualquier actualización mayor** del framework Laravel o cambios críticos en la estructura de datos (migraciones pesadas), actuando como punto de restauración garantizado ante cualquier imprevisto.
