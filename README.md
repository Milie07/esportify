# ESPORTIFY
 Esportify est une application d'e-sports permettant la création, la validation et la gestion d'évènement dédiés. Ce projet est réalisé dans le cadre du Titre Professionnele Développeur Web et Web Mobile
 _________________________________________________________________________
## SOMMAIRE
1.  Fonctionnalités
    - Page d'accueil :
        -> Présentation de l'entreprise
        -> Slide 
        -> evènements validés en cours ou à venir
    - Menu d'application
        -> Retour vers la page d'accueil
        -> Accès à tous les évènements
        -> Connexion avec niveaux d'accès / Inscription
        -> Contact
    - Vue globale de tous les évènements
        -> Modale pour le détail d'un évènement
    - Espaces utilisateur et Rôles dédiés :
        -> Visiteur : lecture publique
        -> Joueur : Espace en cours => Espace personnel, favoris, scores et lien vers une demande pour devenir organisateur
        -> Organisateur : Espace en cours => Espace Personnel, historique d'évènements crées, favoris et scores et formulaire de création d'évènement à soumettre (gestion de ses propres évènements), démarrer un évènement.
        -> Admin : Espace en cours => Dashboard regroupant les mêmes fonctionalités plus la possibilités de lister tous les inscrits, de visualiser toutes les demandes de joueurs pour devenir organisateur ainsi qu eles demandes d'évènements (modération, vaidation et gestion des droits)
    - Evènements :
        -> CRUD et statuts (En Attente, En cours, Validé, Terminé ou Refusé)
    - Filtre asynchrone :
        -> filter les évènements par date et heure, organisateurs ou nombre de joueurs
2.  Architecture et Technologie
    - Front-End
        -> HTML/CSS/Bootstrap 5.3.6 et Sass 1.92.1, Javascript et WebPack Encore
    - Back-End
        -> PHP 8.2, Symfony 5.4, Doctrine ORM, Twig
    - BDD Relationnelle
        -> MariaDB/MySQL (Doctrine Migrations)
    - NoSQL
        -> Prévu avec MongoDB pour la messagerie/contact
    - Outils
        -> Symfony CLI, Composer, Node.js, npm, Git et GitHub
3.  Pré-Requis
    - Windows et Laragon
    - PHP CLI
    - Symfony CLI configuré pour ce PHP
    - Composer, Node.js et npm
    - MariaDB/MySQL et phpMyAdmin
    - Certificats SSL ( Norton peut causer des erreurs)
4.  Installation (Windows et Laragon)
    - Cloner le dépôt
        `git clone - <https://github.com/Milie07/esportify.git>
        cd esportify`
    - Forcer le PHP Laragon pour Symfony CLI
        `symfony local:php:use C:\laragon\bin\php\php-8.2.27-nts-Win32-vs16-x64\php.exe`
    - Installer les dépendances PHP
        `composer install`
    - Installer les dépendances front
        `npm install`
    - Copier la config locale
        `type .env > .env.local`
    - puis éditer DATABASE_URL, MAILER_DSN, etc.
5.  Configuration
    - Créer/Editer .env.local :
    `APP_ENV=dev`
    `APP_DEBUG=1`
    `# Adapter serverVersion à votre MariaDB/MySQL
DATABASE_URL="mysql://root:@127.0.0.1:3306/esportify?
`serverVersion=mariadb-10.4"
6.  Base de Données 
    - Créer la base (si absente)
    `php bin/console doctrine:database:create`
    - Lancer les migrations
    `php bin/console doctrine:migrations:migrate -n` 
7.  Lancement en développement
    - Back : serveur Symfony
        `symfony server:start `
    - Front : compilation SCSS/JS
        `npm run watch`
    - Compte de test 
    Se connecter par pseudo et mot de passe
        .   Admin -> pseudo : AdminElodie, mdp : AdminElodie2025
        .   Organisateur -> pseudo : SoniaL, mdp : SoniaL2025
        .   Joueur -> pseudo : Miraak, mdp : Miraak2025
    D'autres comptes sont crées et disponibles dans un fichier à part.

8.  Sécurité
    - Formulaires : Validation serveur via contraintes Symfony Validator, CSRF activé sur les formulaires, auto‑escape Twig
    - Mots de passe : Encodage via Password Hasher (jamais en clair)
    - Sanitisation front : Fonctions JS de nettoyage (prévention XSS de base) en complément
    - Accès : Access_control par rôle ; redirections post‑login selon rôle ; contrôles is_granted() dans les templates
    - Sessions : Durée d’inactivité configurable (auto‑déconnexion à formaliser dans la config — voir feuille de route)
    - Logs & erreurs : Environnement prod sans debug ; dev avec debug
9. Déploiement
    - A venir
    - Hébergement prévu sur Heroku ou Netlify
10. Conteneurisation 
    - A Venir
    - But : Dockeriser PHP‑FPM + Nginx + MariaDB + Node build
    - Stack : DockerFile (PHP 8.2 + extensions pdo_mysql / mysqli)
    - Intégration : Volumes pour public/, var/, vendor/, node_modules/
11. NoSQL 
    - A Venir 
    - But : Messages et réponses de contact via la page contact,    demandes pour devenir organisateur  
    - Stack : prévu avec MongoDB 
    - Intégration : Adapter un petit service Symfony (driver MOngoDB PHP) endpoints dédiés.
12. Livrables RNCP
    -   Application déployée (URL) 
    -   Dépôt GitHub public
    -   README
    -   Fichier SQL de création de la base
    -   Charte graphique (PDF : palette, polices)
    -    Exports des maquettes : Wireframes (3 mobiles, 3 desktop) et Mockups (3 mobiles, 3 desktop)
    -   Lien de gestion de projet
    -   Documentation technique : 
        .   Réflexions initiales & choix techniques, 
        .   Configuration de l’environnement, 
        .   MCD ou diagramme de classe, 
        .   Diagramme de cas d’utilisation, Diagrammes de séquence
    - Documentation du déploiement.
13. Crédits et Licence      
    Images et icones d'avatars téléchargées à partir du site Freepik https://fr.freepik.com/ en licence gratuite.
    Les autres icônes proviennent de Font Awesome https://fontawesome.com/
    Les polices viennent de Google Fonts https://fonts.google.com/