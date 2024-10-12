# Thème WordPress "Nathalie Mota"

Ce thème WordPress est conçu pour les sites à contenu visuel, avec des templates personnalisés pour l'affichage de photos, des filtres, et un système de personnalisation de la section "hero". Ce document fournit une vue d'ensemble de la structure des fichiers du thème.

---

## Table des matières
1. [Dossier principal](#dossier-principal)
2. [Fichiers racine](#fichiers-racine)
3. [Dossiers d'organisation](#dossiers-dorganisation)
4. [Structure complète du thème](#structure-complète-du-thème)

---

## Dossier principal

`nathalie-mota/` : Dossier contenant les fichiers principaux du thème et les sous-dossiers organisés par fonction.

## Fichiers racine

- **404.php** : Template pour la page d'erreur 404.
- **archive.php** : Template pour les pages d'archive.
- **footer.php** : Template pour le pied de page.
- **front-page.php** : Template pour la page d'accueil.
- **functions.php** : Fichier de configuration principale du thème, incluant les appels de scripts, styles et inclusions.
- **header.php** : Template pour l'en-tête.
- **index.php** : Template par défaut, utilisé comme fallback.
- **page.php** : Template pour les pages de contenu individuel.
- **search.php** : Template pour les résultats de recherche.
- **sidebar.php** : Template pour la barre latérale.
- **single.php** : Template pour les articles de blog.
- **single-photo.php** : Template pour les photos individuelles.
- **style.css** : Feuille de style principale du thème.
- **README.md** : Documentation générale du thème.

## Dossiers d'organisation

### assets/

Ce dossier contient tous les fichiers multimédias du thème, classés par type :

- **css/** : Dossier des fichiers CSS pour la mise en forme et l'apparence.
  - **404.css** : Styles pour la page 404.
  - **animations.css** : Animations globales.
  - **contact.css** : Styles pour le formulaire de contact.
  - **filters.css** : Styles pour les filtres de photos.
  - **footer.css** : Styles pour le pied de page.
  - **front-page.css** : Styles pour la page d'accueil.
  - **gallery.css** : Styles pour la galerie de photos.
  - **header.css** : Styles pour l'en-tête.
  - **lightbox.css** : Styles pour la lightbox.
  - **normalize.css** : Réinitialisation des styles.
  - **single-photo.css** : Styles pour l'affichage des photos individuelles.
  - **styles.css** : Styles globaux du thème.
- **js/** : Dossier pour les fichiers JavaScript.
  - **custom.js** : Script personnalisé du thème.
  - **header.js** : Script pour les interactions d'en-tête.
  - **contact.js** : Script pour le formulaire de contact.
  - **lightbox.js** : Script pour la gestion de la lightbox.
  - **filters.js** : Script pour les filtres de photos.
  - **single-photo.js** : Script pour l'affichage des photos individuelles.
- **fonts/** : Dossier pour les fichiers de polices.

### inc/

Contient les fichiers de configuration et les fonctionnalités supplémentaires :

- **ajax-handlers.php** : Gère les requêtes AJAX pour le filtrage des photos.
- **custom-post-types.php** : Déclare les types de contenu personnalisés, notamment les photos.
- **hero-customizer.php** : Gère la personnalisation de la section "hero" via le customizer.

### layouts/

Contient les mises en page spécifiques :

- **content-sidebar.css** : Styles pour une mise en page avec contenu et barre latérale.

### template-parts/

Contient les fichiers de templates partiels utilisés dans d'autres templates :

- **archive-{post-type}.php** : Template pour les archives des types de contenu personnalisés.
- **content-search.php** : Template pour afficher les résultats de recherche.
- **content-single-photo.php** : Template pour l'affichage des photos individuelles.
- **lightbox.php** : Template pour la lightbox.
- **photo-item.php** : Template pour afficher un item de photo dans une boucle.
- **single-{post-type}.php** : Template pour l'affichage d'un type de contenu personnalisé.

---

## Structure complète du thème

```plaintext
nathalie-mota/
│
├── assets/
│   ├── css/
│   │   ├── 404.css
│   │   ├── animations.css
│   │   ├── contact.css
│   │   ├── filters.css
│   │   ├── footer.css
│   │   ├── front-page.css
│   │   ├── gallery.css
│   │   ├── header.css
│   │   ├── lightbox.css
│   │   ├── normalize.css
│   │   ├── single-photo.css
│   │   └── styles.css
│   │
│   └── js/
│       ├── custom.js
│       ├── header.js
│       ├── contact.js
│       ├── lightbox.js
│       ├── filters.js
│       └── single-photo.js
│
├── inc/
│   ├── ajax-handlers.php
│   ├── custom-post-types.php
│   └── hero-customizer.php
│
├── layouts/
│   └── content-sidebar.css
│
├── template-parts/
│   ├── archive-{post-type}.php
│   ├── content-search.php
│   ├── content-single-photo.php
│   ├── lightbox.php
│   ├── photo-item.php
│   └── single-{post-type}.php
│
├── 404.php
├── archive.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── home.php
├── index.php
├── page.php
├── README.md
├── screenshot.png
├── search.php
├── sidebar.php
├── single.php
├── single-photo.php
└── style.css
