# Projet Docker Full Stack

## Instructions pour construire et démarrer l'application

### Pré-requis

- Docker
- Docker Compose

### Étapes pour construire et démarrer l'application

1. **Clonez ce dépôt :**

    ```bash
    git clone https://github.com/Karim932/docker.git
    cd projet
    ```

2. **Construisez et démarrez les conteneurs :**

    ```bash
    docker-compose up --build
    ```

    Cette commande va :
    - Construire les images Docker pour les services frontend et backend à partir des Dockerfiles situés dans les répertoires respectifs `front` et `back`
    - Démarrer les conteneurs pour chaque service (frontend, backend, db, et phpmyadmin)
    - Créer et initialiser la base de données MySQL avec le fichier bdd.sql 

3. **Accédez à l'application :**
    - Front-end : [http://localhost:8080](http://localhost:8080) -> le site web
    - phpMyAdmin : [http://localhost:8082](http://localhost:8082) -> pour interagir avec la base de donnée

## Architecture Docker de l'application

L'application est composée de 4 services principaux qui sont dans le fichier `docker-compose.yml` :

### Services

- **frontend** : Gère l'interface utilisateur.
    - **Dockerfile** : Situé dans le répertoire `front`.
    - **Ports** : `8080` (externe) mappé vers `80` (interne).
    - **Volumes** :
        - `./back/config:/var/www/html/config`
        - `./back/admin:/var/www/html/admin`
    - **Dépendances** : Dépend du service `backend`.

- **backend** : Ajout des maillot dans la boutique.
    - **Dockerfile** : Situé dans le répertoire `back`.
    - **Ports** : `8081` (externe) mappé vers `80` (interne).
    - **Volumes** :
        - `./back/config:/var/www/html/config`
        - `./back/admin:/var/www/html/admin`
    - **Environnement** :
        - `DATABASE_HOST=db`
        - `DATABASE_USER=user`
        - `DATABASE_PASSWORD=root`
        - `DATABASE_NAME=FootKit`
    - **Dépendances** : Dépend du service `db`.

- **db** : Conteneur MySQL pour la base de données.
    - **Image** : `mysql:5.7`
    - **Environnement** :
        - `MYSQL_ROOT_PASSWORD=root`
        - `MYSQL_DATABASE=FootKit`
        - `MYSQL_USER=user`
        - `MYSQL_PASSWORD=root`
    - **Volumes** :
        - `db_data:/var/lib/mysql`
        - `./db/bdd.sql:/docker-entrypoint-initdb.d/bdd.sql`
    - **Ports** : `3306` (externe) mappé vers `3306` (interne).

- **phpmyadmin** : interface graphique pour accèder à la base de donnée.
    - **Image** : `phpmyadmin/phpmyadmin`
    - **Environnement** :
        - `PMA_HOST=db`
        - `MYSQL_ROOT_PASSWORD=root`
    - **Ports** : `8082` (externe) mappé vers `80` (interne).
    - **Dépendances** : Dépend du service `db`.

### Volumes

- **db_data** : Volume pour gérer les données de la bdd si jamais elle s'arrête ou redémarre. 

### Réseaux

- **app-network** : Réseau Docker personnalisé pour assurer une communication sécurisée entre les conteneurs.

## Guide de test de la communication entre les conteneurs et la persistance des données

### Test de la communication entre les conteneurs

- **Tester la connexion phpMyAdmin vers base de données :**
    1. Ouvrez phpMyAdmin à l'adresse [http://localhost:8082](http://localhost:8082).
    2. Connectez-vous avec les identifiants MySQL (user / root).
    3. La connexion est réussi alors cela prouve que phpMyAdmin peut accèder au conteneur `db`.


- **Tester la connexion back-end vers base de données :**
    1. Il faut s'inscrire et ensuite se connecter en tant qu'utilisateur
    2. La connexion a bien réussi, vous pouvez maintenant accèder au site
    

- **Tester la connexion front-end vers back-end :**
    1. Ouvrez votre navigateur et accédez à [http://localhost:8080](http://localhost:8080).
    2. Après s'être connecté sur phpMyAdmin, on peut modifier le status et le passer à 1 pour devenir administrateur 
    3. Ensuite aller sur l'onglet `pannel admin` et ajoutez un maillot
    4. Normalement il n'y a pas eu d'erreur et le maillot a bien été ajouter dans `boutique`
    5. On peut aller voir sur la base de donnée, il y a le lien de l'image dans la colonne `image` de la table `maillots`

### Test de la persistance des données

1. **Ajouter des données via l'application :**
    - Utilisez l'interface front-end pour ajouter des données à la base de données en ajoutant un nouveau maillot.

2. **Redémarrer les conteneurs :**
    - Arrêtez les conteneurs :
        ```bash
        docker-compose down
        ```
    - Démarrez à nouveau les conteneurs :
        ```bash
        docker-compose up -d --build # -d mode détaché 
        ```

3. **Vérifier la persistance des données :**
    - Ouvrez phpMyAdmin à [http://localhost:8082](http://localhost:8082).
    - Se connecter et vérifier que les données ajoutées précédemment sont toujours présentes dans la bdd.

Si vous avez bien suivi les différentes étapes alors le projet sera fonctionnel et prêt à l'emploi :) 

