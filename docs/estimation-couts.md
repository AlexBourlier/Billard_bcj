# Estimation du temps de développement — BCJ37

Estimation du temps qui aurait été nécessaire à un **développeur freelance
expérimenté** pour réaliser ces travaux dans de bonnes conditions, avec une
qualité de code élevée, une documentation correcte et des tests raisonnables.

Les durées ne sont ni minimisées ni gonflées. Lorsqu'une estimation reste
incertaine, l'hypothèse retenue est indiquée.

## Base de calcul

- **TJM (Tarif Journalier Moyen) : 280 € HT / jour.**
- **1 jour = 7 heures effectives de production.**
- **Tous les montants sont exprimés HT** (hors taxes). Estimation réalisée dans
  le cadre d'une activité d'**auto-entrepreneur** : **aucune TVA** n'est intégrée.

> Le temps de développement inclut l'analyse, l'intégration, l'accessibilité et
> la revue. « Tests » = tests automatisés + vérifications ; « Corrections » =
> reprises après recette.

---

## 1. Détail par lot

### Lot 1 — Carrousel infini des partenaires
- **Complexité** : moyenne.
- **Prérequis** : gestion des partenaires existante.
- Développement 1,5 j · Tests 0,5 j · Documentation 0,25 j · Corrections 0,25 j.
- **Total : 2,5 j — 700 € HT.**

### Lot 2 — Refonte visuelle d'OpenAdmin
- **Complexité** : moyenne (large surface, essentiellement CSS/vues).
- **Prérequis** : charte graphique du club.
- Développement 2,0 j · Tests 0,25 j · Documentation 0,25 j · Corrections 0,5 j.
- **Total : 3,0 j — 840 € HT.**

### Lot 3 — Dashboard analytique du club
- **Complexité** : moyenne à élevée (agrégations sans N+1, données réelles).
- **Prérequis** : données existantes (articles, partenaires, événements, imports).
- Développement 2,0 j · Tests 0,5 j · Documentation 0,25 j · Corrections 0,25 j.
- **Total : 3,0 j — 840 € HT.**

### Lot 4 — Éditeur d'articles + nettoyage HTML serveur
- **Complexité** : élevée (sécurité, compatibilité des anciens contenus).
- **Prérequis** : structure des articles, rendu React.
- Développement 2,0 j · Tests 0,75 j · Documentation 0,25 j · Corrections 0,5 j.
- **Total : 3,5 j — 980 € HT.**

### Lot 5 — Simplification du parcours administrateur
- **Complexité** : moyenne.
- **Prérequis** : structure du menu OpenAdmin.
- Développement 1,5 j · Tests 0,25 j · Documentation 0,5 j · Corrections 0,25 j.
- **Total : 2,5 j — 700 € HT.**

### UX — Gestion unifiée des documents (catégorie + filtre)
- **Complexité** : faible.
- Développement 0,25 j · Tests 0,1 j · Documentation 0,1 j · Corrections 0,05 j.
- **Total : 0,5 j — 140 € HT.**

### Lot 6 — Rôles, permissions serveur et journal d'activité
- **Complexité** : moyenne à élevée (enforcement côté serveur).
- **Prérequis** : RBAC natif d'OpenAdmin.
- Développement 1,5 j · Tests 0,5 j · Documentation 0,25 j · Corrections 0,25 j.
- **Total : 2,5 j — 700 € HT.**

### Lot 7 — Commentaires et maintenabilité
- **Complexité** : faible (ciblé, sans refonte).
- Développement 1,0 j · Tests 0,1 j · Documentation 0,1 j · Corrections 0,05 j.
- **Total : 1,25 j — 350 € HT.**

### Lot 8 — Documentation OpenAPI + tests de contrat
- **Complexité** : moyenne.
- **Prérequis** : routes API stabilisées.
- Développement 1,5 j · Tests 0,5 j · Documentation 0,5 j · Corrections 0,25 j.
- **Total : 2,75 j — 770 € HT.**

### Lot 9 — Bloc d'information administrable
- **Complexité** : moyenne.
- Développement 1,25 j · Tests 0,25 j · Documentation 0,15 j · Corrections 0,1 j.
- **Total : 1,75 j — 490 € HT.**

### Lot 10 — Étude du futur espace licencié (document)
- **Complexité** : moyenne (analyse fonctionnelle, technique et RGPD).
- Rédaction 1,5 j · Corrections 0,25 j (le document **est** le livrable).
- **Total : 1,75 j — 490 € HT.**

### Lot 11 — Tests et contrôle qualité
- **Complexité** : moyenne.
- Développement (tests + correctif routage) 1,5 j · Documentation (checklist) 0,5 j · Corrections 0,25 j.
- **Total : 2,25 j — 630 € HT.**

### Lot 12 — Documentation utilisateur et développeur
- **Complexité** : faible à moyenne.
- Rédaction 1,5 j · Corrections 0,25 j.
- **Total : 1,75 j — 490 € HT.**

### Lot 13 — Rapport de livraison
- **Complexité** : faible.
- Rédaction 0,75 j.
- **Total : 0,75 j — 210 € HT.**

---

## 2. Tableau récapitulatif (améliorations)

| Lot | Temps (jours) | Coût HT |
| --- | ---: | ---: |
| Carrousel partenaires | 2,5 j | 700 € |
| Refonte OpenAdmin | 3,0 j | 840 € |
| Dashboard analytique | 3,0 j | 840 € |
| Éditeur d'articles + sécurité HTML | 3,5 j | 980 € |
| UX administration | 2,5 j | 700 € |
| Documents unifiés | 0,5 j | 140 € |
| Rôles, permissions, journal | 2,5 j | 700 € |
| Commentaires du code | 1,25 j | 350 € |
| Documentation API | 2,75 j | 770 € |
| Bloc d'information | 1,75 j | 490 € |
| Étude espace licencié | 1,75 j | 490 € |
| Tests et validation | 2,25 j | 630 € |
| Documentation utilisateur/dev | 1,75 j | 490 € |
| Rapport de livraison | 0,75 j | 210 € |
| **Total** | **29,75 j** | **8 330 € HT** |

