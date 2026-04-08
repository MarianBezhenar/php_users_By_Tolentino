FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pgsql pdo_pgsql \
    && apt-get clean

COPY . /var/www/html/

# Script di avvio che esegue schema.sql una volta
RUN echo '#!/bin/bash\n\
if [ -f /var/www/html/schema.sql ]; then\n\
    PGPASSWORD=$PGPASSWORD psql -h $PGHOST -p $PGPORT -U $PGUSER -d $PGDATABASE -f /var/www/html/schema.sql 2>/dev/null\n\
fi\n\
exec apache2-foreground' > /start.sh && chmod +x /start.sh

CMD ["/start.sh"]