# Documentation API BCJ

## Documentation interactive (auto-générée)

La documentation de référence est **générée depuis le code** (annotations sur les
contrôleurs `App\Http\Controllers\Api`) via Scribe — elle n'est donc jamais
désynchronisée des routes réelles.

| Ressource | URL | Contenu |
|-----------|-----|---------|
| Interface web | `/docs` | Documentation navigable (essayer les requêtes) |
| Spécification | `/docs.openapi` | OpenAPI **3.0.3** (importable dans Swagger UI, Insomnia…) |
| Collection | `/docs.postman` | Collection Postman |

La spécification est aussi versionnée dans le dépôt : [`docs/openapi.yaml`](openapi.yaml).

> Choix de version : Scribe 5 produit de l'OpenAPI **3.0.3**, universellement
> supporté par l'outillage. La 3.1 n'apporte rien de nécessaire ici.

## Génération et maintenance

```bash
php artisan scribe:generate
```

À exécuter **après toute création, modification ou suppression d'une route API**,
et à jouer au déploiement. Documenter une route se fait par annotations dans le
docblock du contrôleur : `@group`, `@authenticated`, `@queryParam`, `@urlParam`,
`@bodyParam`, `@response`. Ajouter `@hideFromAPIDocumentation` pour un endpoint
interne (ex. l'import de licences, réservé à l'admin).

### Protection / activation

La doc est publique par défaut (API majoritairement publique). Pour la restreindre
ou la désactiver selon l'environnement, voir `config/scribe.php` :
- `laravel.middleware` : ajouter un middleware (ex. `auth`) pour protéger `/docs` ;
- `laravel.add_routes` : passer à `false` pour ne pas exposer la doc (ex. en
  production).

Aucun secret n'est present dans la documentation generee.

## Base URL

### Local

```txt
http://127.0.0.1:8000/api/v1
```

### Production

```txt
https://bcj37.fr/api/v1
```

---

# Format standard des réponses

Toutes les réponses API publiques respectent le format suivant :

```json
{
    "data": {},
    "meta": {},
    "links": {},
    "error": null
}
```

## Format d’erreur standard

```json
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
---

# Endpoints publics

## 🔹 GET `/public/site`

Retourne les informations globales du site.

### Réponse

```json
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

---

## 🔹 GET `/public/home`

Retourne les données nécessaires à la page d’accueil.

### Cache

```txt
10 minutes
```

### Réponse

```json
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

---

## 🔹 GET `/contacts`

Retourne les informations de contact du club.

```json
{
    "data": [],
    "meta": {  
        "count": 1
    },
    "links": [],
    "error": null
}
```

---

## 🔹 GET `/partenaires`

Retourne la liste des partenaires.

```json
{
    "data": [],
    "meta": {  
        "count": 8
    },
    "links": [],
    "error": null
}
```

---

# Disciplines

## Disciplines supportées

```txt
blackball
americain
snooker
carambole
```

---

## 🔹 GET `/disciplines/{discipline}`

Retourne les contenus d’une discipline.

### Paramètres

| Nom        | Type   | Obligatoire | Description           |
| ---------- | ------ | ----------- | --------------------- |
| discipline | string | oui         | Slug de la discipline |

### Exemple

```txt
GET /disciplines/blackball
```

### Cache

```txt
10 minutes
```

### Réponse

```json
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

---

## 🔹 GET `/disciplines/{discipline}/rankings-preview`

Retourne un aperçu des classements CueScore.

### Paramètres URL

| Nom        | Type   | Obligatoire | Description |
| ---------- | ------ | ----------- | ----------- |
| discipline | string | oui         | Slug        |

### Query

| Nom   | Type    | Défaut | Min | Max | Description        |
| ----- | ------- | ------- | --- | --- | ------------------ |
| limit | integer | 5       | 1   | 10  | Nombre d’entrées |

### Exemple

```txt
GET /disciplines/blackball/rankings-preview?limit=10
```

### Cache

```txt
5 minutes
```

### Réponse

```json
{
    "data": {  
        "national": [],  
        "regional": [],  
        "departemental": []
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
---

## Cas particulier : carambole

```txt
GET /disciplines/carambole/rankings-preview
```

```json
{
    "data": null
    "meta": {
        "discipline": "carambole",
        "rankings_supported": false
        },
    "links": [],
    "error": null
}
```
---

# Cache API

## Clés

```txt
App\Support\CacheKeys
```

### Exemples

```txt
public_home
discipline:blackball
discipline:blackball:rankings_preview:limit:5
```
---

## TTL

| Endpoint                      | Durée |
| ----------------------------- | ------ |
| `/public/home`              | 10 min |
| `/disciplines/{discipline}` | 10 min |
| `/rankings-preview`         | 5 min  |

---

## Invalidation

```txt
App\Support\ApiCacheInvalidator
```

### Méthodes

```txt
publicHome()
discipline()
rankingsPreview()
disciplinePage()
allPublic()
```

### Comportement

```txt
Import CueScore réussi :
   - invalidation discipline
   - invalidation rankings
   - preview
   - warmup preview (limit=5)
```

---

## Warmup

```txt
App\Support\ApiCacheWarmer
```

Clé préchauffée :

```txt
discipline:{slug}:rankings_preview:limit:5
```

Objectif :

```txt
Réduire la latence du premier appel
```

---

# CueScore

## Import

```txt
App\Services\CueScore\CueScoreRankingImporter
```

### Succès

```txt
- fetch actif créé
- entries enregistrées
- cache invalidé
- warmup exécuté
```

### Échec

```txt
- fetch en failed
- cache conservé
- aucun warmup
```

---

# Tests

## Tous les tests

```txt
php artisan test
```

## Par type

```bash
php artisan test tests/Unit
php artisan test tests/Integration
php artisan test tests/Feature
```

## Cache

```bash
php artisan test tests/Feature/ApiCache*
```

## CueScore

```bash
php artisan test tests/Feature/CueScore*
```

### Résultat

```txt
172 tests passed
```

---

# CI (GitHub Actions)

Organisation des tests :

```txt
Unit tests
Integration tests
API cache tests
CueScore API tests
Other feature tests
```

---

# Notes techniques

## Paramètre `limit`

```txt
min: 1
max: 10
default: 5
```

Garantit la cohérence des clés de cache.

---

## Compatibilité cache

Sans cache tags.

Drivers compatibles :

```txt
array
file
database
redis
memcached
```

---

# Évolutions possibles

## Cache

* Redis
* Cache tags
* Warmup multi-limits
* Metrics hit/miss

## API

* OpenAPI / Swagger
* Pagination avancée
* Rate limiting

## CueScore

* Retry
* Timeout explicite
* Fallback cache
* Monitoring imports
