# Étude de faisabilité — Espace licencié BCJ37

> **Nature du document.** Ceci est **une étude**, pas une implémentation. Aucun code,
> aucune migration, aucune modification de l'authentification, aucun envoi de courriel
> ni paiement ne sont mis en place à ce stade. Le document sert de base de décision
> pour un développement ultérieur.

## 1. Contexte et objectifs

Le site BCJ37 dispose aujourd'hui :

- d'un **frontend public** React/Vite consommant une **API REST** Laravel (`/api/v1`) ;
- d'une **administration OpenAdmin** avec sa propre authentification (guard `admin`),
  ses **rôles/permissions** (cf. lot 6) et son **journal d'activité** ;
- d'une table `licencies` **minimale** (`id, licence, nom, prenom, url`) alimentée par
  le pipeline d'import Telemat ;
- de **Sanctum** installé (jetons/session), non encore utilisé pour un espace membre ;
- **aucune** table `users` ni authentification côté public : l'espace licencié serait
  donc une **nouveauté** à part entière.

L'espace licencié pourrait permettre de :

- centraliser les informations destinées aux joueurs ;
- réserver certains documents aux membres ;
- consulter les convocations et informations de discipline ;
- consulter le statut de la cotisation annuelle ;
- recevoir des rappels ;
- permettre aux dirigeants d'envoyer des relances par courriel.

Le principe directeur : **réutiliser l'existant** (RBAC OpenAdmin, sanitisation HTML,
cache, pipeline licenciés) et **séparer clairement** l'espace licencié de
l'administration.

---

## 2. Comptes

### 2.1 Cycle de vie proposé

| Étape | Description |
|-------|-------------|
| Création | Le compte est **rattaché à un licencié existant** (table `licencies`), créé par un dirigeant, jamais en libre inscription. |
| Invitation | Envoi d'un courriel d'invitation contenant un lien d'activation à durée limitée (jeton signé). |
| Activation | Le membre définit son mot de passe ; le compte devient actif. |
| Mot de passe oublié | Réinitialisation par courriel (jeton à usage unique, expiration courte). |
| Changement d'adresse | Nouvelle adresse **vérifiée** avant de remplacer l'ancienne. |
| Désactivation | Un ancien membre est **désactivé** (pas supprimé) : accès révoqué, données conservées selon la durée légale. |
| Multi-disciplines | Un membre peut être rattaché à plusieurs disciplines (relation N‑N). |
| Représentant légal | Pour un mineur : un contact « représentant légal » (courriel de contact, destinataire des communications). |

### 2.2 Points d'attention

- Le compte membre est **distinct** du compte administrateur OpenAdmin (deux gardes
  d'authentification séparées : `admin` existant, `member` à créer).
- L'appariement compte ↔ licencié doit être **fiable** (n° de licence + vérification
  courriel) pour éviter qu'un membre accède aux données d'un autre.

### 2.3 Comptes familiaux (gestion par foyer)

Besoin fréquent en club : une même personne (souvent un parent) gère **plusieurs
licenciés d'un même foyer** avec **une seule adresse courriel**, plutôt qu'un compte et
une adresse par licencié.

**Principe proposé** : un **foyer** regroupe plusieurs licenciés ; un **compte
gestionnaire** (un courriel) est rattaché au foyer et gère l'ensemble de ses membres.

