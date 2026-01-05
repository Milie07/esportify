# Guide de Déploiement Esportify

Ce document explique les différences entre l'environnement de développement et de production, ainsi que les bonnes pratiques pour déployer l'application.

## Table des matières

1. [Différences Développement vs Production](#différences-développement-vs-production)
2. [Migrations vs Fixtures](#migrations-vs-fixtures)
3. [Workflow de déploiement](#workflow-de-déploiement)
4. [Gestion des images](#gestion-des-images)
5. [Bonnes pratiques](#bonnes-pratiques)
6. [Résolution de problèmes](#résolution-de-problèmes)

---

## Différences Développement vs Production

| Aspect | Développement (local) | Production (Fly.io) |
|--------|----------------------|---------------------|
| **Base de données** | MySQL/MariaDB 8.0 | PostgreSQL |
| **Fixtures** | ✅ Chargées manuellement | ❌ Jamais utilisées |
| **Migrations** | ✅ Automatiques au démarrage | ✅ Automatiques au démarrage |
| **Uploads** | Persistants (volumes Docker) | ⚠️ Éphémères (sauf images tournois) |
| **Debug** | Activé (`APP_DEBUG=1`) | Désactivé (`APP_DEBUG=0`) |
| **Environnement** | `APP_ENV=dev` | `APP_ENV=prod` |
| **Logs d'erreurs** | Affichés à l'écran | Enregistrés dans les logs |
| **Cache** | Rechargé automatiquement | Doit être vidé manuellement |

---

## Migrations vs Fixtures

### Migrations

**Rôle** : Créer et modifier la structure de la base de données (tables, colonnes, index, clés étrangères).

**Fichiers** : `migrations/Version*.php`

**Quand ?** :
- Création/modification de tables
- Ajout/suppression de colonnes
- Modifications de données en production (UPDATE, INSERT)

**Où ?** : Développement ET Production

**Commandes** :
```bash
# Créer une migration
php bin/console make:migration

# Appliquer les migrations
php bin/console doctrine:migrations:migrate

# Voir le statut des migrations
php bin/console doctrine:migrations:status
```

**Exemple de migration de données** :
```php
// migrations/Version20260105112342.php
public function up(Schema $schema): void
{
    // Mise à jour des dates des tournois
    $this->addSql("UPDATE tournament SET start_at = '2026-01-06 10:00:00' WHERE title = 'Iron Arena'");
}
```

### Fixtures

**Rôle** : Insérer des données de démonstration pour le développement.

**Fichier** : `src/DataFixtures/AppFixtures.php`

**Quand ?** :
- Initialiser la base de données en développement
- Tester l'application avec des données cohérentes

**Où ?** : **UNIQUEMENT en développement**

**Commandes** :
```bash
# Charger les fixtures (⚠️ supprime TOUTES les données existantes)
docker exec -it esportify_web php bin/console doctrine:fixtures:load
```

**⚠️ ATTENTION** :
- Les fixtures **suppriment toutes les données** avant de charger les nouvelles
- **Ne JAMAIS exécuter** en production
- Pour modifier des données en production, utiliser une **migration de données**

---

## Workflow de déploiement

### 1. Développement local (MySQL/MariaDB)

```bash
# Démarrer les conteneurs Docker
docker-compose up -d

# Modifier le code et tester

# Si modification de la structure de la base :
docker exec -it esportify_web php bin/console make:migration

# Tester la migration localement
docker exec -it esportify_web php bin/console doctrine:migrations:migrate

# Vérifier que tout fonctionne
```

### 2. Commit et Push

```bash
# Ajouter les fichiers modifiés
git add .

# Créer un commit descriptif
git commit -m "Description des changements"

# Pousser vers GitHub
git push origin main
```

### 3. Déploiement sur Fly.io (PostgreSQL)

```bash
# Installer Fly CLI (première fois uniquement)
pwsh -Command "iwr https://fly.io/install.ps1 -useb | iex"

# Se connecter (première fois uniquement)
fly auth login

# Déployer l'application
fly deploy

# Les migrations s'exécutent automatiquement au démarrage du conteneur
```

### 4. Vérification post-déploiement

```bash
# Se connecter en SSH à la production
flyctl ssh console -a esportify

# Vérifier les migrations appliquées
php bin/console doctrine:migrations:status

# Vérifier les données (exemple : tournois)
php bin/console doctrine:query:sql "SELECT title, start_at, current_status FROM tournament ORDER BY start_at"

# Vider le cache si nécessaire
php bin/console cache:clear --env=prod

# Quitter
exit
```

---

## Gestion des images

### Images de tournois (versionnées)

**Localisation** : `public/uploads/tournaments/`

**Statut** : ✅ Versionnées dans Git (ressources statiques)

**Pourquoi ?** : Les images de tournois font partie de l'application et doivent être déployées avec le code.

**Configuration** : Voir `.gitignore` ligne 28-30
```gitignore
/public/uploads/*
# Garder les images des tournois (ressources statiques)
!/public/uploads/tournaments/
```

**Ajout d'une nouvelle image** :
1. Placer l'image dans `public/uploads/tournaments/`
2. Ajouter l'image dans Git : `git add public/uploads/tournaments/nouvelle-image.jpg`
3. Créer une migration pour insérer l'enregistrement en base
4. Commit et déployer

### Uploads utilisateurs (non versionnés)

**Localisation** : `public/uploads/` (autres dossiers)

**Statut** : ❌ NON versionnés (ignorés par Git)

**Pourquoi ?** : Ce sont des fichiers générés par les utilisateurs, pas du code source.

**En production** : Les uploads sont éphémères sur Fly.io (perdus au redéploiement)

**Solution future** : Configurer un stockage cloud (Cloudinary, S3) pour la persistance.

---

## Bonnes pratiques

### ✅ À FAIRE

1. **Toujours tester les migrations localement avant de déployer**
   - Créer la migration
   - L'appliquer en dev
   - Vérifier que tout fonctionne
   - Puis déployer

2. **Versionner les images de tournois dans Git**
   - Ce sont des ressources statiques de l'application
   - Elles doivent être disponibles en production

3. **Créer des migrations de données pour modifier les données en production**
   - Ne JAMAIS utiliser les fixtures en production
   - Exemple : `Version20260105112342.php` pour mettre à jour les dates 2026

4. **Vérifier la compatibilité MySQL/PostgreSQL des requêtes SQL**
   - Utiliser des blocs `DO $$` pour PostgreSQL
   - Tester les deux syntaxes si possible
   - Exemples de différences :
     - MySQL : `AUTO_INCREMENT` / PostgreSQL : `SERIAL` ou séquences
     - MySQL : `BLOB` / PostgreSQL : `BYTEA`

5. **Documenter les migrations complexes**
   - Ajouter des commentaires explicatifs
   - Remplir la méthode `getDescription()`

6. **Vider le cache après déploiement si nécessaire**
   ```bash
   flyctl ssh console -a esportify
   php bin/console cache:clear --env=prod
   ```

### ❌ À NE PAS FAIRE

1. **Ne JAMAIS committer des secrets dans Git**
   - `.env.local`
   - `.env.prod`
   - Clés API, mots de passe, tokens

2. **Ne JAMAIS charger les fixtures en production**
   - Risque de perte de toutes les données
   - Utiliser des migrations à la place

3. **Ne JAMAIS modifier directement la base de production**
   - Toujours passer par des migrations
   - Permet de garder un historique et de revenir en arrière

4. **Ne JAMAIS déployer sans tester localement**
   - Toujours vérifier que les migrations fonctionnent en dev
   - Évite les erreurs en production

5. **Ne JAMAIS utiliser `doctrine:schema:update --force` en production**
   - Cette commande est réservée au développement
   - Utiliser les migrations à la place

---

## Résolution de problèmes

### Problème : Migration échoue en production (PostgreSQL) mais fonctionne en dev (MySQL)

**Cause** : Différences de syntaxe SQL entre MySQL et PostgreSQL

**Solution** :
1. Vérifier la syntaxe PostgreSQL
2. Utiliser des blocs procéduraux `DO $$` pour PostgreSQL
3. Exemple :
   ```php
   // MySQL
   $this->addSql("INSERT INTO table (id, name) VALUES (NULL, 'value')");

   // PostgreSQL compatible
   $this->addSql("
       DO $$
       DECLARE
           next_id INT;
       BEGIN
           SELECT COALESCE(MAX(id), 0) + 1 INTO next_id FROM table;
           INSERT INTO table (id, name) VALUES (next_id, 'value');
       END $$;
   ");
   ```

### Problème : Tournois au statut "Terminé" alors qu'ils n'ont pas commencé

**Cause** : Le `TournamentStatusService` a calculé les statuts avec les anciennes dates

**Solution** :
1. Mettre à jour les statuts dans la migration de données
2. Exemple :
   ```php
   $this->addSql("UPDATE tournament SET current_status = 'Validé' WHERE start_at > NOW()");
   ```

### Problème : Image de tournoi ne s'affiche pas en production

**Cause** : L'image n'est pas versionnée dans Git

**Solution** :
1. Vérifier que l'image est dans `public/uploads/tournaments/`
2. Ajouter l'image à Git : `git add public/uploads/tournaments/image.jpg`
3. Commit et redéployer

### Problème : "Table doesn't exist" après déploiement

**Cause** : Les migrations ne se sont pas exécutées

**Solution** :
1. Se connecter en SSH : `flyctl ssh console -a esportify`
2. Vérifier les migrations : `php bin/console doctrine:migrations:status`
3. Exécuter manuellement : `php bin/console doctrine:migrations:migrate --no-interaction`
4. Vérifier les logs : `php bin/console doctrine:migrations:list`

### Problème : Données manquantes après déploiement

**Cause** : Les fixtures ne sont pas exécutées en production

**Solution** :
- **C'est normal !** Les fixtures sont uniquement pour le développement
- Pour ajouter des données en production, créer une **migration de données**
- Voir l'exemple `Version20260105112342.php`

---

## Commandes utiles

### Développement local

```bash
# Créer une migration
docker exec -it esportify_web php bin/console make:migration

# Appliquer les migrations
docker exec -it esportify_web php bin/console doctrine:migrations:migrate

# Charger les fixtures (⚠️ supprime les données)
docker exec -it esportify_web php bin/console doctrine:fixtures:load

# Voir le statut de la base
docker exec -it esportify_web php bin/console doctrine:schema:validate

# Exécuter une requête SQL
docker exec -it esportify_web php bin/console doctrine:query:sql "SELECT * FROM tournament"
```

### Production (Fly.io)

```bash
# Se connecter en SSH
flyctl ssh console -a esportify

# Voir les migrations
php bin/console doctrine:migrations:status

# Appliquer les migrations (si nécessaire)
php bin/console doctrine:migrations:migrate --no-interaction

# Exécuter une requête SQL
php bin/console doctrine:query:sql "SELECT title, current_status FROM tournament"

# Vider le cache
php bin/console cache:clear --env=prod

# Voir les logs
flyctl logs -a esportify

# Redémarrer l'application
flyctl apps restart esportify
```

---

## Contacts et ressources

- **Documentation Symfony** : https://symfony.com/doc/current/index.html
- **Documentation Doctrine Migrations** : https://www.doctrine-project.org/projects/doctrine-migrations/en/latest/index.html
- **Documentation Fly.io** : https://fly.io/docs/
- **Support** : Voir le README principal du projet
