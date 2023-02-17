![screenshot](https://i.imgur.com/MeZvgiz.png)

[![LaravelVersion](https://img.shields.io/badge/Laravel-10-f56857.svg?style=flat-square)](https://laravel.com/docs/10.x/releases#laravel-10)
![PHPVersion](https://img.shields.io/badge/PHP-8.1-777BB4.svg?style=flat-square)
[![GitHub license](https://img.shields.io/github/license/realodix/newt.svg?style=flat-square)](https://github.com/realodix/newt/blob/master/LICENSE)
![Build Status](https://github.com/realodix/urlhub/actions/workflows/tests.yml/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/realodix/urlhub/badge.svg?branch=master)](https://coveralls.io/github/realodix/urlhub) <br>
[![MadeWithLaravel.com shield](https://madewithlaravel.com/storage/repo-shields/1049-shield.svg)](https://madewithlaravel.com/p/plur/shield-link)

> **Warning: UrlHub is still in development**, constantly being optimized and isn't still stable enough to be used in production environments.

> **Whatever your idea, feel free to send a pull request** 😃

UrlHub was created, and is maintained by [Budi Hermawan](https://github.com/realodix), and is an open-source, easy-to-use but powerful URL shortener. It allows you to host your own URL shortener, and gives you many useful features.

### Features
- **Reliable link shortner:** Does the job really well and it is very consistent. UrlHub is definitely one of the most reliable self-hosted URL shortener out there. Would recommend easily.
- **Custom URLs (ex: example.com/laravel):** Allows users to create more descriptive short URLs rather than a randomly generated mix of letters and numbers.
- **QR code generator for each short link:** The fastest way to access to this data is most likely opening the link from a phone. Though short URLs are handy for typing, a more convenient approach to transfer a web link to a mobile phone is through QR codes scanning.
- **Edit or delete your links:** You can change both the address and the destination URL. You can even delete your URL, a feature that is not available with most shorteners.
- **View where link goes:** It's nice to see where the link goes before clicking on it so you can avoid sketchy links.
- **IP anonymization (or IP masking) [optional]:** Anonymizes visitor addresses as soon as technically feasible at the earliest possible stage of the collection network. The full IP address is never written to disk in this case. This feature is designed to help site owners comply with their own privacy policies, recommendations from local data protection authorities and legal regulations like the GDPR, which may prevent the storage of full IP address information.
- **Power of customisation:** Do you want your site to be just for your use, so no one can register? No problem. It's in the configuration. Users must be registered to create Short URL? That's okay. It's in the configuration. From configuration file, you can edit pretty everything of your website. The choice is yours.
- **Sortable list of shortened URLs.**
- **Written in [PHP](https://www.php.net/) and [Laravel 10](https://laravel.com/docs/10.x/releases#laravel-10).**
- **Modern and simple interface.**
- **Made with :heart: &amp; :coffee:.**


## Requirements
* All requirements by [Laravel](https://laravel.com/docs/installation#server-requirements) & dependencies - PHP >= 8.1, [Composer](https://getcomposer.org/) and such.


## Quick Start
### Installation Instructions
1. Run `composer install`.

2. Rename `.env.example` file to `.env` or run `cp .env.example .env`.

   Update `.env` to your specific needs. Don't forget to set `DB_USERNAME` and `DB_PASSWORD` with the settings used behind.

3. Run `php artisan key:generate`.

4. Run `php artisan migrate --seed`.

5. Run `php artisan serve`.

6. Login

   | Email             | Username | Password | Access       |
   |-------------------|----------|----------|--------------|
   | admin@urlhub.test | admin    | admin    | Admin Access |
   | user@urlhub.test  | user     | user     | User Access  |


### Compiling assets with Laravel Mix

#### Using Yarn
1. `yarn`
2. `yarn dev` or `yarn prod`

    *You can watch assets with `yarn watch`*

#### Using NPM
1. `npm install`
2. `npm run dev` or `npm run prod`

    *You can watch assets with `npm run watch`*

   Please note that this project uses Yarn as the package manager, so you can't find the package-lock.json file that is needed by NPM.

## Contributing
The people who contribute to UrlHub do so for the love of open source, our users and ecosystem, and most importantly, pushing the web forward together. Developers like you can help by contributing to rich and vibrant documentation, issuing pull requests to help us cover niche use cases, and to help sustain what you love about UrlHub.

Anybody can help by doing any of the following:
- Ask your employer to use UrlHub in projects.
- Contribute to the core repository.

### Running Tests

From the projects root folder run
- `php artisan test`
- or `composer test`
- or `./vendor/bin/phpunit`

![screenshot](https://i.imgur.com/MjEbKEG.png)


## Bug Report
If you've found a problem in UrlHub which is not a security risk, do a search on [GitHub under Issues](https://github.com/realodix/urlhub/issues) in case it has already been reported. If you are unable to find any open GitHub issues addressing the problem you found, your next step will be to [open a new one](https://github.com/realodix/urlhub/issues/new/choose).

Your issue should contain a title and a clear description of the issue. You should also include as much relevant information as possible and a code sample that demonstrates the issue.

The goal of a bug report is to make it easy for yourself - and others - to replicate the bug and develop a fix. Remember, bug reports are created in the hope that others with the same problem will be able to collaborate with you on solving it.

Do not expect that the bug report will automatically see any activity or that others will jump to fix it. Creating a bug report serves to help yourself and others start on the path of fixing the problem.


## License
UrlHub is an open-source software licensed under the [MIT license](https://github.com/realodix/urlhub/blob/master/LICENSE).
