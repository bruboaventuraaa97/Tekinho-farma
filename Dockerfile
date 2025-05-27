FROM php:8.1-apache

# Copia o projeto para a pasta padrão do Apache
COPY . /var/www/html/

# Habilita o módulo de regravação (opcional, se você usar .htaccess)
RUN a2enmod rewrite

# Expõe a porta 80
EXPOSE 80
