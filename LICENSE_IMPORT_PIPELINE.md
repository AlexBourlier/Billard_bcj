# License Import Pipeline

## Objectif

Ce pipeline importe les licences Telemat dans un flux robuste, testable et maintenable.

Objectifs principaux :

- récupérer la source HTML Telemat,
- valider la réponse HTTP,
- parser le tableau HTML des licences,
- mapper les colonnes source vers les champs métier,
- persister un batch, des snapshots et des issues,
- appliquer des validations minimales puis comparatives,
- activer le batch seulement si le résultat est acceptable,
- conserver un état d’exécution exploitable en cas d’échec.

---

## Entrées connues

### Commande console

Fichier :
- `app/Console/Commands/RunTelematLicenseImportCommand.php`

Commande :
- `php artisan license-import:run`

Options connues :

- `--source=ffbi_telemat`
- `--dry-run`
- `--triggered-by-label=...`

La commande construit un `LicenseImportExecutionContext`, puis dispatch un job.

### Job queue

Fichier :
- `app/Jobs/RunTelematLicenseImportJob.php`

Rôle :

- reconstruire le `LicenseImportExecutionContext` depuis le payload,
- acquérir un lock applicatif,
- exécuter l’orchestrateur,
- journaliser le démarrage, le skip sur lock et la fin/erreur,
- relancer l’exception en cas d’échec.

---

## Contexte d’exécution

DTO :
- `app/Domain/LicenseImport/DTO/LicenseImportExecutionContext.php`

Champs :

- `source`
- `triggerType`
- `triggeredByUserId`
- `triggeredByLabel`
- `requestedAt`
- `dryRun`

---

## Orchestrateur principal

Fichier :
- `app/Domain/LicenseImport/Pipeline/TelematLicenseImportOrchestrator.php`

L’orchestrateur est le point central du pipeline.

### Flux nominal

1. création du batch
2. fetch HTTP
3. validation de la réponse fetch
4. parsing du tableau HTML
5. normalisation / mapping métier
6. persistance des snapshots
7. validation minimale
8. validation comparative
9. activation ou clôture sans activation
10. rafraîchissement des compteurs d’issues
11. journalisation de fin

### Flux d’échec

Deux familles d’échec sont distinguées :

- échecs de fetch Telemat
- échecs inattendus du pipeline

Dans les deux cas :

- une issue est enregistrée,
- le batch est marqué en échec,
- `finished_at` est renseigné,
- les métadonnées d’exécution sont complétées.

---

## Modèle batch

Fichier :
- `app/Models/LicenseImportBatch.php`

Le batch représente une exécution d’import.

Champs importants :

- `source`
- `status`
- `is_active`
- `activated_at`
- `started_at`
- `finished_at`
- `raw_rows_count`
- `valid_rows_count`
- `invalid_rows_count`
- `error_count`
- `warning_count`
- `source_fingerprint`
- `source_columns`
- `meta`
- `summary`

### Statuts connus

Enum :
- `app/Domain/LicenseImport/Enums/BatchStatus.php`

Valeurs connues :

- `pending`
- `running`
- `fetched`
- `parsed`
- `validated_minimal`
- `validated_comparative`
- `activated`
- `rejected`
- `failed`

### États terminaux

Un batch est terminal s’il est dans l’un de ces statuts :

- `activated`
- `rejected`
- `failed`

---

## Persistance métier

### Snapshots

Les snapshots représentent les lignes normalisées et persistées.

Ils permettent :

- de conserver la photographie de l’import,
- d’appliquer les validations,
- d’analyser les lignes invalides,
- de conserver la structure source même en cas d’échec ultérieur.

### Issues

Les issues représentent les warnings et erreurs structurées du pipeline.

Exemples de codes rencontrés dans les tests :

- `fetch_network_failure`
- `fetch_invalid_response`
- `fetch_failed`
- `pipeline_unexpected_failure`
- `missing_required_field`
- `minimal_raw_rows_below_threshold`
- `minimum_valid_ratio_not_reached`
- `maximum_invalid_ratio_exceeded`
- `required_field_fill_rate_below_threshold`
- `duplicate_license_number_rejected`
- `duplicate_license_number_warning`
- `comparative_total_rows_variation_warning`
- `comparative_total_rows_variation_reject`
- `comparative_valid_rows_variation_reject`
- `comparative_invalid_ratio_variation_reject`
- `comparative_field_fill_rate_variation_first_name_warning`
- `comparative_field_fill_rate_variation_first_name_reject`

---

## Validation minimale

But :

- s’assurer que l’import courant est cohérent en lui-même.

Contrôles connus :

- nombre minimal de lignes brutes,
- ratio minimal de lignes valides,
- ratio maximal de lignes invalides,
- taux de remplissage minimal des champs requis,
- politique sur doublons de numéro de licence.

Effets connus :

- les lignes invalides restent persistées,
- des issues détaillées sont créées,
- un échec minimal fait échouer le batch.

---

## Validation comparative

But :

- comparer le batch courant au batch actif précédent de la même source.

Contrôles connus :

- variation du nombre total de lignes,
- variation du nombre de lignes valides,
- variation du ratio de lignes invalides,
- variation du taux de remplissage de champs requis.

Règles connues :

- si aucun batch actif précédent n’existe, la validation comparative est marquée comme `skipped`,
- des warnings comparatifs n’empêchent pas l’activation,
- un seuil `reject` provoque un échec du batch.

---

## Activation

Le pipeline peut finir dans trois modes connus :

### 1. Activation réelle

Cas nominal :
- le batch devient actif,
- les anciens batches actifs de la même source sont désactivés,
- `status = activated`.

### 2. Dry-run

- le batch est terminé mais non activé,
- `is_active = false`,
- `activated_at = null`,
- le résultat d’exécution reste un succès non activé.

### 3. Auto-activation désactivée

- le batch est terminé mais non activé,
- `is_active = false`,
- `activated_at = null`,
- le résultat d’exécution reste un succès non activé.

---

## Métadonnées d’exécution

Le batch enrichit `meta` et `summary`.

### `meta.execution`

Contient notamment :

- `requested_at`
- `started_at`
- `dry_run`
- `finished`
- `result`
- `final_outcome`
- `finished_at`

### `summary.execution`

Contient notamment :

- `finished`
- `result`
- `final_outcome`

En cas d’échec :

- `failure_stage`
- `failure_code`

---

## Lock d’exécution

Le job utilise un lock applicatif.

Objectif :

- éviter deux imports concurrents simultanés pour la même logique de pipeline.

Comportement connu :

- si le lock n’est pas acquis, le job est ignoré,
- un log de skip est écrit,
- l’orchestrateur n’est pas appelé.

---

## Stratégie de tests actuellement couverte

Les tests couvrent déjà :

- commande console,
- job queue,
- lock du job,
- exécution normale,
- fetch invalide,
- panne réseau,
- erreur fetch générique,
- échecs de parsing,
- validations minimales,
- validations comparatives,
- dry-run,
- auto-activation désactivée,
- snapshots HTML réalistes,
- fallback de détection de table,
- normalisation de headers bruités,
- cas où le tableau existe mais le mapping métier ne matche plus.

---

## Limites documentées à ce stade

Ce document décrit uniquement ce qui est vérifié à partir du code et des tests fournis.

Points restant à formaliser plus tard :

- scheduling de production,
- supervision / alerting,
- procédure de re-run opérateur,
- outillage admin/debug,
- conventions officielles de runbook,
- stratégie complète en cas de changement HTML Telemat majeur.