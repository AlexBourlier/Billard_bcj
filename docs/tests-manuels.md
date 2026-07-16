# Checklist de tests manuels

Ce document complète les tests automatisés (`php artisan test`, `npm run lint`,
`npm run build`). Il liste les vérifications à faire **à la main** avant une mise
en production, car elles portent sur le rendu, l'accessibilité et des parcours
qui ne sont pas entièrement couverts par les tests automatisés.

Convention : cocher `[x]` quand la vérification est faite et concluante ; noter la
date et la version testée en bas de page.

---

## 1. Site public

### 1.1 Rendu et responsive
- [ ] Page d'accueil correcte sur **ordinateur**, **tablette**, **téléphone**.
- [ ] Vérifié sur **Chrome** et **Firefox** (au moins).
- [ ] Aucune barre de défilement **horizontale** parasite.
- [ ] Bannière : présente → s'affiche ; absente → pas d'espace vide ni d'erreur.

### 1.2 Partenaires
- [ ] **0 partenaire** : le bloc affiche « Aucun partenaire » (pas d'erreur).
- [ ] **1 à 3 partenaires** : rangée statique centrée (pas de défilement).
- [ ] **4 partenaires ou plus** : carrousel qui défile en boucle sans saut.
- [ ] Logo manquant : le **nom** du partenaire s'affiche à la place.
- [ ] Partenaire **sans lien** : pas de lien cliquable, juste le logo/nom.
- [ ] Partenaire **avec lien** : ouvre dans un nouvel onglet (`rel="noopener"`).
- [ ] **Navigation clavier** : les liens partenaires sont atteignables au `Tab`,
      focus visible ; les logos dupliqués du carrousel ne sont pas focusables.
- [ ] **Mouvement réduit** (`prefers-reduced-motion`) : le carrousel ne défile
      pas automatiquement (liste statique).

### 1.3 Blocs d'information
- [ ] Bloc **info / important / urgent** : couleur et libellé corrects.
- [ ] Un bloc **urgent** est annoncé aux lecteurs d'écran (`role="alert"`).
- [ ] Bloc **hors fenêtre de dates** ou **désactivé** : non affiché.
- [ ] Lien externe → nouvel onglet ; lien interne → même onglet.

### 1.4 Contenu
- [ ] Article mis en avant : titre, image **ou** vidéo, contenu formaté.
- [ ] Message d'accueil riche : mise en forme respectée, **pas de script injecté**.
- [ ] Carte « Nous trouver » : la carte se charge.

---

## 2. Administration (OpenAdmin)

### 2.1 Connexion et tableau de bord
- [ ] Connexion administrateur.
- [ ] Le tableau de bord n'affiche que des **chiffres réels** ; les indicateurs
      non suivis (répartition licenciés, cotisations) sont signalés comme tels.
- [ ] Le compteur « Documents » ouvre **tous** les documents (pas seulement une
      discipline) ; le **filtre par catégorie** fonctionne ; la catégorie apparaît
      en **label** sur chaque ligne.

### 2.2 Articles
- [ ] Créer un article : titre obligatoire, enregistrement OK.
- [ ] Coller depuis **Word** : le contenu est nettoyé (pas de styles parasites).
- [ ] Insérer un lien, puis une **tentative d'injection** (`<script>`) : neutralisée.
- [ ] Éditer un ancien article : contenu préservé.
- [ ] Miniature : upload, remplacement, affichage côté public.

### 2.3 Partenaires
- [ ] Ajouter un partenaire, définir un **ordre**, réordonner.
- [ ] **Désactiver** un partenaire → disparaît du site public.
- [ ] **Date de fin** dépassée → disparaît du site public.
- [ ] Partenaire proche de l'expiration → remonté sur le tableau de bord.

### 2.4 Rôles et permissions
- [ ] Créer un compte avec le rôle **Rédacteur** : accès **articles uniquement**.
- [ ] Ce compte **ne voit pas** et **ne peut pas ouvrir** (URL directe) les
      licenciés, les paramètres, les partenaires.
- [ ] Rôle **Responsable partenaires** : accès partenaires seulement.
- [ ] Le compte **administrateur** conserve l'accès total.

### 2.5 Calendriers et classements
- [ ] Créer / éditer un événement de calendrier pour chaque discipline+portée.
- [ ] Ajouter des liens à un événement.
- [ ] Classements CueScore : créer une entrée par discipline/portée/catégorie,
      la lier à une page discipline, vérifier l'affichage public.

### 2.6 Import de licences (pipeline)
- [ ] Déclencher un import : le lot apparaît dans la liste.
- [ ] Consulter un lot : résumé, avertissements, statut.
- [ ] Revue des correspondances CueScore → licencié.

---

## 3. API publique

- [ ] `GET /api/v1/public/home` : sections attendues (site, menus, partenaires,
      blocs info, article mis en avant).
- [ ] `GET /api/v1/partenaires` : enveloppe standard `data / meta / links / error`.
- [ ] `POST /api/v1/contact` sans données : **422** avec `errors` détaillés.
- [ ] Réponses **vides** (aucune donnée) : structure correcte, pas d'erreur 500.
- [ ] **Pagination** et **filtres** : cohérents avec la documentation.
- [ ] Documentation générée accessible sur `/docs` ; OpenAPI/Postman à jour
      (`php artisan scribe:generate`).

---

## 4. Vérifications techniques (commandes)

À exécuter avant livraison — voir le rapport du lot pour les résultats datés.

| Commande | Attendu |
| --- | --- |
| `php artisan test` | tous les tests au vert |
| `npm run lint` (dossier `frontend`) | aucune erreur |
| `npm run build` (dossier `frontend`) | build réussi |
| `vendor/bin/pint --test` | style conforme |
| `php artisan route:list` | se charge sans exception |
| `php artisan route:cache` | mise en cache réussie |
| `php artisan scribe:generate` | doc + OpenAPI + Postman générés |

---

_Dernière exécution manuelle : ____ / ____ / ______ — version / branche : _______________
