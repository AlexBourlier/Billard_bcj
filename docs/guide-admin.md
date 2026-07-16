# Guide de l'administration — BCJ37

Ce guide s'adresse aux **bénévoles du club** qui gèrent le site au quotidien.
Aucune connaissance technique n'est nécessaire. L'administration est accessible à
l'adresse **`/admin`** du site (par exemple `https://www.bcj37.fr/admin`).

> En cas de doute, ne supprimez jamais un élément : préférez le **désactiver**
> (voir plus bas). Une désactivation est réversible, une suppression ne l'est pas.

---

## 1. Se connecter

1. Rendez-vous sur `/admin`.
2. Saisissez votre **identifiant** et votre **mot de passe** (fournis par le
   responsable du site).
3. Cliquez sur **Se connecter**.

Si vous avez oublié votre mot de passe, contactez le responsable du site : lui
seul peut le réinitialiser.

Pensez à vous **déconnecter** (menu en haut à droite) sur un ordinateur partagé.

---

## 2. Lire le tableau de bord

Après connexion, la première page est le **tableau de bord**. Il rassemble les
chiffres utiles du club :

- nombre d'articles, de documents, de partenaires actifs ;
- partenaires **bientôt expirés** (à renouveler) ;
- prochains événements ;
- état des derniers imports de licences.

> Les chiffres affichés proviennent **uniquement de données réelles**. Lorsqu'une
> information n'est pas encore suivie (par exemple la répartition des licenciés),
> elle est indiquée comme telle plutôt qu'estimée. Ce n'est pas une erreur.

Chaque carte est un **raccourci** : cliquez dessus pour aller directement à la
section concernée.

---

## 3. Gérer les articles (actualités)

### Créer un article

1. Menu **Actualités** → **Ajouter**.
2. Renseignez le **titre** (obligatoire).
3. Rédigez le contenu dans l'**éditeur de texte** (voir §3.4).
4. Choisissez la **discipline** concernée (ou « Le Club » pour une actualité
   générale).
5. Ajoutez une **image** si souhaité (voir §6).
6. Enregistrez.

### Enregistrer un brouillon vs publier

- Un **brouillon** est enregistré mais **non visible** sur le site public.
- Un article **publié** apparaît sur le site.
- Vous pouvez repasser un article en brouillon à tout moment.

### Programmer une publication

Si le formulaire propose une **date de publication** future, l'article
n'apparaîtra sur le site qu'à partir de cette date. Utile pour préparer une
annonce à l'avance.

### Utiliser l'éditeur de texte

L'éditeur permet une mise en forme **simple et propre** :

- paragraphes, **titres**, **gras**, *italique* ;
- listes à puces et numérotées ;
- liens ;
- citations.

Bonnes pratiques :

- **Collez depuis Word** sans crainte : la mise en forme parasite est nettoyée
  automatiquement.
- N'essayez pas de forcer des couleurs ou des tailles de police : l'éditeur s'en
  tient volontairement à la charte du site, pour un rendu homogène.
- Utilisez les **titres** pour structurer, pas le gras seul.

### Modifier un article existant

Menu **Actualités** → cliquez sur l'article → modifiez → enregistrez. Les anciens
articles restent lisibles ; vous pouvez les rééditer sans risque.

---

## 4. Gérer les partenaires

### Ajouter un partenaire

1. Menu **Partenaires** → **Ajouter**.
2. Renseignez le **nom**, ajoutez le **logo**.
3. **Lien (URL)** : facultatif. S'il est renseigné, le logo devient cliquable
   sur le site et ouvre le site du partenaire dans un nouvel onglet.
4. **Texte alternatif** : courte description du logo, utile pour l'accessibilité
   et le référencement (ex. « Logo de la boulangerie Durand »).
5. **Dates de début / fin** : facultatives. Elles délimitent la période
   d'affichage du partenaire.
6. Enregistrez.

### Modifier l'ordre d'affichage

Le champ **ordre** détermine la position dans le carrousel de la page d'accueil
(plus le nombre est petit, plus le partenaire apparaît tôt). Modifiez ce nombre
pour réorganiser.

### Désactiver plutôt que supprimer

- Décochez **actif** pour **retirer** un partenaire du site tout en le
  **conservant** dans l'administration.
- Un partenaire dont la **date de fin** est dépassée disparaît automatiquement du
  site, sans être supprimé.
- Ne supprimez un partenaire que si vous êtes certain de ne plus jamais en avoir
  besoin.

---

## 5. Gérer les documents

1. Menu **Documents** → **Ajouter**.
2. Donnez un **titre** clair (c'est ce que verront les visiteurs).
3. Choisissez la **catégorie** (discipline ou club) : elle sert de classement et
   de filtre.
4. Téléversez le fichier (PDF recommandé).
5. Enregistrez.

Dans la liste des documents, un **filtre par catégorie** et un **label** sur
chaque ligne vous aident à retrouver rapidement un document.

---

## 6. Ajouter une image

- Formats conseillés : **JPEG**, **PNG** ou **WebP**.
- Préférez des images **pas trop lourdes** (idéalement moins de 1 Mo) pour ne pas
  ralentir le site.
- Renseignez toujours un **texte alternatif** décrivant l'image.
- Pour les articles, une image au format **paysage** s'affiche mieux.

---

## 7. Blocs d'information (page d'accueil)

Pour signaler une **fermeture exceptionnelle**, un **tournoi** ou une **info
urgente** :

1. Menu correspondant → **Ajouter** un bloc d'information.
2. Renseignez **titre**, **résumé**, **niveau d'importance** (info, important,
   urgent), **lien** facultatif.
3. **Dates de début / fin** : le bloc s'affiche seulement pendant cette période.
4. **Actif** : décochez pour masquer sans supprimer.

---

## 8. Corriger les erreurs fréquentes

| Situation | Que faire |
| --- | --- |
| Un partenaire n'apparaît pas sur le site | Vérifiez qu'il est **actif** et que la **date de fin** n'est pas dépassée. |
| Une image ne s'affiche pas | Vérifiez qu'elle a bien été téléversée ; réessayez avec un fichier plus léger. |
| Un article n'est pas visible | Il est peut-être en **brouillon** ou a une **date de publication** future. |
| Le site ne semble pas à jour | Les changements sont pris en compte immédiatement ; actualisez la page (F5). |
| Message « champ obligatoire » | Un champ requis (souvent le **titre**) est vide : complétez-le. |
| Un lien ne fonctionne pas | Vérifiez qu'il commence par `https://`. |

En cas de blocage, notez le **message d'erreur** affiché et transmettez-le au
responsable du site : il permet un diagnostic rapide.

---

## 9. Ce que vous pouvez faire selon votre rôle

Chaque compte a un **rôle** qui détermine les sections accessibles :

- **Rédacteur** : les actualités.
- **Responsable partenaires** : les partenaires.
- **Responsable documents** : les documents.
- **Administrateur du site** : l'ensemble des contenus.
- **Administrateur principal** : tout, y compris les réglages techniques.

Si une section ne vous est pas accessible, c'est normal : votre rôle ne
l'inclut pas. Rapprochez-vous du responsable du site si besoin.
