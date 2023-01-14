FROM php:8.2-fpm

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    libzip-dev \
    zip  \
    && docker-php-ext-install zip mysqli pdo pdo_mysql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip gd  \
    && docker-php-ext-install gd  \
    && docker-php-ext-install bcmath \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Habilitar mod_rewrite para que Laravel funcione correctamente
#RUN a2enmod rewrite

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código de la aplicación al contenedor
COPY . .
COPY ".env" ".env"

# Instalar las dependencias de composer
# RUN composer install --no-dev

# Generar una clave de encriptación para Laravel
#RUN php artisan key:generate

# Ejecutar las migraciones
#RUN php artisan migrate --seed

# Ejecutar la aplicación con PHP Artisan Serve
# CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]
ADD infrastructure/run.sh /run.sh
RUN chmod 755 /run.sh

CMD ["sh", "/run.sh"]
