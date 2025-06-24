#!/bin/bash
# Get the ownership of the mounted directory
OWNER_UID=$(stat -c '%u' /var/www/html)
OWNER_GID=$(stat -c '%g' /var/www/html)

# Change nginx user to match the host user
usermod -u $OWNER_UID nginx 2>/dev/null || true
groupmod -g $OWNER_GID nginx 2>/dev/null || true

# Now nginx user has same ID as host user
chown -R nginx:nginx /var/www/html

exec "$@"