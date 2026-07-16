# Documentation développeur — BCJ37

Documentation technique de reprise du projet. Le [README](../README.md) donne la
vue d'ensemble ; ce document détaille l'installation, la configuration, les
commandes, l'architecture applicative et les points sensibles.

> **Aucun secret ne doit figurer dans le dépôt** ni dans cette documentation.
> Toutes les valeurs sensibles vivent dans `.env` (non versionné).

---

## 1. Stack et versions

| Élément | Version |
| --- | --- |
| PHP | ^8.1 |
| Laravel | ^10.10 |
| Sanctum | ^3.3 |
| OpenAdmin (`open-admin-org/open-admin`) | ^1.0 |
| Purifier (`mews/purifier`) | ^3.4 |
| Scribe (`knuckleswtf/scribe`) | ^5.9 |
| React | ^19 |
| Vite | ^8 |
| Base de données | MySQL (SQLite `:memory:` pour les tests) |

---

## 2. Installation

### Backend (Laravel)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate        # crée le schéma ET sème les rôles/permissions métier
php artisan db:seed        # données de démonstration (calendriers)
php artisan serve
```

### Frontend (React + Vite)

```bash
cd frontend
npm install
npm run dev                # serveur de développement
```

L'administration OpenAdmin est servie par Laravel sous `/admin`. Le frontend
public consomme l'API `/api/v1`.

---

## 3. Variables d'environnement

Les clés sont définies dans `.env.example`. Les principales, hors valeurs par
défaut Laravel :

| Clé | Rôle |
| --- | --- |
| `APP_URL`, `ADMIN_HTTPS` | URL de base ; forcer HTTPS sur les liens admin. |
| `DB_*` | Connexion MySQL. |
| `CACHE_DRIVER`, `QUEUE_CONNECTION` | Cache applicatif et file d'attente. |
| `ADMIN_IMPORT_QUEUE_CONNECTION` | File dédiée aux imports déclenchés depuis l'admin (permet l'asynchrone sans changer la file globale). |
| `MAIL_*` | Envoi de courriels (pas d'envoi automatique en production sans règle validée). |
| `CKE5_LICENSE_KEY` | Clé de l'éditeur enrichi (voir §7). |
| `MATOMO_URL`, `MATOMO_SITE_ID`, `MATOMO_ENABLE` | Analytics auto-hébergé (respect vie privée). |
| `FACEBOOK_ACCESS_TOKEN`, `FACEBOOK_PAGE_ID` | Récupération d'informations de la page Facebook. |
| `LICENSE_IMPORT_*` | Pipeline d'import des licences (source Telemat/FFBI) : identifiants, sélecteurs, seuils de validation, rétention, verrou, file. Voir §8. |

> Ne jamais committer une valeur réelle pour `*_PASSWORD`, `*_TOKEN`,
> `*_SECRET`, `*_KEY` ou `CKE5_LICENSE_KEY`.

---

## 4. Commandes utiles

```bash
# Tests (SQLite en mémoire)
php artisan test

# Formatage PHP (Laravel Pint)
vendor/bin/pint            # applique
vendor/bin/pint --test     # vérifie sans modifier

# Routes
php artisan route:list
php artisan route:cache    # mise en cache (à valider avant déploiement)

# Documentation API (Scribe)
php artisan scribe:generate

# Frontend
cd frontend && npm run lint
cd frontend && npm run build

# Sitemap
php artisan sitemap:generate

# Import des licences (pipeline Telemat/FFBI)
php artisan license-import:run [--source=ffbi_telemat] [--dry-run] [--triggered-by-label=...]
php artisan license-import:scheduled-run          # variante planifiée

# Classements CueScore
php artisan cuescore:import [rankingId] [--discipline=] [--active-only] [--with-match]
php artisan cuescore:match {fetchId}
```

---

## 5. Architecture

```txt
Frontend React (Vite)  ──►  API Laravel /api/v1  ──►  MySQL
                                   ▲
                        Administration OpenAdmin (/admin)
