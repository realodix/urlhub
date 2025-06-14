![screenshot](https://i.imgur.com/MeZvgiz.png)

[![LaravelVersion](https://img.shields.io/badge/Laravel-12-f53003.svg?style=flat-square)](https://laravel.com/docs/12.x/releases#laravel-12)
![PHPVersion](https://img.shields.io/badge/PHP-8.2-777BB4.svg?style=flat-square)
[![GitHub license](https://img.shields.io/github/license/realodix/urlhub.svg?style=flat-square)](https://github.com/realodix/urlhub/blob/master/LICENSE)
![Build Status](https://github.com/realodix/urlhub/actions/workflows/tests.yml/badge.svg)
[![MadeWithLaravel.com shield](https://madewithlaravel.com/storage/repo-shields/1049-shield.svg)](https://madewithlaravel.com/p/plur/shield-link)

> **Whatever your idea, feel free to send a pull request** 😃

UrlHub was created, and is maintained by [Budi Hermawan](https://github.com/realodix), and is an open-source, easy-to-use but powerful URL shortener. It allows you to host your own URL shortener, and gives you many useful features.

### Features
- **Reliable link shortner:** Does the job really well and it is very consistent. UrlHub is definitely one of the most reliable self-hosted URL shortener out there. Would recommend easily.
- **Custom URLs (ex: example.com/laravel):** Allows users to create more descriptive short URLs rather than a randomly generated mix of letters and numbers.
- **QR Code Generation:** The fastest way to access to this data is most likely opening the link from a phone. Though short URLs are handy for typing, a more convenient approach to transfer a web link to a mobile phone is through QR codes scanning.
- **Edit or delete your links:** You can change both the address and the destination URL. You can even delete your URL, a feature that is not available with most shorteners.
- **Device-Aware Redirection**: Redirect mobile visitors to the appropriate URL depending on the operating system.
- **Password protection:** Limit access to the destination URL with a password.
- **Link expiration:** Set the expiration date and time to create temporary short links. Specify the expiration URL where visitors will be redirected after the old link has expired.
- **Parameter Passing**: Forward query parameters from your short link to the destination URL.
- **View where link goes:** It's nice to see where the link goes before clicking on it so you can avoid sketchy links.
- **Dark mode**: Switch to a darker color scheme for a more comfortable experience in low-light environments.
- **Power of customisation:** Do you want your site to be just for your use, so no one can register? No problem. It's in the configuration. Users must be registered to create Short URL? That's okay. It's in the configuration. From configuration file, you can edit pretty everything of your website. The choice is yours.
- **Sortable list of shortened URLs.**
- **Modern and simple interface.**
- **Made with :heart: &amp; :coffee:.**


## Requirements
UrlHub is a Laravel application. That means it requires this setup:

- PHP 8.2 or newer
- A web server like Apache or Nginx
- MySQL / MariaDB / SQLite


## Quick Start
### Installation Instructions
1. Rename `.env.example` file to `.env` or run `cp .env.example .env`.

   Update `.env` to your specific needs.

2. Run `composer install`.

3. Run `php artisan key:generate`.

4. Run `php artisan migrate --seed`.

5. Run `php artisan serve`.

6. Login

   | Email             | Username | Password | Access       |
   |-------------------|----------|----------|--------------|
   | admin@urlhub.test | admin    | admin    | Admin Access |
   | user@urlhub.test  | user     | user     | User Access  |


### Bundle application's assets

1. `npm install`

2.
    ```sh
    # Run the Vite development server...
    npm run dev

    # Build and version the assets for production...
    npm run build
    ```

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

![phpunit_image](https://github.com/user-attachments/assets/3beb22f3-673c-44b3-a2f3-a14172971db7)


## Bug Report
If you've found a problem in UrlHub which is not a security risk, do a search on [GitHub under Issues](https://github.com/realodix/urlhub/issues) in case it has already been reported. If you are unable to find any open GitHub issues addressing the problem you found, your next step will be to [open a new one](https://github.com/realodix/urlhub/issues/new/choose).

Your issue should contain a title and a clear description of the issue. You should also include as much relevant information as possible and a code sample that demonstrates the issue.

The goal of a bug report is to make it easy for yourself - and others - to replicate the bug and develop a fix. Remember, bug reports are created in the hope that others with the same problem will be able to collaborate with you on solving it.

Do not expect that the bug report will automatically see any activity or that others will jump to fix it. Creating a bug report serves to help yourself and others start on the path of fixing the problem.


## License
UrlHub is an open-source software licensed under the [MIT license](https://github.com/realodix/urlhub/blob/master/LICENSE).
