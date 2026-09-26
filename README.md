# 🍽️ Arzon Yegulik - Affordable Food Delivery Platform

**O'zbekiston aholisiga arzon va sifatli ovqat ta'minot hizmati**

## 📱 Loyiha Strukturasi

```
arzonyegulik/
├── mobile/              # Flutter Mobil Ilova
│   ├── lib/
│   ├── assets/
│   ├── pubspec.yaml
│   └── ...
├── backend/             # Laravel Backend
│   ├── app/
│   ├── routes/
│   ├── database/
│   ├── .env
│   └── ...
├── docs/                # Dokumentatsiya
└── README.md
```

## 🎯 Asosiy Xususiyatlar

### 📱 Flutter Mobile (iOS/Android)
- Material Design 3 + Green (#2ECC71) accent
- User authentication & profiles
- Restaurant & food catalog
- Real-time order tracking
- Payment integration (Stripe/Click/Payme)
- Chat with delivery person
- Favorites & saved addresses

### 🔧 Laravel Backend (REST API)
- JWT authentication
- Restaurant management
- Order management
- Payment processing
- User profiles
- Real-time notifications
- Admin panel

### 💾 MySQL Database
- Users (customers, restaurants, drivers)
- Restaurants & menu items
- Orders & order items
- Payments & transactions
- Addresses
- Reviews & ratings

---

## 🚀 Boshlash Uchun

### Mobile (Flutter)
```bash
cd mobile
flutter pub get
flutter run
```

### Backend (Laravel)
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

---

## 📊 Monetizatsiya Modeli

1. **Restaurant Commission**: 15-20%
2. **Delivery Fee**: 5,000-15,000 so'm
3. **Premium Subscription**: Restaurants uchun reklama
4. **In-App Ads**: Partner brands uchun

---

## 📝 Status

- ✅ Loyiha struktura
- ⏳ Flutter mobile setup
- ⏳ Laravel backend setup
- ⏳ Database schema
- ⏳ Authentication
- ⏳ API endpoints
- ⏳ Mobile screens

**Version**: 0.1.0 | **Stage**: 🔧 Development
