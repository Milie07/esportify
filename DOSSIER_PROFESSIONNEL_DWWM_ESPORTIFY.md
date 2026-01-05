# DOSSIER PROFESSIONNEL
# TITRE PROFESSIONNEL DÉVELOPPEUR WEB ET WEB MOBILE

**Projet : Esportify - Plateforme e-sport dédiée aux tournois en ligne**

---

## DÉVELOPPER LA PARTIE FRONT-END D'UNE APPLICATION WEB OU WEB MOBILE SÉCURISÉE

### I. INSTALLER ET CONFIGURER SON ENVIRONNEMENT DE TRAVAIL EN FONCTION DU PROJET WEB OU WEB MOBILE

#### a. Création du dossier

Pour démarrer le projet Esportify j'ai commencé par créer le dossier projet en effectuant ces commandes :

```bash
mkdir esportify
cd esportify
```

Puis j'ai initialisé le dépôt Git local pour assurer le versionnement du code dans le terminal de ma machine :

```bash
git init
```

J'avais au préalable installé Git sur mon système pour pouvoir l'utiliser directement dans mon terminal en le téléchargeant depuis https://www.git-scm.com et une fois installé j'ai pu initialiser le dépôt dans le terminal de mon IDE, VSCode. Lors de la première utilisation de Git il a été nécessaire de configurer mon identité pour que les commits soient correctement associés à mon compte et à mon espace avec les commandes :

```bash
git config --global user.name "mon pseudo utilisateur"
git config --global user.email "mon-email@email.com"
```

#### b. Création du fichier .gitignore, création du repository GitHub et liaison avec le dépôt local

Je me suis ensuite rendue sur https://github.com/new, dans mon espace, pour créer un nouveau repository et ainsi héberger le code source. J'ai choisi de créer le .gitignore et le README.md moi-même directement en local. Le repository m'a largement facilité le travail dans un premier temps car je travaille sur deux environnements différents (mac et Windows) et je pouvais directement passer d'un poste à l'autre en récupérant le travail avec la commande `git pull`. J'ai pu sécuriser le code via les sauvegardes distantes et faciliter le déploiement. Dans un contexte professionnel cela aurait facilité également le travail collaboratif.

J'ai créé mon fichier .gitignore (annexe ? p…) avec seulement, dans un premier temps, ce qui est lié à mon IDE et aux environnements OS :

```
# Éditeur & OS
/.vscode/
.DS_Store
Thumbs.db
```

J'ai créé mon dépôt sur GitHub via l'interface web puis j'ai manuellement, en local, utilisé la commande suivante pour copier le repository dans VS Code :

```bash
git clone https://github.com/Milie07/esportify.git
```

J'ai réalisé ensuite mon premier commit :

```bash
git add .
git commit -m "Initial commit"
git push origin main
```

J'ai ensuite choisi de créer deux branches distinctes : la branche « dev » et la branche « cleanup ».

```bash
git branch dev
git branch cleanup
```

Les deux branches ont été créées pour séparer le développement de la production et des tests ou corrections de bugs.

Je précise que j'ai pris soin d'appliquer les bonnes pratiques de Git tout au long du projet avec des commits clairs et descriptifs (annexe 3 p). Les principales commandes Git que j'ai utilisées sont en annexe (annexe 2 p…).

#### c. Choix et installation de l'environnement de développement local

