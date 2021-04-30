## Frontend Setup
- Run `npm run install & npm run dev`

## Docker Setup
- Run `cd laradock && cp .env.example .env && docker-compose up -d nginx mysql phpmyadmin workspace`

## Database Setup
- Enter http://localhost:8081 on your browser
- Enter the following credentials: root for Username and Password and mysql for Server
- Create a database and name it twitter_clone with phpMyAdmin

## Laravel Setup
- Run `cd laradock && docker-compose exec workspace bash`
- Run `cp .env.example .env && php artisan key:generate && php artisan migrate`