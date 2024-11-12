FROM php:8.1-fpm

# Instalações necessárias
WORKDIR /var/www
COPY . .

RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip unzip apache2 libapache2-mod-php8.1
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql gd

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Copiar a configuração do Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Habilitar o módulo rewrite do Apache
RUN a2enmod rewrite

# Comando para iniciar o Apache
CMD ["apache2-foreground"]
