# author-reports
Statistics reporting system for authors based in Laravel 5.4.

## Installation
The following extensions are required: `php-gd`, `php-xml`, `php-mbstring`.

Install all dependencies
```sh
composer install
npm install
```

All CSS and JS are compiled using
```sh
npm run dev
```

In production mode run the following instead, it will generate minimized files
```sh
npm run production
```

## Configuration

### Public Directory
Configure the web server's document root to be the `public` directory, the `index.php` file there will handle all requests.

### Directory Permissions
All directories within `storage` and `bootstrap/cache` must be writable by the web server.

### Environment
Copy `.env.example` into `.env` and configure accordingly. Then set up the Application key using:

```sh
php artisan key:generate
```

### Database Migrations
All migrations are located in `database/migrations`, to run them do:
```sh
php migrate
```
