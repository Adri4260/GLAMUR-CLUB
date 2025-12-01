# 💜 GLAMUR CLUB

![Status](https://img.shields.io/badge/Estat-En%20Desenvolupament-green?style=flat-square) ![Version](https://img.shields.io/badge/Versió-Sprint%202-purple?style=flat-square) ![License](https://img.shields.io/badge/Llicència-Educational-blue?style=flat-square)

## 🧴 Descripció del projecte
**GLAMUR CLUB** és un e-commerce exclusiu dedicat a la venda de **perfums i productes de bellesa i higiene**.
El lloc destaca per la seva funcionalitat innovadora: la possibilitat de **crear el teu propi perfum personalitzat**, combinant aromes segons els gustos de l’usuari o mitjançant suggeriments basats en **Intel·ligència Artificial**.

Aquest projecte forma part de la **Iteració 1: Entorn, escaparate i contacte**, desenvolupat dins del curs **DAW 2n – CIPFP Batoi**.

### 👨‍💻 Equip de Desenvolupament
| Membre | Rol | Contacte |
| :--- | :--- | :--- |
| **Adrián Becerra Pérez** | Full Stack Dev & DevOps | [GitHub](https://github.com/adri4260) |
| **Jose Juan Alemany Márquez** | Full Stack Dev & UX/UI | [GitHub](https://github.com/Pepe1109) |

---

## 🧠 Metodologia de treball

El desenvolupament segueix pràctiques d'integració contínua i treball col·laboratiu:

* **Control de versions:** Git + GitHub.
* **Flux de treball:** Estructura basada en **feature branches** (una branca per funcionalitat).
* **Qualitat:** Revisió de codi (Code Review) abans de fusionar a la branca principal.
* **Planificació:** Organització mitjançant tauler Kanban i seguiment temporal amb diagrames de Gantt.

---

## 🚀 Funcionalitats Implementades (Sprints)

El projecte s'ha estructurat seguint les fites del client (C1-C5):

### 🧩 C1. Importació de Dades (Excel → JSON)
Sistema automatitzat per poblar el catàleg de productes.
- **Flux:** Càrrega de fitxer `.xlsx` o `.csv` → Processament amb `PhpSpreadsheet` → Conversió a JSON → Enviament a `JSON Server`.
- **Objectiu:** Evitar la introducció manual de productes.

### 👥 C2. Sistema d'Autenticació (Auth)
Gestió completa d'usuaris utilitzant JSON Server com a persistència.
- **Funcions:** Registre, Login (sessions PHP + Cookies), i edició de Perfil.
- **Seguretat:** Hash de contrasenyes amb `bcrypt` i validació de duplicats.

### 💬 C3. Social: Comentaris i Valoracions
Interacció dinàmica en temps real mitjançant **AJAX / Fetch API**.
- **Features:** Valoracions (1-5 estrelles), "M'agrada" i comentaris d'usuaris.
- **Dinamisme:** Actualització de la interfície sense recarrega de pàgina.

### ☁️ C4. Infraestructura i Desplegament (AWS)
Arquitectura al núvol robusta i segura per a producció.
- **Servidors:** Apache (HTTP/S) amb Virtual Hosts separats (`app` i `backup`).
- **Seguretat:** Certificats SSL/TLS, usuaris aïllats, i restriccions FTP.
- **Backups:** Script automatitzat nocturn per a còpies de seguretat remotes.

### 🧭 C5. UX/UI i Frontend Modern
Disseny centrat en l'usuari amb **Vite** i CSS modern ("Dark Emerald Theme").
- **Components:** Cercador, Filtres per categoria, Carret visible, i "Hero" animat.
- **Responsive:** Adaptació total a dispositius mòbils.

---

## 🛠️ Stack Tecnològic

### 🖥️ Entorn Client (Frontend)
- **Core:** HTML5, CSS3, JavaScript, PHP (ES6 Modules).
- **Build Tool:** [Vite](https://vitejs.dev/) per a un entorn de desenvolupament ràpid.
- **Estils:** CSS natiu amb variables (Custom Properties).

### 🐳 Entorn Servidor (Backend & DevOps)
- **Containerització:** Docker & Docker Compose.
- **Llenguatge:** PHP 8.3 (FPM).
- **Base de Dades (Simulada):** JSON Server (Node.js).
- **Dependències:** Composer (`phpoffice/phpspreadsheet`).

---

## 🗂️ Gestió del Projecte

Seguiment de tasques i planificació temporal:

| Recurs | Enllaç / Arxiu | Descripció |
| :--- | :--- | :--- |
| **Kanban Board** | [🔗 Veure Tauler de Projecte](https://github.com/users/adri4260/projects/5) | *Estat de les tasques (To Do, In Progress, Done)* |
| **Gantt Sprint 1** | `ganttSprint1.gan` | *Planificació inicial i setup* |
| **Gantt Sprint 2** | `ganttSprint2.gan` | *Autenticació i funcionalitats core* |

---

## ☁️ Infraestructura AWS (Adrián)

Detalls del desplegament al núvol per a la correcció i accés:

| Servei | Detall / URL |
| :--- | :--- |
| **Compte AWS** | **[Compte d'Adrián]** |
| **Domini Principal** | `https://app.glamurclub.es` |
| **Domini Backups** | `https://backup.glamurclub.es` |
| **Domini Test** | `https://test.glamurclub.es` |
| **Accés SSH** | Usuari: `ubuntu` (Claus públiques autoritzades) |
| **IP elástica** | 98.95.115.229 |
| **Llançar json** | npx json-server --watch /home/usuariElegit/ftp/www/Web/vite-project/public/data/datos.json --port 3000 |
| **FTP** | Port 21 (Mode passiu 30000-30050) |

> **Nota:** L'accés als backups està protegit per `mod_auth` (Usuari: profe / Contrasenya: 1234).

---

## ⚠️ Prevenció de riscos laborals i seguretat

Pla de prevenció per garantir la salut de l'equip i la seguretat del projecte.

### 👩‍💻 Riscos laborals del personal

| **Tipus de risc** | **Descripció** | **Mesures preventives** |
|--------------------|----------------|---------------------------|
| 🖥️ **Fatiga visual** | Exposició prolongada a pantalles. | 🔹 Pauses visuals cada 60 min.<br>🔹 Mode fosc a l'IDE i web.<br>🔹 Filtres de llum blava. |
| 💺 **Ergonomia** | Postura inadequada o mobiliari no ergonòmic. | 🔹 Cadires ergonòmiques.<br>🔹 Estiraments periòdics.<br>🔹 Ajust d'altura de monitors. |
| ⏱️ **Estrès** | Compliment de terminis ajustats (Sprints). | 🔹 Metodologia Agile/Kanban.<br>🔹 Planificació realista.<br>🔹 Pauses regulars. |
| 🧠 **Psicosocial** | Aïllament en treball remot. | 🔹 Reunions periòdiques (Dailies).<br>🔹 Suport emocional i tècnic. |

### 🏢 Riscos de l’empresa i del projecte

| **Tipus de risc** | **Descripció** | **Mesures preventives** |
|--------------------|----------------|---------------------------|
| 🔒 **Seguretat digital** | Accés no autoritzat a dades. | 🔹 Contrasenyes segures (bcrypt).<br>🔹 Repositoris privats.<br>🔹 HTTPS i SSH sense root. |
| ☁️ **Pèrdua de dades** | Fallada del servidor o errors de desplegament. | 🔹 **Backups nocturns automatitzats** a host remot.<br>🔹 Entorn de proves separat. |
| 🪪 **RGPD/LOPD** | Gestió de dades personals. | 🔹 Polítiques de privacitat clares.<br>🔹 Informació sobre l'ús de cookies. |
| 💰 **Tecnològic** | Fallada d'eines externes (Docker, GitHub). | 🔹 Alternatives documentades.<br>🔹 Manteniment de dependències al dia. |

---

## ⚙️ Instal·lació Local

Per aixecar el projecte en local:

1. **Clonar el repositori:**
   ```bash
   git clone [https://github.com/adri4260/glamur-club.git](https://github.com/adri4260/glamur-club.git)
   cd glamur-club
