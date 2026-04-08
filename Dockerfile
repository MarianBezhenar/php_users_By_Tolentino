FROM php:8.2-apache

# Installa estensioni PHP e client PostgreSQL (per eseguire psql)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pgsql pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/

# Crea uno script di avvio che:
# 1. Risolve il conflitto MPM
# 2. Attende il DB e applica schema.sql
# 3. Avvia Apache
RUN echo '#!/bin/bash\n\
set -eux\n\
\n\
# --- Fix MPM ---\n\
a2dismod mpm_event mpm_worker || true\n\
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* || true\n\
a2enmod mpm_prefork\n\
apache2ctl -t\n\
\n\
# --- Attendi che PostgreSQL sia pronto ---\n\
until pg_isready -h "$PGHOST" -p "$PGPORT" -U "$PGUSER"; do\n\
  echo "Waiting for PostgreSQL..."\n\
  sleep 2\n\
done\n\
\n\
# --- Esegui schema.sql (solo se esiste) ---\n\
if [ -f /var/www/html/schema.sql ]; then\n\
    echo "Applying schema.sql to $PGDATABASE..."\n\
    PGPASSWORD="$PGPASSWORD" psql -h "$PGHOST" -p "$PGPORT" -U "$PGUSER" -d "$PGDATABASE" -f /var/www/html/schema.sql 2>&1\n\
    echo "Schema applied (errors about existing objects are safe)."\n\
fi\n\
\n\
# --- Avvia Apache ---\n\
exec apache2-foreground\n\
' > /start.sh && chmod +x /start.sh

CMD ["/start.sh"]