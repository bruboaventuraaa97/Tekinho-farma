FROM php:8.1-apache

# Instala a extensão do PostgreSQL (pdo_pgsql + pgsql)
RUN docker-php-ext-install pdo_pgsql pgsql

# Copia seus arquivos para o Apache
COPY . /var/www/html/

# Ativa o rewrite (opcional se usar .htaccess)
RUN a2enmod rewrite

EXPOSE 80
