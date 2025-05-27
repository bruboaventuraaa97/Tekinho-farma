FROM php:8.1-apache

# Instala as dependências do PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql pgsql

# Ativa o módulo de reescrita do Apache (opcional)
RUN a2enmod rewrite

# Copia o projeto para a pasta do Apache
COPY . /var/www/html/

EXPOSE 80
