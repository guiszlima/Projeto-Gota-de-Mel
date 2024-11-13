Usa a imagem base oficial do PHP com FPM
FROM php:8.2-fpm

Instala pacotes essenciais e dependências do Laravel
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

Instala o Composer (gerenciador de dependências PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

Define o diretório de trabalho
WORKDIR /var/www

Copia o código do projeto para dentro do contêiner
COPY . .

Instala as dependências do projeto Laravel
RUN composer install --no-dev --optimize-autoloader

Configura as permissões corretas para o diretório de armazenamento
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

Expõe a porta do PHP-FPM
EXPOSE 9000

Comando padrão para o contêiner
CMD ["php-fpm"]