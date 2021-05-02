## Pre-requisites
- PHP 7.3 (If you don't have PHP 7.3 and you are on Mac and you have Brew installed, run these commands `brew install php@7.3 && brew unlink php@7.2 && brew link php@7.3`, this is assuming you have PHP 7.2 installed)
- Docker
- NPM
## Vue Setup
- Run `cd twitter-clone && npm run install & npm run dev`

## Docker Setup
- Run `git submodule update --init`
- Run `cd laradock && cp .env.example .env && docker-compose up -d nginx mysql phpmyadmin workspace`

## Database Setup
- Enter http://localhost:8081 on your browser
- Enter the following credentials: root for Username and Password and mysql for Server
- Create a database and name it twitter_clone with phpMyAdmin

## Laravel Setup
- Go back to the root of the project and run `composer install` or `cd /path/to/twitter-clone && composer install`
- Run `composer dump-autoload`
- Run `cp .env.example .env && php artisan key:generate && php artisan jwt:secret`
- Run `cd laradock && docker-compose exec workspace bash`
- Run `php artisan migrate`
