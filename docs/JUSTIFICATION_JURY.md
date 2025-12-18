# Gestion automatique des statuts de tournois - Justification technique

## 🎯 Problématique identifiée

### Symptôme
Les tournois terminés continuaient à s'afficher sur la page d'accueil et la page événements, avec un statut "Validé" ou "En Cours" alors qu'ils auraient dû avoir le statut "Terminé".

**Exemple concret :**
- Tournoi "Eclipse Masters" : 16/12 10h → 17/12 10h
- Le 17/12 à 15h (5h après la fin), le statut était toujours "Validé"
- Le tournoi s'affichait toujours sur la page d'accueil

### Cause racine
La logique métier existait (service `TournamentStatusService`) mais **n'était jamais déclenchée automatiquement**.

---

## 🔍 Analyse technique

### Architecture existante

```
┌──────────────────────────────────────────┐
│  TournamentStatusService.php             │
│                                          │
│  public function updateAllStatus() {     │
│    // Logique de transition des statuts │
│    // VALIDÉ → EN_COURS → TERMINÉ        │
│  }                                       │
└──────────────────────────────────────────┘
```

**Le problème :** Cette méthode ne s'exécute que si elle est appelée explicitement.

### Pourquoi le code ne s'exécutait pas ?

PHP est un langage **synchrone** et **événementiel** :
- Le code s'exécute uniquement lors d'une requête HTTP
- Sans requête → aucun code ne tourne
- La base de données MySQL ne fait rien automatiquement (pas de triggers configurés)