- **Nombre total de jours : 29,75 j** (≈ 6 semaines à temps plein).
- **Coût total : 8 330 € HT.**
- **Coût moyen par lot fonctionnel** (14 lignes) : **≈ 595 € HT**.

---

## 3. Estimation du futur espace licencié

Cette partie **n'a pas été développée** (étude uniquement, lot 10). L'estimation
ci-dessous distingue clairement les deux versions. Elle suppose la réutilisation
de l'existant (RBAC, sanitisation, cache, file d'attente).

### 3.1 Version minimale

Périmètre : authentification dédiée, espace personnel, consultation des documents
réservés, statut de cotisation renseigné manuellement, gestion des comptes
(invitation, activation, mot de passe oublié, désactivation) et comptes familiaux
de base.

| Bloc | Jours |
| --- | ---: |
| Authentification membre + activation / réinitialisation | 3,0 j |
| Espace personnel (pages React + endpoints) | 3,0 j |
| Documents réservés (niveaux de visibilité, contrôle serveur) | 2,5 j |
| Cotisation renseignée manuellement (table + admin + affichage) | 2,0 j |
| Gestion des comptes + comptes familiaux (foyer) | 2,5 j |
| Tests et sécurité | 2,0 j |
| Documentation | 1,0 j |
| **Total** | **16,0 j** |

- **Coût : 16,0 j × 280 € = 4 480 € HT** (fourchette réaliste **15 à 18 j**, soit
  **4 200 à 5 040 € HT**).
- **Risques techniques** : séparation stricte auth membre / admin ; contrôle
  d'autorisation par membre côté serveur ; parcours d'activation par courriel.
- **Dépendances** : configuration d'un service d'envoi de courriels (activation,
  réinitialisation) ; validation RGPD des données collectées.
- **Reportable en V2** : préférences de communication, historique détaillé.

### 3.2 Version avancée (en supplément de la minimale)

Périmètre additionnel : gestion complète des cotisations, relances par courriel
(individuelle / groupée, modèles, prévisualisation, validation, historique,
anti-doublon, limitation), tableaux de bord et statistiques, préférences de
communication, automatisations contrôlées, alertes, exports.

| Bloc | Jours |
| --- | ---: |
| Système de relances (file, modèles, prévisualisation, validation, historique, anti-doublon) | 5,0 j |
| Gestion complète des cotisations (statuts, historique) | 2,5 j |
| Tableaux de bord et statistiques licenciés | 2,5 j |
| Préférences de communication + consentement | 2,0 j |
| Automatisations contrôlées + alertes | 2,5 j |
| Exports (données / RGPD) | 1,5 j |
| Tests et sécurité renforcés | 2,5 j |
| Documentation | 1,0 j |
| **Total** | **17,0 j** |

- **Coût : 17,0 j × 280 € = 4 760 € HT** (fourchette réaliste **15 à 20 j**, soit
  **4 200 à 5 600 € HT**).
- **Risques techniques** : envoi de courriels en masse (délivrabilité,
  limitation, aucune automatisation sans règle validée) ; conformité RGPD
  (consentement, exports, anonymisation) ; volumétrie des historiques.
- **Dépendances** : prestataire d'envoi de courriels avec contrat de
  sous-traitance ; **validation RGPD par une personne compétente** avant mise en
  service des relances.
- **Reportable en V2** : automatisations avancées, statistiques poussées,
  préférences fines de communication.

---

## 4. Synthèse globale du projet

| Périmètre | Jours | Coût HT |
| --- | ---: | ---: |
| Améliorations demandées (lots 1 à 13) | 29,75 j | **8 330 €** |
| Espace licencié — version minimale | 16,0 j | **4 480 €** |
| Espace licencié — version avancée (supplément) | 17,0 j | **4 760 €** |
| **Total si l'ensemble était réalisé** | **62,75 j** | **17 570 € HT** |

*Tous les montants sont exprimés **HT** (auto-entrepreneur, sans TVA).*

---

## 5. Priorités : meilleur rapport valeur / temps

Classement des travaux par rapport **valeur pour le club / temps de
développement** (du plus rentable au plus optionnel) :

1. **Sécurité du contenu (lot 4)** — indispensable, non négociable : protège le
   site des contenus dangereux pour un coût modéré.
2. **Carrousel partenaires (lot 1)** — impact visible immédiat, valorise les
   partenaires, coût faible.
3. **Dashboard analytique (lot 3)** — aide réelle à la décision des dirigeants à
   partir de données déjà présentes.
4. **Rôles et permissions (lot 6)** — réduit le risque d'erreur en confiant
   l'administration à des bénévoles avec des accès limités.
5. **UX administration + documentation (lots 5, 12)** — autonomie des bénévoles,
   réduit le besoin d'assistance technique.
6. **Documentation API (lot 8)** — maintenabilité et reprise par un autre
   développeur.
7. **Bloc d'information (lot 9)** — utile ponctuellement (fermetures, tournois),
   coût faible.
8. **Espace licencié — version minimale** — forte valeur d'usage, à lancer
   **après** validation RGPD ; commencer par cette version avant la version
   avancée (relances).

La **version avancée** de l'espace licencié offre un bon retour à moyen terme
mais concentre les risques (RGPD, courriels) : à engager une fois la version
minimale éprouvée et le cadre RGPD validé.
