# Laravel Mandrill driver

This package re-enables Mandrill driver functionality using the Mail facade in Laravel 6+.

## Install

To install the package in your project, you need to require the package via composer:

```sh
composer require mixisLv/laravel-mandrill-driver
```

## Configure

To use the Mandrill driver, set the `MAIL_DRIVER` environment variable to "mandrill". Next, update the `config/services.php` configuration file to include the following options:

```php
'mandrill' => [
    'secret' => env('MANDRILL_SECRET'),
],
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email info@mixis.lv instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
