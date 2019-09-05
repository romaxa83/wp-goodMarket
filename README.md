<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template</h1>
    <br>
</p>

Yii 2 Advanced Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Build Status](https://travis-ci.org/yiisoft/yii2-app-advanced.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-app-advanced)

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```

[![pipeline status](https://t-me.pp.ua/wi1w/webserver/badges/7.1-opencart/pipeline.svg)](https://t-me.pp.ua/wi1w/webserver/commits/7.1-opencart)

# Deploying with Docker

## Copy required files from dist

```
$ cp .env.dist .env
$ cp traefik.toml.dist traefik.toml
```

### Set vars in env & traefik

## Set required perm to acme for cert generate

```
$ sudo chmod 400 acme.json
```

## Login to registry (hub.t-me.pp.ua)

```
$ docker login REGISTRY
```

## Pull and run container

```
$ docker-compose up -d
```

## Dump db

```
$ set -a && . ./.env && set +a && docker exec -ti $(docker ps -f name=goodmarket_mariadb_ -q) sh -c "mysqldump -u$MYSQL_USER -p$MYSQL_PASSWORD -hmariadb $MYSQL_DATABASE > /docker-entrypoint-initdb.d/dump.sql"
```

#Codeception Test 

```
www/codeception.yml - добавляем namespace (прм - backend/modules/blog)

саму структуру тестов берем с любого метода 

\Codeception\Util\Debug::debug(<data>); - вывод в консоль при выполнений (нужно к команде вызова добавить флаг -d )