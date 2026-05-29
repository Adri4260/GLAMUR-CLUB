FROM php:8.4-cli

# Instalamos dependencias de PHP y utilidades básicas (incluido curl)
RUN apt-get update -y && apt-get install -y \
    libzip-dev zip unzip git libpng-dev libjpeg-dev libfreetype6-dev curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd

# ¡PREVISIÓN! Instalamos Node.js y NPM para que Laravel pueda compilar Vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install --no-interaction

EXPOSE 8000