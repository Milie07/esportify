# ESPORTIFY
 Esportify est une application d'e-sports permettant la création, la validation et la gestion de tournois/évènements dédiés. Ce projet est réalisé dans le cadre du Titre Professionnele Développeur Web et Web Mobile
 _________________________________________________________________________
## SOMMAIRE
1.  **Fonctionnalités**
  - Page d'accueil :
    * Présentation de l'entreprise 
    * Evènements validés à venir sous forme de slide avec images et dates
  - Menu d'application
    * Retour vers la page d'accueil
    * Accès à tous les évènements
    * Inscription
    * Contact
    * Avatar (connecté/non connecté)
    * Score (si connecté)
    * menu Connexion / Accès à l'espace dédié de l'utilisateur /Déconnexion
  - Vue globale de tous les évènements
    * Affichage de tous les évènements validés
    * Modale pour le détail d'un évènement
  - Page Contact
    * Envoi de messages réceptionnés sur l'espace de l'admin
  - Espaces utilisateur et Rôles dédiés :
    * Visiteur : lecture publique
    * Joueur : Espace en dev => Espace personnel, favoris, scores et lien vers une demande pour devenir organisateur (à venir)
    * Organisateur : Espace en dev => Espace Personnel avec historique d'évènements crées, favoris et scores - formulaire de création d'évènement à soumettre et la gestion de ses propres évènements (OK) plus la possibilité de démarrer un évènement.(à venir)
    * Admin : Espace en dev => Dashboard regroupant les mêmes fonctionalités, plus la possibilités de lister tous les inscrits (à venir), de visualiser les messages envoyés depuis la page contact (Ok), les demandes de joueurs pour devenir organisateur ainsi que les demandes d'évènements (modération, validation et refus Ok)
  - Evènements :
    * CRUD et statuts (En Attente, En Cours, Validé, Terminé ou Refusé)
    * Mise à jour automatique des statuts via `TournamentStatusService`
      - Un tournoi passe automatiquement en "En Cours" quand sa date de début est atteinte
      - Il passe en "Terminé" quand sa date de fin est dépassée
      - Service appelé régulièrement (cron ou requêtes utilisateur)
  - Filtre asynchrone :
    * Filtrer les évènements par date et heure, organisateurs ou nombre de joueurs sur la page évènements
2.  **Architecture et Technologie**
  - Front-End
    * HTML/CSS/Bootstrap 5.3.6 et Sass 1.92.1, Javascript et WebPackEncore
  - Back-End
    * PHP 8.2, Symfony 5.4, Doctrine ORM, Twig
  - BDD Relationnelle
    * Développement : MySQL/MariaDB 8.0 (Docker)
    * Production : PostgreSQL (Fly.io)
    * Gestion du schéma : Doctrine Migrations
  - NoSQL
    * MongoDB pour la gestion des messages de la page contact et lagestion des demandes
  - Outils
    * Symfony CLI, Composer, Node.js, npm, Git et GitHub
3.  **Pré-Requis**
  - Docker Desktop
  - Git
  - Fly CLI (pour le déploiement en production)
  Tous les autres outils sont gérés par Docker