Au tout début du projet, j'ai choisi d'installer XAMPP (sur Windows) et MAMP (sur mon MacBook) avec PHP 8.2 pour sa rapidité de mise en place avec une installation simple et rapide via le web pour son service « tout-en-un » avec Apache, MySQL et PHP pré-configurés, et son usage via son interface graphique directement. Le démarrage des services se fait, pour Apache, sur le port 80 et pour MySQL sur le port 3306 par défaut. J'ai ensuite déplacé le dossier du projet dans le répertoire htdocs de XAMPP. J'ai par la suite transitionné vers le logiciel Laragon (https://laragon.org/) pour plus de flexibilité toujours avec PHP 8.2. Je l'ai trouvé plus rapide (sur Windows), plus facilement adaptable avec la possibilité de basculer sur de multiples versions PHP, un accès direct à Git, Composer avec son terminal intégré et des extensions que j'ai pu facilement activées (MongoDB entre autres). Comme je l'ai fait pour XAMPP, j'ai activé les extensions PHP via le fichier php.ini :

- `extension=intl` # pour la gestion des fonctionnalités d'internationalisation (formatage de dates, nombres etc…)
- `extension=pdo_mysql` # pilote pour la connexion à la BDD SQL via PDO PHP Data Objects
- `extension=zip` # pour lire, extraire des fichiers zip directement depuis PHP
- `extension=gd` # Bibliothèque pour la manipulation d'images
- `extension=mongodb` # pour la connexion à la BDD NoSQL
- `extension=curl` # permet d'effectuer des requêtes HTTP et HTTPS via des URLs externes (appels API, téléchargements, transferts de données)
- `extension=openssl` # fournit des certificats de sécurité, chiffrement SSL/TLS, hachage etc…

#### d. Choix et Installation des dépendances front

L'installation des dépendances front dont j'avais besoin se compose de :

- **Node.js** téléchargé depuis https://www.nodejs.org qui inclut automatiquement npm (Node Package Manager). Npm est nécessaire pour gérer les dépendances de mon projet Bootstrap et Sass. J'ai initialisé le projet npm avec la commande :

```bash
npm init -y
```

(-y pour accepter toutes les valeurs par défaut) ce qui m'a créé le fichier « package.json » (annexe ?, p…).

- **Le Framework CSS Bootstrap 5.3.8** et **le préprocesseur CSS Sass 1.92.1** pour la gestion du Responsive Design avec une grille flexible et des composants adaptés à tous les écrans et prêts à l'emploi (boutons, modale, navbars etc…). La documentation est riche et facilite le développement. L'utilisation de Sass a permis une personnalisation des variables Bootstrap pour moduler le design avec la charte graphique ainsi qu'une réutilisation et une maintenance facilitée avec les Mixins.

```bash
npm install bootstrap@5.3.8 sass@1.92.1 --save-dev
```

#### e. Choix et installation des dépendances back

L'installation des dépendances back dont j'avais besoin se compose de :

- **PHP 8.2 et PhpMyAdmin** via XAMPP et MAMP puis Laragon. Lorsque j'ai démarré le projet, la version PHP de XAMPP était 8.2. Je l'ai gardée car c'était une version stable et éprouvée au moment du projet et j'ai choisi l'équilibre ainsi que la fiabilité. Cette version bénéficiait d'un support actif jusqu'en 2024 et des correctifs de sécurité jusqu'en décembre 2026 ce qui est suffisant pour le développement et sa mise en production initiale. Une mise à jour vers PHP 8.4 est prévue.

- **La dernière version stable de Composer 2.8.12** (composer.phar – SHA 256) compatible avec PHP 8.2 téléchargé depuis internet (https://getcomposer.org/download/)

- **Le framework Symfony 5.4** version LTS (Long Term Support) avec la commande suivante :

```bash
composer create-project symfony/skeleton:"5.4.*" .
# le point sert pour spécifier le dossier actuel
```

Cela m'a créé la structure du projet Symfony ainsi que les fichiers composer.json, composer.lock, symfony.lock (le fichier composer.json est en annexe ? p…).

J'ai choisi de créer une structure minimum avec symfony/Skeleton puis de rajouter les dépendances au fur et à mesure avec la commande `composer require`.

J'ai entre autre, installé les bundles supplémentaires suivants :

```bash
composer require symfony/orm-pack
# Pack complet Doctrine ORM qui installe tous les composants nécessaires pour mapper
# les objets PHP vers la base de données et gérer les entités.

composer require symfony/form
# Crée, affiche et valide des formulaires HTML de manière orientée objet
# avec gestion automatique des erreurs

composer require symfony/security-bundle
# qui gère l'authentification des utilisateurs, les autorisations d'accès
# et toute la sécurité de l'application.

composer require symfony/validator
# qui valide automatiquement les données (entités, formulaires, variables)

composer require symfony/webpack-encore-bundle
# qui gère la compilation et l'optimisation des assets en JS/CSS avec Webpack

composer require mongodb/mongodb
# Pour connecter l'application à la BDD MongoDB NoSQL.
```

et des outils de développement avec notamment :

```bash
composer require --dev symfony/maker-bundle
# Pour générer automatiquement du code (entités, controllers, form etc.)
# via des commandes pour accélérer le développement

composer require --dev phpunit/phpunit
# Framework de tests unitaires et fonctionnels pour tester le code

composer require --dev doctrine/doctrine-fixtures-bundle
# Pour charger automatiquement les données de tests (fixtures) dans la BDD

composer require --dev phpstan/phpstan
# qui est un analyseur statique qui détecte les bugs, les erreurs de typage
# et les problèmes potentiels sans exécuter le code.
```

Toutes les dépendances installées n'ont pas vocation à servir au début mais j'ai souhaité avoir tout le projet en place pour ne me soucier que du code après. J'ai eu quelques soucis de versions et de chemins notamment, surtout que j'ai switché sur environnements régulièrement, et pour pallier à ces problèmes j'ai décidé de conteneuriser l'application pour ne plus avoir affaire à ces contretemps.

#### f. Conteneurisation avec Docker

Les deux Dockerfile complets (annexe 3 et annexe 4), les fichiers docker-compose.yml et docker-compose.override.yml (annexe 5 et 6), et les principales commandes (annexe 7) sont en p… … et …

J'ai téléchargé et installé Docker Desktop directement du web via l'adresse https://www.docker.com. Le package est complet et comprend l'interface graphique Docker Desktop, Docker CLI, Docker compose et Docker Engine. Les commandes docker et docker-compose sont de fait immédiatement utilisables dans le terminal.

La première motivation d'utiliser Docker s'est justifiée par la volonté de retrouver la même configuration sur tous mes postes de développement. Cela m'a permis aussi :
- de déployer facilement en production.
- d'orchestrer ensemble les Bases de données Relationnelles et non Relationnelles (SQL et NoSQL).
- d'y inclure les outils d'administration PHPMyAdmin et MongoExpress.

Dans un premier temps je me suis concentrée sur la partie Front-End. La deuxième partie concernant le rajout des deux conteneurs pour MongoDB ainsi que le choix d'utilisation de deux Dockerfile et de deux fichiers docker-compose est abordée dans la partie Back-End p…

Cette version initiale comprenant 3 conteneurs principaux :

- **Le conteneur web (esportify_web)** basé sur l'image officielle `php:8.2-apache`. J'ai choisi cette image car elle combine PHP 8.2 avec le serveur web Apache, évitant ainsi de configurer deux services séparés. L'image de base a été personnalisée en y ajoutant toutes les extensions installées en local pour y retrouver l'environnement créé à la base. L'application est accessible via le port 8080.

- **Le conteneur MySQL (esportify_db)** basé sur l'image officielle `mysql:8.0` qui est une version stable et largement utilisée de MySQL. Cette version est donnée comme offrant de bonnes performances, une compatibilité avec Doctrine ORM et un support à long terme.

- **Le conteneur PhpMyAdmin (esportify_phpmyadmin)** basé sur l'image officielle `phpmyadmin:latest`. Cet outil d'administration permet de gérer MySQL via une interface graphique. Accessible sur le port 8081 et se connecte automatiquement à la base de données MySQL.

Toutes les images utilisées dans le projet sont des images officielles provenant du Docker Hub. Cela garantit la **sécurité** car les images officielles sont régulièrement mises à jour, la **fiabilité** parce qu'utilisées par des millions de personnes et la **maintenance** pour le support actif et la sécurité encore une fois.

En ligne de commande, j'ai principalement utilisé (les autres lignes de commandes se trouvent en annexe 7 p…) :

```bash
docker-compose up --build  # Pour construire et démarrer les conteneurs
docker-compose down        # Pour arrêter les conteneurs
docker-compose exec esportify_web bash  # Pour exécuter des commandes dans le conteneur
docker-compose ps -a       # Pour lister les conteneurs (actifs et arrêtés)
```

---

### II. MAQUETTER DES INTERFACES UTILISATEUR WEB ET WEB MOBILE

Pour la conception des interfaces de l'application, j'ai opté pour une approche méthodique en plusieurs étapes, utilisant des outils adaptés à chaque phase du processus.

J'ai d'abord conçu la charte graphique (annexe 8 p…) en commençant par le logo d'Esportify. J'ai choisi **Canva** pour sa création en raison de sa simplicité d'utilisation et de sa bibliothèque de templates, permettant une conception graphique rapide et professionnelle sans nécessiter de compétences avancées en design. Je l'ai utilisé aussi pour le choix des couleurs et ainsi réalisé la palette de couleurs.

**Figma** s'est ensuite imposé comme l'outil principal pour la réalisation des wireframes et mockups (annexe 9, 10, 11 et 12 p…). Ce choix se justifie par plusieurs avantages déterminants :
- Sa nature collaborative et cloud, permettant un accès et des modifications depuis n'importe quel appareil
- Ses fonctionnalités de prototypage interactif, idéales pour visualiser les parcours utilisateurs
- Son système de composants réutilisables, garantissant la cohérence graphique à travers l'ensemble des interfaces
- Sa compatibilité native avec les formats web et mobile, facilitant les exports

Toutes les images ont été prises sur **Freepik** sous licence gratuite, garantissant ainsi la conformité légale des ressources utilisées tout en maintenant une qualité visuelle professionnelle.

Quant aux icônes et polices, j'ai fait appel à **Google Fonts** et **Font Awesome**, toujours en licence gratuite pour respecter les droits d'auteur et éviter tout problème de propriété intellectuelle.

#### Livrables

La démarche de conception a produit les éléments suivants, disponibles en annexe :
- Une charte graphique complète définissant l'identité visuelle du projet
- Des wireframes illustrant la structure et l'organisation des contenus
- Des mockups haute-fidélité présentant le rendu final des interfaces

---

### III. RÉALISATION DES INTERFACES UTILISATEUR STATIQUES WEB ET WEB MOBILE

#### a. Utilisation de HTML5

La structure sémantique HTML5 a été privilégiée pour l'ensemble des templates Twig. J'ai utilisé le moteur de templates **Twig** intégré à Symfony qui offre une sécurité native contre les failles XSS grâce à l'échappement automatique des variables avec la syntaxe `{{ variable }}`.

Le template de base [base.html.twig](templates/base.html.twig) structure l'ensemble de l'application avec :

```html
<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Esportify : Une plateforme e-sport...">
    <!-- Sécurité : Favicon avec formats multiples pour compatibilité -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('build/images/icones/favicon.svg') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('build/images/icones/favicon32.png') }}" />
</head>
```

Les balises **meta viewport** garantissent le responsive design. La balise **meta description** optimise le référencement SEO. L'attribut `lang="fr-FR"` permet l'accessibilité pour les lecteurs d'écran.

Pour la navigation, j'ai utilisé les balises sémantiques appropriées :

```html
<header class="navbar navbar-expand-lg">
    <nav class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="{{ path('app_home') }}" class="nav-link">Accueil</a>
            </li>
        </ul>
    </nav>
</header>
```

Les fonctions Twig `{{ path('route_name') }}` génèrent automatiquement les URLs en fonction du routing Symfony, évitant les chemins codés en dur et facilitant la maintenance.

La structure des pages de contenu utilise systématiquement les balises `<main>` pour le contenu principal et `<footer>` pour le pied de page, respectant ainsi la sémantique HTML5 et améliorant l'accessibilité.

Pour le formulaire de connexion [signin.html.twig](templates/auth/signin.html.twig:24-25), j'ai implémenté la **protection CSRF** (Cross-Site Request Forgery) :

```html
<form action="{{ path('app_login') }}" method="post">
    <input type="hidden" name="_csrf_token" value="{{ csrf_token('authenticate') }}">
    <!-- Champs du formulaire -->
</form>
```

Cette protection empêche les attaques par requêtes falsifiées en générant un token unique pour chaque session.

Les attributs HTML5 de validation sont systématiquement utilisés pour une première couche de sécurité côté client :

```html
<input class="form-control input_filter"
       minlength="2"
       maxlength="100"
       placeholder="Votre pseudo"
       type="text"
       required
       autocomplete="username"/>
```

Les attributs `minlength`, `maxlength` et `required` fournissent une validation basique côté navigateur, tandis que `autocomplete` améliore l'expérience utilisateur tout en respectant les bonnes pratiques de sécurité.

#### b. Stylisation avec Bootstrap et Sass et CSS

Pour la stylisation de l'application, j'ai opté pour une approche modulaire combinant **Bootstrap 5.3.8**, **Sass** et du CSS personnalisé.

La configuration Sass [app.scss](assets/styles/app.scss:1-8) importe Bootstrap et surcharge ses variables :

```scss
@use "sass:map";
@import "~bootstrap/scss/functions";
@import "palette";
@import "~bootstrap/scss/variables";
// Merge de la palette perso avec les couleurs du theme de base
$theme-colors: map.merge($theme-colors, $custom-theme-colors);
@import "~bootstrap/scss/bootstrap";
@import "custom";
```

J'ai créé un fichier [_palette.scss](assets/styles/_palette.scss) contenant les couleurs de la charte graphique pour centraliser et faciliter la maintenance :

```scss
$darkBlue: #043644;
$lightBlue: #064663;
$greenFluo: #04D9B2;
$beige: #ECB365;
$clair1: #F2F2F2;
$clair2: #E8E8E8;
```

Ces couleurs sont ensuite utilisées de manière cohérente dans toute l'application.

Le fichier [_custom.scss](assets/styles/_custom.scss:57-83) contient les **mixins Sass** réutilisables pour les boutons :

```scss
@mixin bouton($size, $txt-color: $lightBlue) {
    @if $size == xl {
        width: 210px;
    } @else if $size == lg {
        width: 130px;
    }
    border: none;
    border-radius: 50px;
    font-family: $header-font-family;
    font-weight: 500;
    color: $txt-color;
}

@mixin btn-hover($hover-color: $lightBlue, $shadow-color: $beige) {
    transition: all 0.3s ease-in-out;
    &:hover {
        color: $hover-color !important;
        box-shadow: 1px 1px 10px $shadow-color;
    }
}
```

Ces mixins permettent de créer des boutons cohérents avec des tailles standardisées et des effets de hover uniformes :

```scss
.btn-custom-lg-green {
    @include bouton(xl);
    @include btn-hover($lightBlue, $greenFluo);
}
```

Pour le **responsive design**, j'ai utilisé les media queries Sass :

```scss
h1 {
    font-size: 3rem; //48px

    @media (max-width: 568px) {
        font-size: 32px;
    }
}
```

La grille Bootstrap est utilisée pour la mise en page responsive avec les classes `container-fluid`, `row`, et `col-*` :

```html
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
            <!-- Contenu -->
        </div>
    </div>
</div>
```

Pour les formulaires, j'ai créé une classe personnalisée `input_filter` [_custom.scss](assets/styles/_custom.scss:86-112) qui assure une expérience utilisateur cohérente et gère l'autocomplétion du navigateur :

```scss
.input_filter {
    background-color: white;
    border: 3px solid $beige;
    border-radius: 10px;
    color: $lightBlue !important;

    &:active, &:focus {
        border: 3px solid $greenFluo;
        box-shadow: 1px 1px 10px $greenFluo;
    }

    &:-webkit-autofill {
        // pour outrepasser le style par défaut
        box-shadow: 0 0 0 1000px white inset !important;
        -webkit-box-shadow: 0 0 0 1000px white inset !important;
        -webkit-text-fill-color: $lightBlue !important;
    }
}
```

Cette classe gère également le comportement de l'autocomplétion Chrome qui impose normalement un fond bleu, ici outrepassé pour respecter la charte graphique.

Le système de navigation utilise Bootstrap pour le menu burger responsive avec des **transitions CSS** pour améliorer l'expérience utilisateur :

```scss
.nav-link {
    color: $beige !important;
    transition: all 0.3s ease-in-out;

    &:hover {
        color: $greenFluo !important;
        text-decoration: underline;
        text-underline-offset: 5px;
    }
}
```

La compilation Sass vers CSS est gérée par **Webpack Encore** qui minifie et optimise les fichiers pour la production, réduisant ainsi le temps de chargement et améliorant les performances.

---

### IV. RÉALISATION DES INTERFACES UTILISATEUR DYNAMIQUES WEB ET WEB MOBILE

#### a. Sécurité des formulaires du côté du navigateur

La sécurité des formulaires constitue une priorité fondamentale. J'ai mis en place une validation JavaScript [script.js](assets/js/script.js:1-52) comme première couche de défense côté client :

```javascript
/* VALIDATION DES FORMULAIRES
    Première couche de sécurité front
    sf Entité HTML MDN
*/

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("form.needs-validation").forEach((form) => {
        form.addEventListener("submit", (e) => {
            let isFormValid = true;
            const inputs = form.querySelectorAll(
                'input[type="text"], input[type="email"], input[type="password"]'
            );

            inputs.forEach((input) => {
                if (input.type === "email") {
                    if (!isValidEmail(input.value.trim())) {
                        input.classList.add("is-invalid");
                        isFormValid = false;
                    }
                }
            });

            if (!isFormValid) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
});
```

Cette validation vérifie :
- Le format des emails avec une regex
- La longueur minimale et maximale des champs
- La correspondance des mots de passe
- Les champs obligatoires

La fonction `e.preventDefault()` empêche la soumission du formulaire si des erreurs sont détectées, évitant ainsi des requêtes inutiles au serveur et améliorant l'expérience utilisateur avec un retour immédiat.

Pour les mots de passe, j'ai implémenté une validation stricte :

```javascript
function validatePassword(pwdInput) {
    const pwd = pwdInput.value.trim();
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

    if (!regex.test(pwd)) {
        pwdInput.classList.add("is-invalid");
        return false;
    }
    return true;
}
```

Cette regex impose :
- Au moins 8 caractères
- Au moins une majuscule
- Au moins une minuscule
- Au moins un chiffre

Ces contraintes correspondent exactement aux validations côté serveur, assurant la cohérence du système de validation en double couche.

#### b. Dynamisation avec Bootstrap

Les composants Bootstrap JavaScript sont utilisés pour enrichir l'interface :

```javascript
// Activation des popovers de Bootstrap
import * as bootstrap from "bootstrap";

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach((element) => {
        new bootstrap.Popover(element);
    });
});
```

Le **menu burger** utilise les composants collapse de Bootstrap pour le responsive :

```html
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
    <i class="fa-solid fa-bars"></i>
</button>
<nav class="collapse navbar-collapse" id="navbarNav">
    <!-- Navigation -->
</nav>
```

Les **modales Bootstrap** [script.js](assets/js/script.js:200-297) affichent les détails des événements de manière dynamique :

```javascript
const modalEl = document.getElementById("modalEvent");
let bsModal = null;
if (modalEl && window.bootstrap?.Modal) {
    bsModal = new bootstrap.Modal(modalEl, {backdrop: "static"});
}

function fillModal(ev) {
    elsModal.title.textContent = ev.title || "Évènement";
    elsModal.desc.textContent = ev.description || "";
    elsModal.img.src = ev.imgUrl || "/uploads/tournaments/default-event.jpg";
}

listEvent?.addEventListener("click", (e) => {
    const btn = e.target.closest(".btn-details");
    if (!btn) return;

    const id = btn.dataset.eventId;
    const ev = getEventById(id);
    if (!ev) return;

    fillModal(ev);
    bsModal?.show();
});
```

Cette approche utilise la **délégation d'événements** pour optimiser les performances en attachant un seul écouteur sur le conteneur parent plutôt qu'un écouteur par carte d'événement.

Le dropdown du menu utilisateur utilise Bootstrap pour gérer l'affichage conditionnel basé sur les rôles :

```twig
<ul class="dropdown-menu">
    {% if is_granted('ROLE_ADMIN') %}
        <li><a class="dropdown-item" href="{{ path('admin_dashboard') }}">Dashboard admin</a></li>
    {% elseif is_granted('ROLE_ORGANIZER') %}
        <li><a class="dropdown-item" href="{{ path('organizer_space') }}">Espace organisateur</a></li>
    {% elseif is_granted('ROLE_PLAYER') %}
        <li><a class="dropdown-item" href="{{ path('player_space') }}">Espace joueur</a></li>
    {% endif %}
</ul>
```

#### c. Rendu dynamique avec JavaScript

Le système de **filtrage asynchrone** [script.js](assets/js/script.js:54-197) permet de filtrer les événements sans recharger la page :

```javascript
const ENDPOINT = "/api/events";
let events = [];

async function initEvents() {
    try {
        const res = await fetch(ENDPOINT, {cache: "no-store"});
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();

        events = Array.isArray(data) ? data : [];
        applyFilters();
    } catch (err) {
        console.error("Erreur de chargement des événements :", err);
    }
}

function applyFilters() {
    const org = organizerSelect?.value || "all";
    const minTs = toMs(dateAt?.value);
    const minP = playersCount?.value ? parseInt(playersCount.value, 10) : null;

    const out = events
        .filter((ev) => (ev.status || "").toLowerCase() === "valide")
        .filter((ev) => org === "all" || ev.organizer === org)
        .filter((ev) => {
            if (minTs === null) return true;
            const start = new Date(ev.startsAt).getTime();
            return start >= minTs;
        })
        .filter((ev) => minP === null || ev.playersRegistered >= minP);

    render(out);
}
```

Cette approche utilise :
- **Fetch API** pour récupérer les données de manière asynchrone
- **Array.filter()** pour filtrer les événements selon plusieurs critères
- **Cache "no-store"** pour s'assurer d'avoir toujours les données à jour
- **Gestion des erreurs** avec try/catch pour éviter les plantages

Le rendu des cartes utilise **DocumentFragment** [script.js](assets/js/script.js:87-121) pour optimiser les performances DOM :

```javascript
function render(list) {
    listEvent.innerHTML = "";
    if (!list || list.length === 0) {
        if (stateEmpty) stateEmpty.hidden = false;
        return;
    }

    const frag = document.createDocumentFragment();
    list.forEach((event) => {
        const node = template.content.cloneNode(true);
        const img = node.querySelector(".event-img");
        const title = node.querySelector(".event-title");

        if (img) {
            img.src = event.imgUrl || "/build/images/jpg/placeholder.jpg";
            img.alt = `Illustration de ${event.title}`;
        }
        if (title) title.textContent = event.title;

        frag.appendChild(node);
    });
    listEvent.appendChild(frag);
}
```

L'utilisation de **DocumentFragment** minimise les reflows du DOM en effectuant toutes les modifications en mémoire avant un seul ajout au DOM, améliorant considérablement les performances pour de grandes listes.

Le système de **gestion des cookies** [script.js](assets/js/script.js:318-338) respecte le RGPD :

```javascript
document.addEventListener("DOMContentLoaded", () => {
    const acceptBtn = document.getElementById("cookie-accept");
    const declineBtn = document.getElementById("cookie-decline");

    function setConsent(value) {
        document.cookie = "esportify_consent=" + value +
                         "; path=/; max-age=" + 180 * 24 * 60 * 60;
        const banner = document.getElementById("cookie-banner");
        if (banner) banner.style.display = "none";
    }

    if (acceptBtn) {
        acceptBtn.addEventListener("click", () => setConsent("accepted"));
    }
    if (declineBtn) {
        declineBtn.addEventListener("click", () => setConsent("declined"));
    }
});
```

Le bandeau de cookies apparaît uniquement si l'utilisateur n'a pas encore donné son consentement :

```twig
{% if app.request.cookies.get('esportify_consent') is null %}
    <div id="cookie-banner" class="cookie-banner">
        <!-- Contenu du bandeau -->
    </div>
{% endif %}
```

Cette implémentation garantit la conformité RGPD en :
- Demandant explicitement le consentement
- Permettant de refuser les cookies
- Stockant le choix pour 180 jours
- N'affichant le bandeau que si nécessaire

---

## DÉVELOPPER LA PARTIE BACK-END D'UNE APPLICATION WEB OU WEB MOBILE SÉCURISÉE

### I. METTRE EN PLACE UNE BASE DE DONNÉES RELATIONNELLE

#### a. 2ème conteneurisation – évolution du Dockerfile et du docker-compose.yml

Lors de l'évolution du projet, j'ai enrichi la conteneurisation pour supporter les deux bases de données (SQL et NoSQL) ainsi que leurs outils d'administration.

Le [Dockerfile.dev](Dockerfile.dev:1-73) a été optimisé pour inclure toutes les extensions nécessaires :

```dockerfile
FROM php:8.2-apache

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libicu-dev \
  libzip-dev \
  openssl \
  libssl-dev \
  cron \
  libpng-dev \
  libjpeg-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install intl pdo pdo_mysql zip gd

# Installer l'extension MongoDB avec support SSL
RUN pecl install mongodb \
  && docker-php-ext-enable mongodb

# Activer le mod_rewrite (nécessaire pour Symfony)
RUN a2enmod rewrite
```

La configuration du **timezone** [Dockerfile.dev](Dockerfile.dev:21-24) garantit la cohérence des dates :

```dockerfile
RUN echo "date.timezone=Europe/Paris" > /usr/local/etc/php/conf.d/timezone.ini \
    && ln -snf /usr/share/zoneinfo/Europe/Paris /etc/localtime \
    && echo "Europe/Paris" > /etc/timezone
```

Cela évite les décalages horaires dans les logs et les timestamps de la base de données.

Le **docker-compose.yml** [docker-compose.yml](docker-compose.yml:1-92) final orchestre 5 conteneurs :

```yaml
services:
  web:
    build:
      context: .
      dockerfile: Dockerfile.dev
    ports:
      - "8080:8080"
    depends_on:
      db:
        condition: service_healthy
      mongo:
        condition: service_started
    environment:
      DATABASE_URL: "mysql://esportify_user:esportify_pass@db:3306/esportify?serverVersion=8.0"
      MONGODB_URL: "mongodb://root:rootpass@mongo:27017"
      CRON_SECRET_TOKEN: "dev_secret_token_change_in_production"

  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: esportify
      MYSQL_USER: esportify_user
      MYSQL_PASSWORD: esportify_pass
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 5s
      retries: 10

  mongo:
    image: mongo:7
    environment:
      MONGO_INITDB_ROOT_USERNAME: root
      MONGO_INITDB_ROOT_PASSWORD: rootpass
    volumes:
      - esportify_mongo_data:/data/db
```

Les **healthchecks** [docker-compose.yml](docker-compose.yml:43-48) garantissent que MySQL est prêt avant le démarrage de l'application :

```yaml
healthcheck:
  test: ["CMD", "mysqladmin", "ping", "-h", "localhost", "-u", "root", "-proot"]
  interval: 5s
  timeout: 3s
  retries: 10
```

Cela évite les erreurs de connexion au démarrage lorsque l'application tente de se connecter à une base de données pas encore prête.

Le fichier [docker-compose.override.yml](docker-compose.override.yml:1-17) sert pour le développement local :

```yaml
services:
  web:
    command: >
      bash -c "
        composer install --no-interaction &&
        php bin/console doctrine:database:create --if-not-exists &&
        php bin/console doctrine:migrations:migrate --no-interaction ||
        true &&
        php bin/console cache:clear &&
        apache2-foreground
        "
    environment:
      APP_ENV: dev
```

Cette configuration exécute automatiquement :
1. L'installation des dépendances Composer
2. La création de la base de données si elle n'existe pas
3. L'exécution des migrations
4. Le vidage du cache
5. Le démarrage d'Apache

La séparation dev/prod via deux fichiers docker-compose permet de gérer différemment les environnements sans dupliquer la configuration.

#### b. Création de la BDD et des Tables

##### i. Via Doctrine et Composer

Doctrine ORM a été configuré via le fichier `.env` :

```bash
DATABASE_URL="mysql://esportify_user:esportify_pass@db:3306/esportify?serverVersion=8.0&charset=utf8mb4"
```

L'entité [Member.php](src/Entity/Member.php:15-22) utilise les **annotations Doctrine** pour mapper la classe PHP vers la table SQL :

```php
#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[UniqueEntity(fields: ['pseudo'], message: 'Ce pseudo est déjà utilisé.')]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')]
#[ORM\Table(name: 'member')]
#[ORM\UniqueConstraint(name: "uq_member_pseudo", columns: ["pseudo"])]
#[ORM\UniqueConstraint(name: "uq_member_email", columns: ["email"])]
class Member implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'member_id', type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id = null;
```

Les **contraintes de validation** [Member.php](src/Entity/Member.php:29-40) assurent l'intégrité des données :

```php
#[ORM\Column(name: 'first_name', type: Types::STRING, length: 100)]
#[Assert\NotBlank(message: "Le nom est obligatoire.")]
#[Assert\Length(
  min: 2,
  max: 100,
  message: "Le nom doit contenir entre {{ min }} et {{ max }} caractères."
)]
#[Assert\Regex(
  pattern: '/^[a-zA-ZÀ-ÿ\-]+$/u',
  message: "Le nom ne peut contenir que des lettres, les accents et les tirets."
)]
private ?string $firstName = null;
```

Ces validations constituent la **deuxième couche de sécurité** côté serveur, complémentaire à la validation JavaScript côté client.

Pour le mot de passe [Member.php](src/Entity/Member.php:73-75), le hash est stocké et non le mot de passe en clair :

```php
#[ORM\Column(name: 'password_hash', type: Types::STRING, length: 255)]
#[Assert\NotBlank(groups: ['registration'], message: "Le mot de passe est obligatoire.")]
private ?string $passwordHash = null;
```

Le système de **rôles hiérarchiques** [Member.php](src/Entity/Member.php:82-98) utilise les relations Doctrine :

```php
#[ORM\ManyToOne(targetEntity: MemberAvatars::class)]
#[ORM\JoinColumn(name: "member_avatar_id", referencedColumnName: "member_avatar_id", nullable: true, onDelete: "SET NULL")]
private ?MemberAvatars $memberAvatar = null;

#[ORM\ManyToOne(targetEntity: MemberRoles::class)]
#[ORM\JoinColumn(name: "member_role_id", referencedColumnName: "member_role_id", nullable: false)]
#[Assert\NotNull(message: "Le rôle de l'utilisateur est obligatoire.")]
private ?MemberRoles $memberRole = null;
```

La méthode `getRoles()` [Member.php](src/Entity/Member.php:147-168) convertit les rôles de la base de données en rôles Symfony :

```php
public function getRoles(): array
{
    $roles = ['ROLE_USER'];

    if ($this->memberRole instanceof MemberRoles) {
        $code = $this->memberRole->getCode();
        if ($code) {
            $code = strtoupper($code);
            if (!str_starts_with($code, 'ROLE_')) {
                $code = 'ROLE_' . $code;
            }
            $roles[] = $code;
        }
    }
    return array_values(array_unique($roles));
}
```

Cette méthode garantit que chaque utilisateur a au minimum le rôle `ROLE_USER` et ajoute son rôle spécifique (ADMIN, ORGANIZER, PLAYER).

##### ii. Migrations

Les migrations Doctrine permettent de versionner le schéma de base de données. La première migration crée les tables :

```bash
php bin/console make:migration
```

Cette commande génère automatiquement un fichier de migration en comparant les entités PHP avec l'état actuel de la base de données.

Pour exécuter les migrations :

```bash
php bin/console doctrine:migrations:migrate
```

Les fichiers de migration dans le dossier `migrations/` contiennent les requêtes SQL pour créer les tables avec leurs contraintes d'intégrité :

```php
$this->addSql('CREATE TABLE member (
    member_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    pseudo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    UNIQUE INDEX uq_member_pseudo (pseudo),
    UNIQUE INDEX uq_member_email (email),
    PRIMARY KEY(member_id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
```

Les **contraintes UNIQUE** garantissent qu'un pseudo ou un email ne peut être utilisé qu'une seule fois, renforçant la sécurité et l'intégrité des données.

#### c. Installation et création de la BDD non relationnelle

##### i. Choix et Initialisation

J'ai choisi **MongoDB 7** comme base de données NoSQL pour stocker l'historique des tournois. MongoDB offre :
- Une flexibilité dans la structure des documents
- Des performances élevées pour les écritures massives
- Une scalabilité horizontale naturelle
- Un format JSON natif facilitant l'intégration avec JavaScript

L'ajout des conteneurs MongoDB et Mongo Express dans [docker-compose.yml](docker-compose.yml:62-87) :

```yaml
mongo:
  image: mongo:7
  container_name: esportify_mongo
  ports:
    - "27017:27017"
  environment:
    MONGO_INITDB_ROOT_USERNAME: root
    MONGO_INITDB_ROOT_PASSWORD: rootpass
  volumes:
    - esportify_mongo_data:/data/db

mongo_express:
  image: mongo-express:1
  ports:
    - "8082:8081"
  environment:
    ME_CONFIG_MONGODB_ADMINUSERNAME: root
    ME_CONFIG_MONGODB_ADMINPASSWORD: rootpass
    ME_CONFIG_BASICAUTH_USERNAME: admin
    ME_CONFIG_BASICAUTH_PASSWORD: pass
  depends_on:
    - mongo
```

Mongo Express fournit une interface web d'administration accessible sur le port 8082, protégée par une **authentification basique** pour éviter les accès non autorisés.

##### ii. Intégration MongoDB

L'installation du driver MongoDB PHP :

```bash
composer require mongodb/mongodb
```

Le service [MongoDBService.php](src/Service/MongoDBService.php:8-22) encapsule la connexion :

```php
namespace App\Service;

use MongoDB\Client;
use MongoDB\Collection;

class MongoDBService
{
    private Client $client;
    private string $database = 'esportify';

    public function __construct(string $mongodbUrl)
    {
        $this->client = new Client($mongodbUrl);
    }

    public function getCollection(string $collectionName): Collection
    {
        return $this->client->{$this->database}->selectCollection($collectionName);
    }
}
```

Cette approche utilise l'**injection de dépendances** Symfony. La configuration dans `services.yaml` :

```yaml
App\Service\MongoDBService:
    arguments:
        $mongodbUrl: '%env(MONGODB_URL)%'
```

L'URL MongoDB est stockée dans les variables d'environnement [docker-compose.yml](docker-compose.yml:19) :

```yaml
MONGODB_URL: "mongodb://root:rootpass@mongo:27017"
```

Cette externalisation des credentials évite de les coder en dur et facilite le changement de configuration entre environnements.

L'utilisation du service dans un controller :

```php
public function __construct(private MongoDBService $mongoService) {}

public function saveHistory(): Response
{
    $collection = $this->mongoService->getCollection('tournament_history');
    $collection->insertOne([
        'tournament_id' => $tournamentId,
        'status' => 'completed',
        'timestamp' => new \MongoDB\BSON\UTCDateTime()
    ]);
}
```

#### d. Choix de la sécurité des bases

Pour **MySQL**, plusieurs mesures de sécurité ont été mises en place :

1. **Utilisateur dédié** avec privilèges limités [docker-compose.yml](docker-compose.yml:34-38) :
```yaml
MYSQL_USER: esportify_user
MYSQL_PASSWORD: esportify_pass
```
L'application n'utilise pas le compte root, limitant les risques en cas de compromission.

2. **Isolation réseau** : Les bases de données sont dans le même réseau Docker que l'application mais ne sont pas exposées publiquement (sauf pour le développement local).

3. **Volumes persistants** [docker-compose.yml](docker-compose.yml:89-91) :
```yaml
volumes:
  esportify_db_data:
  esportify_mongo_data:
```
Les données survivent aux redémarrages des conteneurs.

4. **Paramètres préparés avec Doctrine** : Doctrine utilise systématiquement les requêtes préparées, empêchant les injections SQL.

Pour **MongoDB**, la sécurité repose sur :

1. **Authentification obligatoire** [docker-compose.yml](docker-compose.yml:68-70) :
```yaml
MONGO_INITDB_ROOT_USERNAME: root
MONGO_INITDB_ROOT_PASSWORD: rootpass
```

2. **Mongo Express protégé** par authentification HTTP basique [docker-compose.yml](docker-compose.yml:84-85) :
```yaml
ME_CONFIG_BASICAUTH_USERNAME: admin
ME_CONFIG_BASICAUTH_PASSWORD: pass
```

3. **Connexion chiffrée** : Le driver MongoDB PHP supporte SSL/TLS configuré dans le Dockerfile avec `libssl-dev`.

En production, des mesures supplémentaires seraient appliquées :
- Changement des mots de passe par défaut
- Utilisation de secrets Docker ou variables d'environnement chiffrées
- Limitation des connexions par IP avec des firewalls
- Chiffrement des volumes
- Backups automatiques réguliers

---

### II. DÉVELOPPER DES COMPOSANTS D'ACCÈS AUX DONNÉES SQL ET NOSQL

#### a. CRUD (Create, Read, Update et Delete)

Les opérations CRUD sont gérées par les **Repositories** Doctrine. Le [MemberRepository.php](src/Repository/MemberRepository.php:17-23) hérite de `ServiceEntityRepository` :

```php
namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }
}
```

Cette classe fournit automatiquement les méthodes :
- `find($id)` : Récupère une entité par son ID
- `findAll()` : Récupère toutes les entités
- `findOneBy(['pseudo' => 'value'])` : Recherche par critère
- `findBy(['status' => 'active'], ['createdAt' => 'DESC'])` : Recherche avec tri

Pour les opérations **Create** et **Update**, le RegisterController utilise l'EntityManager :

```php
public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em)
{
    $member = new Member();
    $form = $this->createForm(RegistrationFormType::class, $member);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Hash du mot de passe
        $hashedPassword = $passwordHasher->hashPassword(
            $member,
            $form->get('plainPassword')->getData()
        );
        $member->setPassword($hashedPassword);

        // Persistance
        $em->persist($member);
        $em->flush();

        return $this->redirectToRoute('app_login');
    }
}
```

La méthode `persist()` marque l'objet pour l'insertion, tandis que `flush()` exécute réellement la requête SQL. Cette approche **Unit of Work** permet de grouper plusieurs opérations et de les exécuter en une seule transaction.

Pour le **Delete**, avec suppression en cascade :

```php
$em->remove($member);
$em->flush();
```

Les relations OneToMany avec `orphanRemoval: true` [Member.php](src/Entity/Member.php:117-122) suppriment automatiquement les entités liées :

```php
#[ORM\OneToMany(mappedBy: "member", targetEntity: MemberAddFavoritesTournament::class, orphanRemoval: true)]
private Collection $memberAddFavorites;
```

Pour **MongoDB**, les opérations CRUD utilisent le driver natif via le service :

```php
// Create
$collection->insertOne(['field' => 'value']);

// Read
$document = $collection->findOne(['_id' => new ObjectId($id)]);

// Update
$collection->updateOne(
    ['_id' => new ObjectId($id)],
    ['$set' => ['field' => 'newValue']]
);

// Delete
$collection->deleteOne(['_id' => new ObjectId($id)]);
```

#### b. Sécurisation des comptes utilisateur

La sécurité des comptes repose sur plusieurs piliers :

**1. Hachage des mots de passe**

Configuration dans [security.yaml](config/packages/security.yaml:4-5) :

```yaml
password_hashers:
    Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: "auto"
```

L'algorithme "auto" utilise bcrypt par défaut avec un coût de 12, assurant un hachage robuste contre les attaques par force brute.

Le hachage dans le controller :

```php
$hashedPassword = $passwordHasher->hashPassword(
    $member,
    $form->get('plainPassword')->getData()
);
$member->setPassword($hashedPassword);
```

Le mot de passe en clair n'est **jamais stocké** en base de données.

**2. Validation stricte du formulaire**

Le [RegistrationFormType.php](src/Form/RegistrationFormType.php:79-89) impose des contraintes sévères :

```php
->add('plainPassword', RepeatedType::class, [
    'type' => PasswordType::class,
    'constraints' => [
        new Assert\NotBlank(['message' => 'Le mot de passe est obligatoire.']),
        new Assert\Length([
            'min' => 8,
            'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
        ]),
        new Assert\Regex([
            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'message' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.',
        ]),
    ],
])
```

**3. Protection CSRF**

Tous les formulaires incluent la protection CSRF [RegistrationFormType.php](src/Form/RegistrationFormType.php:125-129) :

```php
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => Member::class,
        'csrf_protection' => true,
        'csrf_field_name' => '_csrf_token',
        'csrf_token_id' => 'register',
    ]);
}
```

**4. Sanitization des entrées**

Le service [InputSanitizer.php](src/Service/InputSanitizer.php:5-55) nettoie toutes les entrées utilisateur :

```php
final class InputSanitizer
{
    public function text(string $v, int $max = 255): string
    {
        $v = trim(preg_replace('/\s+/u', ' ', $v));
        $v = strip_tags($v);
        return mb_substr($v, 0, $max);
    }

    public function textarea(string $v, int $max = 5000, bool $allowBasic = false): string
    {
        $v = trim($v);
        if ($allowBasic) {
            // Supprimer les attributs dangereux
            $v = strip_tags($v, '<p><br><ul><ol><li><strong><em>');
            // Supprimer tous les attributs (onclick, onerror, etc.)
            $v = preg_replace('/<(\w+)[^>]*>/', '<$1>', $v);
        } else {
            $v = strip_tags($v);
        }
        return mb_substr($v, 0, $max);
    }
}
```

Cette classe supprime :
- Les balises HTML dangereuses
- Les attributs d'événements JavaScript (onclick, onerror)
- Les espaces multiples
- Limite la longueur des chaînes

**5. Rate Limiting sur la connexion**

Le [AuthController.php](src/Controller/AuthController.php:29-41) implémente un rate limiter :

```php
public function login(
    AuthenticationUtils $authenticationUtils,
    Request $request,
    RateLimiterFactory $loginLimiter
): Response {
    // Créer un Rate-Limiting par IP
    $limiter = $loginLimiter->create($request->getClientIp());

    // Vérifier si la limite est atteinte
    if (false === $limiter->consume(1)->isAccepted()) {
        throw new TooManyRequestsHttpException(
            'Trop de tentatives de connexion. Veuillez réessayer plus tard.'
        );
    }
}
```

Cette protection limite les tentatives de connexion par IP, empêchant les attaques par force brute.

#### c. Sécurisation des accès

La configuration [security.yaml](config/packages/security.yaml:13-16) définit une **hiérarchie de rôles** :

```yaml
role_hierarchy:
    ROLE_ADMIN: [ROLE_ORGANIZER, ROLE_PLAYER, ROLE_USER]
    ROLE_ORGANIZER: [ROLE_PLAYER, ROLE_USER]
    ROLE_PLAYER: [ROLE_USER]
```

Un administrateur hérite automatiquement des permissions d'organisateur, de joueur et d'utilisateur, évitant la duplication de code.

Le **firewall principal** [security.yaml](config/packages/security.yaml:22-42) gère l'authentification :

```yaml
main:
    lazy: true
    provider: app_user_provider
    form_login:
        login_path: app_login
        check_path: app_login
        username_parameter: pseudo
        password_parameter: password
    logout:
        path: app_logout
        target: app_login
    remember_me:
        secret: "%kernel.secret%"
        lifetime: 604800  # 7 jours
        path: /
        always_remember_me: false
```

La fonctionnalité **remember_me** utilise un cookie signé avec le secret de l'application, empêchant la falsification. Le cookie expire après 7 jours pour limiter la fenêtre d'exposition.

Le **contrôle d'accès** [security.yaml](config/packages/security.yaml:49-53) protège les routes :

```yaml
access_control:
    - {path: ^/admin_dashboard, roles: ROLE_ADMIN}
    - {path: ^/organizer_space, roles: ROLE_ORGANIZER}
    - {path: ^/player_space, roles: ROLE_PLAYER}
    - {path: ^/events/create, roles: [ROLE_ORGANIZER, ROLE_ADMIN]}
```

Toute tentative d'accès non autorisé redirige vers la page de connexion.

Dans les templates, les accès conditionnels utilisent `is_granted()` [base.html.twig](templates/base.html.twig:76-88) :

```twig
{% if is_granted('ROLE_ADMIN') %}
    <li><a href="{{ path('admin_dashboard') }}">Dashboard admin</a></li>
{% elseif is_granted('ROLE_ORGANIZER') %}
    <li><a href="{{ path('organizer_space') }}">Espace organisateur</a></li>
{% elseif is_granted('ROLE_PLAYER') %}
    <li><a href="{{ path('player_space') }}">Espace joueur</a></li>
{% endif %}
```

Cette approche garantit que l'interface s'adapte automatiquement aux permissions de l'utilisateur.

---

### III. DÉVELOPPER DES COMPOSANTS MÉTIERS CÔTÉ SERVEUR

#### a. Controllers

Les controllers Symfony orchestrent le flux de l'application. Ils suivent le principe de **responsabilité unique** en déléguant la logique métier aux services.

Le [AuthController.php](src/Controller/AuthController.php:11-53) gère l'authentification :

```php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function signin(): Response
    {
        return $this->render('auth/signin.html.twig');
    }

    public function login(
        AuthenticationUtils $authenticationUtils,
        Request $request,
        RateLimiterFactory $loginLimiter
    ): Response {
        $limiter = $loginLimiter->create($request->getClientIp());

        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException(
                'Trop de tentatives de connexion. Veuillez réessayer plus tard.'
            );
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/signin.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
```

Ce controller :
- Utilise l'**injection de dépendances** pour les services
- Implémente le **rate limiting** pour la sécurité
- Sépare les responsabilités (affichage vs traitement)
- Retourne des objets Response typés

Les autres controllers suivent le même pattern :
- **HomeController** : Gère la page d'accueil
- **EventsController** : Liste et filtre les événements
- **SpaceController** : Gère les espaces utilisateurs (admin, organisateur, joueur)
- **CreateEventController** : Création de tournois
- **FavoriteEventController** : Gestion des favoris

Chaque controller hérite de `AbstractController` qui fournit des méthodes utilitaires :
- `$this->render()` : Rendu de templates Twig
- `$this->redirectToRoute()` : Redirection vers une route nommée
- `$this->createForm()` : Création de formulaires
- `$this->addFlash()` : Messages flash pour l'utilisateur

#### b. Services

Les services encapsulent la logique métier réutilisable. Ils sont déclarés dans le conteneur Symfony et injectés où nécessaire.

Le [InputSanitizer](src/Service/InputSanitizer.php) a déjà été évoqué pour la sécurité.

Le [MongoDBService](src/Service/MongoDBService.php) encapsule l'accès à MongoDB.

D'autres services incluent :

- **TournamentService** : Logique métier des tournois
- **UserService** : Gestion des utilisateurs
- **FileUploadService** : Gestion des uploads de fichiers
- **EventFormatterService** : Formatage des événements pour l'API
- **FavoriteEventService** : Gestion des favoris
- **TournamentStatusService** : Mise à jour automatique des statuts

Exemple de structure d'un service :

```php
namespace App\Service;

class TournamentService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MongoDBService $mongoService
    ) {}

    public function createTournament(array $data): Tournament
    {
        $tournament = new Tournament();
        $tournament->setTitle($data['title']);
        $tournament->setStatus('pending');

        $this->em->persist($tournament);
        $this->em->flush();

        // Log dans MongoDB
        $this->mongoService->getCollection('tournament_history')->insertOne([
            'tournament_id' => $tournament->getId(),
            'action' => 'created',
            'timestamp' => new \MongoDB\BSON\UTCDateTime()
        ]);

        return $tournament;
    }
}
```

Les services favorisent :
- La **réutilisabilité** du code
- La **testabilité** via l'injection de dépendances
- La **séparation des préoccupations**
- La **maintenabilité** avec une logique centralisée

#### c. Entités

Les entités représentent le modèle de données. L'entité [Member](src/Entity/Member.php) a été largement détaillée précédemment.

D'autres entités du projet :
- **Tournament** : Les tournois e-sport
- **TournamentImages** : Images associées aux tournois
- **TournamentHistory** : Historique des tournois (lié à MongoDB)
- **MemberRoles** : Rôles des utilisateurs (admin, organizer, player)
- **MemberAvatars** : Avatars disponibles
- **MemberAddFavoritesTournament** : Table de liaison favoris
- **MemberRegisterTournament** : Inscriptions aux tournois
- **MemberParticipateTournament** : Participations effectives

Les relations entre entités utilisent les annotations Doctrine :
- `@ManyToOne` / `@OneToMany` : Relations un-à-plusieurs
- `@ManyToMany` : Relations plusieurs-à-plusieurs
- `orphanRemoval: true` : Suppression en cascade
- `@JoinColumn` : Configuration des clés étrangères

#### d. Repositories

Les repositories personnalisent les requêtes aux bases de données. Ils héritent de `ServiceEntityRepository` et peuvent définir des méthodes spécifiques :

```php
class TournamentRepository extends ServiceEntityRepository
{
    public function findValidTournaments(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.status = :status')
            ->setParameter('status', 'valide')
            ->orderBy('t.startsAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByOrganizer(Member $organizer): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.organizer = :organizer')
            ->setParameter('organizer', $organizer)
            ->getQuery()
            ->getResult();
    }
}
```

Le **QueryBuilder** Doctrine :
- Construit des requêtes SQL de manière objet
- Utilise des **paramètres liés** pour éviter les injections SQL
- Permet des requêtes complexes avec jointures
- Retourne des objets PHP hydratés

#### e. Sécurité

Plusieurs couches de sécurité ont été implémentées :

**1. Protection XSS**
- Échappement automatique Twig
- Sanitization des entrées avec InputSanitizer
- Content Security Policy (à configurer en production)

**2. Protection CSRF**
- Tokens CSRF sur tous les formulaires
- Vérification automatique par Symfony

**3. Protection contre les injections SQL**
- Requêtes préparées Doctrine
- Paramètres liés dans le QueryBuilder
- ORM qui abstrait le SQL

**4. Authentification et autorisation**
- Hachage bcrypt des mots de passe
- Hiérarchie de rôles
- Access control sur les routes
- Remember me sécurisé

**5. Rate Limiting**
- Limitation des tentatives de connexion
- Protection contre le brute force

**6. Validation des données**
- Validation côté client (JavaScript)
- Validation côté serveur (Symfony Validator)
- Contraintes d'entité Doctrine
- Contraintes de base de données (UNIQUE, NOT NULL)

**7. Sécurité des fichiers**
- Validation des types MIME
- Limitation de taille
- Stockage hors du webroot
- Noms de fichiers sécurisés (hash)

#### f. Tests réalisés

Des tests unitaires et fonctionnels ont été développés avec PHPUnit.

Installation :

```bash
composer require --dev phpunit/phpunit
```

Exemple de test pour le RegisterController :

```php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    public function testRegisterPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signup');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Inscription');
    }

    public function testRegisterWithValidData(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signup');

        $form = $crawler->selectButton('Inscription')->form([
            'registration_form[firstName]' => 'John',
            'registration_form[lastName]' => 'Doe',
            'registration_form[pseudo]' => 'johndoe',
            'registration_form[email]' => 'john@example.com',
            'registration_form[plainPassword][first]' => 'Password123',
            'registration_form[plainPassword][second]' => 'Password123',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/login');
    }
}
```

Exécution des tests :

```bash
php bin/phpunit
```

Les tests garantissent :
- Le bon fonctionnement des routes
- La validation des formulaires
- La logique métier
- La sécurité (tokens CSRF, validation)

---

### IV. DOCUMENTER LE DÉPLOIEMENT D'UNE APPLICATION DYNAMIQUE WEB ET WEB MOBILE

#### a. Choix de l'outil - Fly.io

Pour le déploiement, j'ai choisi **Fly.io** pour plusieurs raisons :
- Support natif de Docker
- Déploiement proche de l'infrastructure de développement
- CLI simple et efficace
- Free tier généreux pour les projets personnels
- SSL/TLS automatique
- Scaling horizontal facile

Installation de la CLI Fly.io :

```bash
# macOS
brew install flyctl

# Windows
powershell -Command "iwr https://fly.io/install.ps1 -useb | iex"
```

Authentification :

```bash
flyctl auth login
```

#### b. Documentation de mon déploiement

**1. Préparation du Dockerfile de production**

J'ai créé un `Dockerfile` optimisé pour la production basé sur le `Dockerfile.dev` avec quelques modifications :

```dockerfile
FROM php:8.2-apache

# Installation des dépendances
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libicu-dev \
  libzip-dev \
  && docker-php-ext-install intl pdo pdo_mysql zip

# Copier Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le workdir
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . .

# Installer les dépendances sans dev
RUN composer install --no-dev --optimize-autoloader

# Build des assets
RUN npm ci && npm run build

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080

CMD ["apache2-foreground"]
```

**2. Configuration Fly.io**

Initialisation du projet :

```bash
flyctl launch
```

Cette commande génère un fichier `fly.toml` :

```toml
app = "esportify"
primary_region = "cdg"

[build]
  dockerfile = "Dockerfile"

[env]
  APP_ENV = "prod"

[http_service]
  internal_port = 8080
  force_https = true
  auto_stop_machines = true
  auto_start_machines = true
  min_machines_running = 0

[[services]]
  protocol = "tcp"
  internal_port = 8080

  [[services.ports]]
    port = 80
    handlers = ["http"]

  [[services.ports]]
    port = 443
    handlers = ["tls", "http"]
```

**3. Configuration des secrets**

Les variables sensibles sont stockées comme secrets :

```bash
flyctl secrets set DATABASE_URL="mysql://user:pass@host:3306/db"
flyctl secrets set MONGODB_URL="mongodb://user:pass@host:27017"
flyctl secrets set APP_SECRET="votre-secret-symfony"
```

**4. Base de données en production**

Pour MySQL, j'utilise un service externe comme PlanetScale ou un volume Fly.io persistant.

Pour MongoDB, j'utilise MongoDB Atlas en tier gratuit.

**5. Déploiement**

```bash
flyctl deploy
```

Cette commande :
1. Build l'image Docker
2. Push l'image vers le registry Fly.io
3. Crée ou met à jour les machines
4. Exécute les health checks
5. Route le trafic vers les nouvelles instances

**6. Vérification du déploiement**

```bash
flyctl status
flyctl logs
```

**7. Migrations en production**

Pour exécuter les migrations :

```bash
flyctl ssh console
php bin/console doctrine:migrations:migrate --no-interaction
```

Ou via un script de déploiement automatisé.

#### c. Production en cours

Le déploiement en production est actuellement en cours de finalisation. Les étapes réalisées :

✅ Conteneurisation complète de l'application
✅ Configuration Docker optimisée pour la production
✅ Tests locaux de l'image de production
✅ Configuration Fly.io
✅ Mise en place des secrets

En cours :
- Configuration de la base de données de production
- Migration des données de test
- Configuration du domaine personnalisé
- Mise en place du CI/CD avec GitHub Actions
- Configuration des backups automatiques
- Monitoring et alerting

Prochaines étapes :
- Optimisation des performances (cache Redis)
- CDN pour les assets statiques
- Logs centralisés
- Tests de charge

---

## CONCLUSION ET PERSPECTIVE

Le projet Esportify m'a permis de mettre en pratique l'ensemble des compétences requises pour le titre professionnel de Développeur Web et Web Mobile.

### Compétences acquises

**Sur le plan technique**, j'ai maîtrisé :
- La conteneurisation avec Docker pour garantir la cohérence des environnements
- L'architecture Symfony avec ses composants (ORM, Security, Form, Validator)
- L'intégration de bases de données relationnelles (MySQL) et non relationnelles (MongoDB)
- Le développement front-end responsive avec Bootstrap et Sass
- La programmation JavaScript ES6+ avec fetch API et manipulation DOM
- Le versionnement Git avec une stratégie de branches
- Les tests unitaires et fonctionnels avec PHPUnit

**Sur le plan de la sécurité**, j'ai implémenté :
- Validation en double couche (client et serveur)
- Protection CSRF sur tous les formulaires
- Hachage sécurisé des mots de passe avec bcrypt
- Rate limiting contre les attaques par force brute
- Sanitization systématique des entrées utilisateur
- Contrôle d'accès basé sur les rôles (RBAC)
- Requêtes préparées pour prévenir les injections SQL
- Échappement automatique des variables Twig contre XSS

**Sur le plan méthodologique**, j'ai appliqué :
- Une approche mobile-first pour le responsive design
- Le principe de séparation des préoccupations (MVC)
- L'injection de dépendances pour la testabilité
- Le pattern Repository pour l'accès aux données
- Les migrations pour versionner le schéma de base de données

### Difficultés rencontrées et solutions apportées

La principale difficulté a été la gestion de deux environnements de développement différents (macOS et Windows). La solution a été la conteneurisation avec Docker, garantissant une parfaite reproductibilité.

L'intégration simultanée de MySQL et MongoDB a nécessité une réflexion architecturale pour déterminer quelle donnée stocker où. J'ai opté pour MySQL pour les données transactionnelles (membres, tournois) et MongoDB pour l'historique et les logs.

La sécurisation multi-couches a demandé une attention particulière à chaque niveau (navigateur, formulaire, controller, service, base de données) pour garantir une protection en profondeur.

### Perspectives d'évolution

Plusieurs axes d'amélioration sont envisagés :

**Fonctionnalités** :
- Système de notifications en temps réel avec Mercure
- Messagerie entre joueurs et organisateurs
- Système de matchmaking automatique
- Intégration avec les APIs des plateformes e-sport (Twitch, Discord)
- Calendrier des événements avec synchronisation Google Calendar
- Statistiques avancées des joueurs

**Technique** :
- Migration vers Symfony 6.4 LTS ou 7.x
- Mise à jour PHP 8.4 pour les dernières optimisations
- Implémentation d'un cache Redis pour les performances
- API REST complète pour une future application mobile native
- Progressive Web App (PWA) pour l'expérience mobile
- Elasticsearch pour la recherche avancée d'événements

**Sécurité** :
- Authentification à deux facteurs (2FA)
- OAuth2 pour l'authentification sociale (Google, Discord)
- Audit de sécurité avec OWASP ZAP
- Content Security Policy (CSP) stricte
- Politique de mot de passe plus robuste avec vérification Have I Been Pwned

**DevOps** :
- CI/CD complet avec GitHub Actions
- Monitoring avec Sentry pour les erreurs
- Logs centralisés avec ELK Stack
- Tests de performance automatisés
- Backups automatiques quotidiens
- Blue/Green deployment pour zéro downtime

Ce projet m'a permis de développer une vision complète du cycle de vie d'une application web moderne, de la conception à la mise en production, en passant par le développement sécurisé et les tests. Les compétences acquises constituent une base solide pour ma carrière de développeur web et web mobile.

---

**Annexes** (non incluses dans ce document) :
- Annexe 1 : Fichier .gitignore complet
- Annexe 2 : Principales commandes Git utilisées
- Annexe 3 : Historique des commits
- Annexe 4 : Dockerfile complet
- Annexe 5 : docker-compose.yml complet
- Annexe 6 : docker-compose.override.yml complet
- Annexe 7 : Principales commandes Docker
- Annexe 8 : Charte graphique
- Annexe 9 : Wireframes Desktop
- Annexe 10 : Wireframes Mobile
- Annexe 11 : Mockups Desktop
- Annexe 12 : Mockups Mobile
- Annexe 13 : Fichier package.json
- Annexe 14 : Fichier composer.json
- Annexe 15 : Schéma de base de données MySQL
- Annexe 16 : Structure MongoDB
- Annexe 17 : Extraits de code principaux
- Annexe 18 : Résultats des tests
- Annexe 19 : Documentation de l'API
- Annexe 20 : Guide de déploiement