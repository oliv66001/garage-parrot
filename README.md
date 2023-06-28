# garage-parrot

## Déployer le projet en local

#### Prérequis

Symfony cli

#### Installation

git clone git@github.com:oliv66001/garage-parrot.git

cd garage-parrot

Créer un fichier .env.local à la racine du projet et y ajouter les variables d'environnement joint dans l'anexe.

Dans le terminal :

composer install

Si il y a un problème soulevé par composer taper la commande suivante :

composer update

Creation de la base de données :

php bin/console doctrine:database:create

php bin/console make:migration

php bin/console doctrine:migrations:migrate

php bin/console doctrine:fixtures:load

Lancer le serveur :

symfony server:start

#### Utilisation

Dans le navigateur :

Pour la connexion de l'administrateur :

voir mail et mot de passe dans l'anexe

L'administrateur doit créer un utilisateur pour pouvoir se connecter en tant que collaborateur.

