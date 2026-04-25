<p align="center">
  <img src="https://bcj37.fr/uploads/img/a97fed9810c39a9270caead79d014450.png"
       alt="Logo BCJ37"
       width="180">
</p>

# BCJ37

Site officiel du club BCJ37 basé sur Laravel, avec API publique, intégration CueScore et back-office OpenAdmin.

---

## Documentation

- [Documentation API](docs/api.md)
- Documentation interactive (Scribe) :

```txt
/docs
```

---

## Architecture / Schéma

### Architecture générale

Le projet **bcj37.fr** repose sur une architecture **MVC Laravel** classique, enrichie par :

- une **API publique performante**
- un **front moderne**
- un **panel d’administration dédié**

---

### Administration (OpenAdmin)

Le back-office est **totalement isolé** du site public.

#### Caractéristiques :

- Authentification indépendante
- Accès restreints aux administrateurs
- Formulaires personnalisés
- Gestion de structures legacy
- Séparation stricte front / administration

---

### Front-end

Le front-end est basé sur **Vite + Tailwind CSS**, optimisé pour la performance.

- Blade Components (`<x-layout>`, `<x-cadre>`, `<x-title>`)
- Alpine.js pour les interactions légères
- CSS organisé par fonctionnalités
- Build optimisé pour la production

---

### API publique

L’API expose les données nécessaires au front et aux intégrations externes.

#### Endpoints principaux

- `/public/site`
- `/public/home`
- `/contact`
- `/partenaires`
- `/disciplines/{discipline}`
- `/disciplines/{discipline}/rankings-preview`

#### Caractéristiques :

- Format JSON standardisé (`data / meta / links / error`)
- Cache applicatif avec TTL
- Invalidation automatique après import CueScore
- Warmup automatique du cache
- Paramètre `limit` sécurisé (1 → 10)

---

### Cache API

- Centralisation des clés via `CacheKeys`
- Invalidation via `ApiCacheInvalidator`
- Warmup via `ApiCacheWarmer`

#### Objectifs :

- Réduction des requêtes DB
- Réduction des appels API externes
- Amélioration des performances frontend

---

### APIs & flux de données

#### CueScore API

- Classements nationaux
- Classements régionaux
- Résultats de tournois

#### Facebook Graph API

- Publications Facebook intégrées

#### Matomo

- Statistiques de fréquentation
- Analyse des usages

---

## Tests

Le projet dispose d’une couverture de tests complète :

- Unit
- Integration
- Feature (API, cache, CueScore)

### Lancer les tests

```bash
php artisan test
172 tests passed
```

---

### CI (GitHub Actions)

Les tests sont exécutés automatiquement :

* Unit tests
* Integration tests
* API cache tests
* CueScore API tests

---

## Sécurité & bonnes pratiques

### Sécurité backend

* Framework **Laravel 10** maintenu et sécurisé
* Protection CSRF activée
* Validation systématique des entrées
* Authentification API via **Laravel Sanctum**

---

### Sécurité des données

* Aucune clé API exposée côté client
* Variables sensibles dans `.env`
* Séparation stricte :
* données publiques
* données administratives

---

### Bonnes pratiques de développement

* Architecture MVC respectée
* Logique métier isolée (Services)
* Aucun traitement lourd dans les vues
* Code testé (Feature / Integration / Unit)
* Versionnement Git

---

### Bonnes pratiques OpenAdmin

* Back-office isolé
* Mapping manuel des colonnes legacy
* Utilisation de `ignore()` sur les champs
* Aucune modification des schémas historiques

---

## Performance & maintenance

* Cache API avec TTL
* Invalidation ciblée
* Warmup automatique après import
* Minification JS/CSS
* Build optimisé Vite
* Architecture modulaire

---

## Analytics & conformité

* Utilisation de **Matomo** (auto-hébergé)
* Aucune dépendance à Google Analytics
* Respect de la vie privée
* Adapté à un contexte associatif

---

## Évolutions possibles

### API

* Documentation Swagger / Scribe
* Pagination avancée
* Rate limiting

### Cache

* Passage à Redis
* Cache tags
* Monitoring cache hit/miss

### CueScore

* Retry automatique API
* Timeout / fallback
* Monitoring des imports

---

## Développement

### Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev
```

---

### Environnement de test

* SQLite en mémoire
* Cache array
* Queue sync

---

## Licence

Projet interne BCJ37.

