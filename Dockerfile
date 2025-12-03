FROM php:8.2-apache

# Pour installer les extensions nécessaires à Symfony + cron
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libicu-dev \
  libzip-dev \
  zip \
  openssl \
  libssl-dev \
  cron &&\
  docker-php-ext-install intl pdo pdo_mysql zip

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

# Pour Installer les dépendances Symfony
RUN composer install --no-interaction --optimize-autoloader --no-scripts

# Pour donner les droits à Apache
RUN chown -R www-data:www-data /var/www/html

# Pour installer le cron de mise à jour des statuts des tournois
COPY ./docker/crontab /etc/cron.d/symfony-cron
RUN chmod 0644 /etc/cron.d/symfony-cron && \
    crontab /etc/cron.d/symfony-cron && \
    touch /var/log/cron.log

# POur exposer le port 80
EXPOSE 80

# Lancer Apache + Cron
CMD service cron start && apache2-foreground
