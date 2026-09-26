# 🍽️ Backend API Specification - Arzon Yegulik

## Stack
- **Framework**: Laravel 11
- **Database**: MySQL 8.0+
- **Authentication**: JWT (JSON Web Tokens)
- **API Style**: RESTful

## Database Schema

### Users Table
```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  phone VARCHAR(20) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  avatar_url VARCHAR(255),
  is_active BOOLEAN DEFAULT true,
  role ENUM('customer', 'restaurant', 'driver', 'admin') DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Restaurants Table
```sql
CREATE TABLE restaurants (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  logo_url VARCHAR(255),
  cover_image_url VARCHAR(255),
  category VARCHAR(100),
  rating DECIMAL(3,2) DEFAULT 0,
  is_open BOOLEAN DEFAULT true,
  commission_rate INT DEFAULT 20,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### Foods/Menu Items Table
```sql
CREATE TABLE foods (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  restaurant_id BIGINT NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price INT NOT NULL,
  image_url VARCHAR(255),
  category VARCHAR(100),
  is_available BOOLEAN DEFAULT true,
  rating DECIMAL(3,2) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);
```

### Orders Table
```sql
CREATE TABLE orders (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  restaurant_id BIGINT NOT NULL,
  status ENUM('pending', 'confirmed', 'preparing', 'ready', 'on_the_way', 'delivered', 'cancelled') DEFAULT 'pending',
  total_amount INT NOT NULL,
  delivery_fee INT DEFAULT 0,
  discount INT DEFAULT 0,
  payment_method VARCHAR(50),
  delivery_address TEXT NOT NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);
```

### Order Items Table
```sql
CREATE TABLE order_items (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  order_id BIGINT NOT NULL,
  food_id BIGINT NOT NULL,
  quantity INT NOT NULL,
  unit_price INT NOT NULL,
  subtotal INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (food_id) REFERENCES foods(id)
);
```

### Payments Table
```sql
CREATE TABLE payments (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  order_id BIGINT NOT NULL,
  amount INT NOT NULL,
  payment_method VARCHAR(50) NOT NULL,
  status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
  transaction_id VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id)
);
```

### Addresses Table
```sql
CREATE TABLE addresses (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  title VARCHAR(100),
  street_address TEXT NOT NULL,
  city VARCHAR(100) NOT NULL,
  postal_code VARCHAR(20),
  latitude DECIMAL(10,8),
  longitude DECIMAL(11,8),
  is_default BOOLEAN DEFAULT false,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

## API Endpoints

### Authentication
```
POST   /api/auth/register          - Register new user
POST   /api/auth/login             - Login user (returns JWT token)
POST   /api/auth/logout            - Logout user
POST   /api/auth/refresh-token     - Refresh JWT token
POST   /api/auth/password-reset    - Request password reset
POST   /api/auth/reset-password    - Reset password with token
```

### Users
```
GET    /api/users/profile          - Get current user profile
PUT    /api/users/profile          - Update user profile
POST   /api/users/avatar           - Upload avatar
```

### Restaurants
```
GET    /api/restaurants            - List all restaurants
GET    /api/restaurants/{id}       - Get restaurant details
GET    /api/restaurants/{id}/foods - Get restaurant menu items
GET    /api/restaurants/search     - Search restaurants
```

### Foods/Menu
```
GET    /api/foods                  - List all foods
GET    /api/foods/{id}             - Get food details
GET    /api/foods/search           - Search foods
```

### Orders
```
POST   /api/orders                 - Create new order
GET    /api/orders                 - Get user orders
GET    /api/orders/{id}            - Get order details
PUT    /api/orders/{id}            - Update order status (admin/restaurant)
POST   /api/orders/{id}/cancel     - Cancel order
GET    /api/orders/{id}/track      - Track order in real-time
```

### Payments
```
POST   /api/payments/click         - Pay with Click
POST   /api/payments/payme         - Pay with Payme
POST   /api/payments/callback      - Payment callback (webhook)
GET    /api/payments/{id}          - Get payment details
```

### Addresses
```
GET    /api/addresses              - Get user addresses
POST   /api/addresses              - Create new address
PUT    /api/addresses/{id}         - Update address
DELETE /api/addresses/{id}         - Delete address
PUT    /api/addresses/{id}/default - Set as default
```

### Admin (Protected Routes)
```
GET    /api/admin/users            - List all users
GET    /api/admin/restaurants      - List all restaurants
GET    /api/admin/orders           - List all orders
GET    /api/admin/analytics        - Get analytics data
```

## Response Format

### Success Response
```json
{
  "status": "success",
  "message": "Operation successful",
  "data": {
    "id": 1,
    "name": "John Doe",
    ...
  }
}
```

### Error Response
```json
{
  "status": "error",
  "message": "Error description",
  "errors": {
    "field": ["Error message"]
  }
}
```

## Authentication
- Use Bearer token in Authorization header
- Format: `Authorization: Bearer {token}`
- Token expires in 24 hours
- Refresh token endpoint for token renewal

## Monetization Endpoints
```
POST   /api/analytics/revenue      - Track revenue
POST   /api/ads/campaigns          - Create ad campaign
GET    /api/ads/performance        - Get ad performance
POST   /api/subscriptions          - Create restaurant subscription
```

---

**Status**: Schema ready for implementation
**Next Step**: Laravel project setup and migration files
