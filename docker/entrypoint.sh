#!/bin/sh
set -eu
# Only set ownership on the volume root; never remove or overwrite its contents.
chown www-data:www-data /var/www/html/uploads
php /var/www/html/config/setup.php
exec docker-php-entrypoint "$@"
