# Utiliser l'image PHP 8.2 avec FPM
FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libgd-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    ungit \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip \
    && docker-php-ext-install opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer Node.js et npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Créer un utilisateur non-root
RUN groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www

# Copier les fichiers de l'application
COPY . /var/www

# Copier la configuration PHP personnalisée
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Définir les permissions
RUN chown -R www:www /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Passer à l'utilisateur www
USER www

# Définir le répertoire de travail
WORKDIR /var/www

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Installer les dépendances Node.js et compiler les assets
RUN npm install && npm run build

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

# Script de démarrage
COPY docker/php/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
