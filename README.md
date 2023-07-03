# garage-parrot

## Installation en local du site Garage-Parrot

clone du projet

git clone git@github.com:oliv66001/garage-parrot.git

cd garage-parrot

création d'un fichier .env.local à la racine du projet

copier coller le contenu du fichier contenu dans l'annexe du projet dans le fichier .env.local la base de donnée est déjà configurée pour une utilisation en local avec symfony cli le nom de la base de donnée est garage_parrot

### Tappez la commande suivante pour installer les dépendances

composer install

### Si une erreur est soulevée lors de l'installation de composer, tapez la commande suivante.

composer update

### Tappez la commande suivante pour créer la base de données

php bin/console doctrine:database:create

### Créer un dossier (migrations) à la racine du projet

### Tappez la commande suivante pour créer les tables

php bin/console make:migration

php bin/console doctrine:migrations:migrate

### Tappez la commande suivante pour charger les données de test

php bin/console doctrine:fixtures:load

### Tappez la commande suivante pour lancer le serveur

symfony server:start

Pour la connexion en tant qu'administrateur voir le fichier contenu dans l'annexe du projet
La création de nouveau collaborateur se fait via la page /admin/inscription





