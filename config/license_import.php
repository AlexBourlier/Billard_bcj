<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Source configuration
    |--------------------------------------------------------------------------
    |
    | Décrit la source externe et les paramètres généraux d'accès.
    | Les secrets (login, mot de passe, etc.) doivent venir du .env.
    |
    */

    'source' => [
        'name' => 'ffbi_telemat',

        'base_url' => env('LICENSE_IMPORT_BASE_URL'),

        'endpoint' => env('LICENSE_IMPORT_ENDPOINT', ''),

        'method' => env('LICENSE_IMPORT_METHOD', 'GET'),

        'form' => [
            'fields' => [
                'num' => env('LICENSE_IMPORT_NUM', ''),
                'nom' => env('LICENSE_IMPORT_NOM', ''),
                'prenom' => env('LICENSE_IMPORT_PRENOM', ''),
                'club' => env('LICENSE_IMPORT_CLUB', '15061'),
                'cs' => env('LICENSE_IMPORT_CS'),
                'find' => env('LICENSE_IMPORT_FIND', 'auto'),
            ],
        ],

        'auth' => [
            'username' => env('LICENSE_IMPORT_USERNAME'),
            'password' => env('LICENSE_IMPORT_PASSWORD'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP client behavior
    |--------------------------------------------------------------------------
    |
    | Paramètres réseau pour récupérer le HTML distant.
    |
    */

    'http' => [
        'timeout' => (int) env('LICENSE_IMPORT_HTTP_TIMEOUT', 30),
        'connect_timeout' => (int) env('LICENSE_IMPORT_HTTP_CONNECT_TIMEOUT', 10),

        /*
        |--------------------------------------------------------------------------
        | Retries HTTP techniques
        |--------------------------------------------------------------------------
        |
        | Ces retries concernent uniquement la récupération réseau.
        | Ils ne remplacent pas la stratégie de retry du job de queue.
        |
        */
        'retries' => [
            'times' => (int) env('LICENSE_IMPORT_HTTP_RETRIES', 2),
            'sleep_ms' => (int) env('LICENSE_IMPORT_HTTP_RETRY_SLEEP_MS', 1000),
        ],

        'user_agent' => env(
            'LICENSE_IMPORT_USER_AGENT',
            'ClubApp License Import Bot/1.0'
        ),

        'headers' => [
            'Accept' => 'text/html,application/xhtml+xml',
        ],

        'allow_redirects' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Parsing configuration
    |--------------------------------------------------------------------------
    |
    | Définit comment localiser et lire la bonne table HTML.
    |
    */

    'parsing' => [
        /*
        |--------------------------------------------------------------------------
        | Sélecteur principal de table
        |--------------------------------------------------------------------------
        |
        | Si connu et stable, il sera utilisé en priorité.
        | Sinon, le parser pourra basculer sur une détection par en-têtes.
        |
        */
        'table_selector' => env('LICENSE_IMPORT_TABLE_SELECTOR'),

        /*
        |--------------------------------------------------------------------------
        | Fallback par reconnaissance des en-têtes
        |--------------------------------------------------------------------------
        |
        | Si aucun sélecteur fiable n'est disponible, le parser pourra chercher
        | la table dont les colonnes détectées correspondent à celles attendues.
        |
        */
        'use_header_detection_fallback' => true,

        /*
        |--------------------------------------------------------------------------
        | Nombre minimal brut de lignes détectées dans la table HTML
        |--------------------------------------------------------------------------
        |
        | Ce seuil protège déjà contre certaines réponses vides ou tronquées.
        | La vraie validation métier se fait plus bas dans validation.minimal.
        |
        */
        'minimum_detected_rows' => 1,

        /*
        |--------------------------------------------------------------------------
        | Normalisation des en-têtes
        |--------------------------------------------------------------------------
        |
        | Contrôle la stratégie de stabilisation des noms de colonnes source.
        |
        */
        'header_normalization' => [
            'trim' => true,
            'lowercase' => true,
            'collapse_spaces' => true,
            'strip_accents' => true,
            'strip_punctuation' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Column mapping
    |--------------------------------------------------------------------------
    |
    | Mappe les en-têtes source normalisés vers les champs internes du snapshot.
    | Chaque champ peut accepter plusieurs variantes d'en-têtes.
    |
    */

    'mapping' => [

        /*
        |--------------------------------------------------------------------------
        | Champs obligatoires
        |--------------------------------------------------------------------------
        |
        | Leur absence côté colonnes détectées doit faire échouer la validation
        | minimale du batch.
        |
        */
        'required_fields' => [
            'license_number',
            'last_name',
            'first_name',
        ],

        /*
        |--------------------------------------------------------------------------
        | Mapping des champs internes vers leurs alias source normalisés
        |--------------------------------------------------------------------------
        |
        | Les valeurs ci-dessous doivent être exprimées APRES normalisation
        | des en-têtes (minuscules, accents supprimés, ponctuation nettoyée, etc.)
        |
        */
        'fields' => [
            'license_number' => [
                'sources' => [
                    'numero',
                    'num ero',
                    'numero licence',
                    'num licence',
                    'licence',
                    'n licence',
                ],
            ],

            'last_name' => [
                'sources' => [
                    'nom',
                    'nom de famille',
                ],
            ],

            'first_name' => [
                'sources' => [
                    'prenom',
                    'pr enom',
                    'prenom usuel',
                ],
            ],

            'birth_date' => [
                'sources' => [
                    'date naissance',
                    'date de naissance',
                    'naissance',
                ],
            ],

            'gender' => [
                'sources' => [
                    'sexe',
                    'genre',
                ],
            ],

            'status' => [
                'sources' => [
                    'statut',
                    'statut licence',
                ],
            ],

            'category' => [
                'sources' => [
                    'categorie',
                    'cat egorie',
                    'categorie licence',
                ],
            ],

            'license_type' => [
                'sources' => [
                    'type licence',
                    'licence type',
                    'type',
                ],
            ],

            'season' => [
                'sources' => [
                    'saison',
                    'annee sportive',
                ],
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Colonnes source utiles à conserver explicitement
        |--------------------------------------------------------------------------
        |
        | Ces champs sont gardés en colonnes dédiées dans le snapshot en plus
        | des champs métier normalisés.
        |
        */
        'source_columns' => [
            'source_license_number' => [
                'sources' => [
                    'numero licence',
                    'num licence',
                    'licence',
                    'n licence',
                ],
            ],

            'source_status_label' => [
                'sources' => [
                    'statut',
                    'statut licence',
                ],
            ],

            'source_category_label' => [
                'sources' => [
                    'categorie',
                    'categorie licence',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation rules
    |--------------------------------------------------------------------------
    |
    | Règles utilisées pour décider si un batch est acceptable.
    |
    */

    'validation' => [

        /*
        |--------------------------------------------------------------------------
        | Validation minimale
        |--------------------------------------------------------------------------
        |
        | Contrôles absolus, indépendants d'un batch précédent.
        |
        */
        'minimal' => [
            'minimum_raw_rows_count' => (int) env('LICENSE_IMPORT_MIN_RAW_ROWS', 10),

            /*
            |--------------------------------------------------------------------------
            | Ratio minimal de lignes valides
            |--------------------------------------------------------------------------
            |
            | Exprimé en pourcentage entier.
            | Exemple : 80 = au moins 80% des lignes doivent être valides.
            |
            */
            'minimum_valid_ratio_percent' => (int) env('LICENSE_IMPORT_MIN_VALID_RATIO_PERCENT', 80),

            /*
            |--------------------------------------------------------------------------
            | Ratio maximal de lignes invalides
            |--------------------------------------------------------------------------
            |
            | Exprimé en pourcentage entier.
            |
            */
            'maximum_invalid_ratio_percent' => (int) env('LICENSE_IMPORT_MAX_INVALID_RATIO_PERCENT', 20),

            /*
            |--------------------------------------------------------------------------
            | Taux minimal de remplissage des champs clés
            |--------------------------------------------------------------------------
            |
            | Exprimé en pourcentage entier, champ par champ.
            |
            */
            'required_field_fill_rate_percent' => [
                'license_number' => (int) env('LICENSE_IMPORT_MIN_LICENSE_NUMBER_FILL_RATE', 95),
                'last_name' => (int) env('LICENSE_IMPORT_MIN_LAST_NAME_FILL_RATE', 95),
                'first_name' => (int) env('LICENSE_IMPORT_MIN_FIRST_NAME_FILL_RATE', 95),
            ],

            /*
            |--------------------------------------------------------------------------
            | Gestion des doublons sur le numéro de licence dans un batch
            |--------------------------------------------------------------------------
            |
            | allowed: on autorise mais on signale
            | warning: warning explicite
            | reject: rejet du batch
            |
            */
            'duplicate_license_number_policy' => env(
                'LICENSE_IMPORT_DUPLICATE_LICENSE_POLICY',
                'warning'
            ),
        ],

        /*
        |--------------------------------------------------------------------------
        | Validation comparative
        |--------------------------------------------------------------------------
        |
        | Contrôles appliqués par comparaison avec le batch actif précédent.
        | Les seuils sont exprimés en pourcentage de variation.
        |
        */
        'comparative' => [
            'enabled' => true,

            /*
            |--------------------------------------------------------------------------
            | Variation du volume total
            |--------------------------------------------------------------------------
            |
            | warning_percent: seuil d'alerte
            | reject_percent: seuil bloquant
            |
            */
            'total_rows_variation' => [
                'warning_percent' => (int) env('LICENSE_IMPORT_TOTAL_ROWS_VARIATION_WARNING', 15),
                'reject_percent' => (int) env('LICENSE_IMPORT_TOTAL_ROWS_VARIATION_REJECT', 35),
            ],

            /*
            |--------------------------------------------------------------------------
            | Variation du volume de lignes valides
            |--------------------------------------------------------------------------
            */
            'valid_rows_variation' => [
                'warning_percent' => (int) env('LICENSE_IMPORT_VALID_ROWS_VARIATION_WARNING', 15),
                'reject_percent' => (int) env('LICENSE_IMPORT_VALID_ROWS_VARIATION_REJECT', 35),
            ],

            /*
            |--------------------------------------------------------------------------
            | Variation du taux de remplissage des champs clés
            |--------------------------------------------------------------------------
            */
            'field_fill_rate_variation' => [
                'warning_percent' => (int) env('LICENSE_IMPORT_FIELD_FILL_RATE_WARNING', 10),
                'reject_percent' => (int) env('LICENSE_IMPORT_FIELD_FILL_RATE_REJECT', 25),
            ],

            /*
            |--------------------------------------------------------------------------
            | Hausse du taux d'invalidité
            |--------------------------------------------------------------------------
            */
            'invalid_ratio_variation' => [
                'warning_percent' => (int) env('LICENSE_IMPORT_INVALID_RATIO_WARNING', 10),
                'reject_percent' => (int) env('LICENSE_IMPORT_INVALID_RATIO_REJECT', 25),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Activation policy
    |--------------------------------------------------------------------------
    |
    | Définit comment un batch devient actif.
    |
    */

    'activation' => [
        'auto_activate_when_valid' => true,

        /*
        |--------------------------------------------------------------------------
        | En mode dry run, le pipeline s'exécute mais n'active jamais le batch.
        | Très utile en environnement de test ou pendant les premiers essais.
        |
        */
        'dry_run' => (bool) env('LICENSE_IMPORT_DRY_RUN', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Projection strategy
    |--------------------------------------------------------------------------
    |
    | Définit comment les données validées sont projetées dans la table cible.
    | Permet de changer facilement de stratégie sans modifier le code.
    |
    */

    'projection' => [
        'strategy' => env('LICENSE_IMPORT_PROJECTION_STRATEGY', 'full_replace'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Lock / concurrency
    |--------------------------------------------------------------------------
    |
    | Contrôle l'anti-concurrence du job.
    |
    */

    'lock' => [
        'name' => env('LICENSE_IMPORT_LOCK_NAME', 'license-import:ffbi-telemat'),
        'ttl_seconds' => (int) env('LICENSE_IMPORT_LOCK_TTL_SECONDS', 900),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue / job execution
    |--------------------------------------------------------------------------
    |
    | Paramètres d'exécution du job de queue.
    |
    */

    'job' => [
        'queue' => env('LICENSE_IMPORT_QUEUE', 'default'),
        'timeout_seconds' => (int) env('LICENSE_IMPORT_JOB_TIMEOUT_SECONDS', 1200),
    ],

    /*
    |--------------------------------------------------------------------------
    | Retention policy
    |--------------------------------------------------------------------------
    |
    | La purge n'est pas encore automatique en V1, mais la politique est déjà
    | définie ici pour préparer la suite.
    |
    */

    'retention' => [
        'enabled' => false,

        'keep_active_batch_forever' => true,

        'keep_last_activated_batches' => (int) env('LICENSE_IMPORT_KEEP_LAST_ACTIVATED_BATCHES', 10),
        'keep_last_rejected_batches' => (int) env('LICENSE_IMPORT_KEEP_LAST_REJECTED_BATCHES', 10),
        'keep_last_failed_batches' => (int) env('LICENSE_IMPORT_KEEP_LAST_FAILED_BATCHES', 10),

        'max_age_days' => (int) env('LICENSE_IMPORT_RETENTION_MAX_AGE_DAYS', 365),
    ],

    /*
    |--------------------------------------------------------------------------
    | Observability / logging
    |--------------------------------------------------------------------------
    |
    | Paramètres de traçabilité et de journalisation.
    |
    */

    'observability' => [
        'log_channel' => env('LICENSE_IMPORT_LOG_CHANNEL'),

        'store_source_columns' => true,
        'store_source_fingerprint' => true,

        /*
        |--------------------------------------------------------------------------
        | Conserver ou non une partie du contenu brut à des fins de diagnostic.
        | À false par défaut pour limiter les risques et le bruit.
        |
        */
        'store_raw_html_excerpt' => false,

        /*
        |--------------------------------------------------------------------------
        | Taille maximale d'un éventuel extrait de HTML stocké dans meta.
        |
        */
        'raw_html_excerpt_max_length' => 2000,
    ],

];