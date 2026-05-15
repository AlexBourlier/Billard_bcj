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

<pre class="overflow-visible! px-0!" data-start="1979" data-end="2141"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>Frontend React</span><br/><span>https://www.bcj37.fr</span><br/><span>        ↓</span><br/><span>Laravel API</span><br/><span>https://api.bcj37.fr/api</span><br/><span>        ↓</span><br/><span>MySQL</span><br/><br/><span>Administration OpenAdmin</span><br/><span>https://www.bcj37.fr/admin</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

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

<pre class="overflow-visible! px-0!" data-start="3057" data-end="3140"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>DisciplinePage</span><br/><span>├── Articles</span><br/><span>├── Calendrier</span><br/><span>├── Classements</span><br/><span>└── Documents</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

## Routing principal

<pre class="overflow-visible! px-0!" data-start="3164" data-end="3241"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>/</span><br/><span>/club</span><br/><span>/contact</span><br/><span>/calendrier</span><br/><span>/posts/:slug</span><br/><span>/disciplines/:discipline</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

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

<pre class="overflow-visible! px-0!" data-start="3938" data-end="4045"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>blackball_calendrier_international</span><br/><span>blackball_calendrier_national</span><br/><span>snooker_calendrier_regional</span><br/><span>...</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

---

## Nouvelle architecture

Le système repose désormais uniquement sur :

<pre class="overflow-visible! px-0!" data-start="4124" data-end="4181"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>calendars</span><br/><span>calendar_events</span><br/><span>calendar_event_links</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

## Relations

<pre class="overflow-visible! px-0!" data-start="4197" data-end="4357"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>Calendar</span><br/><span>└── hasMany CalendarEvent</span><br/><br/><span>CalendarEvent</span><br/><span>├── belongsTo Calendar</span><br/><span>└── hasMany CalendarEventLink</span><br/><br/><span>CalendarEventLink</span><br/><span>└── belongsTo CalendarEvent</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

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

<pre class="overflow-visible! px-0!" data-start="4636" data-end="4780"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>/api/posts</span><br/><span>/api/posts/{slug}</span><br/><br/><span>/api/disciplines/{discipline}</span><br/><br/><span>/api/calendars</span><br/><span>/api/calendars/{discipline}</span><br/><br/><span>/api/rankings</span><br/><span>/api/documents</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

## Format standardisé

<pre class="overflow-visible! px-0!" data-start="4805" data-end="4879"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute inset-x-4 top-12 bottom-4"><div class="pointer-events-none sticky z-40 shrink-0 z-1!"><div class="sticky bg-token-border-light"></div></div></div><div class="relative"><div class=""><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>{</span><br/><span>  "data": [],</span><br/><span>  "meta": {},</span><br/><span>  "links": [],</span><br/><span>  "error": </span><span class="ͼy">null</span><br/><span>}</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

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

<pre class="overflow-visible! px-0!" data-start="5261" data-end="5287"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>Performance ~66</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

### Nouveau frontend React

<pre class="overflow-visible! px-0!" data-start="5317" data-end="5388"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>Performance ~91</span><br/><span>Accessibility 100</span><br/><span>Best Practices 100</span><br/><span>SEO 100</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

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

<pre class="overflow-visible! px-0!" data-start="6006" data-end="6034"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute inset-x-4 top-12 bottom-4"><div class="pointer-events-none sticky z-40 shrink-0 z-1!"><div class="sticky bg-token-border-light"></div></div></div><div class="relative"><div class=""><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>php artisan test</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

---

# Documentation API

Documentation disponible :

<pre class="overflow-visible! px-0!" data-start="6090" data-end="6106"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>/docs</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

Documentation générée avec Scribe.

---

# Installation

## Backend Laravel

<pre class="overflow-visible! px-0!" data-start="6185" data-end="6279"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute inset-x-4 top-12 bottom-4"><div class="pointer-events-none sticky z-40 shrink-0 z-1!"><div class="sticky bg-token-border-light"></div></div></div><div class="relative"><div class=""><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>composer install</span><br/><span class="ͼ10">cp</span><span> .env.example .env</span><br/><span>php artisan key:generate</span><br/><span>php artisan migrate</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

## Frontend React

<pre class="overflow-visible! px-0!" data-start="6300" data-end="6335"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute inset-x-4 top-12 bottom-4"><div class="pointer-events-none sticky z-40 shrink-0 z-1!"><div class="sticky bg-token-border-light"></div></div></div><div class="relative"><div class=""><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span class="ͼ10">npm</span><span> install</span><br/><span class="ͼ10">npm</span><span> run dev</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

---

# Déploiement

## Production

### Backend API

<pre class="overflow-visible! px-0!" data-start="6389" data-end="6424"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>https://api.bcj37.fr/api/v1</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

### Frontend React

<pre class="overflow-visible! px-0!" data-start="6446" data-end="6477"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>https://www.bcj37.fr</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

### Administration

<pre class="overflow-visible! px-0!" data-start="6499" data-end="6536"><div class="relative w-full mt-4 mb-1"><div class=""><div class="relative"><div class="h-full min-h-0 min-w-0"><div class="h-full min-h-0 min-w-0"><div class="border border-token-border-light border-radius-3xl corner-superellipse/1.1 rounded-3xl"><div class="h-full w-full border-radius-3xl bg-token-bg-elevated-secondary corner-superellipse/1.1 overflow-clip rounded-3xl lxnfua_clipPathFallback"><div class="pointer-events-none absolute end-1.5 top-1 z-2 md:end-2 md:top-1"></div><div class="relative"><div class="pe-11 pt-3"><div class="relative z-0 flex max-w-full"><div id="code-block-viewer" dir="ltr" class="q9tKkq_viewer cm-editor z-10 light:cm-light dark:cm-light flex h-full w-full flex-col items-stretch ͼs ͼ16"><div class="cm-scroller"><pre class="cm-content q9tKkq_readonly m-0"><code><span>https://www.bcj37.fr/admin</span></code></pre></div></div></div></div></div></div></div></div></div><div class=""><div class=""></div></div></div></div></div></pre>

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
