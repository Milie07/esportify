# 📚 Guide des Migrations Doctrine - Esportify

## 🎯 Principe de base

Les migrations sont comme un **historique Git pour votre base de données**. Chaque modification de structure génère un nouveau fichier de migration.

**IMPORTANT :** Vous ne créez PAS une migration complète à chaque fois ! Seulement pour les changements.

---

## 🔄 Workflow Normal (Dev → Prod)

### 1️⃣ EN DÉVELOPPEMENT

#### Quand vous modifiez votre structure de données :

```bash
# 1. Vous modifiez une entité (ex: ajouter un champ "description" à Member)
# Editez: src/Entity/Member.php

# 2. Vous générez automatiquement la migration
docker exec esportify_web php bin/console make:migration

# 3. Vérifiez la migration générée dans migrations/
# Elle contient UNIQUEMENT les changements (ex: ALTER TABLE member ADD description...)

# 4. Appliquez la migration en dev
docker exec esportify_web php bin/console doctrine:migrations:migrate
```

#### Exemples de modifications qui nécessitent une migration :
- ✅ Ajouter/supprimer un champ dans une entité
- ✅ Modifier le type d'un champ (VARCHAR → TEXT)
- ✅ Ajouter/supprimer une table (nouvelle entité)
- ✅ Modifier une relation (OneToMany, ManyToMany, etc.)
- ✅ Ajouter/supprimer un index

---

### 2️⃣ EN PRODUCTION (Déploiement)

```bash
# 1. Vous déployez votre code (avec les nouveaux fichiers de migration)
git push

# 2. Sur le serveur de production, vous appliquez les migrations
php bin/console doctrine:migrations:migrate --no-interaction

# C'EST TOUT ! Doctrine sait quelles migrations sont déjà appliquées
# Il n'applique QUE les nouvelles
```

**🔒 Doctrine garde une trace** des migrations déjà appliquées dans la table `doctrine_migration_versions`.

---

## 📋 Commandes Essentielles

### Vérifier l'état des migrations
```bash
docker exec esportify_web php bin/console doctrine:migrations:status
```

### Créer une nouvelle migration (après modification d'entité)
```bash
docker exec esportify_web php bin/console make:migration
```

### Appliquer les migrations en attente
```bash
docker exec esportify_web php bin/console doctrine:migrations:migrate
```

### Voir les différences entre entités et DB (sans créer de migration)
```bash
docker exec esportify_web php bin/console doctrine:schema:update --dump-sql
```

### ⚠️ UNIQUEMENT EN DEV - Synchroniser directement (sans migration)
```bash
# À ÉVITER en prod ! À utiliser uniquement pour débugger en dev
docker exec esportify_web php bin/console doctrine:schema:update --force
```

---

## 🎬 Scénarios Courants

### Scénario 1 : Ajouter un champ "bio" à Member

```php
// src/Entity/Member.php
#[ORM\Column(type: 'text', nullable: true)]
private ?string $bio = null;

// Getter/Setter...
```

```bash
# Générer la migration
docker exec esportify_web php bin/console make:migration
# → Crée migrations/VersionXXXXXXXXXXXX.php avec "ALTER TABLE member ADD bio..."

# Appliquer en dev
docker exec esportify_web php bin/console doctrine:migrations:migrate

# Commiter et pusher
git add migrations/ src/Entity/Member.php
git commit -m "Ajout du champ bio au profil membre"
git push

# En prod (après déploiement)
php bin/console doctrine:migrations:migrate
```

---

### Scénario 2 : Créer une nouvelle entité "Comment"

```bash
# 1. Créer l'entité avec Maker
docker exec esportify_web php bin/console make:entity Comment

# 2. Générer la migration
docker exec esportify_web php bin/console make:migration
# → Crée "CREATE TABLE comment..."

# 3. Appliquer
docker exec esportify_web php bin/console doctrine:migrations:migrate

# 4. Déployer normalement
git add . && git commit -m "Ajout du système de commentaires"
```

---

### Scénario 3 : Modifier des données existantes

