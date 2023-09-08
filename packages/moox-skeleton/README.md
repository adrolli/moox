# Moox Skeleton

This template is used for generating all Moox packages.

## Installation

You can install the package via composer:

```bash
composer require moox/skeleton
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="moox-skeleton-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="moox-skeleton-config"
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](https://github.com/mooxphp/moox/security/policy) on how to report security vulnerabilities.

## Credits

- Alf Drollinger - alf@drollinger.info
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
