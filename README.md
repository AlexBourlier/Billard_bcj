BCJ37

<p align="center"> <img src="https://bcj37.fr/uploads/img/a97fed9810c39a9270caead79d014450.png" alt="Logo BCJ37" width="180"> </p>


Site officiel du Billard Club de Joué-lès-Tours (BCJ37).

Le projet repose sur une architecture moderne découplée :

* Backend Laravel API-first
* Frontend React + TypeScript
* Administration OpenAdmin
* API publique centralisée
* Architecture scalable et maintenable

---

# Sommaire

* [Présentation]()
* [Objectifs]()
* [Architecture globale]()
* [Stack technique]()
* [Backend Laravel API]()
* [Frontend React]()
* [Administration OpenAdmin]()
* [Architecture des calendriers]()
* [API publique]()
* [SEO &amp; performances]()
* [Sécurité]()
* [Tests]()
* [Installation]()
* [Déploiement]()
* [Évolutions possibles]()
* [Licence]()

---

# Présentation

Le projet BCJ37 correspond à la refonte complète du site officiel du club.

L’ancien site reposait sur une architecture Laravel monolithique difficile à maintenir :

* pages lourdes ;
* logique métier dupliquée ;
* structure peu modulaire ;
* performances limitées ;
* référencement incomplet.

La nouvelle architecture a été entièrement repensée afin de proposer :

* un backend Laravel API-first ;
* un frontend React moderne ;
* une administration isolée ;
* une meilleure maintenabilité ;
* de meilleures performances ;
* une architecture évolutive.

---

# Objectifs

* Moderniser entièrement le site du club
* Séparer frontend / backend / administration
* Uniformiser les données métiers
* Améliorer les performances Lighthouse
* Optimiser le référencement SEO
* Simplifier la maintenance long terme
* Centraliser les calendriers et classements
* Préparer les futures évolutions API

---

# Architecture globale
```txt
Frontend React
https://www.bcj37.fr
        ↓
    Laravel API
https://api.bcj37.fr/api
        ↓
    MySQL
Administration OpenAdmin
https://www.bcj37.fr/admin
```
---

# Stack technique

## Backend

* Laravel 10
* PHP 8.3
* MySQL
* OpenAdmin
* Laravel Sanctum

## Frontend

* React
* TypeScript
* Vite
* React Router
* React Helmet Async

## Outils

* Git
* GitHub
* Lighthouse
* Matomo
* CueScore API

---

# Backend Laravel API

Le backend Laravel est entièrement orienté API-first.

Le site public ne dépend plus des vues Blade Laravel.

Laravel gère désormais :

* l’API publique ;
* la logique métier ;
* l’administration OpenAdmin ;
* les imports externes ;
* le cache ;
* la sécurité ;
* les services métiers.

---

# Frontend React

Le frontend public a été entièrement refactorisé avec React + TypeScript.

## Fonctionnalités principales

* Routing dynamique React Router
* Architecture modulaire
* Pages disciplines dynamiques
* Responsive mobile/tablette/desktop
* SEO dynamique
* Navigation optimisée
* Chargement API asynchrone

## Structure

```txt
DisciplinePage
├── Articles
├── Calendrier
├── Classements
└── Documents
```

## Routing principal

```txt
/
/club
/contact
/calendrier
/posts/:slug
/disciplines/:discipline
```

---

# Administration OpenAdmin

L’administration est totalement isolée du frontend public.

## Fonctionnalités

* Gestion des articles
* Gestion des calendriers
* Gestion des documents
* Gestion des classements
* Gestion des pages club
* Gestion des partenaires

## Caractéristiques

* Authentification sécurisée
* Administration indépendante
* CRUD personnalisés
* Gestion centralisée des données métier

---

# Architecture des calendriers

Le système calendrier a été entièrement refactorisé.

## Ancienne architecture

L’ancien système reposait sur :

* plusieurs tables par discipline ;
* plusieurs tables par scope ;
* duplication importante du code ;
* maintenance complexe.

Exemple :

```bash
blackball_calendrier_international
blackball_calendrier_national
snooker_calendrier_regional
```
---

## Nouvelle architecture

Le système repose désormais uniquement sur :

```bash
calendars
calendar_events
calendar_event_links
```
## Relations

```txt
Calendar
└── hasMany CalendarEvent
CalendarEvent
├── belongsTo Calendar
└── hasMany CalendarEventLink
CalendarEventLink
└── belongsTo CalendarEvent
```

## Avantages

* Architecture normalisée
* Maintenance simplifiée
* API unifiée
* Administration factorisée
* Réduction massive de duplication
* Scalabilité améliorée

---

# API publique

L’API expose toutes les données nécessaires au frontend React.

## Endpoints principaux

```bash
/api/posts
/api/posts/{slug}
/api/disciplines/{discipline}
/api/calendars
/api/calendars/{discipline}
/api/rankings
/api/documents
```
## Format standardisé

```json
{
    "data": [],
    "meta": {},
    "links": [],
    "error": null
}
```

## Fonctionnalités

* Cache applicatif
* Validation des paramètres
* Réponses JSON standardisées
* Architecture REST
* Chargement optimisé frontend

---

# SEO & performances

Le projet a été optimisé pour le référencement et les performances.

## SEO

* React Helmet Async
* Meta dynamiques
* Open Graph
* Sitemap XML
* Robots.txt
* URLs propres

## Lighthouse

### Ancien site
```txt
Performance ~66
```
### Nouveau frontend React

```txt
Performance ~91
Accessibility 100
Best Practices 100
SEO 100
```
## Optimisations

* Découpage des composants React
* Chargement API optimisé
* Architecture modulaire
* Minification Vite
* Responsive optimisé

---

# Sécurité

## Backend

* Laravel 10 sécurisé
* Validation systématique
* Protection CSRF
* Authentification Sanctum
* Variables sensibles dans `.env`

## Administration

* Back-office isolé
* Accès restreints
* Authentification dédiée

## API

* Aucune clé sensible exposée
* Validation des entrées
* Contrôle des paramètres

---

# Tests

Le projet dispose de tests automatisés :

* Unit tests
* Feature tests
* Integration tests
* API tests

## Lancer les tests
```bash
php artisan test
```
---

# Documentation API

Documentation disponible :
```txt
/docs
```
Documentation générée avec Scribe.

---

# Installation

## Backend Laravel
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```
## Frontend React
```bash
npm install
npm run dev
```
---

# Déploiement

## Production

### Backend API
```url
https://api.bcj37.fr/api/v1
```
### Frontend React
```url
https://www.bcj37.fr
```
### Administration
```url
https://www.bcj37.fr/admin
```
---

# Analytics & conformité

Le projet utilise Matomo auto-hébergé :

* respect de la vie privée ;
* aucune dépendance Google Analytics ;
* conformité adaptée à un contexte associatif.

---

# Évolutions possibles

## Backend

* Cache Redis
* Rate limiting API
* Monitoring API
* Jobs Laravel
* Queue Redis

## Frontend

* Lazy loading avancé
* Prerender / SSR
* Optimisation images WebP
* Vue calendrier mensuelle

## API

* JSON-LD
* Event schema
* Article schema
* Documentation Swagger

---

# Licence

Projet interne BCJ37.
