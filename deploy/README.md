# Déploiement BCJ37 (o2switch)

Kit de déploiement pour l'architecture **back/front séparés** :

| Élément | Sous-domaine | Contenu servi |
| --- | --- | --- |
| API Laravel | `api.test.alexandrebourlier.fr` | le dossier `public/` de l'application Laravel |
| Front React | `test.alexandrebourlier.fr` | le contenu de `frontend/dist/` (build Vite) |

> **Aucun secret dans ce dossier.** `.env.production` (racine) et `frontend/.env.production`
> restent locaux (ignorés par git) et sont transférés de façon chiffrée (SFTP).

## Contenu du kit

| Fichier | Rôle |
| --- | --- |
| `deploy.ps1` | Orchestrateur Windows : build front → upload SFTP → commandes serveur. |
| `winscp-upload.txt` | Script WinSCP : téléverse le backend (API) et le build (front). |
| `remote.sh` | Commandes serveur : composer, migrations, caches, storage:link. |
| `front.htaccess` | Réécritures SPA (React Router) + HTTPS + cache assets, pour le front. |

---

## 1. Configuration o2switch (à faire une seule fois)

1. **Sous-domaines** (cPanel → Domaines/Sous-domaines) :
   - `api.test.alexandrebourlier.fr` → **document root** `/home/boal2619/api.test.alexandrebourlier.fr/public`
     *(le `/public` est important : on n'expose jamais la racine de Laravel).*
   - `test.alexandrebourlier.fr` → document root `/home/boal2619/test.alexandrebourlier.fr`.
2. **Version PHP** (cPanel → MultiPHP Manager) : régler **PHP 8.1+** (idéalement 8.3)
   sur le sous-domaine de l'API.
3. **Base de données** (cPanel → MySQL) : créer la base + l'utilisateur, et reporter
   les identifiants dans `.env.production` (`DB_*`).
4. **`.env.production`** (racine) — vérifier au minimum :
   - `APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY=...` (généré),
   - `APP_URL=https://api.test.alexandrebourlier.fr`,
   - `FRONTEND_URL=https://test.alexandrebourlier.fr` *(indispensable : autorise le CORS du front),*
   - `SESSION_DOMAIN` / `SANCTUM_STATEFUL_DOMAINS` cohérents avec les domaines ci-dessus.
5. **`frontend/.env.production`** : `VITE_API_URL=https://api.test.alexandrebourlier.fr/api/v1`
   *(utilisé au build : le front tape directement sur le sous-domaine API).*
6. **Session WinSCP** : enregistrer une session **SFTP** nommée **`o2switch-sftp`**
   — Protocole `SFTP`, hôte `melon.o2switch.net`, port `22`, utilisateur `boal2619`
   (mot de passe ou clé SSH). **Se connecter une fois via l'interface** pour
   mémoriser la clé d'hôte. On utilise SFTP (et non FTP) car il voit le vrai
   filesystem du serveur (comme le SSH) et chiffre les transferts.

---

## 2. Déploiement

### Méthode automatique (recommandée, Windows)

```powershell
powershell -ExecutionPolicy Bypass -File deploy\deploy.ps1
```

Le script : build le front, téléverse back + front, puis exécute `remote.sh` en SSH.
Adapter au préalable les variables en tête de `deploy.ps1` (chemins, hôte, port).

### Méthode manuelle (3 étapes)

```powershell
# 1) Build du front (embarque VITE_API_URL de frontend/.env.production)
cd frontend ; npm ci ; npm run build ; cd ..

# 2) Téléversement (via la session WinSCP enregistrée ; PAS de /ini=nul)
& "C:\Program Files (x86)\WinSCP\WinSCP.com" /script="deploy\winscp-upload.txt"

# 3) Commandes serveur (en SSH)
ssh boal2619@melon.o2switch.net "cd /home/boal2619/api.test.alexandrebourlier.fr && bash remote.sh"
```

`remote.sh` lance : `composer install --no-dev`, `storage:link`, `migrate --force`,
reconstruction des caches (`config`/`route`/`view`), `scribe:generate`.

---

## 3. Sécurité et points d'attention

- **Base de données** : `migrate --force` n'applique que les migrations **en attente**
  (jamais de reset). **Fais une sauvegarde de la base avant un déploiement important.**
- **Fichiers uploadés** : le dossier `storage/` du serveur **n'est pas écrasé**
  (exclu du téléversement). Les images des articles/partenaires restent en place.
- **Migrations ajoutées récemment** (idempotentes) : `posts.favoris` (garde `hasColumn`),
  workflow de publication (`status`, `published_at`, `updated_by`). Elles s'appliquent
  sans risque sur une base existante.
- **Cache** : après déploiement, `remote.sh` reconstruit les caches. En cas
  d'affichage figé, `php artisan optimize:clear` sur le serveur.
- **HTTPS / CORS** : le front (`test.…`) et l'API (`api.test.…`) étant sur des
  sous-domaines distincts, l'accès est autorisé via `FRONTEND_URL` dans le `.env`.
- **Aucun mot de passe dans ce dépôt** : l'authentification SFTP passe par une
  session WinSCP (ou une clé SSH) ; `.env` est transféré chiffré et n'est pas versionné.

---

## 4. Retour arrière (rollback)

1. **Code** : revenir à la version précédente (`git checkout <tag/commit>` en local)
   puis relancer le déploiement.
2. **Base** : restaurer la sauvegarde effectuée avant le déploiement.
3. **Caches** : `php artisan optimize:clear` sur le serveur.

Ne jamais exécuter `migrate:rollback` en production sans sauvegarde ni vérification
des méthodes `down()`.
