FROM php:8.2-apache

# Pour installer les extensions nécessaires à Symfony
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip && \
    docker-php-ext-install intl pdo pdo_mysql zip

# Pour activer le mod_rewrite (nécessaire pour Symfony)
RUN a2enmod rewrite

# Pour copier la config Apache personnalisée
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

# Pour copier le code Symfony dans le conteneur
COPY .. /var/www/html

# Pour définir le dossier de travail
WORKDIR /var/www/html

# Pour donner les droits à Apache
RUN chown -R www-data:www-data /var/www/html
