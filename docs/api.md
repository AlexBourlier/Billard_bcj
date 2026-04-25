# Documentation API BCJ

## Base URL

### Local 

```txt
http://127.0.0.1:8000/api/v1
```

### Production

A définir

---

## Format standard des réponses

Toutes les réponses API publiques suivent le format suivant :

```JSON
{
    "data": {},
    "meta": {},
    "links": {},
    "error": null
}
```

### Erreur standard

```JSON
{
  "data": null,
  "meta": {},
  "links": {},
  "error": {
    "code": "discipline_not_found",
    "message": "Discipline not found"
  }
}
```

## Endpoint publics

** GET `/public/site` **
Retourne les informations globales du site

### Réponse
```JSON
{
  "data": {
    "site_settings": {},
    "menus": []
  },
  "meta": {
    "menus_count": 5
  },
  "links": [],
  "error": null
}
```

** GET `/public/home` **
Retourne les données nécessaires à la page d'accueil publique

### Cache
```txt
10 minutes
```

### Réponse
```JSON
{
  "data": {
    "site_settings": {},
    "menus": [],
    "partners": [],
    "featured_post": {}
  },
  "meta": {
    "menus_count": 5,
    "partners_count": 8,
    "has_featured_post": true
  },
  "links": [],
  "error": null
}
```

## Disciplines

### Disciplines supportées

```txt
blackball
americain
snooker
carambole
```

** GET `/disciplines/{discipline}` **
Retourne les contenus liés à une discipline

#### Paramètres URL 

| Nom        | Type   | Obligatoire | Description           |
| ---------- | ------ | ----------: | --------------------- |
| discipline | string |         oui | Slug de la discipline |

#### Exemple

** GET `/disciplines/blackball` **

#### Cache

```txt
10 minutes
```

#### Réponse
```JSON
{
  "data": {
    "posts": [],
    "calendar": [],
    "documents": [],
    "rankings": []
  },
  "meta": {
    "discipline": "blackball",
    "posts_count": 0,
    "calendar_count": 0,
    "documents_count": 0,
    "rankings_count": 0
  },
  "links": [],
  "error": null
}
```

#### Erreur discipline inconnue

```JSON
{
  "data": null,
  "meta": {},
  "links": [],
  "error": {
    "code": "discipline_not_found",
    "message": "Discipline not found"
  }
}
```

** GET `/disciplines/{discipline}/rankings-preview` **
Retourne un aperçu des classements CueScore pour une discipline.

#### Paramètres URL
| Nom        | Type   | Obligatoire | Description           |
| ---------- | ------ | ----------: | --------------------- |
| discipline | string |         oui | Slug de la discipline |

#### Paramètres query
| Nom   |    Type | Défaut | Min | Max | Description                                                      |
| ----- | ------: | -----: | --: | --: | ---------------------------------------------------------------- |
| limit | integer |      5 |   1 |  10 | Nombre maximum d’entrées individuelles retournées par classement |

#### Exemple
`GET /disciplines/blackball/rankings-preview?limit=10`

#### Cache
`5 minutes`

#### Réponse
```JSON
{
  "data": {
    "national": [
      {
        "ranking": {
          "id": 1,
          "name": "FFB - Blackball - TN - Master",
          "cuescore_id": "123456",
          "url": "https://cuescore.com/ranking/example",
          "source_type": "ranking",
          "discipline": "blackball",
          "scope": "national",
          "ranking_type": "individual",
          "team_category": null,
          "season": "2025-2026",
          "is_active": true,
          "sort_order": 1
        },
        "entries": [],
        "meta": []
      }
    ],
    "regional": [],
    "départemental": []
  },
  "meta": {
    "discipline": "blackball",
    "count": 1,
    "limit": 10,
    "rankings_supported": true
  },
  "links": [],
  "error": null
}
```

### Cas particulier : carambole
Les classements CueScore ne sont pas supportés pour le carambole

`GET /discipline/carambole/rankings-preview`

