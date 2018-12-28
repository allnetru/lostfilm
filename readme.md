# Lostfilm Laravel Backend

## Requirements
 - PHP 7.1:
   Laravel 5.6 or greater
 - MySQL/PostgreSQL/MS SQL Server

## Environment

### PHP 7.1
```
sudo add-apt-repository ppa:ondrej/php && sudo apt-get update

sudo apt-get install php7.1-fpm php7.1-cli php7.1-mysql php7.1-mcrypt php7.1-json php7.1-mbstring php7.1-gd php7.1-curl php7.1-xml php7.1-phpdbg php7.1-zip
sudo apt-get install php-memcached
```

### Memcached
```
sudo apt-get install memcached
```

### MySQL
```
sudo apt-get install mysql-server-5.7
mysql -uroot -p -e 'create database lostfilm;'
```

## Development environment

### Composer
Install composer https://getcomposer.org/

### Git
```
sudo apt-get install git
```

### Project Backend
```
git clone https://github.com/allnetru/lostfilm.git
cd lostfilm

sudo chmod -R 755 storage

composer install

cp .env.example .env
php artisan key:generate
ln -rs storage/app/public public/storage

php artisan migrate
php artisan clear-compiled && php artisan config:cache && php artisan queue:restart && php artisan route:cache
```

## Tests

### Backend

Global tests
```
./vendor/bin/phpunit
```

Specify tests
```
./vendor/bin/phpunit --filter=UserTest
```

Coverage tests
```
phpdbg -qrr ./vendor/bin/phpunit --coverage-html=./storage/coverage
```

## Serve
```
php artisan serve
```
Or better setup your own nginx virtual host with php-fpm.

