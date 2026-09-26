# 🏗️ Architecture Overview

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Flutter Mobile App                        │
│  (iOS/Android - Material Design 3, Green Theme #2ECC71)     │
└──────────────────────┬──────────────────────────────────────┘
                       │ (REST API, JWT Auth)
                       ▼
┌─────────────────────────────────────────────────────────────┐
│              Laravel REST API Server                         │
│  (Authentication, Orders, Payments, Real-time Updates)     │
└──────────────────────┬──────────────────────────────────────┘
                       │ (SQL Queries)
                       ▼
┌─────────────────────────────────────────────────────────────┐
│                   MySQL Database                             │
│  (Users, Restaurants, Foods, Orders, Payments)             │
└─────────────────────────────────────────────────────────────┘
```

## Mobile App Layers (Flutter)

```
┌─────────────────────────────────┐
│     UI Layer (Screens/Widgets)  │
│  - Home, Restaurant, Food, Cart │
│  - Checkout, Order Tracking     │
│  - Profile, Settings            │
└────────────────┬────────────────┘
                 │
┌────────────────▼────────────────┐
│   State Management (Provider)    │
│  - Auth Provider                │
│  - Cart Provider                │
│  - Order Provider               │
│  - User Provider                │
└────────────────┬────────────────┘
                 │
┌────────────────▼────────────────┐
│   Repository Layer              │
│  - AuthRepository               │
│  - RestaurantRepository         │
│  - OrderRepository              │
│  - PaymentRepository            │
└────────────────┬────────────────┘
                 │
┌────────────────▼────────────────┐
│   API Service Layer             │
│  - HTTP Client (Dio)            │
│  - Interceptors                 │
│  - Error Handling               │
└────────────────┬────────────────┘
                 │
              (API Calls)
```

## Backend Layers (Laravel)

```
┌─────────────────────────────────┐
│    Route Layer                  │
│  - api/restaurants              │
│  - api/orders                   │
│  - api/payments                 │
└────────────────┬────────────────┘
                 │
┌────────────────▼────────────────┐
│    Controller Layer             │
│  - RestaurantController         │
│  - OrderController              │
│  - PaymentController            │
│  - AuthController               │
└────────────────┬────────────────┘
                 │
┌────────────────▼────────────────┐
│    Service Layer                │
│  - RestaurantService            │
│  - OrderService                 │
│  - PaymentService               │
│  - AuthService                  │
└────────────────┬────────────────┘
                 │
┌────────────────▼────────────────┐
│    Model/Repository Layer       │
│  - User Model                   │
│  - Restaurant Model             │
│  - Order Model                  │
│  - Payment Model                │
└────────────────┬────────────────┘
                 │
┌────────────────▼────────────────┐
│    Database Layer (Eloquent)    │
│  - Migrations                   │
│  - Query Builder                │
└─────────────────────────────────┘
```

## Data Flow

### User Authentication Flow
```
1. User enters credentials in Flutter app
2. UI calls AuthProvider
3. AuthProvider calls AuthRepository
4. AuthRepository calls API Service
5. API Service sends POST /api/auth/login to Laravel
6. Laravel AuthController validates and returns JWT token
7. Token stored in Flutter (SecureStorage)
8. User data stored in local state
9. Navigate to Home screen
```

### Order Creation Flow
```
1. User adds foods to cart (UI → CartProvider)
2. User proceeds to checkout
3. User enters delivery address
4. User selects payment method
5. App sends POST /api/orders to Laravel
6. OrderController creates order in database
7. PaymentService processes payment
8. Notification sent to restaurant
9. Order tracking updates in real-time
10. User sees "Order Confirmed" screen
```

### Real-time Order Tracking
```
1. User navigates to tracking screen
2. App polls /api/orders/{id}/track endpoint
3. Laravel queries order status from database
4. Returns current status (preparing, on_the_way, etc.)
5. Map shows delivery location (from GPS updates)
6. UI updates every 10 seconds
```

## Database Relationships

```
users (1) ──────── (many) orders
users (1) ──────── (many) addresses
users (1) ──────── (many) reviews

restaurants (1) ──────── (many) foods
restaurants (1) ──────── (many) orders

foods (1) ──────── (many) order_items
foods (1) ──────── (many) reviews

orders (1) ──────---- (many) order_items
orders (1) ──────---- (one) payments

reviews (many) ──────---- (one) users
reviews (many) ──────---- (one) foods
```

## Security Architecture

### Mobile
- JWT tokens stored in SecureStorage
- HTTPS only for API calls
- Certificate pinning
- Input validation
- Sensitive data not logged

### Backend
- JWT middleware for route protection
- Rate limiting (20 requests/minute)
- SQL injection protection (Eloquent ORM)
- CORS configuration
- Password hashing (bcrypt)
- Environment variables for secrets

### Database
- User passwords hashed with bcrypt
- Sensitive fields indexed
- Regular backups
- SSL connection for database

## Scalability Considerations

### Current Phase (MVP)
- Single Laravel server
- MySQL database on same server
- No caching layer
- Direct database queries

### Future Optimizations
- Redis for caching
- Database read replicas
- Load balancing
- CDN for images
- Message queue for notifications (RabbitMQ/Redis)
- Microservices for payments/notifications

---

**Last Updated**: 2026-09-26
