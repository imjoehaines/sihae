# Sihae [![Build Status](https://travis-ci.org/imjoehaines/sihae.svg)](https://travis-ci.org/imjoehaines/sihae) [![StyleCI](https://styleci.io/repos/42362618/shield)](https://styleci.io/repos/42362618)

[![Sihae home page](screenshot.png)](https://raw.githubusercontent.com/imjoehaines/sihae/master/screenshot.png)

## Setup
- `composer install`
- `cp .env.example .env`
- `php vendor/bin/doctrine orm:schema-tool:create`
- `php vendor/bin/doctrine-migrations migrations:migrate`

## Deploying
- `composer install --no-dev`
- `php vendor/bin/doctrine-migrations migrations:migrate`
- `rm data/cache/router.php`
