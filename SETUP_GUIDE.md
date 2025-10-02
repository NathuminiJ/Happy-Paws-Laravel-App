# 🚀 Happy Paws - Complete Setup Guide

## 📋 Prerequisites

Before starting, ensure you have:
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- Redis 7+
- Git

## 🛠️ Installation Steps

### 1. Clone and Setup
```bash
git clone <repository-url> happy-paws
cd happy-paws
composer install
npm install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE happy_paws_db;

# Run migrations and seeders
php artisan migrate --seed
```

### 4. Build Assets
```bash
npm run build
```

### 5. Start Application
```bash
php artisan serve
```

## 🐳 Docker Alternative

```bash
docker-compose up -d
```

## 👤 Default Users

### Admin User
- **Email**: admin@happypaws.com
- **Password**: password
- **Access**: Full admin panel

### Customer User
- **Email**: customer@example.com
- **Password**: password
- **Access**: Customer features

## 🔧 Configuration

### Database Configuration
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=happy_paws_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Redis Configuration
```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### MongoDB Configuration (Optional)
```env
MONGODB_HOST=127.0.0.1
MONGODB_PORT=27017
MONGODB_DATABASE=happy_paws_mongo
```

## 🎯 Features Overview

### Customer Features
- Browse products with advanced search
- Add items to shopping cart
- Complete checkout process
- View order history
- Manage profile and subscriptions
- Contact support

### Admin Features
- Comprehensive dashboard
- Product management (CRUD)
- Order management
- Customer management
- Inventory tracking
- Analytics and reporting

## 🔐 Security Features

- Laravel Jetstream authentication
- Two-factor authentication
- Role-based access control
- CSRF protection
- XSS prevention
- SQL injection prevention
- Rate limiting
- Security headers

## 📊 Performance Features

- Redis caching
- Database optimization
- Asset minification
- Query optimization
- Background job processing

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
```

## 🚀 Deployment

### Heroku
1. Create Heroku app
2. Add MySQL addon
3. Set environment variables
4. Deploy via GitHub Actions

### AWS
1. Set up ECS cluster
2. Configure RDS for MySQL
3. Set up ElastiCache for Redis
4. Deploy via Docker

## 📱 API Usage

### Authentication
```bash
# Get API token
curl -X POST https://your-domain.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com", "password": "password"}'
```

### Get Products
```bash
curl -X GET https://your-domain.com/api/v1/products \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 🆘 Troubleshooting

### Common Issues

1. **Permission errors**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

2. **Database connection issues**
   - Check MySQL service is running
   - Verify credentials in .env

3. **Redis connection issues**
   - Check Redis service is running
   - Verify Redis configuration

4. **Asset compilation issues**
   ```bash
   npm run dev
   ```

## 📞 Support

For additional support:
- Check the documentation
- Review the security guide
- Contact the development team

---

**Happy coding! 🐾**