```

- **Backend API-first** : le site public ne dépend d'aucune vue Blade.
- **Frontend** : React + TypeScript, routing React Router, SEO via React Helmet.
- **Administration** : OpenAdmin, isolée du frontend.

### Conventions transverses

- **Codes de discipline** (entiers) : `1` blackball, `2` carambole, `3` snooker,
  `4` américain ; `null`/`0` = « club ». Centralisés dans
  [`App\Support\DisciplineMapper`](../app/Support/DisciplineMapper.php).
- **Scope `visible()`** : les modèles publics exposent un scope filtrant sur
  `actif` + fenêtre de dates + ordre d'affichage (ex. `Partenaire`, `InfoBlock`).
- **Invalidation du cache** : les modèles affichés en page d'accueil déclenchent
  `ApiCacheInvalidator::publicHome()` à chaque écriture (`saved`/`deleted`).

---

## 6. API publique (`/api/v1`)

Toutes les réponses suivent l'enveloppe standard :

```json
{ "data": …, "meta": {}, "links": [], "error": null }
```

Principaux endpoints (voir `routes/api.php` pour la liste exhaustive) :

| Méthode | Chemin | Objectif |
| --- | --- | --- |
| GET | `/api/v1/public/home` | Données de la page d'accueil (settings, menus, partenaires, blocs info, article vedette). |
| GET | `/api/v1/public/site` | Réglages + menus. |
| GET | `/api/v1/partenaires` | Partenaires visibles. |
| GET | `/api/v1/posts`, `/posts/slug/{slug}`, `/posts/discipline/{discipline}` | Articles. |
| GET | `/api/v1/calendrier/{discipline}/{scope}` | Calendriers. |
| GET | `/api/v1/cuescore/rankings`, `/cuescore/{discipline}/{scope}/{type}` | Classements CueScore. |
| GET | `/api/v1/documents/{discipline}` | Documents par catégorie. |
| POST | `/api/v1/contact` | Formulaire de contact (validation → 422 en cas d'erreur). |

La documentation complète et à jour est générée par **Scribe** (`/docs`,
`/docs.openapi`, `/docs.postman`). Voir aussi [`docs/api.md`](api.md).

### Procédure lors d'un changement de route

1. Modifier la route et son contrôleur.
2. Mettre à jour les annotations Scribe si nécessaire.
3. Régénérer : `php artisan scribe:generate`.
4. Vérifier les **tests de contrat** (`tests/Feature/Api/ApiContractTest.php`).

---

## 7. Éditeur enrichi et nettoyage du HTML

- Le champ d'édition des articles est enregistré via l'extension de formulaire
  `ck5` → [`App\Admin\Extensions\Form\Ck5Decoupled`](../app/Admin/Extensions/Form/Ck5Decoupled.php).
  **Attention au nom** : malgré « Ck5 », l'implémentation repose sur un éditeur
  léger côté vue (`admin.form.ck5-decoupled`). L'éditeur est volontairement
  **bridé** (pas de couleurs ni tailles arbitraires) pour préserver la charte.
- Le contenu est **nettoyé côté serveur** par
  [`App\Support\HtmlSanitizer::post()`](../app/Support/HtmlSanitizer.php), qui
  applique le profil `post` de Purifier ([`config/purifier.php`](../config/purifier.php)) :
  liste blanche de balises/attributs, schémas d'URL limités à `http`/`https`/`mailto`.
- **Règle de sécurité** : un contenu créé dans OpenAdmin n'est pas réputé sûr. Le
  frontend affiche du HTML brut (`dangerouslySetInnerHTML`) ; ce HTML **doit**
  donc toujours avoir été passé par la liste blanche serveur.

---

## 8. Import des licences (pipeline)

- Source unique : **Telemat/FFBI**, orchestrée par le pipeline DDD
  (`TelematLicenseImportOrchestrator`) qui projette en `full_replace` vers la
  table `licencies`.
- Déclenchement : commande `license-import:run` (CLI) ou depuis l'admin
  (`/admin/license-import/*`), lecture seule côté consultation des lots.
- Les seuils de validation (`LICENSE_IMPORT_MIN_*`, `*_VARIATION_*`,
  `*_FILL_RATE_*`) protègent contre un import dégradé : un lot hors seuils est
  **rejeté** ou marqué en avertissement plutôt qu'activé.
- La rétention (`LICENSE_IMPORT_KEEP_LAST_*`, `*_RETENTION_MAX_AGE_DAYS`) purge
  les anciens lots.

---

## 9. Rôles et permissions

Bâtis sur le **RBAC natif d'OpenAdmin** (aucun second système). Semés par la
migration `…_seed_club_roles_and_permissions.php`. Chaque permission métier porte
un `http_path` **appliqué côté serveur** (masquer un bouton ne suffit pas).

| Rôle (slug) | Permissions |
| --- | --- |
| `redacteur` | articles |
| `resp_partenaires` | partenaires |
| `resp_documents` | documents |
| `admin_site` | articles, partenaires, documents, classements, licenciés, paramètres |
| `administrator` | accès total (bypass `isAdministrator`), non modifié |

Le journal d'activité s'appuie sur `operation_log` (OpenAdmin), déjà activé.

---

## 10. Tests

Suite exécutée sur SQLite en mémoire (voir `phpunit.xml`) :

```bash
php artisan test
```

Domaines couverts : contrat d'API, visibilité partenaires/blocs info,
statistiques du tableau de bord, rôles/permissions, nettoyage HTML, pipeline
d'import, matching CueScore. La **checklist de tests manuels** complète la suite :
[`docs/tests-manuels.md`](tests-manuels.md).

---

## 11. Déploiement (o2switch)

1. `git pull` sur la branche de déploiement.
2. `composer install --no-dev --optimize-autoloader`.
3. `php artisan migrate --force`.
4. `php artisan config:cache && php artisan route:cache`.
5. `php artisan scribe:generate` (si l'API a changé).
6. Frontend : `cd frontend && npm ci && npm run build`, puis publier `dist/`.
7. Vérifier `.env` de production (aucun secret dans le dépôt).

---

## 12. Retour arrière

- **Code** : revenir au commit précédent (`git revert` ou redéploiement de la
  version antérieure).
- **Cache** : `php artisan route:clear && php artisan config:clear` si un cache
  obsolète pose problème.
- **Migrations** : ne pas exécuter `migrate:rollback` en production sans avoir
  vérifié les méthodes `down()` et sans sauvegarde préalable de la base.
- **Import de licences** : le mode `--dry-run` permet de tester sans activer un
  lot ; la table `licencies` est reconstruite en `full_replace`, donc conserver
  une sauvegarde avant un import de production.

---

## 13. Points sensibles

- **HTML brut côté React** : toute nouvelle source de contenu affichée en
  `dangerouslySetInnerHTML` doit passer par `HtmlSanitizer` (§7).
- **Cache page d'accueil** : si un modèle affiché en home cesse d'invalider le
  cache, les changements admin ne remontent plus — conserver le hook
  `booted()` → `ApiCacheInvalidator`.
- **Routes calendrier unifiées** : les 16 combinaisons discipline × portée
  partagent le contrôleur `CalendarEventController` ; leurs noms de routes sont
  rendus **uniques** (sinon `route:cache` échoue).
- **Colonne `posts.favoris`** : présente dans le modèle mais absente des
  migrations versionnées — à régulariser par une migration avant de s'appuyer
  sur `Post::factory()` en base fraîche.
- **Compte `administrator`** : ne jamais lui retirer le bypass ; c'est le filet
  de sécurité contre un verrouillage accidentel des accès.
