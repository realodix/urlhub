<h4>Warning: The Plur is still under development.</h4>

![screenshot](https://i.imgur.com/rHJQyQz.jpg)
<h1 align="center">A modern and robust URL shortener built with Laravel.</h1>

Plur is a open-source link shortening web application. It allows you to host your own URL shortener, and to brand your URLs. Plur is especially easy to use, and provides a modern, themable feel.

### Features
* Written in PHP and powered by the Laravel 5.7.
* Modern and simple interface.
* Made with :heart: &amp; :coffee:.

## Requirements
- [All requirements by Laravel](https://laravel.com/docs/installation#server-requirements) - PHP, OpenSSL, [Composer](https://getcomposer.org/) and such.
- MySQL or MariaDB.


## Quick Start
1. Run `composer install`

2. Rename `.env.example` file to `.env`

   Update `.env` to your specific needs. Don't forget to set `DB_USERNAME` and `DB_PASSWORD` with the settings used behind.

3. Run `php artisan key:generate`

4. Run `php artisan migrate --seed`

5. Run `php artisan serve`.

   After installed and you can access http://localhost:8000 in your browser.

6. Login

   **E-Mail**: admin@plur.test | **Password**: admin <br>
   **E-Mail**: user@plur.test | **Password**: user


## To Do's
- [ ] URL shortener on admin dashboard.
- [ ] Realtime check for available shortlink using ajax & [TypeWatch](https://github.com/dennyferra/TypeWatch).
- [ ] Use [server-side.options](https://datatables.net/examples/data_sources/server_side) provided by the DataTable.


## License
The Plur is open-sourced software licensed under the [MIT license](https://github.com/realodix/plur/blob/master/LICENSE).
