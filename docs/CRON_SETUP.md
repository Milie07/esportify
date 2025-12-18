# Configuration du Cron - Mise à jour automatique des statuts de tournois

Ce document explique comment configurer la mise à jour automatique des statuts des tournois (VALIDÉ → EN_COURS → TERMINÉ).

## 📋 Vue d'ensemble

Les statuts des tournois sont mis à jour automatiquement en fonction de leurs dates de début et de fin :

- **VALIDÉ** : Tournoi validé par un admin, pas encore commencé
- **EN_COURS** : Le tournoi a commencé (`startAt <= now < endAt`)
- **TERMINÉ** : Le tournoi est terminé (`endAt < now`)

## 🏠 Environnement de développement (Docker local)

### Configuration

Le cron est maintenant **automatiquement configuré** dans l'environnement de développement.

**Fichiers impliqués :**
- [`Dockerfile.dev`](Dockerfile.dev) : Installe `cron`
- [`docker/crontab`](docker/crontab) : Définit la tâche cron (toutes les 5 minutes)
- [`docker/start.sh`](docker/start.sh) : Lance `cron` + `Apache` au démarrage

### Démarrage

```bash
# Rebuild les conteneurs pour appliquer les changements
docker-compose down
docker-compose build
docker-compose up -d

# Vérifier que le cron fonctionne
docker exec esportify_web ps aux | grep cron

# Voir les logs du cron (si configurés)
docker exec esportify_web tail -f /var/log/cron.log
```

### Test manuel

Pour tester la mise à jour des statuts sans attendre le cron :

```bash
# Via la commande console
docker exec esportify_web php bin/console app:update-tournament-status

# Via la route HTTP (avec le token de dev)
curl "http://localhost:8080/admin/update-tournaments-status?token=dev_secret_token_change_in_production"
```

## ☁️ Environnement de production (Fly.dev)

### Configuration GitHub Actions (Recommandé ✅)

Au lieu d'avoir un cron dans le conteneur qui peut s'arrêter, on utilise **GitHub Actions** pour appeler la route de mise à jour toutes les 5 minutes.

#### Étape 1 : Configurer le secret dans GitHub

1. Allez dans votre repository GitHub
2. **Settings** → **Secrets and variables** → **Actions**
3. Cliquez sur **New repository secret**
4. Nom : `CRON_SECRET_TOKEN`
5. Valeur : Générez un token aléatoire sécurisé

**Générer un token sécurisé :**

```bash
# Option 1 : OpenSSL
openssl rand -hex 32

# Option 2 : Node.js
node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"

# Option 3 : Python
python3 -c "import secrets; print(secrets.token_hex(32))"
```

Exemple de token généré : `a8f7d9e2b4c6f3a1e8d7b5c4f2a9e6d3b8c7f4a1e9d6b3c5f8a2e7d4b1c9f6a3`

#### Étape 2 : Configurer la variable d'environnement sur Fly.dev

```bash
# Définir le même token sur Fly.dev
fly secrets set CRON_SECRET_TOKEN="a8f7d9e2b4c6f3a1e8d7b5c4f2a9e6d3b8c7f4a1e9d6b3c5f8a2e7d4b1c9f6a3"

# Vérifier les secrets
fly secrets list
```

#### Étape 3 : Déployer les changements

```bash
# Commit et push les changements
git add .
git commit -m "Add GitHub Actions cron for tournament status updates"
git push origin main

# Déployer sur Fly.dev
fly deploy
```

#### Étape 4 : Vérifier que ça fonctionne

1. Allez dans votre repository GitHub
2. **Actions** → **Update Tournament Status (Cron)**
3. Cliquez sur **Run workflow** → **Run workflow** pour tester manuellement
4. Vérifiez que le workflow s'exécute sans erreur

**Le workflow s'exécutera automatiquement toutes les 5 minutes.**

### Test manuel en production

```bash
# Tester avec le token (remplacer YOUR_TOKEN par votre vrai token)
curl "https://esportify.fly.dev/admin/update-tournaments-status?token=YOUR_TOKEN"

# Réponse attendue :
{
  "success": true,
  "message": "Tournament statuses updated successfully",
  "timestamp": "2025-12-17T14:35:00+01:00"
}
```

## 🔒 Sécurité

### Route sécurisée

La route [`/admin/update-tournaments-status`](src/Controller/TournamentStatusUpdateController.php) accepte deux types d'accès :