4.  **Installation (Mode conteneurisé)**
  - Installation Minimale
    * Cloner le repo
    `git clone - <https://github.com/Milie07/esportify.git>`
    `cd esportify`
  - **Images et fichiers statiques**
    * Les **images de tournois** (`public/uploads/tournaments/`) sont versionnées dans Git car ce sont des ressources statiques de l'application
    * Les **uploads utilisateurs** (avatars, etc.) ne sont PAS versionnés et doivent être ajoutés manuellement en développement si nécessaire
    * Pour restaurer les uploads complets : décompresser `docs/uploads.zip` dans `public/`
  - Lancement de l'application
    - `docker compose build`
    - `docker compose up -d`
    ou `docker-compose up --build`
    - `docker exec -it esportify_web php bin/console doctrine:fixtures:load` à lancer pour regénérer les données en base dans un environnement local.
    - Les images de tests sont dans un dossier nommé `uploads.zip` situé dans le dossier `docs/upload/` à décompresser et à charger en local dans le dossier `public`pour les initialiser via la BDD.
  - Accès
    * Accès à l'application Symfony -> conteneur esportify_web (http:/localhost:8080)
    * Accès à la base de donnée phpmyadmin -> conteneuresportify_phpmyadmin (http://localhost:8081)
    * Accès à la base de donnée NoSQL -> conteneur esportify_mogo_espress(http://localhost:8082)
  - Note
    Toutes les dépendances (Composer, extensions PHP, migrations,fixtures) sont gérées dans Docker
  - Compte de test 
    * compte Admin (rôle ADMIN) -> pseudo: ElodieAdmin / mdp:AdminElodie2025
    * compte Admin (rôle ADMIN) -> pseudo: RaphAdmin / mdp: AdminRaph2025
    * compte Organisateur (rôle ORGANIZER) -> pseudo: HugoOrga / mdp:OrgaHugo2025
    * compte Organisateur (rôle ORGANIZER) -> pseudo: AlexOrga / mdp:OrgaAlex2025
    * compte Joueur (rôle PLAYER) -> pseudo: NicoPlayer / mdp PlayNico2025
5.  **Configuration**
  `APP_ENV=prod`
  `APP_DEBUG=0`
6.  **Base de Données SQL (Mode conteneurisé)**
  La base MariaDB est gérée automatiquement par Docker.

  - **Migrations (Structure de la base)**
    * Les migrations créent et modifient la **structure** des tables (colonnes, index, clés étrangères)
    * Fichiers : `migrations/Version*.php`
    * Exécution automatique au démarrage du conteneur :
      ```bash
      php bin/console doctrine:database:create --if-not-exists
      php bin/console doctrine:migrations:migrate --no-interaction
      ```
    * Les migrations s'appliquent en **développement ET en production**
    * Pour créer une migration : `php bin/console make:migration`

  - **Fixtures (Données de test)**
    * Les fixtures insèrent des **données de démonstration** (membres, tournois, rôles, etc.)
    * Fichier : `src/DataFixtures/AppFixtures.php`
    * **Uniquement pour le développement** - JAMAIS en production
    * Chargement manuel :
      ```bash
      docker exec -it esportify_web php bin/console doctrine:fixtures:load
      ```
    * ⚠️ **ATTENTION** : Cette commande **supprime** toutes les données existantes avant de charger les fixtures

  - **Modifications de données en production**
    * Pour modifier des données en production, il faut créer une **migration de données**
    * Exemple : `migrations/Version20260105112342.php` met à jour les dates des tournois 2026
    * Les migrations de données utilisent des requêtes SQL UPDATE/INSERT dans la méthode `up()`
7. **Base de Données NoSQL (Mode conteneurisé)** 
  - L'application intègre une base NoSQL MongoDB dédiée à la messagerie et aux intéractions utilisateurs:
  - L'objectif est d'isoler toutes les données liées :
    * aux messages issus de la page Contact
    * aux reponses administrateurs
    * aux demandes pour devenir organisateurs
    * aux futurs messages asynchrones liés aux tournois/évènements (chatasynchrone)
  - Base NOSQL
    * nom de la base : esportify
    * nom des collections : contact_messages
                            tournament_requests
  - Technologie
    * MongoDB (conteneurisé)
    * Driver PHP MongoDB via un service Symfony dédié
    * Stockage flexible adapté aux données relationnelles
  - Collections 
    * contact_messages
    * tournaments_requests
    * admin_messages (à venir)
    * threads_events (optionnelle)  
8.  **Lancement en développement**
  L'application est entièrement conteneurisée
  - Démarrage complet (Apache + PHP + MySQL + phpMyAdmin)
    `docker compose up --build`
    `npm install`
    `npm run dev`
    `docker exec -it esportify_web php bin/console doctrine:fixtures:load` à lancer pour regénérer les données en base dans un environnement local.
9.  **Sécurité**
  - Formulaires : Validation serveur via contraintes Symfony Validator,CSRF activé sur les formulaires, auto‑escape Twig
  - Mots de passe : Encodage via Password Hasher (jamais en clair), validation stricte (8+ caractères, majuscule, minuscule, chiffre)
  - Injections SQL : Protection via Doctrine ORM avec requêtes paramétrées (QueryBuilder + setParameter)
  - Sanitization front : Fonctions JS de nettoyage (prévention XSS debase) en complément
  - XSS : Service InputSanitizer dédié (strip_tags, validation email, etc.) + auto-escape Twig
  - Upload de fichiers : Validation stricte via FileUploadService (whitelist MIME, taille max 5MB, suppression métadonnées EXIF, noms aléatoires sécurisés)
  - Accès : Access_control par rôle (RBAC), hiérarchie ADMIN > ORGANIZER > PLAYER, contrôles is_granted() et denyAccessUnlessGranted()
  - Sessions : Timeout 30min, cookie SameSite:lax (protection CSRF), remember_me 7 jours max
  - Logs & erreurs : Monolog configuré, environnement prod sans debug
  - Rate-limiter : Protection anti-brute-force sur connexion (5 tentatives/15min par IP via symfony/rate-limiter, politique sliding window)
  - À améliorer : En-têtes HTTP de sécurité (CSP, HSTS, X-Frame-Options), logging d'audit, reset password
10. **Déploiement**
  - Déploiement sur fly.io (https://fly.io/)
  - Adresse de déploiement : https://esportify.fly.dev/
  - **Architecture de production**
    * Application : Conteneur Docker (PHP 8.2 + Apache) sur Fly.io
    * Base de données SQL : PostgreSQL hébergée sur Fly.io
    * Base de données NoSQL : MongoDB Atlas (plan gratuit)
    * Fichiers statiques : Uploads éphémères (non persistants)
    * Tâches planifiées : Cron intégré au conteneur (mise à jour automatique des statuts)
    * HTTPS : Certificat Let's Encrypt automatique via Fly.io
  - **Variables d'environnement en production**
    * `APP_ENV=prod`
    * `APP_DEBUG=0`
    * `APP_SECRET` : Généré avec `openssl rand -hex 32` et configuré via `fly secrets`
    * `DATABASE_URL` : PostgreSQL Fly.io (automatique via `fly postgres attach`)
    * `MONGODB_URL` : MongoDB Atlas connection string
  - **Workflow de déploiement**
    1. **Développement local** (MySQL/MariaDB)
       ```bash
       # Modifier le code et tester
       docker-compose up -d

       # Créer une migration si nécessaire
       docker exec -it esportify_web php bin/console make:migration

       # Tester la migration localement
       docker exec -it esportify_web php bin/console doctrine:migrations:migrate
       ```

    2. **Commit et Push**
       ```bash
       git add .
       git commit -m "Description des changements"
       git push origin main
       ```

    3. **Déploiement sur Fly.io** (PostgreSQL)
       ```bash
       # Installer Fly CLI (première fois uniquement)
       pwsh -Command "iwr https://fly.io/install.ps1 -useb | iex"

       # Se connecter (première fois uniquement)
       fly auth login

       # Déployer l'application
       fly deploy

       # Les migrations s'exécutent automatiquement au démarrage
       ```

    4. **Vérification post-déploiement**
       ```bash
       # Se connecter en SSH à la production
       flyctl ssh console -a esportify

       # Vérifier les migrations appliquées
       php bin/console doctrine:migrations:status

       # Vérifier les données
       php bin/console doctrine:query:sql "SELECT title, start_at, current_status FROM tournament"

       # Quitter
       exit
       ```
  - **Les Limitations du plan gratuit**
    * Uploads non persistants (perdus au redéploiement)
    * 512 MB RAM par machine
    * Auto-stop après inactivité (démarrage automatique à la première requête)
  
  - **Note importante** : Ne JAMAIS committer `.env.local` ou des secrets dans Git
11. **Conteneurisation** 
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
    DATABASE_URL
    `DATABASE_URL="mysql://esportify_user:esportify_pass@db:3306esportify?serverVersion=8.0&charset=utf8mb4"`
    MONGODB_URL
    `MONGODB_URL: "mongodb://root:rootpass@mongo:27017"`
  - Lancement en mode conteneurisé 
    Voir la section 4. **Installation**
  - Structure et choix
  Fourniture d'une stack complète et reproductible PHP-FPM / Apache,MariaDB, phpMyAdmin. La stack utilise le bind-mount du code source mais isole le dossier `vendor/` dans un volume anonyme. 
  J'ai fait ce choix car le volume.:/var/www/html de mon docker-composeinitial écrasait les vendor installés dans l'image. Ainsi pendant lebuild, Composer installait /var/www/html/vendor sauf qu'au moment delancer le conteneur Docker remplaçait tout par /var/www/html du dossier en local. Et comme il n'y était pas, Symfony plantait `Dependencies aremissing. Try running "composer install".`. En choisissant depositionner le dossier `vendor/` en volume anonyme, il n'est plus écrasé par la machine au moment du build et Symfony démarre sans problème. (Sf -> Bind‑mounts : https://docs.docker.com/storagebind-mounts/, Volumes : https://docs.docker.com/storage/volumes/)
    * Configuration utilisée :
    `volumes:`
    `.:/var/www/html # bind-mount du code`
    `/var/www/html/vendor # volume anonyme qui isole vendor/`
    - Avantages et Inconvénients :
      - Avantages
        * Modifications instantanées du code Symfony
        * vendor/ géré par Docker -> pas d'incohérence entre les  systèmes
        * Composer fonctionne toujours dans le conteneur 
        * ne pas avoir à exécuter `composer install` en local
      -  Inconvénients
        * Le dossier `vendor` n'apparait plus en local
        * Toute mise à jour des dépendances doit se faire dans le  conteneur avec la commande: 
        `docker exec -it esportify_web composer install`
12. **Livrables RNCP**
  - A la racine du projet
    - README
    - Fichier SQL de création de la base
  - Dans le dossier `docs/maquettes/` à la racine du projet
    -  Charte graphique (PDF : palette, polices)
    -  Exports des maquettes : Wireframes (3 mobiles, 3 desktop) et  Mockups (3 mobiles, 3 desktop) 
    - Diagrammes : MCD, MLD, MPD, Diagramme de Séquence et User_Case
  - Sur la Copie à rendre
    - Dépôt GitHub public
    - Lien de gestion de projet
    - Documentation technique : 
      .   Réflexions initiales & choix techniques, 
      .   Configuration de l’environnement, 
      .   MCD ou diagramme de classe,
      .   Diagramme de cas d’utilisation, Diagrammes de séquence
  - A venir :
    - En cours sur IONOS je pense.
    - Lien de l'application déployée (URL) 
13. **Crédits et Licence**  
  Freepik : Images et icones d'avatars téléchargées en licence gratuite.
  https://fr.freepik.com/ 
  Font Awesome : Autres icônes 
  https://fontawesome.com/
  Google Fonts : Polices 
  https://fonts.google.com/