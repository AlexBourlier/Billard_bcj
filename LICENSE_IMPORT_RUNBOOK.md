# License Import Runbook

## Objet

Ce document décrit la procédure minimale d’exploitation du pipeline d’import des licences Telemat.

Il couvre :

- le lancement manuel,
- le dry-run,
- la lecture du résultat,
- l’interprétation des statuts batch,
- l’analyse d’un échec,
- la stratégie minimale de re-run.

Ce document décrit uniquement les comportements réellement présents dans le projet à ce stade.

---

## Point d’entrée opérateur

### Commande disponible

Fichier :
- `app/Console/Commands/RunTelematLicenseImportCommand.php`

Commande :

```bash
php artisan license-import:run