#!/usr/bin/env bash
#
# Commandes a executer SUR LE SERVEUR (o2switch), dans le dossier du backend
# Laravel (celui servi par api.test.alexandrebourlier.fr).
#
# A lancer via SSH ou depuis le Terminal cPanel :
#     bash remote.sh
#
# Prerequis :
#   - Le code Laravel a deja ete televerse (voir winscp-upload.txt).
#   - Le fichier .env de production est present dans ce dossier.
#   - PHP 8.1+ et Composer sont disponibles.
#
# IMPORTANT : sur o2switch, le "php" par defaut en ligne de commande peut etre
# une ancienne version. On force donc une version >= 8.1 via PHP_BIN.
set -euo pipefail

# --- A ADAPTER si besoin --------------------------------------------------
# Binaire PHP >= 8.1. On tente l'auto-detection ci-dessous ; tu peux forcer une
# valeur en exportant PHP_BIN avant de lancer le script.
PHP_BIN="${PHP_BIN:-}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
# --------------------------------------------------------------------------

# Extraction de l'archive du code (deploiement deterministe via `git archive`).
# Tous les fichiers versionnes sont ainsi remis a jour d'un coup, sans risque
# d'oubli. N'affecte NI le .env, NI vendor/, NI les fichiers uploades dans
# storage/ (tous absents de l'archive). tar ecrase les fichiers presents mais
# ne supprime pas ceux qui n'existent plus dans le depot (vieux fichiers
# residuels sans gravite).
if [ -f app.tar.gz ]; then
    echo "==> Extraction du code (app.tar.gz)"
    tar -xzf app.tar.gz
    rm -f app.tar.gz
fi

# Auto-detection d'un PHP 8.x sur o2switch (cPanel EasyApache).
if [ -z "$PHP_BIN" ] || ! "$PHP_BIN" -v >/dev/null 2>&1; then
    for candidate in \
        /opt/alt/php83/usr/bin/php \
        /opt/alt/php82/usr/bin/php \
        /opt/alt/php81/usr/bin/php \
        /opt/cpanel/ea-php83/root/usr/bin/php \
        /opt/cpanel/ea-php82/root/usr/bin/php \
        /opt/cpanel/ea-php81/root/usr/bin/php \
        ea-php83 ea-php82 ea-php81 php8.3 php8.2 php8.1 php; do
        if command -v "$candidate" >/dev/null 2>&1; then PHP_BIN="$candidate"; break; fi
    done
fi

if ! "$PHP_BIN" -v >/dev/null 2>&1; then
    echo "ERREUR : aucun binaire PHP 8.x trouve. Exporte PHP_BIN=... et relance." >&2
    exit 1
fi

echo "==> PHP : $($PHP_BIN -v | head -n1)"

echo "==> Arborescence storage / cache (creee si absente, premier deploi)"
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
         storage/logs storage/app/public bootstrap/cache

echo "==> Dependances PHP (production)"
# On execute Composer avec le bon PHP (le php CLI par defaut d'o2switch peut etre
# trop ancien). Composer doit etre disponible dans le PATH (sinon adapter ci-dessus).
if command -v "$COMPOSER_BIN" >/dev/null 2>&1; then
    $PHP_BIN "$(command -v "$COMPOSER_BIN")" install --no-dev --optimize-autoloader --no-interaction
else
    echo "ERREUR : Composer introuvable dans le PATH. Installe-le ou ajuste COMPOSER_BIN." >&2
    exit 1
fi

echo "==> Lien symbolique du stockage public (si absent)"
$PHP_BIN artisan storage:link || true

echo "==> Migrations de la base (sans destruction)"
# --force : requis en production (pas de confirmation interactive).
# Ne JAMAIS utiliser migrate:fresh / migrate:refresh ici : ils videraient la base.
$PHP_BIN artisan migrate --force

echo "==> Nettoyage puis reconstruction des caches"
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

echo "==> Documentation de l'API (OpenAPI / Scribe)"
$PHP_BIN artisan scribe:generate || echo "   (scribe:generate ignore - non bloquant)"

echo "==> Droits d'ecriture sur storage et bootstrap/cache"
chmod -R ug+rw storage bootstrap/cache || true

echo "==> Termine."
