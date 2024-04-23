<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## How to Install

Run the local server using XAMPP, Laragon, etc... and create a database. We suggest to use database name 'atmakitchen_dev'.

Clone the project

```bash
  git clone https://github.com/iyorima/AtmaKitchen_API.git
```

Go to the project directory

```bash
  cd AtmaKitchen_API
```

Change the branch into your name

```bash
  git checkout -b ＜new-branch＞
```

Install dependencies and generate the app key

```bash
  composer install
  php artisan key:generate
```

Make a migration of database

```bash
  php artisan migrate:fresh
```

Make a seeder of data

```bash
  php artisan db:seed DatabaseSeeder
```

Start the server

```bash
  php artisan serve
```
