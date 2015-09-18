# Sihae [![Build Status](https://travis-ci.org/imjoehaines/sihae.svg)](https://travis-ci.org/imjoehaines/sihae)

[![Sihae home page](screenshot.png)](https://raw.githubusercontent.com/imjoehaines/sihae/master/screenshot.png)

## Setup
- Create a postgres database
- Configure `.env`
- `composer install`
- `npm install`
- `php artisan migrate`

Run tests with `npm test` or individually:
- phpcs: `npm run sniffer`
- Behat: `npm run acceptance`
- phpspec: `npm run unit`
