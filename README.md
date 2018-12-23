MyBank API
==========

API pour gérer les comptes bancaires, les opérations et les catégories de l'application MyBank.
Cette API est utilisée par le frontal Angular [MyBank UI][1].

Technologies
------------

Cette API repose sur le framework [API Platform][2] facilitant la création d'API REST.

Pré-requis
----------

Installer :
* Docker
* Docker Compose

Installation
------------

Faire un clone du dépôt et se positionner dans le répertoire créé :

    $ git clone https://github.com/plecavelier/mybank-api.git
    $ cd mybank-api

Lancer le build de l'image Docker :

    $ docker-compose build

Démarrer le conteneur Docker :

    $ docker-compose up -d

Il est maintenant possible d'accéder à :

- L'API : `http://localhost`
- Un phpMyAdmin pour administrer la base de données MySQL : `http://localhost:3680`

Tests
-----

Faire un appel à l'API :

    $ curl http://localhost

*Remplacer localhost par l'IP ou le nom de l'hôte si le conteneur n'a pas été lancé sur la machine locale.*

Vous devriez obtenir la réponse `{"code":401,"message":"JWT Token not found"}` car l'authentification à l'API n'a pas été effectuée.

Lancer la requête suivante au serveur pour récupérer un token :

    $ curl -X POST http://localhost/login -d username=username -d password=password

Cet utilisateur correspond à celui importé par défaut dans les fixtures.

Refaire un appel à l'API en renseignant le token dans le header `Authorization` :

    $ curl --header "Authorization: Bearer {token}" http://localhost

Vous devriez avoir la réponse suivante de l'API `{"@context":"/contexts/Entrypoint","@id":"/","@type":"Entrypoint","account":"/accounts","operation":"/operations","tag":"/tags"}`.

L'API est désormais utilisable !

Il est également possible d'accéder à une interface web permettant de visualiser la structure de l'API et d'effectuer des requêtes de test.
Pour cela, installer un module sur votre navigateur favori permettant d'ajouter des headers à la volée (par exemple [Modify Headers][3] pour Firefox).
Puis, ajouter le header `Authorization` avec le token et accéder à l'URL `http://localhost/docs` dans votre navigateur

Déploiement
-----------

L'outil [Deployer][4] est utilisé pour effectuer les déploiements de l'application.

Dans un premier temps, assurez-vous que votre serveur respecte les [pré-requis Symfony][5].

Puis copier le fichier `servers.yml.dist` vers `servers.yml` et renseigner les paramètres de vos différents environnements (cf. [section Servers de la doc de Deployer][6]).

Connectez-vous au serveur MySQL en root et créer un nouvel utilisateur et la base de données associée :

    $ create database mybank;
    $ create user 'mybank'@'localhost' identified by 'password';
    $ grant all on mybank.* to 'mybank';
    
A partir de l'environnement local, rentrer ensuite dans le conteneur Docker :

    $ docker-compose exec api bash

Lancer le déploiement avec la commande suivante :

    $ vendor/bin/dep deploy environment -vvv

Le paramètre `environment` doit correspondre à une clé de votre fichier `servers.yml`.

Suite au déploiement, pour créer un nouvel utilisateur, se positionner dans le répertoire de déploiement sur le serveur et exécuter les commandes suivantes :

    $ chmod u+x current/bin/console
    $ current/bin/console fos:user:create --env=prod

Crédits
-------

Créé par [Pierre Lecavelier][3]. 

[1]: https://github.com/plecavelier/mybank-ui.git
[2]: https://api-platform.com/
[3]: http://pierre.crashdump.net
[4]: https://deployer.org/
[5]: http://symfony.com/doc/current/reference/requirements.html
[6]: https://deployer.org/docs/servers