**Références officielles :**
- [Symfony - Console Commands](https://symfony.com/doc/current/console.html)
- [PHP Manual - Language Reference](https://www.php.net/manual/en/langref.php)

---

## 💡 Solution retenue : GitHub Actions (Cron externe)

### Pourquoi cette solution ?

J'ai comparé plusieurs approches :

| Solution | Coût | Complexité | Fiabilité | Choix |
|----------|------|------------|-----------|-------|
| Cron interne (Docker) | ~10€/mois* | Moyenne | Haute | ❌ |
| GitHub Actions | Gratuit | Faible | Haute | ✅ |
| Services externes (cron-job.org) | Gratuit | Très faible | Moyenne | ⚠️ |
| Trigger à chaque visite | Gratuit | Faible | Faible | ❌ |

*Sur Fly.dev, nécessite une machine toujours active (auto_stop_machines = false)

### Architecture mise en place

```
┌─────────────────────────────────────────────────────┐
│  GitHub Actions Workflow                            │
│  (.github/workflows/update-tournament-status.yml)   │
│                                                      │
│  Déclenché automatiquement : toutes les 5 minutes   │
└──────────────────┬──────────────────────────────────┘
                   │
                   │ HTTP GET avec token sécurisé
                   ▼
┌─────────────────────────────────────────────────────┐
│  Route sécurisée : /admin/update-tournaments-status │
│  TournamentStatusUpdateController.php               │
│                                                      │
│  - Vérifie le token secret (CRON_SECRET_TOKEN)      │
│  - Appelle TournamentStatusService::updateAllStatus()│
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  TournamentStatusService.php                        │
│                                                      │
│  1. Récupère tous les tournois (SELECT)             │
│  2. Compare dates avec l'heure actuelle             │
│  3. Met à jour les statuts (UPDATE)                 │
└─────────────────────────────────────────────────────┘
```

### Composants développés

#### 1. Route HTTP sécurisée
**Fichier :** `src/Controller/TournamentStatusUpdateController.php`

```php
#[Route('/admin/update-tournaments-status', methods: ['GET', 'POST'])]
public function update(Request $request, TournamentStatusService $service): Response
{
    // Authentification par token pour GitHub Actions
    $providedToken = $request->query->get('token');
    $expectedToken = $_ENV['CRON_SECRET_TOKEN'];

    if ($providedToken !== $expectedToken) {
        return new JsonResponse(['error' => 'Invalid token'], 403);
    }

    // Exécution de la mise à jour
    $service->updateAllStatus();

    return new JsonResponse(['success' => true]);
}
```

**Sécurité :**
- Token secret stocké dans les variables d'environnement
- Vérifié à chaque requête
- Différent entre dev et production

#### 2. Workflow GitHub Actions
**Fichier :** `.github/workflows/update-tournament-status.yml`

```yaml
name: Update Tournament Status (Cron)

on:
  schedule:
    # Toutes les 5 minutes
    - cron: '*/5 * * * *'

  # Déclenchement manuel possible
  workflow_dispatch:

jobs:
  update-tournament-status:
    runs-on: ubuntu-latest
    steps:
      - name: Call tournament status update endpoint
        run: |
          curl -X GET \
            "https://esportify.fly.dev/admin/update-tournaments-status?token=${{ secrets.CRON_SECRET_TOKEN }}"
```

**Avantages :**
- Gratuit (2000 min/mois incluses dans GitHub Free)
- Logs visibles et traçables
- Déclenchement manuel possible pour les tests

**Référence :** [GitHub Actions - Scheduled Events](https://docs.github.com/en/actions/using-workflows/events-that-trigger-workflows#schedule)

#### 3. Configuration sécurisée

**GitHub Secrets :**
- `CRON_SECRET_TOKEN` configuré dans Settings > Secrets > Actions

**Fly.dev Secrets :**
```bash
fly secrets set CRON_SECRET_TOKEN="[token aléatoire 64 caractères]"
```

---

## 🧪 Tests et validation

### Test 1 : Mise à jour manuelle
```bash
curl "https://esportify.fly.dev/admin/update-tournaments-status?token=XXX"

# Réponse attendue :
{
  "success": true,
  "message": "Tournament statuses updated successfully",
  "timestamp": "2025-12-17T15:30:00+01:00"
}
```

### Test 2 : Vérification en base de données
```sql
SELECT
    title,
    DATE_FORMAT(start_at, '%d/%m/%Y %H:%i') as debut,
    DATE_FORMAT(end_at, '%d/%m/%Y %H:%i') as fin,
    current_status
FROM tournament
ORDER BY start_at DESC;

-- Résultat : Les tournois terminés ont le statut "Terminé"
```

### Test 3 : Affichage sur le site
Les requêtes filtrent correctement :
```php
// TournamentRepository::findValidatedForCarousel()
$status = [
    CurrentStatus::VALIDE->value,
    CurrentStatus::EN_COURS->value
];
// Les tournois "Terminé" ne sont plus affichés
```

---

## 📚 Références techniques

### Documentation officielle
1. **Symfony Console Commands**
   https://symfony.com/doc/current/console.html

2. **GitHub Actions - Scheduled workflows**
   https://docs.github.com/en/actions/using-workflows/events-that-trigger-workflows#schedule

3. **Fly.io - Secrets Management**
   https://fly.io/docs/reference/secrets/

4. **Cron expression syntax**
   https://crontab.guru/

### Standards et bonnes pratiques
1. **OWASP - API Security**
   Protection par token des endpoints sensibles

2. **Twelve-Factor App - Config**
   https://12factor.net/config
   Variables d'environnement pour les secrets

---

## 🎓 Apprentissages et compétences démontrées

### Compétences techniques
- ✅ Analyse et résolution de bugs complexes
- ✅ Architecture distribuée (séparation des responsabilités)
- ✅ Sécurisation d'APIs (authentification par token)
- ✅ CI/CD avec GitHub Actions
- ✅ Gestion des secrets et variables d'environnement
- ✅ Déploiement cloud (Fly.io)

### Démarche professionnelle
1. **Diagnostic** : Identification du problème (statuts non mis à jour)
2. **Analyse** : Compréhension de la cause (pas de déclencheur)
3. **Comparaison** : Évaluation de plusieurs solutions
4. **Choix argumenté** : GitHub Actions (gratuit, fiable, simple)
5. **Implémentation** : Code sécurisé et testé
6. **Documentation** : Guide de déploiement et maintenance

---

## 🔧 Maintenance et évolution

### Monitoring
- **GitHub Actions** : Onglet "Actions" du repository
- **Logs Fly.dev** : `fly logs`
- **Fréquence actuelle** : Toutes les 5 minutes

### Modification de la fréquence
Pour changer l'intervalle, modifier `.github/workflows/update-tournament-status.yml` :

```yaml
# Toutes les 10 minutes
- cron: '*/10 * * * *'

# Toutes les heures
- cron: '0 * * * *'

# Tous les jours à 8h
- cron: '0 8 * * *'
```

### Coût total
**0 € / mois** (GitHub Actions Free Tier)

---

## 📊 Résultat final

✅ **Problème résolu :**
- Les tournois terminés passent automatiquement au statut "Terminé"
- Ils ne s'affichent plus sur la page d'accueil
- Mise à jour toutes les 5 minutes, 24h/24, 7j/7

✅ **Solution scalable :**
- Fonctionne même si personne ne visite le site
- Pas de coût supplémentaire
- Logs et monitoring intégrés

✅ **Sécurité :**
- Route protégée par token
- Token différent entre dev et production
- Pas de secrets commités dans le code

---

**Auteur :** Emi
**Date :** Décembre 2024
**Projet :** Esportify - Plateforme de gestion de tournois e-sport
