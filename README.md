# Sihae [![Build Status](https://travis-ci.org/imjoehaines/sihae.svg)](https://travis-ci.org/imjoehaines/sihae) [![StyleCI](https://styleci.io/repos/42362618/shield)](https://styleci.io/repos/42362618)

Sihae is a PHP 7.1+ blog engine built with Slim Framework and Doctrine ORM.

[![Sihae home page](screenshot.png)](https://raw.githubusercontent.com/imjoehaines/sihae/master/screenshot.png)

## Setup
```sh
$ composer install
$ cp .env.example .env
# configure .env with database connection details
# create a database matching the "DB_NAME" in your .env
$ php vendor/bin/doctrine-migrations migrations:migrate
```

## Deploying
```sh
$ composer install --no-dev
$ php vendor/bin/doctrine-migrations migrations:migrate

# if a route has changed, remove the route cache file
$ rm data/cache/router.php
```
