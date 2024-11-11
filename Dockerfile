FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar arquivos do projeto
COPY . .

# Definir variável para permitir execução do Composer como superusuário
ENV COMPOSER_ALLOW_SUPERUSER=1

# Ajustar permissões
RUN chown -R www-data:www-data /var/www

# Instalar dependências do Composer
RUN composer install --no-dev --optimize-autoloader

# Expor porta 9000
EXPOSE 80

# Comando para iniciar o PHP-FPM
CMD ["php-fpm"]