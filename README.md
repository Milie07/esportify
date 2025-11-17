# ESPORTIFY
 Esportify est une application d'e-sports permettant la création, la validation et la gestion de tournois/évènements dédiés. Ce projet est réalisé dans le cadre du Titre Professionnele Développeur Web et Web Mobile
 _________________________________________________________________________
## SOMMAIRE
1.  **Fonctionnalités**
    - Page d'accueil :
        * Présentation de l'entreprise
        * Slider 
        * Evènements validés à venir
    - Menu d'application
        * Retour vers la page d'accueil
        * Accès à tous les évènements
        * Connexion avec niveaux d'accès / Inscription
        * Contact
    - Vue globale de tous les évènements
        * Affichage de tous les évènements validés
        * Modale pour le détail d'un évènement
    - Page Contact
        * Formualire en dev
    - Espaces utilisateur et Rôles dédiés :
        * Visiteur : lecture publique
        * Joueur : Espace en dev => Espace personnel, favoris, scores et lien vers une demande pour devenir organisateur
        * Organisateur : Espace en dev => Espace Personnel, historique d'évènements crées, favoris et scores et formulaire de création d'évènement à soumettre (gestion de ses propres évènements), démarrer un évènement.
        * Admin : Espace en dev => Dashboard regroupant les mêmes fonctionalités plus la possibilités de lister tous les inscrits, de visualiser toutes les demandes de joueurs pour devenir organisateur ainsi qu eles demandes d'évènements (modération, vaidation et gestion des droits)
    - Evènements :
        * CRUD et statuts (En Attente, En cours, Validé, Terminé ou Refusé)
    - Filtre asynchrone :
        * Filter les évènements par date et heure, organisateurs ou nombre de joueurs
2.  **Architecture et Technologie**
    - Front-End
        * HTML/CSS/Bootstrap 5.3.6 et Sass 1.92.1, Javascript et WebPack Encore
    - Back-End
        * PHP 8.2, Symfony 5.4, Doctrine ORM, Twig
    - BDD Relationnelle
        * MariaDB/MySQL (Doctrine Migrations)
    - NoSQL
        * Prévu avec MongoDB pour la messagerie, contact et la gestion des demandes
    - Outils
        * Symfony CLI, Composer, Node.js, npm, Git et GitHub
3.  **Pré-Requis**
    - Docker Desktop
    - Git
    Tous les autres outils sont gérés par Docker
