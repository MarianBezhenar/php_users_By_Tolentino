FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 🔧 Risolve il conflitto MPM: disabilita event, abilita prefork
RUN a2dismod mpm_event || true && a2enmod mpm_prefork

COPY . /var/www/html/

EXPOSE 80