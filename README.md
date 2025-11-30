# 🚀 Quick Setup

### Prerequisites
- PHP 8.3+
- Composer
- MySql
- Node.js: 18 or higher

### Installation

1. **Clone and Install**
```bash
git clone https://github.com/bagusindars/laravel-reverb-chat-app.git
cd laravel-reverb-chat-app
composer install
npm install
```

2. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

create database first, then paste database name to .env (DB_DATABASE)

3. **Configure `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel-live-chat
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

4. **Database Setup**
```bash
# Create database first, then:
php artisan migrate
php artisan db:seed
```

5. **Start Services**
```bash
# Build the asset
npm run build

# Terminal 1: Start server or you can skip this step if using virtual host (.test, etc)
php artisan serve

# Terminal 2: Start reverb for live broadcasting
php artisan reverb:start

```

## 📋 Usage
### Web
```bash
to access the web dashboard open with default port or via virtual host
http://127.0.0.1:8000 
```

For Simulating the Login User (Agent) is by Triggering the Registration Live Chat Support and Check It on Database (Agent)
