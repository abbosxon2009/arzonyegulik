# 🛠️ Development Guide

## Requirements

### Flutter Mobile
- Flutter 3.x
- Dart 3.x
- Android Studio / Xcode
- Visual Studio Code

### Laravel Backend
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js (optional, for frontend assets)

---

## Flutter Mobile Setup

```bash
# Project yaratish
flutter create --org com.arzonyegulik mobile

# Dependencies o'rnatish
cd mobile
flutter pub get

# Run on emulator
flutter run

# Build APK
flutter build apk --release

# Build iOS
flutter build ios --release
```

### Main Dependencies
```yaml
# State Management
provider: ^6.0.0
riverpod: ^2.4.0

# Network
http: ^1.1.0
dio: ^5.3.0

# Database & Storage
hive: ^2.2.0
sqflite: ^2.3.0

# UI
flutter_screenutil: ^5.9.0
getwidget: ^3.0.0

# Auth
jwt_decoder: ^2.0.0
flutter_secure_storage: ^9.0.0

# Maps & Location
google_maps_flutter: ^2.5.0
geolocator: ^10.0.0

# Payment
flutter_stripe: ^10.0.0

# Notifications
firebase_messaging: ^14.6.0
flutter_local_notifications: ^16.1.0

# Other
intl: ^0.19.0
shared_preferences: ^2.2.0
image_picker: ^1.0.0
```

---

## Laravel Backend Setup

```bash
# Project yaratish
composer create-project laravel/laravel backend

cd backend

# .env fayl konfiguratsiya
cp .env.example .env
php artisan key:generate

# Database o'rnatish
php artisan migrate
php artisan db:seed

# Server ishga tushirish
php artisan serve
```

### Main Packages
```bash
# API & Authentication
composer require laravel/sanctum
composer require tymon/jwt-auth

# File Storage
composer require league/flysystem-aws-s3-v3

# Validation
composer require illuminate/validation

# Payment Integration
composer require stripe/stripe-php

# Notification
composer require illuminate/notifications

# Admin Panel
composer require filament/filament

# Testing
composer require --dev phpunit/phpunit
composer require --dev laravel/pint
```

---

## Database Schema

```sql
-- Users (Customers)
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255),
    avatar_url VARCHAR(255),
    created_at TIMESTAMP
);

-- Restaurants
CREATE TABLE restaurants (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    description TEXT,
    owner_id BIGINT,
    rating FLOAT,
    logo_url VARCHAR(255),
    cover_image VARCHAR(255),
    address TEXT,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    created_at TIMESTAMP
);

-- Foods/Menu Items
CREATE TABLE foods (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    restaurant_id BIGINT,
    name VARCHAR(255),
    description TEXT,
    price DECIMAL(10, 2),
    image_url VARCHAR(255),
    category VARCHAR(100),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

-- Orders
CREATE TABLE orders (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT,
    restaurant_id BIGINT,
    total_amount DECIMAL(10, 2),
    delivery_fee DECIMAL(10, 2),
    status ENUM('pending', 'confirmed', 'preparing', 'on_the_way', 'delivered', 'cancelled'),
    delivery_address TEXT,
    created_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

-- Order Items
CREATE TABLE order_items (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    order_id BIGINT,
    food_id BIGINT,
    quantity INT,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (food_id) REFERENCES foods(id)
);

-- Payments
CREATE TABLE payments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    order_id BIGINT,
    amount DECIMAL(10, 2),
    method ENUM('card', 'click', 'payme', 'cash'),
    status ENUM('pending', 'completed', 'failed'),
    transaction_id VARCHAR(255),
    created_at TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- Addresses
CREATE TABLE addresses (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT,
    label VARCHAR(100),
    street VARCHAR(255),
    city VARCHAR(100),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    is_default BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Reviews
CREATE TABLE reviews (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    food_id BIGINT,
    user_id BIGINT,
    rating INT,
    comment TEXT,
    created_at TIMESTAMP,
    FOREIGN KEY (food_id) REFERENCES foods(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## API Endpoints

### Authentication
- `POST /api/auth/register` - Register
- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout
- `GET /api/auth/me` - Get current user

### Restaurants
- `GET /api/restaurants` - List all
- `GET /api/restaurants/{id}` - Get details
- `GET /api/restaurants/{id}/foods` - Get foods
- `GET /api/restaurants/search?q=name` - Search

### Foods
- `GET /api/foods` - List all
- `GET /api/foods/{id}` - Get details
- `GET /api/foods/search?q=name` - Search

### Cart
- `GET /api/cart` - Get cart
- `POST /api/cart/add` - Add item
- `DELETE /api/cart/remove/{id}` - Remove item
- `POST /api/cart/clear` - Clear cart

### Orders
- `GET /api/orders` - List user orders
- `POST /api/orders` - Create order
- `GET /api/orders/{id}` - Get order details
- `GET /api/orders/{id}/track` - Track order
- `PUT /api/orders/{id}/cancel` - Cancel order

### User
- `GET /api/user/profile` - Get profile
- `PUT /api/user/profile` - Update profile
- `GET /api/user/addresses` - Get addresses
- `POST /api/user/addresses` - Add address
- `DELETE /api/user/addresses/{id}` - Delete address

### Payments
- `POST /api/payments/process` - Process payment
- `GET /api/payments/{id}/status` - Payment status

---

## Code Conventions

### Flutter
- Named routing
- Provider/Riverpod for state management
- Repository pattern for API calls
- Material Design 3 + Green theme

### Laravel
- RESTful API design
- Resource classes for responses
- Request validation
- Model relationships
- Service layer for business logic

---

## Git Workflow

```bash
# Feature branch
git checkout -b feature/feature-name
git commit -m "feat: add feature"
git push origin feature/feature-name

# Merge to main
git checkout main
git merge feature/feature-name

# Tags
git tag v0.1.0
git push origin v0.1.0
```

---

## Testing

### Flutter
```bash
flutter test
```

### Laravel
```bash
php artisan test
```

---

## Deployment

### Mobile
- Build APK/IPA
- Upload to Play Store / App Store

### Backend
- Deploy to VPS/Cloud
- Setup SSL certificate
- Configure database
- Setup environment variables

---

**Last Updated**: 2026-09-26
