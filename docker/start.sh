#!/bin/bash

# Exporter les variables d'environnement pour le cron
# Ne pas utiliser set -e car certaines commandes peuvent échouer sans conséquence
printenv > /etc/environment || true

# S'assurer que le fichier de log existe
touch /var/log/cron.log || true

# Démarrer cron en arrière-plan
cron || echo "Warning: cron failed to start"

# Afficher les logs du cron en arrière-plan
tail -f /var/log/cron.log 2>/dev/null &

# Démarrer Apache en avant-plan (c'est la commande principale)
exec apache2-foreground
