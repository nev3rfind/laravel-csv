<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Application look:
![image](https://github.com/nev3rfind/laravel-csv/assets/57880277/0815d20b-fd80-4d02-bd5f-cb9536b760b0)


## Installation

To run a local copy please follow those steps:

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

![image](https://github.com/nev3rfind/laravel-csv/assets/57880277/e8920dfa-0d18-4273-a2db-616d1574d81e)


## API Testing

I used POSTMAN

First need to register:
![image](https://github.com/nev3rfind/laravel-csv/assets/57880277/71252f11-06a5-46c0-b8db-3c8a03bdb5a8)

Then login to get a token (Bearer):
![image](https://github.com/nev3rfind/laravel-csv/assets/57880277/a2032db9-9a79-4fc0-b0f4-e3f2bb76fefd)

Add authorization token and fetch product:
![image](https://github.com/nev3rfind/laravel-csv/assets/57880277/70d6fa77-ae9a-48a1-b558-3ffed55ff62f)


