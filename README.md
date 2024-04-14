<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Installation

To run a local copy please follwo those steps:

Clone repository:

git clone https://github.com/nev3rfind/laravel-csv.git

Copy env.example to .env and configure environment variables

```
cp .env.example .env

```

Start Laravel Sail and also run Docker (need to enable there WSL integration)
Also, I ran everything in Ubuntu

```
./vendor/bin/sail up -d

```

Generate application key:

```
./vendor/bin/sail artisan key:generate
```

Create migration for queue jobs (actually already there, it was only ran initially)

```
php artisan queue:table
```

Migrations:

```
./vendor/bin/sail artisan migrate
```

Run queue worker:

```
./vendor/bin/sail artisan queue:work
```

Also run 3 tests:

```
php artisan test
```
