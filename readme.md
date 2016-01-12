[![Stories in Ready](https://badge.waffle.io/imjoehaines/sihae.png?label=ready&title=Ready)](https://waffle.io/imjoehaines/sihae)
# Sihae [![Build Status](https://travis-ci.org/imjoehaines/sihae.svg)](https://travis-ci.org/imjoehaines/sihae) [![StyleCI](https://styleci.io/repos/42362618/shield)](https://styleci.io/repos/42362618)

[![Sihae home page](screenshot.png)](https://raw.githubusercontent.com/imjoehaines/sihae/master/screenshot.png)

*Sidebar image from [NASA's HubbleSite](http://hubblesite.org/gallery/album/pr2009025i)*

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
