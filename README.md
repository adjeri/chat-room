# Chat room

Laravel and Vue.js based app for chat with Pusher.

## Features

- Text chat
- Video chat

## Requirement

- Create a [Pusher](https://pusher.com/) account if not yet done. You can use the sandbox plan (free)
- Create a new app on Pusher

# Setup

- Clone the project
- Browse into the project folder
- Create an environment file by making a copy of the `.env.example` file
```
cp .env.example .env
```
- Generate a key
```
php artisan key:generate
```
- Install `composer` packages
```
composer install
```
- Open the `.env` file and configure the database and pusher accordingly

```
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```
```
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1
```
- Run the migrations
```
php artisan migrate
```

- Install `npm` packages
```
npm install
```
```
npm run dev
```
- Serve your app
```
php artisan serve
```
- Open the app in your browser via the link provided by the previous command
- Register as a new user
- Login and enjoy
