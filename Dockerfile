FROM php:8.1-fpm
WORKDIR /var/www
COPY . .
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip unzip apache2 libapache2-mod-php8.1
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql gd
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install
COPY . /var/www
RUN echo "<VirtualHost *:80>
ServerName app.lojagotasdemel.com.br
ServerAdmin gui.spicacci.dev@gmail.com
DocumentRoot /var/www/html/Projeto-Gota-de-Mel/public
<Directory /var/www/html/Projeto-Gota-de-Mel/public>
    AllowOverride All
    Require all granted
</Directory>
ErrorLog \${APACHE_LOG_DIR}/app_lojagotasdemel_error.log
CustomLog \${APACHE_LOG_DIR}/app_lojagotasdemel_access.log combined
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
CMD ["apache2-foreground"]