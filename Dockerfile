FROM php:8.2-apache


COPY src/ /var/www/html/


RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

EXPOSE 80



FROM php:8.2-apache

# Instalimi i MySQL PDO driver
RUN docker-php-ext-install pdo pdo_mysql

# Aktivizo mod_rewrite në Apache për REST API
RUN a2enmod rewrite

# Kopjo fajllat PHP në root të Apache serverit
COPY src/ /var/www/html/

EXPOSE 80