1. **Avec token** (pour GitHub Actions) :
   - Token dans les paramètres : `?token=XXXX`
   - Token dans les headers : `X-Cron-Token: XXXX`
   - Retourne du JSON

2. **Sans token** (pour les admins connectés) :
   - Nécessite l'authentification et le rôle `ROLE_ADMIN`
   - Redirige vers la page events

### Bonnes pratiques

- ✅ Ne **jamais** commiter le token dans le code
- ✅ Utiliser des tokens **différents** entre dev et prod
- ✅ Régénérer le token si compromis
- ✅ Vérifier les logs GitHub Actions régulièrement

## 📊 Monitoring

### Vérifier que les mises à jour fonctionnent

#### En développement

```bash
# Voir les tournois et leurs statuts
docker exec esportify_db mysql -u esportify_user -pesportify_pass esportify \
  -e "SELECT tournament_id, title, DATE_FORMAT(start_at, '%d/%m/%Y %H:%i') as debut, \
      DATE_FORMAT(end_at, '%d/%m/%Y %H:%i') as fin, current_status FROM tournament \
      ORDER BY start_at DESC"
```

#### En production

1. **Via phpMyAdmin** : Vérifier la table `tournament` et les valeurs de `current_status`

2. **Via les logs GitHub Actions** :
   - GitHub → Actions → Update Tournament Status (Cron)
   - Vérifier les exécutions récentes

3. **Via la route** :
   ```bash
   curl "https://esportify.fly.dev/admin/update-tournaments-status?token=YOUR_TOKEN"
   ```

### Fréquence d'exécution

**Développement :** Toutes les 5 minutes (cron local)
**Production :** Toutes les 5 minutes (GitHub Actions)

Pour modifier la fréquence :

```yaml
# Dans .github/workflows/update-tournament-status.yml
on:
  schedule:
    # Toutes les 10 minutes au lieu de 5
    - cron: '*/10 * * * *'

    # Toutes les heures
    - cron: '0 * * * *'

    # Tous les jours à 8h00 UTC (9h00 Paris hiver, 10h00 Paris été)
    - cron: '0 8 * * *'
```

## 🐛 Dépannage

### Le cron ne s'exécute pas en dev

```bash
# Vérifier que cron est installé
docker exec esportify_web which cron

# Vérifier que cron tourne
docker exec esportify_web ps aux | grep cron

# Redémarrer le conteneur
docker-compose restart web

# Rebuild si nécessaire
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### GitHub Actions échoue

**Erreur : "Invalid token"**
- Vérifier que `CRON_SECRET_TOKEN` est bien configuré dans GitHub Secrets
- Vérifier que le même token est configuré sur Fly.dev

**Erreur : "CRON_SECRET_TOKEN not configured"**
- Le secret n'est pas défini sur Fly.dev
- Exécuter : `fly secrets set CRON_SECRET_TOKEN="votre_token"`

**Erreur : HTTP 403 ou 500**
- Vérifier les logs Fly.dev : `fly logs`
- Vérifier que l'application est bien déployée

### Les statuts ne se mettent pas à jour

```bash
# Tester manuellement la commande
docker exec esportify_web php bin/console app:update-tournament-status

# Vérifier les dates des tournois
docker exec esportify_db mysql -u esportify_user -pesportify_pass esportify \
  -e "SELECT title, start_at, end_at, current_status FROM tournament"

# Vérifier le timezone PHP
docker exec esportify_web php -r "echo date_default_timezone_get();"
# Doit retourner : Europe/Paris
```

## 📚 Ressources

- [Documentation Symfony Console](https://symfony.com/doc/current/console.html)
- [GitHub Actions - Scheduled Events](https://docs.github.com/en/actions/using-workflows/events-that-trigger-workflows#schedule)
- [Fly.io - Secrets](https://fly.io/docs/reference/secrets/)
- [Crontab Guru](https://crontab.guru/) - Testeur de syntaxe cron

## ✅ Checklist de déploiement

Avant de déployer en production, assurez-vous que :

- [ ] Le token `CRON_SECRET_TOKEN` est généré (32+ caractères aléatoires)
- [ ] Le secret est configuré dans GitHub Secrets
- [ ] Le secret est configuré sur Fly.dev (`fly secrets set`)
- [ ] Le workflow GitHub Actions est committé
- [ ] L'application est déployée sur Fly.dev
- [ ] Un test manuel de la route fonctionne
- [ ] Le workflow GitHub Actions s'exécute avec succès
- [ ] Les statuts des tournois se mettent à jour correctement
