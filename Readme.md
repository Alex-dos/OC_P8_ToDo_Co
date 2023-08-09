<!-- codacy-status -->
<!-- /codacy-status -->

installation se rendre dans le dossier à l'intérieur du repertoire de votre server local :

 lancer la commande : git clone https://github.com/Alex-dos/OC_P8_ToDo_Co

pour installer les dépendances de symfony se rendre dans : cd P8ToDoList composer install

Pour créer la base de données, vous devez d'abord configurer correctement le fichier .env, puis exécuter et modifier cette variable avec le user, mot de passe et non de base de données MySQL

.env DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"

info voir doc lien ci dessous https://symfony.com/doc/5.4/configuration.html Pour créer la base de données, vous devez d'abord configurer correctement le fichier .env ou .env.local, puis exécuter :

php bin/console doctrine:database:create

et ensuite : php bin/console doctrine:schema:update --force

Lancer les fixtures :

php bin/console doctrine:fixtures:load

Import de la base données

Création de la BDD test :

php bin/console doctrine:database:create  --env=test

php bin/console doctrine:schema:update --force --env=test

php bin/console doctrine:fixtures:load -n --env=test

Utilisation du lien acceder à l'index :

http://votreHost/

Vous pouvez acceder aux détails de chaque tâche en cliquant sur le nom dans la card.

Vous pouvez inscrire et vous logger ainsi que gerer les tâches

Pour lancer les test :

XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html html

Bonne visite