| Élément | Proposition |
|---------|-------------|
| Modèle | Table `foyers` ; chaque `licencie` appartient à **au plus un** foyer ; un compte gestionnaire par foyer. |
| Rattachement | Réalisé par un dirigeant (ou proposé lors de l'invitation), jamais deviné automatiquement. |
| Vue gestionnaire | Le gestionnaire voit et gère, pour chaque membre du foyer : informations, statut de cotisation, documents réservés, convocations. |
| Communications | Un seul courriel pour le foyer ; les relances de cotisation sont **regroupées** (un message listant les membres concernés). |
| Bascule de compte individuel | Un membre **adulte** peut disposer de son **propre** compte au lieu d'être géré par le foyer. |
| Cas à gérer | Membre changeant de foyer (déménagement), passage à la majorité, foyer comptant plusieurs adultes, membre sans foyer. |

**Points de vigilance (RGPD)** :

- Pour un **mineur**, la gestion par le représentant légal (autorité parentale) est
  légitime.
- Pour un **membre majeur** rattaché à un foyer, l'accès du gestionnaire à ses données
  personnelles suppose son **consentement** ; à défaut, il doit pouvoir gérer son propre
  compte. Ce point doit être **validé par une personne compétente en protection des
  données** (cf. §8).

Cette fonctionnalité peut être introduite **dès la version minimale** (rattachement +
vue gestionnaire), la gestion fine du consentement des majeurs pouvant être précisée
ensuite.

---

## 3. Données personnelles (minimisation)

Principe : **ne collecter que le strict nécessaire**. Pour chaque donnée, on précise la
finalité, qui peut la consulter, la durée de conservation et son caractère obligatoire.

| Donnée | Finalité | Consultée par | Conservation (à valider) | Obligatoire |
|--------|----------|---------------|--------------------------|-------------|
| Nom, prénom | Identification | Membre, dirigeants | Durée d'adhésion + délai légal | Oui |
| N° de licence | Appariement licencié | Membre, dirigeants | Idem | Oui |
| Courriel | Connexion, communications | Membre, dirigeants | Idem | Oui |
| Disciplines | Documents/convocations ciblés | Membre, dirigeants | Idem | Oui |
| Téléphone | Contact | Dirigeants | Idem | Facultatif |
| Représentant légal (mineur) | Communication légale | Dirigeants | Idem | Selon âge |
| Statut cotisation | Suivi financier | Membre (le sien), dirigeants | Exercice comptable + délai légal | Facultatif |

> ⚠️ Les **durées de conservation** et la base légale doivent être **validées par une
> personne compétente en protection des données** (cf. §8). Ce document ne constitue pas
> un avis juridique.

---

## 4. Cotisations

Statuts proposés : `non renseignée`, `à régler`, `partiellement réglée`, `réglée`,
`exonérée`, `cas particulier`.

> **Règle importante** : l'**absence de donnée** (`non renseignée`) ne doit **jamais**
> être présentée comme un **impayé**. Elle signifie seulement que l'information n'a pas
> encore été saisie.

Données envisagées (par saison) : `saison`, `montant théorique`, `montant versé`,
`date`, `mode de règlement`, `commentaire interne` (non visible du membre), `historique`.

- Le **système de saison** doit être explicite (une saison sportive ≠ année civile).
  À prévoir : une notion de saison réutilisable (déjà présente sous forme de chaîne
  `2025-2026` sur les classements CueScore — à centraliser).
- **Pas de paiement en ligne** dans cette étude : uniquement le **suivi** d'un statut
  saisi manuellement par les dirigeants.

---

## 5. Relances par courriel

Fonctions à étudier : relance **individuelle** et **groupée**, **modèles** de messages,
**prévisualisation**, **validation avant envoi**, **historique**, **protection contre les
doublons**, **limitation d'envoi** (anti-abus), **distinction** entre message
administratif (obligatoire) et communication facultative, **gestion des erreurs** d'envoi.

> **Règle importante** : aucune relance ne doit partir **automatiquement** sans une
> **règle explicitement validée par le club** et une **action humaine** de validation.

Aspects techniques :

- Envois via **file d'attente** (queue database + worker, déjà en place pour les imports)
  pour ne pas bloquer et pour tracer les erreurs.
- **Journalisation** de chaque envoi (destinataire, modèle, date, statut) — sans stocker
  le contenu de champs sensibles.
- Respect du **consentement** pour les communications facultatives (préférences).

---

## 6. Documents réservés

Niveaux de visibilité proposés :

1. **Public** (déjà géré : documents actuels) ;
2. **Tous les licenciés** ;
3. **Une discipline** particulière ;
4. **Un rôle** particulier (ex. capitaines) ;
5. **Un utilisateur** précis.

Implémentation envisagée : un champ `visibilite` sur les documents + un contrôle
**côté serveur** à chaque téléchargement (jamais uniquement en masquant un lien). Le
fichier est servi par un contrôleur qui vérifie l'autorisation, pas par une URL publique
devinable.

---

## 7. Sécurité

- **Authentification** dédiée (garde `member`, distincte d'OpenAdmin) via Sanctum
  (session SPA) ;
- **vérification de l'adresse courriel** à l'activation et au changement ;
- **réinitialisation** par jeton à usage unique et expiration courte ;
- **limitation des tentatives** de connexion (throttling, déjà disponible) ;
- **contrôle d'autorisation côté serveur** systématique (un membre n'accède qu'à ses
  propres données — vérification `member_id` sur chaque ressource) ;
- **expiration des sessions** ;
- **journalisation** des accès sensibles ;
- **séparation stricte** entre l'administration (OpenAdmin) et l'espace licencié :
  gardes, routes, middlewares et vues distincts.

> « Masquer un bouton ne suffit pas » : toute restriction doit être **appliquée côté
> serveur** (routes, requêtes, actions, exports), comme pour le RBAC admin du lot 6.

---

## 8. Protection des données (RGPD)

Impacts à identifier : données personnelles collectées, durées de conservation, droits
d'**accès / rectification / export / suppression / anonymisation**, mise à jour de la
**politique de confidentialité**, choix des **prestataires d'envoi de courriels**
(sous‑traitants, hébergement des données).

> ⚠️ Ce document **ne donne pas d'avis juridique**. Les points suivants doivent être
> **validés par une personne compétente en protection des données** avant tout
> développement :
> - bases légales et durées de conservation ;
> - contenu de la politique de confidentialité et des mentions d'information ;
> - contrat de sous‑traitance avec le prestataire d'envoi de courriels ;
> - modalités d'exercice des droits (export, suppression, anonymisation) ;
> - **consentement des membres majeurs** dont les données sont accessibles à un
>   gestionnaire de foyer (cf. §2.3).

---

## 9. Parcours utilisateurs

1. **Activation du compte** : réception de l'invitation → clic sur le lien → définition
   du mot de passe → accès à l'espace.
2. **Consultation des informations** : le membre voit ses infos, ses disciplines, les
   informations du club.
3. **Consultation de la cotisation** : statut de la saison en cours (jamais présenté
   comme impayé si non renseigné).
4. **Téléchargement d'un document réservé** : contrôle d'autorisation côté serveur puis
   téléchargement.
5. **Mise à jour d'une cotisation** (dirigeant) : saisie du statut/montant → historique.
6. **Préparation d'une relance** (dirigeant) : sélection des destinataires, choix d'un
   modèle, prévisualisation.
7. **Validation puis envoi d'une relance** : confirmation humaine → mise en file →
   journalisation.
8. **Désactivation d'un ancien membre** : accès révoqué, données conservées.
9. **Publication d'une information réservée à une discipline** : contenu visible
   uniquement des membres de la discipline concernée.

---

## 10. Deux versions comparées

### 10.1 Version minimale

**Fonctionnalités** : compte licencié + activation, informations du club, documents
réservés (niveaux public / tous licenciés / discipline), statut de cotisation renseigné
**manuellement**.

| Axe | Détail |
|-----|--------|
| Architecture | Garde `member` (Sanctum), espace React dédié, contrôleurs API protégés |
| Tables envisagées | `members` (ou `users` + lien `licencie_id`), `foyers` (comptes familiaux), `member_discipline`, `cotisations`, champ `visibilite` sur `documents` |
| Endpoints | `POST /member/login`, `POST /member/logout`, `POST /member/activate`, `GET /member/me`, `GET /member/cotisation`, `GET /member/documents`, `GET /member/documents/{id}/download` |
| Permissions | Rôle « membre » (accès à ses données) ; gestion côté admin via le RBAC existant |
| Modifications React | Nouvelles pages : connexion, activation, tableau de bord membre, documents, cotisation |
| Modifications Laravel | Garde/guard `member`, middlewares, contrôleurs, `FormRequest`, ressources |
| Modifications OpenAdmin | Écrans de gestion des comptes, des cotisations, du niveau de visibilité des documents |
| Sécurité | Auth dédiée, contrôle serveur par membre, throttling, vérification courriel |
| Tests | Auth, autorisation (accès aux données d'autrui refusé), visibilité documents |
| Maintenance | Modérée : périmètre restreint, pas d'envoi de courriels |
| Complexité | **Moyenne** |
| Risques | Appariement compte↔licencié ; conformité RGPD (à valider) |
| Ordre de développement | Auth → comptes → documents réservés → cotisation (lecture) |

### 10.2 Version avancée

**Fonctionnalités** (en plus de la minimale) : relances par courriel, modèles de
messages, historique, statistiques, préférences de communication, automatisations
**contrôlées**, alertes, exports.

| Axe | Détail |
|-----|--------|
| Architecture | Minimale + file d'attente (envois), moteur de modèles, journal des envois |
| Tables envisagées | + `email_templates`, `email_logs`, `communication_preferences`, `member_events` |
| Endpoints | + `POST /admin/relances/preview`, `POST /admin/relances/send`, `GET /admin/relances/history` (côté admin) |
| Permissions | Rôle « gestionnaire des licenciés » (RBAC lot 6) pour relances/cotisations |
| Modifications React | + écrans dirigeants (préparation/validation des relances, historique, stats) |
| Modifications Laravel | + jobs d'envoi, service de modèles, journalisation, gestion des erreurs |
| Modifications OpenAdmin | + gestion des modèles, historique des envois, préférences |
| Sécurité | + validation humaine obligatoire, anti-doublon, limitation d'envoi, journalisation renforcée |
| Tests | + rendu des modèles, file d'attente, anti-doublon, refus d'envoi automatique non validé |
| Maintenance | **Élevée** (prestataire courriel, délivrabilité, RGPD) |
| Complexité | **Élevée** |
| Risques | Délivrabilité des courriels, conformité RGPD renforcée, sur‑sollicitation des membres |
| Ordre de développement | Version minimale d'abord → modèles → relances (préparation) → envoi validé → historique/stats |

---

## 11. Recommandation

Développer d'abord la **version minimale** (valeur immédiate : documents réservés +
statut de cotisation), en réutilisant le RBAC, la sanitisation et le cache déjà en place,
puis n'ajouter la **version avancée** (relances) qu'après validation RGPD et mise en place
d'un prestataire d'envoi fiable.

**À faire valider avant tout développement** : les points RGPD du §8 par une personne
compétente en protection des données, et la politique de communication du club (règles
de relance).
