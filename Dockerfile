FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Forza la rimozione di qualsiasi MPM tranne prefork
RUN rm -f /etc/apache2/mods-available/mpm_event.* \
        /etc/apache2/mods-enabled/mpm_event.* \
        /etc/apache2/mods-available/mpm_worker.* \
        /etc/apache2/mods-enabled/mpm_worker.* && \
    a2enmod mpm_prefork

COPY . /var/www/html/

EXPOSE 80