# 🌍 Glamur Club — Documentación Global del Sistema

**Desarrollado por:** Adrián Becerra y Jose Juan Alemany
**Identificador:** Grupo 04

---

## 1. Relación entre Frontend, Backend y Base de Datos

El proyecto implementa una **arquitectura cliente-servidor totalmente desacoplada**.

El **Frontend** (SPA en Vue.js) se encarga exclusivamente de la interfaz de usuario y la gestión del estado en el navegador del cliente. No realiza conexiones directas a la base de datos. En su lugar, realiza peticiones asíncronas HTTP (vía Axios) al **Backend** (Laravel).

El **Backend** actúa como cerebro lógico del sistema, validando las reglas de negocio y comunicándose de forma privada y segura con la **Base de Datos** (MySQL) para extraer o persistir la información, devolviendo los resultados al Frontend en formato JSON.

---

## 2. Los Diferentes Entornos

Para garantizar un ciclo de vida seguro del software, el proyecto cuenta con dos entornos completamente aislados:

| Entorno | Infraestructura | Objetivo |
|---|---|---|
| **Desarrollo (Local)** | Máquinas de los desarrolladores con Docker | Creación y testeo de nuevas funcionalidades con Hot Reload y base de datos local |
| **Producción (AWS)** | Servidores cloud optimizados | Servir la aplicación a usuarios reales con rendimiento, seguridad y conexiones HTTPS cifradas |

---

## 3. Infraestructura Cloud (AWS)

La infraestructura en la nube está contenida en una **VPC** (Virtual Private Cloud) que separa lógicamente los recursos en tres capas:

| Capa | Componente | Descripción |
|---|---|---|
| **Capa Pública** | EC2 + Nginx | Único punto de entrada a Internet. Gestiona la terminación HTTPS y actúa como proxy inverso |
| **Capa de Aplicación** | Contenedores Docker (Vue + Laravel) | Operan en los puertos internos de la EC2, completamente aislados del exterior |
| **Capa de Datos** | Amazon RDS (MySQL) | Subred privada sin enrutamiento hacia el Internet Gateway, invisible e inaccesible desde el exterior |

---

## 4. Sistema DNS

La resolución de nombres se gestiona delegando los servidores de nombres (NS) de la zona hija al administrador del dominio padre (`ddaw.es`). Se han configurado dos registros tipo `A` apuntando a la IP pública (Elastic IP) de la instancia EC2:

| Registro | Tipo | Destino |
|---|---|---|
| `www.projecte04.ddaw.es` | A | IP Elástica EC2 — Sirve la aplicación Vue |
| `api.projecte04.ddaw.es` | A | IP Elástica EC2 — Sirve la API de Laravel |

---

## 5. Medidas de Seguridad Implementadas

**Seguridad Perimetral (Security Groups):** La instancia EC2 solo permite tráfico entrante por los puertos `80` y `443`. La base de datos RDS solo acepta conexiones procedentes del Security Group de la EC2, bloqueando cualquier acceso externo directo.

**Cifrado en Tránsito (HTTPS):** Todo el tráfico entre el usuario y el servidor se cifra mediante HTTPS. Se utiliza **Let's Encrypt con Certbot** para la generación y renovación automática de los certificados SSL/TLS. Nginx fuerza la redirección del puerto `80` al `443`.

**Aislamiento Interno:** Los contenedores no exponen puertos al exterior (`0.0.0.0`), sino que están vinculados a `127.0.0.1`. Solo el proxy inverso local de Nginx puede comunicarse con ellos, haciendo la API y el frontend invisibles a escaneos de puertos externos.
