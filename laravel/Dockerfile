# Usamos PHP 8.2 (el estándar actual y más estable para Laravel 11)
FROM php:8.4-cli

# Instalamos extensiones del sistema necesarias para Laravel y MySQL
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libonig-dev default-mysql-client \
    && docker-php-ext-install pdo_mysql zip mbstring

# Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /app

# Copiamos el código
COPY . .

# Exponemos el puerto de Artisan
EXPOSE 8000

# Comando para iniciar Laravel en el contenedor
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]