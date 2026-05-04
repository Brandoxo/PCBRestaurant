# syntax=docker/dockerfile:1

################################################################################
# Etapa 1: Instalar dependencias con Composer
################################################################################
FROM composer:lts as deps

WORKDIR /app

# Copiar solo composer.json y composer.lock para cache
COPY composer.json composer.lock ./

# Instalar dependencias sin ejecutar scripts
RUN --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction --optimize-autoloader --prefer-dist --no-scripts

# Copiar el resto del proyecto
COPY . .

# Ejecutar los scripts de Composer ahora que artisan existe
RUN composer run-script post-autoload-dump

################################################################################
# Etapa 2: Imagen final - PHP 8.2 + Apache
################################################################################
FROM php:8.2-apache as final

# Instalar extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev libxml2-dev zip unzip git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Configurar PHP para producción
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar dependencias de Composer desde la etapa anterior
COPY --from=deps /app/vendor /var/www/html/vendor

# Copiar el resto de la aplicación
COPY . /var/www/html

# Configurar permisos necesarios
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Configurar Apache para apuntar al directorio public/
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

EXPOSE 80

USER www-data