## About Foodics-task

A task to place orders and decrease stock amount of its products' ingredients.

## Tech

This is built using laravel 9, typically should run using php 8.0 - 8.2.

## Installation
### Using PHP/Composer:
```
composer install
cp .env.example .env                  
php artisan key:generate
```

### Using docker:
```
docker run --rm \
    -v "$(pwd)":/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    bash -c "composer install && cp .env.example .env && php artisan key:generate"
```

# Running

### Using sail
```
./vendor/bin/sail up
```
```
./vendor/bin/sail test
```
## Contact

[ayahiacs@gmail.com](mailto:ayahiacs@gmail.com).