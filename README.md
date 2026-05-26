# 🌿 AgroBio — Plateforme de Gestion Interne

Plateforme interne de gestion pour une coopérative agricole bio. Permet de gérer les fermes partenaires, le catalogue produits, les commandes, les tâches, les projets et d'analyser les ventes.

---

## Stack technique

| Couche | Technologie |
|--------|-------------|
| Backend | Laravel 13.5 / PHP 8.4 |
| Base de données | MySQL 8 |
| Frontend | Blade + Vanilla JS + CSS inline |
| Auth | Session-based (sans Sanctum) |
| Polices | Playfair Display, DM Sans (Google Fonts) |

---

## Fonctionnalités

- **Tableau de bord** — KPIs, graphique des ventes, tâches prioritaires, top fermes, commandes récentes
- **Catalogue Bio** — produits par ferme avec filtres, statut stock, vue grille/liste
- **Commandes** — création, statuts (Nouveau / En cours / Livré / Annulé), modes de livraison
- **Fermes Partenaires** — infos contact, certification bio, statut contrat, produits par ferme
- **Clients** — dérivés des commandes, historique par client, filtres
- **Tâches** — Kanban drag & drop, priorités, assignation, échéances
- **Projets** — gestion de projets avec membres (pivot `project_user`)
- **Analytics** — graphiques ventes hebdo/mensuel
- **Auth** — login, register, logout, rôles (admin / manager / staff)

---

## Structure de la base de données

```
users           — rôles : admin / manager / staff
farms           — fermes partenaires, certification bio, contrat
products        — liés à une ferme, stock, catégorie, prix
orders          — numéro unique CMD-xxx, client, livraison, statut
order_items     — produits d'une commande (prix au moment de la commande)
projects        — statut, dates
project_user    — pivot membres/projets
tasks           — priorité, catégorie, assignation, Kanban
```

---

## Installation

```bash
git clone https://github.com/codebyderkaoui/Agrobio
cd agrobio

cp .env.example .env
# Renseigner DB_DATABASE, DB_USERNAME, DB_PASSWORD dans .env

composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Accéder à : [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Compte de démonstration

| Champ | Valeur |
|-------|--------|
| Email | ahmed@agrobio.ma |
| Mot de passe | password |
| Rôle | Admin |

---

## Architecture notable

- **Pas de Sanctum** — les routes API lisent la session via `StartSession` ajouté dans `bootstrap/app.php`
- **Pas de panier** — application interne, le staff crée les commandes directement
- **Pas de table clients** — les clients sont dérivés des champs `client_name / client_email / client_phone` des commandes
- **Soft deletes** sur `products`, `orders`, `projects`, `tasks` — préserve l'historique
- **Tout le CSS** est inline dans `layouts/app.blade.php`, sans étape de build
- **Numéros de commande** générés avec une boucle `do/while` pour éviter les doublons (format `CMD-xxx`)

---

## Routes principales

### Web (protégées par auth)
| Route | Vue |
|-------|-----|
| `/` | Tableau de bord |
| `/products` | Catalogue Bio |
| `/orders` | Commandes |
| `/farms` | Fermes Partenaires |
| `/clients` | Clients |
| `/tasks` | Tâches (Kanban) |
| `/projects` | Projets |
| `/analytics` | Analytics |

### API REST (`/api/...`)
19 endpoints — produits, commandes, tâches, projets, fermes, clients, analytics.
Les routes DELETE sont réservées aux admins.

---

## Lancer le projet

```bash
cd agrobio
php artisan serve
```
