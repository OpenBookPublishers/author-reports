# author-reports
Tiny PHP framework to generate author reports

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

Copy `.env.example` into `.env` and configure accordingly.

Point the server to `/public`, which is the document root.