```JSON
{
  "data": null,
  "meta": {
    "discipline": "carambole",
    "rankings_supported": false
  },
  "links": [],
  "error": null
}
```

---

## Cache API

### Clés de cache
Les clés sont centralisées dans : 

```php
App\Support\CacheKeys
```
### Clés principales

```txt
public_home
discipline:{slug}
discipline:{slug}:rankings_preview:limit:{limit}
```

### Exemples

```txt
public_home
discipline:blackball
discipline:blackball:rankings_preview:limit:5
discipline:blackball:rankings_preview:limit:10
```

### TTL

| Endpoint                                     |      Durée |
| -------------------------------------------- | ---------: |
| `/public/home`                               | 10 minutes |
| `/disciplines/{discipline}`                  | 10 minutes |
| `/disciplines/{discipline}/rankings-preview` |  5 minutes |

### Invalidation
L'invalidation est gérée par : 

```php
App\Support\ApiCacheInvalidator
```

#### Méthodes disponibles

```php
publicHome()
discipline(string $discipline)
rankingsPreview(string $discipline)
disciplinePage(string $discipline)
allPublic()
```

#### Comportement
Lorsqu'un import CueScore réussit : 

```txt
1. Le cache discipline est invalidé
2. Le cache rankings-preview est invalidé
3. Le cache rankings-preview par défaut est préchauffé
```
---
### Warmup
Le Warmup est géré par : 

```php
App\Support\ApiCacheWarmer
```

Après un import CueScore réussi, l'API préchauffe le cache : 

```php
discipline:{slug}:rankings_preview:limit:5
```

Objectif : 

```txt
Réduire la latence du prochain appel frontend
```

---

## CueScore

### Import CueScore
L'import CueScore est géré par : 

```php
App\Services\CueScore\CueScoreRankingImporter
```

### Comportement après import réussi

```txt
fetch actif créé
entries enregistrées
cache discipline invalidé
cache rankings-preview invalidé
warmup rankings-preview exécuté
```

### Comportement après import échoué

```txt
fetch créé avec status failed
cache existant conservé
pas de warmup
```

---

## Tests

### Lancer tous les tests

```bash
php artisan test
```

### Tests par catégorie

```bash
php artisan test tests/Unit
php artisan test tests/Integration
php artisan test tests/Feature
```

### Tests par catégorie

```bash
php artisan test tests/Unit
php artisan test tests/Integration
php artisan test tests/Feature
```

### Tests cache

```bash
php artisan test tests/Feature/ApiCacheTest.php
php artisan test tests/Feature/ApiCacheInvalidatorTest.php
php artisan test tests/Feature/ApiCacheWithCueScoreInvalidationTest.php
```

### Tests CueScore API

```bash
php artisan test tests/Feature/CueScoreRankingsPreviewApiTest.php
```

### Dernier résultat connu 

```txt
172 tests passed
```

---

## GitHub Actions
Les tests sont organisés par catégories dans la CI: 

```txt
Unit tests
Integration tests
API cache tests
CueScore API tests
Other feature tests
```

---

## Notes techniques

### Gestion du paramètre `limit`

Le paramètre `limit` est borné côté backend :

```txt
min: 1
max: 10
default: 5
```

Cela garantit que toutes les clés de cache générées sont couvertes par l'invalidation.

---

### Compatibilité cache

Le système acuel fonctionne sans cache tags.

Drivers compatibles : 

```txt
array
file
database
redis
memcached
```

Les cache tags pourront être ajoutés plus tard si Redis ou Memcached est utilisé. 

---

## Evolutions possibles

### Cache

* Passage à Redis
* Cache tags par discipline
* Warmup multi-limits
* Mesure cache hit/miss

### API

* Documentation OpenAPI / Swagger
* Documentation Scribe
* Pagination plus avancée
* Rate limiting

### CueScore

* Retry en cas d'échec API
* Timeout explicite
* Fallback sur dernier cache valide
* Monitoring des imports