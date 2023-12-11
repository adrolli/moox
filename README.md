# Moved

This project has moved to https://github.com/mooxphp/moox/

## Installation

```bash

git clone https://github.com/adrolli/moox

composer install

# copy and adjust .env

php artisan migrate

php artisan make:filament-user

```

## Transformer

The transformer is able to create new composerable packages and populate them with Filament resources.

### Prepare

The `moox:prepare` command creates new package skeletons.

## Transform

The `moox:transform` command copies associated Filament resources including model, migration and relations, creates a Filament plugin and updates config, translation and readme.

## License

Moox is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
