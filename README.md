# Travel Togo

Application web de découverte touristique et de réservation d'excursions au Togo.

Le site présente les régions, destinations et excursions du pays, permet aux voyageurs de réserver en ligne, de gérer leurs réservations et de contacter l'équipe. Un espace d'administration complet permet de gérer le catalogue, les réservations, les utilisateurs et les messages reçus.

> Projet réalisé avec Laravel 13 et Bootstrap 5, à partir du dossier de conception (MCD/MLD/MPD) fourni.

---

## Sommaire

- [Stack technique](#stack-technique)
- [Fonctionnalités](#fonctionnalités)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Comptes de démonstration](#comptes-de-démonstration)
- [URLs principales](#urls-principales)
- [Structure du projet](#structure-du-projet)
- [Base de données](#base-de-données)
- [Règles métier](#règles-métier)
- [Tests](#tests)
- [Qualité du code](#qualité-du-code)
- [Dépannage](#dépannage)

---

## Stack technique

| Élément | Version / choix |
|---|---|
| Langage | PHP 8.3+ (testé avec PHP 8.4.27) |
| Framework | Laravel 13.42 |
| Base de données | MySQL / MariaDB (utf8mb4) |
| Front-end | Blade, Bootstrap 5 (CDN), Bootstrap Icons, CSS personnalisé |
| Tests | PHPUnit 12 + SQLite en mémoire |
| Formatage | Laravel Pint |
| Authentification | Système personnalisé sur la table `utilisateurs` |

Aucun build front-end n'est nécessaire : Bootstrap et les polices sont chargés via CDN, le style spécifique est dans `public/css/app.css`.

---

## Fonctionnalités

### Espace public

- Page d'accueil avec destinations et excursions mises en avant.
- Catalogue des destinations avec recherche, filtre par région et par catégorie.
- Fiche destination : description, localisation, photos, excursions associées.
- Catalogue des excursions à venir, filtrable par destination.
- Fiche excursion : prix, durée, places restantes, formulaire de réservation.
- Formulaire de contact avec validation et limitation de fréquence.
- Pages d'erreur personnalisées en français (403, 404, 419, 500).

### Espace touriste

- Inscription, connexion, déconnexion.
- Mot de passe oublié et réinitialisation (lien envoyé dans les logs en local).
- Modification du profil et du mot de passe.
- Réservation d'une excursion avec calcul du montant côté serveur.
- Consultation et annulation de ses réservations (jusqu'à 48 h avant l'excursion).
- Compte bloqué : connexion refusée par le middleware `active`.

### Espace administrateur

- Tableau de bord avec statistiques (utilisateurs, destinations, excursions, réservations, messages).
- Gestion des régions (CRUD).
- Gestion des catégories (CRUD).
- Gestion des destinations (CRUD, catégories associées, upload et suppression de photos).
- Gestion des excursions (CRUD).
- Gestion des réservations : recherche, filtre par statut, détail, changement de statut contrôlé.
- Gestion des utilisateurs : consultation, blocage/déblocage (sauf comptes administrateurs et son propre compte).
- Consultation et suppression des messages de contact.

### Sécurité

- Protection CSRF sur tous les formulaires.
- Mots de passe hachés avec bcrypt.
- Validation serveur de toutes les entrées.
- Affectation de masse limitée par listes `Fillable`.
- Middlewares `auth`, `active` et `admin` sur les routes protégées.
- Échappement automatique des sorties par Blade.
- Formulaire de contact limité à 5 envois par minute (`throttle:5,1`).

---

## Prérequis

- **PHP 8.3 ou supérieur** avec les extensions `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `tokenizer`, `xml`, `ctype`, `json`.
- **Composer 2**.
- **MySQL 5.7+ / MariaDB 10.3+**.
- Pour les tests : extension **`pdo_sqlite`** (et `sqlite3`).

> ⚠️ PHP 8.2 n'est pas suffisant pour Laravel 13. Vérifiez la version réellement utilisée en ligne de commande avec `php -v`.

---

## Installation

### 1. Se placer dans le projet

```powershell
cd "C:\Users\komik\OneDrive\Desktop\Dossier de conception  Travel Togo\travel-togo"
```

### 2. Installer les dépendances PHP

```powershell
php C:\xampp\php\composer.phar install
```

Ou, si Composer est disponible globalement :

```powershell
composer install
```

### 3. Configurer l'environnement

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Vérifiez ensuite ces valeurs dans `.env` :

```dotenv
APP_NAME="Travel Togo"
APP_LOCALE=fr
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=travel_togo
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
MAIL_MAILER=log
```

### 4. Créer la base de données

Avec XAMPP :

```powershell
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS travel_togo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 5. Créer les tables et les données de démonstration

```powershell
php artisan migrate --seed
```

Pour repartir de zéro :

```powershell
php artisan migrate:fresh --seed
```

### 6. Lier le stockage public

Nécessaire pour afficher les photos uploadées par les administrateurs :

```powershell
php artisan storage:link
```

### 7. Lancer le serveur

```powershell
php artisan serve
```

Le site est disponible sur **http://localhost:8000**.

Pour utiliser un autre port :

```powershell
php artisan serve --port=8123
```

---

## Comptes de démonstration

Créés par `DatabaseSeeder` :

| Rôle | E-mail | Mot de passe |
|---|---|---|
| Administrateur | `admin@traveltogo.tg` | `Admin@2026` |
| Touriste | `touriste@traveltogo.tg` | `Touriste@2026` |
| Touriste | `awa.diallo@example.com` | `Touriste@2026` |
| Touriste (bloqué) | `yao.kouassi@example.com` | `Touriste@2026` |
| Touriste | `fatou.traore@example.com` | `Touriste@2026` |

> Le compte `yao.kouassi@example.com` est volontairement bloqué pour tester le refus de connexion.

---

## URLs principales

| URL | Description |
|---|---|
| `/` | Accueil |
| `/destinations` | Catalogue des destinations |
| `/destinations/{id}` | Fiche destination |
| `/excursions` | Catalogue des excursions |
| `/excursions/{id}` | Fiche excursion et réservation |
| `/contact` | Formulaire de contact |
| `/inscription` | Création de compte |
| `/connexion` | Connexion |
| `/mot-de-passe-oublie` | Demande de réinitialisation |
| `/profil` | Mon profil (connecté) |
| `/mes-reservations` | Mes réservations (connecté) |
| `/admin` | Tableau de bord administrateur |
| `/admin/reservations` | Suivi des réservations |
| `/admin/utilisateurs` | Gestion des utilisateurs |
| `/admin/messages` | Messages de contact |

---

## Structure du projet

```text
travel-togo/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Contrôleurs de l'administration
│   │   │   ├── Auth/           # Connexion, inscription, mot de passe
│   │   │   ├── ContactController.php
│   │   │   ├── DestinationController.php
│   │   │   ├── ExcursionController.php
│   │   │   ├── HomeController.php
│   │   │   ├── ProfileController.php
│   │   │   └── ReservationController.php
│   │   └── Middleware/         # AdminMiddleware, EnsureUserIsActive
│   └── Models/                 # Utilisateur, Region, Categorie, Destination,
│                               # Photo, Excursion, Reservation, ContactMessage
├── database/
│   ├── factories/              # Fabriques utilisées par les tests
│   ├── migrations/             # Schéma de la base
│   └── seeders/                # Jeu de données de démonstration
├── public/
│   └── css/app.css             # Design system du site
├── resources/views/
│   ├── admin/                  # Vues de l'administration
│   ├── auth/                   # Connexion, inscription, mot de passe
│   ├── destinations/           # Catalogue et fiche destination
│   ├── excursions/             # Catalogue et fiche excursion
│   ├── reservations/           # Mes réservations
│   ├── errors/                 # Pages 403, 404, 419, 500
│   ├── layouts/                # Layouts public et admin
│   └── partials/               # Cartes et composants réutilisables
├── routes/web.php              # Toutes les routes de l'application
└── tests/Feature/              # Tests fonctionnels
```

---

## Base de données

Le schéma suit le dossier de conception :

| Table | Rôle |
|---|---|
| `utilisateurs` | Comptes (administrateurs et touristes), rôle, blocage |
| `regions` | Régions du Togo |
| `categories` | Catégories touristiques |
| `destinations` | Destinations, coordonnées, région |
| `categorie_destination` | Association many-to-many destinations/catégories |
| `photos` | Photos des destinations (URL distante ou fichier local) |
| `excursions` | Excursions : prix, durée, places, date, destination |
| `reservations` | Réservations : personne, excursion, montant, statut |
| `contact_messages` | Messages envoyés via le formulaire de contact |

Tables techniques Laravel : `sessions`, `cache`, `jobs`, `password_reset_tokens`.

---

## Règles métier

- **Places restantes** : `places_max - somme(nb_personnes)` des réservations `en_attente` et `confirmee`. Aucune place n'est stockée en base.
- **Montant** : calculé côté serveur avec `prix × nb_personnes`, jamais envoyé par le client.
- **Réservation** : impossible pour une excursion passée ou complète, et le nombre de personnes doit tenir dans les places restantes.
- **Annulation** : autorisée uniquement pour une réservation active et jusqu'à **48 heures** avant la date de l'excursion.
- **Transitions de statut** (administration) :
  - `en_attente` → `confirmee` ou `annulee`
  - `confirmee` → `annulee` ou `terminee`
  - Une réservation ne peut être terminée qu'après la date de l'excursion.
- **Blocage** : un administrateur ne peut pas bloquer son propre compte ni un autre administrateur.
- **Photos** : JPG, PNG ou WEBP, 4 Mo maximum, 8 photos par envoi ; stockées dans `storage/app/public/destinations`.

---

## Tests

La suite couvre les pages publiques, l'authentification, les réservations, le contact et les accès à l'administration.

```powershell
php artisan test --compact
```

Résultat attendu : **30 tests, 100 assertions**.

Les tests utilisent SQLite en mémoire (`phpunit.xml`). Si PHPUnit affiche `could not find driver`, activez `pdo_sqlite` et `sqlite3` dans le `php.ini` utilisé par la ligne de commande, puis relancez.

Pour exécuter un seul fichier :

```powershell
php artisan test --compact tests/Feature/ReservationTest.php
```

Pour filtrer un test :

```powershell
php artisan test --compact --filter=annulation
```

---

## Qualité du code

Formater tout le projet :

```powershell
vendor\bin\pint --format agent
```

> L'option `--dirty` nécessite un dépôt Git. Ce dossier n'étant pas versionné, utilisez la commande ci-dessus.

Vérifier que toutes les vues Blade compilent :

```powershell
php artisan view:cache
php artisan view:clear
```

---

## Dépannage

### `could not find driver` pendant les tests

Activez les extensions SQLite dans le `php.ini` de la version PHP utilisée :

```ini
extension=pdo_sqlite
extension=sqlite3
```

Redémarrez le terminal puis vérifiez avec `php -m`.

### Erreur « Permission denied » ou fichiers non modifiables

OneDrive peut réappliquer l'attribut lecture seule sur `storage` et `bootstrap/cache` :

```powershell
attrib -r storage /s
attrib -r bootstrap\cache /s
```

### Erreur 500 après modification d'une vue

Un cache de vue obsolète peut persister :

```powershell
php artisan view:clear
```

### Les photos uploadées ne s'affichent pas

```powershell
php artisan storage:link
```

Vérifiez aussi `FILESYSTEM_DISK=public` dans `.env`.

### La réinitialisation de mot de passe n'envoie pas d'e-mail

En local, `MAIL_MAILER=log` : le lien est écrit dans `storage/logs/laravel.log`.

### Le serveur ne démarre pas

Vérifiez que le port est libre et que la version de PHP en ligne de commande est bien 8.3+ :

```powershell
php -v
php artisan serve --port=8123
```

---

## Remarques

- Les images de démonstration sont des URLs Unsplash ; une connexion Internet est nécessaire pour les afficher.
- Les données de démonstration (régions, destinations, excursions, réservations) sont recréées par `php artisan migrate:fresh --seed`.
- Le projet n'utilise pas Vite : inutile de lancer `npm install` ou `npm run build`.
