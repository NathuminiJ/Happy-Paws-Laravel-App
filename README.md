# 🐾 Happy Paws - Premium Pet Supply Store

A comprehensive e-commerce application built with Laravel 11, featuring advanced security, real-time features, and exceptional user experience.

## 🌟 Key Features

### E-commerce Core
- Product catalog with advanced search/filtering
- Shopping cart with real-time updates
- Complete checkout process
- Order management system
- User role separation (Admin/Customer)

### Security & Performance
- Laravel Jetstream with 2FA
- Laravel Sanctum API authentication
- OWASP Top 10 compliance
- Redis caching and queuing
- Rate limiting and security headers

### Advanced Laravel Features
- Event-driven architecture
- Background job processing
- Real-time broadcasting
- Advanced Eloquent optimization
- Comprehensive testing suite

### Modern UI/UX
- Tailwind CSS with pet supply aesthetic
- Livewire dynamic components
- Mobile-first responsive design
- Accessibility compliant

## 🚀 Quick Start

1. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Setup database**
   ```bash
   php artisan migrate --seed
   ```

4. **Build assets**
   ```bash
   npm run build
   ```

5. **Start application**
   ```bash
   php artisan serve
   ```

## 🐳 Docker Setup

```bash
docker-compose up -d
```

## 📊 Admin Access

- **Admin Panel**: `/admin`
- **Email**: admin@happypaws.com
- **Password**: password

## 🔧 API Endpoints

- Products: `/api/v1/products`
- Cart: `/api/v1/cart`
- Orders: `/api/v1/orders`
- Authentication required for protected routes

## 🛡️ Security

Comprehensive security implementation following OWASP guidelines with detailed documentation in `SECURITY.md`.

## 📈 Performance

- Redis caching
- Database optimization
- Asset minification
- Query optimization

Built with ❤️ for pet lovers everywhere! 🐾