# 💜 GLAMUR CLUB

## 🧴 Descripció del projecte
**GLAMUR CLUB** és un e-commerce dedicat a la venda de **perfums i productes de bellesa i higiene**.  
A més, incorpora una funcionalitat innovadora: la possibilitat de **crear el teu propi perfum personalitzat**, combinant aromes segons els gustos de l’usuari o utilitzant **intel·ligència artificial** per suggerir fórmules úniques.

Aquest projecte forma part de la **Iteració 1: Entorn, escaparate i contacte**, desenvolupat dins del curs **DAW 2n – CIPFP Batoi** per:
- 👨‍💻 *Adrián Becerra Pérez*  
- 👨‍💻 *Jose Juan Alemany Márquez*

---

## ⚙️ Estructura del projecte
El projecte està separat en dos entorns principals:

### 🖥️ Entorn client
- **Framework:** [Vite](https://vitejs.dev/)
- **Tecnologies:** HTML5, CSS3, JavaScript (ES6)
- **Objectius:**
  - Maquetació de la pàgina d’inici (aparador de productes)
  - Formulari de contacte amb validació client-side
  - Disseny coherent amb la imatge corporativa

### 🐳 Entorn servidor
- **Eina:** Docker Compose
- **Components:**
  - Contenidor PHP/Apache
  - Base de dades MySQL
- **Objectius:**
  - Validació de dades al servidor
  - Emmagatzematge o enviament de formularis
  - Preparació per desplegament en hosting segur

---

## 🧠 Metodologia de treball
- Control de versions amb **Git + GitHub**
- Estructura basada en **ramificacions per funcionalitat (feature branches)**
- Revisió de codi abans de fusionar
- Planificació d’sprints amb **tauler Kanban**

---

## 🗂️ Kanban del projecte

| 🧱 Tasca | 🏷️ Label | 📍 Estat inicial |
|-----------|-----------|----------------|
| Configurar entorn local amb Vite | `frontend`, `setup` | To Do |
| Configurar Docker Compose | `backend`, `setup` | To Do |
| Connectar GitHub amb repositori local | `devops` | To Do |
| Crear tauler de Kanban | `planning` | To Do |
| Maquetar pàgina d’inici | `frontend` | To Do |
| Formulari de contacte (HTML + JS) | `frontend`, `form` | To Do |
| Validació client-side | `validation` | To Do |
| Validació servidor (PHP) | `backend`, `validation` | To Do |
| Prevenció de riscos laborals | `documentation`, `safety` | To Do |
| Desplegar en servidor segur | `deployment` | To Do |

---

## ⚠️ Prevenció de riscos laborals i de seguretat — Projecte *GLAMUR CLUB (E-commerce)*

### 👩‍💻 Riscos laborals del personal

| **Tipus de risc** | **Descripció** | **Mesures preventives** |
|--------------------|----------------|---------------------------|
| 🖥️ **Fatiga visual** | Exposició prolongada a pantalles durant el desenvolupament o la gestió del web. | 🔹 Fer pauses visuals cada 60 minuts.<br>🔹 Ajustar la il·luminació ambiental.<br>🔹 Utilitzar filtres de llum blava i mantenir una distància òptima del monitor. |
| 💺 **Dolor d’esquena i tensió muscular** | Postura inadequada o mobiliari no ergonòmic durant les jornades de treball. | 🔹 Utilitzar cadires ergonòmiques i reposapeus.<br>🔹 Fer estiraments periòdics i mantenir una postura correcta.<br>🔹 Ajustar l’altura de la taula i la pantalla. |
| ⏱️ **Estrès o pressió de temps** | Compliment de terminis ajustats o càrrega de feina elevada. | 🔹 Organitzar tasques amb metodologia Agile.<br>🔹 Planificar sprints realistes i incloure pauses regulars.<br>🔹 Fomentar un ambient de treball col·laboratiu i flexible. |
| 🧠 **Risc psicosocial** | Aïllament o manca de comunicació en treball remot o d’equip. | 🔹 Fer reunions periòdiques de coordinació.<br>🔹 Promoure suport emocional i tècnic entre companys.<br>🔹 Mantenir canals oberts de comunicació. |
| ⚡ **Risc elèctric** | Ús de diversos equips electrònics (ordinadors, pantalles, carregadors). | 🔹 Evitar sobrecàrregues d’endolls.<br>🔹 Utilitzar regletes amb protecció.<br>🔹 No manipular cables o endolls amb les mans humides. |

---

### 🏢 Riscos de l’empresa

| **Tipus de risc** | **Descripció** | **Mesures preventives** |
|--------------------|----------------|---------------------------|
| 🔒 **Seguretat digital** | Accés no autoritzat a dades de clients, base de dades o repositori de codi. | 🔹 Ús de contrasenyes segures i autenticació 2FA.<br>🔹 Repositoris privats a GitHub.<br>🔹 Còpies de seguretat regulars i xifrat de dades. |
| ☁️ **Caiguda del servidor o pèrdua de dades** | Problemes en el hosting o errors en el desplegament. | 🔹 Mantenir còpies de seguretat automàtiques.<br>🔹 Supervisar el servidor i tenir plans de recuperació.<br>🔹 Utilitzar entorns d’assaig abans de producció. |
| 🪪 **Incompliment de la LOPD o RGPD** | Gestió inadequada de dades personals dels usuaris o clients. | 🔹 Informar clarament sobre l’ús de dades.<br>🔹 Implementar polítiques de privacitat visibles.<br>🔹 Complir amb la normativa europea RGPD. |
| 💰 **Risc econòmic o financer** | Retards en el llançament o sobrecostos per errors tècnics. | 🔹 Control pressupostari i planificació de recursos.<br>🔹 Revisió contínua dels objectius del projecte.<br>🔹 Comunicació constant amb els responsables de cada àrea. |
| 🧩 **Dependència tecnològica** | Fallada d’eines externes (Vite, Docker, GitHub) o canvis sobtats en serveis. | 🔹 Disposar d’alternatives tècniques documentades.<br>🔹 Evitar dependències crítiques d’un únic proveïdor.<br>🔹 Actualitzar regularment les dependències i entorns. |

---

### 📋 Conclusió

Aquest pla de prevenció busca garantir **la salut i seguretat dels treballadors** (Pepe i Adri) durant el desenvolupament del projecte *GLAMUR CLUB*, així com **la protecció i continuïtat del sistema** a nivell d’empresa i infraestructura tècnica.


## 🚀 Desplegament
El projecte s’allotjarà en un **servidor segur (hosting compartit o VPS)** amb suport per a PHP i base de dades MySQL  
El client podrà revisar:
- La pàgina d’inici operativa  
- El formulari de contacte funcional  

---

## 🧾 Llicència
Aquest projecte és d’ús educatiu i desenvolupat per a finalitats formatives dins del mòdul de **Desenvolupament d’Aplicacions Web (DAW)**.

---

## 📅 Estat actual
**Iteració 1 en desenvolupament (fins al 22 d’octubre)**  
Propera tasca: *Configuració de l’entorn client amb Vite i estructura bàsica de fitxers.*

