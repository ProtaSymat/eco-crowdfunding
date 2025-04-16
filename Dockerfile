FROM php:8.1-apache

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Activer les extensions PHP nécessaires
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Configurer Apache
COPY . /var/www/html
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/storage

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les dépendances et construire l'application
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Exposer le port Apache
EXPOSE 80

# Configurer Apache pour qu'il pointe vers public/
RUN sed -i -e "s/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/public/" /etc/apache2/sites-available/000-default.conf

# Copier les fichiers .env à partir des variables d'environnement
CMD php artisan migrate --force && apache2-foreground