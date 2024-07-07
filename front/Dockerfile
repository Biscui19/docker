# Utiliser l'image officielle PHP avec Apache
FROM php:7.4-apache

RUN docker-php-ext-install pdo_mysql

# Copier les fichiers de l'application dans le répertoire de travail d'Apache
COPY . /var/www/html/

# Exposer le port 80
EXPOSE 80

# Définir le point d'entrée par défaut
CMD ["apache2-foreground"]
