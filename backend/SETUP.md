# Backend Setup Guide

## 1) Laravel project yaratish

```bash
composer create-project laravel/laravel backend
cd backend
composer require tymon/jwt-auth
composer require guzzlehttp/guzzle
php artisan key:generate
```

## 2) `.env` sozlamalar

```env
APP_NAME="Arzon Yegulik"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=arzonyegulik
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=
```

## 3) JWT sozlamalari

```bash
php artisan jwt:secret
```

## 4) Database migrations va model yaratish

```bash
php artisan make:model User -m
php artisan make:model Restaurant -m
php artisan make:model Food -m
php artisan make:model Order -m
php artisan make:model OrderItem -m
php artisan make:model Address -m
php artisan make:model Payment -m
```

## 5) Auth API

- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `POST /api/auth/refresh`

## 6) Restaurant API

- `GET /api/restaurants`
- `GET /api/restaurants/{id}`
- `GET /api/restaurants/{id}/foods`
- `GET /api/restaurants/search`

## 7) Order API

- `POST /api/orders`
- `GET /api/orders`
- `GET /api/orders/{id}`
- `PUT /api/orders/{id}`
- `POST /api/orders/{id}/cancel`

## 8) Payment API

- `POST /api/payments/click`
- `POST /api/payments/payme`
- `POST /api/payments/callback`

## 9) Next milestone

Keyingi bosqich:
- User auth
- restaurant + food migrations
- order workflow
- payment integration

## 10) Recommended package list

```bash
composer require laravel/sanctum
composer require spatie/laravel-permission
composer require intervention/image
composer require maatwebsite/excel
```

## 11) Safe default development workflow

```bash
php artisan migrate
php artisan serve
```

## 12) Suggested project structure

```text
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   ├── Models/
│   └── Services/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
├── .env.example
├── composer.json
└── README.md
```
