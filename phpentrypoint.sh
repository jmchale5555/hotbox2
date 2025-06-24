#!/bin/bash
# Get the ownership of the mounted directory
OWNER_UID=$(stat -c '%u' /var/www/html)
OWNER_GID=$(stat -c '%g' /var/www/html)

# Change nginx user to match the host user
usermod -u $OWNER_UID www-data 2>/dev/null || true
groupmod -g $OWNER_GID www-data 2>/dev/null || true

# Now nginx user has same ID as host user
chown -R www-data:www-data /var/www/html

exec "$@"
