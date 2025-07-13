FROM php:8.2-apache


COPY src/ /var/www/html/


RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

EXPOSE 80





RUN a2enmod rewrite


COPY src/ /var/www/html/

EXPOSE 80


RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
