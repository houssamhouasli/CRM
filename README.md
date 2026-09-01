# 📦 CRM & Logistics Management System (Web & Desktop)

Application complète de gestion de la relation client (CRM), de distribution et de gestion logistique (Dépôts, Stocks, Camions, Commandes, Livraisons et Retours) développée avec **Laravel 12**, **Livewire 3**, **TailwindCSS** et encapsulée en application bureau avec **Electron**.

---

## 🚀 Fonctionnalités Principales

### 👥 Gestion Multi-Rôles & Permissions
- 👑 **Administrateur** :
  - Gestion globale des régions, utilisateurs, clients et catalogue de produits/catégories.
  - Suivi des commandes, statuts, exportations globales et rapports financiers.
  - Supervision des mouvements de stock et validation finale des retours.
- 💼 **Commercial** :
  - Gestion du portefeuille clients par région affectée.
  - Consultation des produits, suivi des commandes et de l'état des livraisons.
- 🏭 **Dépositaire (Responsable Dépôt)** :
  - Gestion du stock du dépôt en temps réel et historique des mouvements.
  - Préparation des commandes et assignation des livraisons aux camions/livreurs.
  - Gestion des réapprovisionnements (commandes de restock et réceptions).
  - Suivi des totaux journaliers (*Daily Totals*) et validation des retours marchandises.
- 🚚 **Livreur** :
  - Gestion du stock embarqué dans le camion (*Truck Stock*).
  - Traitement et validation des livraisons clients sur le terrain.
  - Enregistrement des retours de produits (articles remboursables / non-remboursables).
  - Impression des bons de livraison et factures.

### 📊 Autres Modules Clés
- **Gestion des Stocks & Mouvements** : Suivi fin des entrées/sorties au niveau des dépôts et des camions.
- **Cycle de Vie des Commandes & Livraisons** : De la saisie de commande jusqu'à la livraison finale avec émission de documents PDF.
- **Gestion des Retours de Marchandise** : Traitement des retours avec workflow de validation / rejet.
- **Génération & Impression PDF** : Factures, bons de commande et bons de livraison via `barryvdh/laravel-dompdf`.
- **Application Bureau (Electron)** : Interface desktop autonome intégrant une barre de navigation (`electron-shell.html`) et le serveur applicatif.

---

## 🛠️ Stack Technique

- **Backend** : [Laravel 12](https://laravel.com), PHP 8.2+
- **Frontend** : Blade, [Livewire 3](https://livewire.laravel.com), [Volt](https://livewire.laravel.com/docs/volt), TailwindCSS, Vite
- **Desktop Shell** : [Electron](https://www.electronjs.org/), Electron-Builder
- **Base de Données** : MySQL (ou SQLite)
- **Outils & Packages** : `barryvdh/laravel-dompdf`, `laravel/breeze`, `pestphp/pest`

---

## 📋 Prérequis

- **PHP** : >= 8.2 (avec extensions `pdo`, `mbstring`, `openssl`, `curl`, `gd` activées)
- **Composer** : >= 2.x
- **Node.js** : >= 18.x et **npm**
- **MySQL** ou **MariaDB** (ou SQLite configuré dans `.env`)

---

## ⚙️ Installation & Démarrage (Mode Web)

1. **Cloner le projet** :
   ```bash
   git clone https://github.com/houssamhouasli/CRM.git
   cd CRM
   ```

2. **Installer les dépendances PHP et JavaScript** :
   ```bash
   composer install
   npm install
   ```

3. **Configurer l'environnement** :
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Éditez le fichier `.env` pour renseigner les accès à votre base de données (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).*

4. **Exécuter les migrations et le seeder de test** :
   ```bash
   php artisan migrate --seed
   ```

5. **Lancer le serveur de développement** :
   ```bash
   # Lancer Laravel et Vite en parallèle :
   composer run dev
   # Ou séparément :
   php artisan serve
   ```
   Accédez à l'application web via : `http://127.0.0.1:8000`

---

## 🖥️ Utilisation en Mode Application Bureau (Electron)

L'application peut être lancée ou packagée comme application de bureau native :

### Lancement en mode Desktop :
```bash
npm start
```
*Electron démarrera automatiquement l'interface avec la coquille de navigation intégrée.*

### Packaging & Création de l'installateur Windows :
```bash
npm run dist
```
L'exécutable généré sera disponible dans le dossier `dist/`.

---

## 🔑 Comptes de Test (Générés par le Seeder)

Le mot de passe pour tous les comptes de test est : `password`

| Rôle | Email | Description |
| :--- | :--- | :--- |
| 👑 **Admin** | `admin@crm.ma` | Accès complet administrateur |
| 💼 **Commercial** | `com.casa@crm.ma` | Commercial région Casablanca |
| 💼 **Commercial** | `com.fes@crm.ma` | Commercial région Fès |
| 🏭 **Dépositaire** | `depo.casa@crm.ma` | Responsable Dépôt Casablanca |
| 🏭 **Dépositaire** | `depo.fes@crm.ma` | Responsable Dépôt Fès |
| 🚚 **Livreur** | `livreur.casa@crm.ma` | Chauffeur / Livreur Casa |
| 🚚 **Livreur** | `livreur.fes@crm.ma` | Chauffeur / Livreur Fès |

---

## 📂 Structure du Projet

```text
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/         # Contrôleurs du tableau de bord et gestion Admin
│   │   ├── Commercial/    # Gestion clients & suivi commercial
│   │   ├── Depositaire/   # Gestion des stocks dépôts, commandes, restock
│   │   └── Livreur/       # Gestion du camion, tournées et retours
│   └── Models/            # Modèles Eloquent (Client, Order, Delivery, Product, etc.)
├── database/
│   ├── migrations/        # Schémas des tables et index de performance
│   └── seeders/           # Données initiales (Régions, Dépôts, Produits, Utilisateurs)
├── electron-shell.html    # Interface coquille pour le client Desktop
├── main.js                # Point d'entrée Electron
├── resources/views/       # Vues Blade & composants Livewire
├── routes/web.php         # Définition des routes et middlewares par rôle
└── package.json           # Scripts & dépendances Electron / Vite
```