4.  **Installation (Mode conteneurisé)**
    - Installation Minimale 
      * Cloner le repo
      `git clone - <https://github.com/Milie07/esportify.git>`
      `cd esportify`
      * Les fichiers envoyés dans `public/uploads` ne sont pas versionnés dans Git. Pour le rendu, un dossier complet `uploads/` est fourni à part dans le projet. Ainsi il faut décompresser le fichier `uploads.zip` situé à la racine du projet dans le dossier `docs/` nommé `uploads` puis le glisser dans le dossier `public/`
    - Lancement de l'application
      `docker compose build`
      `docker compose up -d`
    - Accès
      * Accès à l'application Symfony -> conteneur esportify_web (http://localhost:8080)
      * Accès à la base de donnée phpmyadmin -> conteneur esportify_phpmyadmin (http://localhost:8081:80)
    - Note
      Toutes les dépendances (Composer, extensions PHP, migrations, fixtures) sont gérées dans Docker
    - Compte de test 
      * compte Admin -> pseudo: ElodieAdmin / mdp: AdminElodie2025
5.  **Configuration**
    `APP_ENV=dev`
    `APP_DEBUG=1`

6.  **Base de Données (Mode conteneurisé)**
    La base MariaDB est gérée automatiquement par Docker.
    - Au lancement du service web, les commandas suivantes s'exécutent :
    `php bin/console doctrine:database:create --if-not-exists`
    `php bin/console doctrine:migrations:migrate --no-interaction`
    `php bin/console doctrine:fixtures:load --no-interaction`
    Les données de démonstration proviennent de : 
    `src/DataFixtures/AppFixtures.php`
    Elles sont chargées automatiquement lors du premier lancement et lors d'un reset complet avec `docker-compose down -v`
7.  **Lancement en développement**
    L'application est entièrement conteneurisée
    - Démarrage complet (Apache + PHP + MySQL + phpMyAdmin)
      `docker compose build`
      `docker compose up -d`
      `npm install`
      `npm run dev`
8.  **Sécurité**
    - Formulaires : Validation serveur via contraintes Symfony Validator, CSRF activé sur les formulaires, auto‑escape Twig
    - Mots de passe : Encodage via Password Hasher (jamais en clair)
    - Sanitization front : Fonctions JS de nettoyage (prévention XSS de base) en complément
    - Accès : Access_control par rôle ; redirections post‑login selon rôle ; contrôles is_granted() dans les templates
    - Sessions : Durée d’inactivité configurable (auto‑déconnexion à formaliser dans la config — voir feuille de route)
    - Logs & erreurs : Environnement prod sans debug ; dev avec debug
9. **Déploiement**
    - A venir
    - Hébergement prévu sur Heroku ou Netlify
10. **Conteneurisation** 
    - Contenu :
      * Dockerfile : image PHP 8.2 + extensions (pdo_mysql, mysqli)
      * docker-compose.yml avec :
        php-fpm (Dockerfile local)
        nginx (reverse proxy)
        mariadb (persistée avec volumes)
        phpmyadmin
        node-builder (build des assets)
    - Volumes partagés 
      * public/
      * var/
      * vendor/
      * node_modules/
    - Environnement Docker 
      Exemple de DATABASE_URL
      `DATABASE_URL="mysql://esportify_user:esportify_pass@db:3306/esportify?serverVersion=8.0&charset=utf8mb4"`
    - Lancement en mode conteneurisé 
      Voir la section 4. **Installation**

    - Structure et choix
    Fourniture d'une stack complète et reproductible PHP-FPM / Apache, MariaDB, phpMyAdmin. La stack utilise le bind-mount du code source mais isole le dossier `vendor/` dans un volume anonyme. 
    J'ai fait ce choix car le volume.:/var/www/html de mon docker-compose initial écrasait les vendor installés dans l'image. Ainsi pendant le build, Composer installait /var/www/html/vendor sauf qu'au moment de lancer le conteneur Docker remplaçait tout par /var/www/html du dossier en local. Et comme il n'y était pas, Symfony plantait `Dependencies are missing. Try running "composer install".`. En choisissant de positionner le dossier `vendor/` en volume anonyme, il n'est plus écrasé par la machine au moment du build et Symfony démarre sans problème. (Sf -> Bind‑mounts : https://docs.docker.com/storage/bind-mounts/, Volumes : https://docs.docker.com/storage/volumes/)
      * Configuration utilisée :
      `volumes:`
      `.:/var/www/html # bind-mount du code`
      `/var/www/html/vendor # volume anonyme isolant vendor/`
      - Avantages et Inconvénients :
        - Avantages
          * Modifications instantanées du code Symfony
          * vendor/ géré par Docker -> pas d'incohérence entre les  systèmes
          * Composer fonctionne toujours dans le conteneur 
          * ne pas avoir à exécuter `composer install` en local
        -  Inconvénients
          * Le dossier `vendor` n'apparait plus en local
          * Toute mise à jour des dépendances doit se faire dans le   conteneur avec la commande: 
          `docker exec -it esportify_web composer install`

11. **NoSQL** 
    - En cours 
    - But : Messages et réponses de contact via la page contact, demandes pour devenir organisateur  
    - Stack : prévu avec MongoDB 
    - Intégration : Adapter un petit service Symfony (driver MongoDB PHP) avec endpoints dédiés.

12. **Livrables RNCP**
    - A la racine du projet
      - README
      - Fichier SQL de création de la base
    - Dans le dossier `docs/maquettes/` à la racine du projet
      -  Charte graphique (PDF : palette, polices)
      -  Exports des maquettes : Wireframes (3 mobiles, 3 desktop) et   Mockups (3 mobiles, 3 desktop) 
      - Diagrammes : MCD, MLD, MPD et User_Case
    - Sur la Copie à rendre
      - Dépôt GitHub public
      - Lien de gestion de projet
      - Documentation technique : 
        .   Réflexions initiales & choix techniques, 
        .   Configuration de l’environnement, 
        .   MCD ou diagramme de classe,
        .   Diagramme de cas d’utilisation, Diagrammes de séquence
    - A venir :
      - Documentation du déploiement
      - Lien de l'application déployée (URL) 
13. **Crédits et Licence**  
    Images et icones d'avatars téléchargées à partir du site Freepik https://fr.freepik.com/ en licence gratuite.
    Les autres icônes proviennent de Font Awesome https://fontawesome.com/
    Les polices viennent de Google Fonts https://fonts.google.com/