#!/bin/bash
# script pour les permissions du repertoire ou sont telechargé les images


UPLOAD_DIR=/var/www/html/admin/maillot

# check si il existe
mkdir -p $UPLOAD_DIR

# permissions
chown -R www-data:www-data $UPLOAD_DIR
chmod -R 755 $UPLOAD_DIR

# la commande par défaut du conteneur
exec "$@"
