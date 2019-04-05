<h1>Newt</h1>

[![MadeWithLaravel.com shield](https://madewithlaravel.com/storage/repo-shields/1049-shield.svg)](https://madewithlaravel.com/p/plur/shield-link)
[![StyleCI shield](https://github.styleci.io/repos/146186200/shield)](https://github.styleci.io/repos/146186200)
[![GitHub license](https://img.shields.io/github/license/realodix/newt.svg)](https://github.com/realodix/newt/blob/master/LICENSE)
[![GitHub license](https://img.shields.io/github/license/realodix/newt.svg?style=flat-square)](https://github.com/realodix/newt/blob/master/LICENSE)

**Warning: This software is still in development,** so we don’t recommend you run it on a production site.

Newt is a free, open-source and easy-to-use but powerful URL shortener. It allows you to host your own URL shortener, and gives you many useful features.

### Features
* URL Shortener.
* Customized short URL's(ex: example.com/laravel).
* QR code generator for each short link.
* Sortable list of shortened URLs.
* Written in PHP and [Laravel 5.8](https://laravel.com/docs/5.8).
* Datatables with server-side processing.
* Modern and simple interface.
* Made with :heart: &amp; :coffee:.

### Screenshots

| ![screenshot](https://i.imgur.com/KrLJEd1.jpg) | ![screenshot](https://i.imgur.com/XILcFpO.jpg) | ![screenshot](https://i.imgur.com/n3NVd52.jpg) | ![screenshot](https://i.imgur.com/imRINvR.jpg) |
|-|-|-|-|


## Requirements
- [All requirements by Laravel](https://laravel.com/docs/installation#server-requirements) - PHP >= 7.1.3, [Composer](https://getcomposer.org/) and such.
- MySQL or MariaDB.


## Quick Start
### Installation Instructions
1. Run `composer install`.

2. Rename `.env.example` file to `.env` or run `cp .env.example .env`.

   Update `.env` to your specific needs. Don't forget to set `DB_USERNAME` and `DB_PASSWORD` with the settings used behind.

3. Run `php artisan key:generate`.

4. Run `php artisan migrate --seed`.

5. Run `php artisan serve`.

   After installed, you can access http://localhost:8000 in your browser.

6. Login

   | Email           | Username | Password | Access       |
   |-----------------|----------|----------|--------------|
   | admin@newt.test | admin    | admin    | Admin Access |
   | user@newt.test  | user     | user     | User Access  |

### Compiling assets with Laravel Mix
#### Using Yarn:
1. From the projects root folder run `yarn`
2. Run `yarn dev` or `yarn prod`
  * *You can watch assets with `yarn watch`*

#### Using NPM:
1. From the projects root folder run `npm install`
2. Run `npm run dev` or `npm run prod`
  * *You can watch assets with `npm run watch`*


## Contributing
Any useful suggestion and PR are welcomed. If you would like to contribute, please do the following:

1. Fork the repository.
2. Hack on a separate topic branch created from the latest `master`.
3. Commit and push the topic branch.
4. Make a pull request.
5. Welcome to the club :sunglasses: and thank you for helping out!


## License
Newt is an open-source software licensed under the [MIT license](https://github.com/realodix/newt/blob/master/LICENSE).