Parfois vous voulez modifier des **données** (pas la structure). Par exemple, changer le rôle de tous les utilisateurs.

```bash
# 1. Générer une migration vide
docker exec esportify_web php bin/console make:migration
```

```php
// migrations/VersionXXXXXXXXXX.php
public function up(Schema $schema): void
{
    // Pas de changement de structure
    // $this->addSql('ALTER...');

    // Modification de données
    $this->addSql("UPDATE member SET member_score = 0 WHERE member_score < 0");
}
```

```bash
# 2. Appliquer
docker exec esportify_web php bin/console doctrine:migrations:migrate
```

---

## ⚠️ À NE JAMAIS FAIRE

### ❌ Modifier une migration déjà appliquée en prod
```bash
# INTERDIT - La migration est déjà exécutée !
# Si vous modifiez migrations/Version20251203133333.php APRÈS l'avoir appliquée en prod,
# les changements ne seront JAMAIS appliqués (Doctrine pense qu'elle est déjà faite)
```

**Solution :** Créez une NOUVELLE migration avec les corrections.

---

### ❌ Supprimer une migration déjà appliquée
```bash
# INTERDIT - Casse l'historique
rm migrations/VersionXXXXXXXXXX.php  # ❌ NE PAS FAIRE
```

**Solution :** Si vraiment nécessaire, utilisez `doctrine:migrations:version --delete` (avancé).

---

### ❌ Utiliser doctrine:schema:update en production
```bash
# EN PROD - JAMAIS !
php bin/console doctrine:schema:update --force  # ❌ DANGEREUX
```

**Pourquoi ?** Pas de traçabilité, pas d'historique, risque de perte de données.

---

## 🏗️ Structure Actuelle de Votre Projet

Vous avez maintenant **2 migrations** :

1. **Version20251203133333** (✅ Complète)
   - Crée TOUTES les tables de base (member, tournament, etc.)
   - À appliquer lors d'une installation fraîche

2. **Version20251223140000** (✅ Sessions)
   - Crée la table `sessions` pour la gestion des sessions
   - Migration séparée car ajoutée après

---

## 📦 Installation Fraîche (Nouveau Serveur)

Sur un nouveau serveur (ou en local), pour créer la DB complète :

```bash
# 1. Créer la base de données
php bin/console doctrine:database:create

# 2. Appliquer TOUTES les migrations dans l'ordre
php bin/console doctrine:migrations:migrate --no-interaction

# 3. Charger les fixtures (données de test) si besoin
php bin/console doctrine:fixtures:load --no-interaction
```

**Doctrine applique automatiquement les migrations dans l'ordre chronologique.**

---

## 🆘 Troubleshooting

### Ma DB de dev est cassée
```bash
# Reset complet (perte de données !)
docker-compose down -v
docker-compose up -d
# Les migrations se réappliquent automatiquement au démarrage
```

### J'ai une table en trop/en moins en dev
```bash
# Voir les différences
docker exec esportify_web php bin/console doctrine:schema:update --dump-sql

# Synchroniser (dev uniquement)
docker exec esportify_web php bin/console doctrine:schema:update --force
```

### Les migrations ne se lancent pas en prod
```bash
# Vérifier l'état
php bin/console doctrine:migrations:status

# Forcer l'exécution
php bin/console doctrine:migrations:migrate --allow-no-migration
```

---

## ✅ Résumé Ultra-Court

| Situation | Commande |
|-----------|----------|
| J'ai modifié une entité | `make:migration` puis `migrate` |
| Je déploie en prod | `migrate` (les nouvelles migrations s'appliquent auto) |
| Je veux voir les changements | `doctrine:schema:update --dump-sql` |
| Reset complet en dev | `docker-compose down -v && docker-compose up -d` |
| Installation fraîche | `doctrine:database:create` puis `migrate` |

---

**📌 La règle d'or :**
- **Dev** : Modifier entités → `make:migration` → `migrate` → commit
- **Prod** : Déployer → `migrate`

C'est tout ! Les migrations gèrent l'historique automatiquement. 🚀
