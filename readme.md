![screenshot](https://i.imgur.com/wFCO4HZ.jpg)
<h1 align="center">A modern and robust URL shortener built with Laravel.</h1>

Plur is a open-source link shortening web application. It allows you to host your own URL shortener, and to brand your URLs. Plur is especially easy to use, and provides a modern, themable feel.

## Requirements
- [All requirements by Laravel](https://laravel.com/docs/installation#server-requirements) - PHP, OpenSSL, [Composer](https://getcomposer.org/) and such.
- MySQL or MariaDB.


## Quick Start
1. Run `composer install`

2. Rename `.env.example` file to `.env`

   Update `.env` to your specific needs. Don't forget to set `DB_USERNAME` and `DB_PASSWORD` with the settings used behind.

3. Run `php artisan key:generate`

4. Run `php artisan migrate`

5. Run `php artisan serve`.

   You should now be able to visit http://localhost:8000 in your browser.


## License
The Plur is open-sourced software licensed under the [MIT license](https://github.com/realodix/plur/blob/master/LICENSE).
