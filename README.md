MyBank API
==========

API pour gérer les comptes bancaires, les opérations et les catégories de l'application MyBank.
Cette API est utilisée par le frontal Angular [MyBank UI][1].

Technologies
------------

Cette API repose sur le framework [API Platform][2] facilitant la création d'API REST.

Pré-requis
----------

* PHP 7
* MySQL

Installation
------------

Faire un clone du dépôt et se positionner dans le répertoire créé :

    $ git clone https://github.com/plecavelier/mybank-api.git
    $ cd mybank-api

Générer les clés SSH nécessaires pour l'authentification JWT :

    $ mkdir var/jwt
    $ openssl genrsa -out var/jwt/private.pem -aes256 4096
    $ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem

Lancer l'installation avec composer et saisir les paramètres adéquats :

    $ composer install

Le paramètre `jwt_key_pass_phrase` doit correspondre au mot de passe choisi durant la création des clés SSH.

Créer la base de données :

    $ bin/console doctrine:database:create
    $ bin/console doctrine:schema:create

Concernant les données, il est possible de créer :
* un utilisateur avec la commande `$ bin/console fos:user:create`
* ou un jeu de tests en important les fixtures `$ bin/console doctrine:fixtures:load`

Lancer le serveur HTTP :

    $ bin/console server:run

Tests
-----

Faire un appel à l'API :

    $ curl http://127.0.0.1:8000

Vous devriez obtenir la réponse `{"code":401,"message":"JWT Token not found"}` car l'authentification à l'API n'a pas été effectuée.

Lancer la requête suivante au serveur pour récupérer un token :

    $ curl -X POST http://localhost:8000/login -d username=username -d password=password

Cet utilisateur correspond à celui importé par les fixtures.
Si vous avez créé votre propre utilisateur, remplacer les paramètres `username` et `password`.

Refaire un appel à l'API en renseignant le token dans le header `Authorization` :

    $ curl --header "Authorization: Bearer {token}" http://127.0.0.1:8000

Vous devriez avoir la réponse suivante de l'API `{"@context":"/contexts/Entrypoint","@id":"/","@type":"Entrypoint","account":"/accounts","operation":"/operations","tag":"/tags"}`.

l'API est désormais utilisable !

Il est également possible d'accéder à une interface web permettant de visualiser la structure de l'API et d'effectuer des requêtes de test.
Pour cela, installer un module sur votre navigateur favori permettant d'ajouter des headers à la volée (par exemple [Modify Headers][3] pour Firefox).
Puis, ajouter le header `Authorization` avec le token et accéder à l'URL `http://127.0.0.1:8000` dans votre navigateur.

Credits
-------

Créé par [Pierre Lecavelier][3]. 

[1]:  https://github.com/plecavelier/mybank-ui.git
[2]:  https://api-platform.com/
[3]:  http://pierre.crashdump.net
