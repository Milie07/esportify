#!/bin/bash

# Exporter les variables d'environnement pour le cron
printenv | grep -v "no_proxy" > /etc/environment

# Démarrer cron en arrière-plan
cron

# Afficher les logs du cron en arrière-plan
tail -f /var/log/cron.log &

# Démarrer Apache en avant-plan
apache2-foreground
