FROM php:8.2-apache

# --- FASE DI BUILD (invariata) ---
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/

# --- FASE DI AVVIO (la novità) ---
CMD ["bash", "-lc", "\
    set -eux; \
    a2dismod mpm_event mpm_worker || true; \
    rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* || true; \
    a2enmod mpm_prefork; \
    apache2ctl -t; \
    exec apache2-foreground \
"]