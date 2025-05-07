# Utilisation de l'image officielle PHP avec Apache
FROM php:8.2-apache

# Installation des dépendances système nécessaires et des extensions PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Activation du module Apache mod_rewrite
RUN a2enmod rewrite

# Configuration du répertoire racine d'Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Installation de Composer (gestionnaire de dépendances PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail de l'image Docker
WORKDIR /var/www/html

# Copier tous les fichiers du projet dans le conteneur
COPY . .

# Installer les dépendances avec Composer
RUN composer install

# Configurer les permissions pour Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
