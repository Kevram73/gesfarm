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
    libwebp-dev \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    vim \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    zip \
    opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Créer un utilisateur non-root
RUN groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www

# Créer le répertoire de l'application et définir les permissions
RUN mkdir -p /var/www \
    && chown -R www:www /var/www

# Copier les fichiers de configuration
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY docker/php/start.sh /usr/local/bin/start.sh
COPY docker/php/start-queue.sh /usr/local/bin/start-queue.sh
RUN chmod +x /usr/local/bin/start.sh /usr/local/bin/start-queue.sh

# Passer à l'utilisateur www
USER www

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers de l'application (en tant qu'utilisateur www)
COPY --chown=www:www . .

# Créer les répertoires nécessaires avec les bonnes permissions
RUN mkdir -p storage bootstrap/cache vendor \
    && chmod -R 775 storage bootstrap/cache vendor

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

CMD ["/usr/local/bin/start.sh"]