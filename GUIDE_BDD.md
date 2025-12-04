# Guide d'accès aux bases de données

## 1. Base de données PostgreSQL (Fly.io)

### Accéder à la console PostgreSQL
```bash
flyctl postgres connect -a esportify-db
```

### Commandes SQL utiles
```sql
-- Voir tous les utilisateurs inscrits
SELECT id, pseudo, email, created_at FROM member ORDER BY created_at DESC;

-- Voir tous les tournois
SELECT id, title, current_status, created_at FROM tournament ORDER BY created_at DESC;

-- Compter les utilisateurs
SELECT COUNT(*) as total_users FROM member;

-- Voir les tournois avec leur organisateur
SELECT t.id, t.title, t.current_status, m.pseudo as organizer
FROM tournament t
JOIN member m ON t.organizer_id = m.id
ORDER BY t.created_at DESC;
```

### Exécuter des requêtes depuis l'application
```bash
# Depuis Fly.io
flyctl ssh console -a esportify -C "php bin/console dbal:run-sql 'SELECT COUNT(*) FROM member'"

# En local (avec tunnel vers PG)
php bin/console dbal:run-sql 'SELECT * FROM member'
```

## 2. Base de données MongoDB (MongoDB Atlas)

### Accéder via le Dashboard Web
1. Va sur [https://cloud.mongodb.com/](https://cloud.mongodb.com/)
2. Connecte-toi avec ton compte
3. Sélectionne ton projet
4. Clique sur "Browse Collections" pour voir tes données

### Collections MongoDB dans Esportify
- `contact_messages` : Messages du formulaire de contact
- `tournament_requests` : Demandes de création de tournois
- `test_connection` : Collection de test

### Tester MongoDB depuis l'application
```bash
# Test de connexion MongoDB
flyctl ssh console -a esportify -C "php bin/console app:test-mongo"
```

### Voir les données MongoDB
```bash
# Compter les messages de contact
flyctl ssh console -a esportify -C "php bin/console app:test-mongo"

# Les résultats afficheront:
# - Nombre de messages de contact
# - Nombre de demandes de tournois
# - Liste des collections disponibles
```

## 3. Accès SSH à l'application

```bash
# Ouvrir un shell dans le container
flyctl ssh console -a esportify

# Une fois connecté, tu peux:
# - Voir les logs : tail -f /var/log/apache2/error.log
# - Lister les uploads : ls -la public/uploads/tournaments/
# - Vider le cache : php bin/console cache:clear
```

## 4. Logs de l'application

```bash
# Voir les logs en temps réel
flyctl logs -a esportify

# Voir seulement les erreurs
flyctl logs -a esportify | grep -i error
```

## 5. Problèmes courants

### Les nouveaux utilisateurs ne s'affichent pas
Les utilisateurs s'inscrivent bien en base PostgreSQL. Tu peux les voir avec:
```bash
flyctl ssh console -a esportify -C "php bin/console dbal:run-sql 'SELECT pseudo, email, created_at FROM member ORDER BY created_at DESC LIMIT 10'"
```

### Les formulaires ne fonctionnent pas
Si les formulaires de contact ou de création de tournoi ne fonctionnent pas, c'est probablement MongoDB qui n'est pas connecté. Teste avec:
```bash
flyctl ssh console -a esportify -C "php bin/console app:test-mongo"
```

### Vérifier les variables d'environnement
```bash
flyctl secrets list -a esportify
```

## 6. Structure actuelle des données

### PostgreSQL (données permanentes)
- **member** : Utilisateurs inscrits (joueurs, organisateurs, admins)
- **tournament** : Tous les tournois (avec leur statut)
- **member_roles** : Rôles disponibles
- **tournament_images** : Images des tournois

### MongoDB (données temporaires/workflow)
- **contact_messages** : Messages depuis le formulaire de contact
- **tournament_requests** : Demandes de création de tournois (workflow de validation)

## 7. Flux de création de tournoi

1. L'organisateur remplit le formulaire de création
2. Les données sont sauvegardées dans **PostgreSQL** (table `tournament`)
3. Une demande est créée dans **MongoDB** (`tournament_requests`)
4. L'admin voit la demande dans son espace
5. L'admin valide/refuse la demande
6. Le statut est mis à jour dans PostgreSQL ET MongoDB
7. Le tournoi validé s'affiche sur l'accueil

## Corrections apportées aujourd'hui

✅ Migration Doctrine créée pour éviter les warnings
✅ Service MongoDB corrigé pour utiliser l'injection de dépendances
✅ Commande de test MongoDB créée (`app:test-mongo`)
✅ Configuration MongoDB ajoutée dans services.yaml
