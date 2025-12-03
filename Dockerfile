FROM php:8.2-apache

# Pour installer les extensions nécessaires à Symfony + cron
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libicu-dev \
  libzip-dev \
  libpq-dev \
  zip \
  openssl \
  libssl-dev \
  cron &&\
  docker-php-ext-install intl pdo pdo_mysql pdo_pgsql zip

# Mettre la TimeZone en corrélation
RUN echo "date.timezone=Europe/Paris" > /usr/local/etc/php/conf.d/timezone.ini \
    && ln -snf /usr/share/zoneinfo/Europe/Paris /etc/localtime \
    && echo "Europe/Paris" > /etc/timezone

# Installer l'extension MongoDB dans le conteneur
RUN pecl install mongodb \
  && docker-php-ext-enable mongodb

# Pour activer le mod_rewrite (nécessaire pour Symfony)
RUN a2enmod rewrite

# Pour installer composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Pour copier la config Apache personnalisée
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Pour copier le code Symfony dans le conteneur
COPY . /var/www/html

# Pour définir le dossier de travail
WORKDIR /var/www/html

#
RUN git config --global --add safe.directory /var/www/html

# Créer les répertoires nécessaires avant l'installation
RUN mkdir -p var/cache var/log public/uploads && chmod -R 777 var

# Pour Installer les dépendances Symfony avec les variables d'environnement minimales
# DATABASE_URL est une fausse valeur juste pour passer l'installation
ENV APP_ENV=prod \
    APP_SECRET=dummysecretforbuildonlychangeatruntime \
    DATABASE_URL=postgresql://dummy:dummy@localhost:5432/dummy \
    COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts

# Copier le fichier autoload_runtime.php pré-généré
COPY ./vendor_stub/autoload_runtime.php /var/www/html/vendor/autoload_runtime.php

# Générer manuellement l'autoloader optimisé
RUN composer dump-autoload --optimize --classmap-authoritative

# Pour donner les droits à Apache
RUN chown -R www-data:www-data /var/www/html

# Pour installer le cron de mise à jour des statuts des tournois
COPY ./docker/crontab /etc/cron.d/symfony-cron
RUN chmod 0644 /etc/cron.d/symfony-cron && \
    crontab /etc/cron.d/symfony-cron && \
    touch /var/log/cron.log

# POur exposer le port 8080
EXPOSE 8080

# Configurer Apache pour écouter sur le port 8080
RUN sed -i 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf

# Script de démarrage pour cron + Apache
COPY ./docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Lancer le script de démarrage
CMD ["/usr/local/bin/start.sh"]
