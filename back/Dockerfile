# Utiliser l'image officielle PHP avec Apache
FROM php:7.4-apache

RUN docker-php-ext-install pdo_mysql

# copier les fichiers de l'application dans le répertoire apache
COPY . /var/www/html/

# copier le script pour les permissions dossier telechargement d'images
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# rendre le script exécutable
RUN chmod +x /usr/local/bin/entrypoint.sh

# exposer le port 80
EXPOSE 80

# définir le point d'entrée par défaut
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
