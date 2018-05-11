spice-shop
==========

## Technologies utilisées

* PHP 5.6.16 (possibilité d'utiliser PHP 7.1)
* Symfony 3.4

## Installation
Avant de pouvoir lancer l'application il est nécessaire d'installer PHP (5.6 ou 7.1).

Une fois PHP installé, il faut :

1. Générer la base de données `php bin/console doctrine:database:create`
2. Mettre à jour le schéma de la base `php bin/console doctrine:schema:update --force`
3. Générer les fixtures `php bin/console doctrine:fixtures:load`, pour réinitialiser les données

## Lancement de serveur
La dernière étape avant de pouvoir accéder au serveur est d'exécuter la commande `php bin/console server:run`

## Problèmes
En cas de problème, il sera peut-être nécessaire de recharger les vendors composer. Pour celà faire :

* `php bin/composer install` ou `php bin/composer update`