Objectifs
----------
[TODO]

Prérequis
------------
-  PHP >= 7.4
-  [composer](https://getcomposer.org/)
-  [symfony](https://symfony.com/download)
-  [yarn](https://classic.yarnpkg.com/en/docs/install/)

Installation
------------
```
git clone git@github.com:sdeparte/mercurePoc.git
cd mercurePoc
composer install
yarn install
```

Exécuter en API
----------
```
symfony serve
yarn encore dev-server --hot
```

Documentation de l'API
----------
http://localhost:8000/api/doc
```
Login : Admin
Password : Admin
```

Lancement des tests
-----------------------------
```
# Lancement des tests unitaires
composer test
# Lancement des tests unitaires avec "coverage report"
composer test:coverage
```