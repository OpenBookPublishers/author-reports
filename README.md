[![Release](https://img.shields.io/github/release/OpenBookPublishers/author-reports.svg?colorB=58839b)](https://github.com/OpenBookPublishers/author-reports/releases) [![License](https://img.shields.io/github/license/OpenBookPublishers/author-reports.svg?colorB=ff0000)](https://github.com/OpenBookPublishers/author-reports/blob/master/LICENSE)

# author-reports
Statistics reporting system for authors based in Laravel 5.4.

## Installation
The following extensions are required: `php-gd`, `php-xml`, `php-mbstring`.

### Dependencies
```sh
composer install
npm install
```

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
php artisan migrate
```

## Production Deployment

### JS and CSS Compilation
Compile all CSS and JS into minimized files using elixir.
```sh
npm run production
```

### Configuration Caching
The following command will cache all configuration files for faster access.
```sh
php artisan config:cache
```

## Development Deployment

### JS and CSS Compilation
Compile all CSS and JS.
```sh
npm run development
```

### Local Development Server
You may start a development server at `http://localhost:8000` using:
```sh
php artisan serve
```
