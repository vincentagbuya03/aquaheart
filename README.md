# AquaHeart

AquaHeart is a web-based sales, refill, and customer management system for water refilling stations built with Laravel.

## Features

- Admin authentication
- Customer management
- Product and inventory management
- Sales and refill transaction logging
- Customer analytics and sales reports
- CSV export and print-friendly transaction reports

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
npm install
npm run build
php artisan serve
```

Default seeded admin accounts:

- `admin@aquaheart.com` / `password123`
- `manager@aquaheart.com` / `password123`

You can also create a new admin with:

```bash
php artisan admin:create your@email.com yourpassword "Your Name"
```

## Render deployment

This repository is ready for Render using Docker.

### Files included

- `Dockerfile`
- `render.yaml`
- `scripts/render-start.sh`

### Recommended setup

- Render Web Service for the app
- External MySQL database such as Aiven

### Required Render environment variables

```env
APP_NAME=AquaHeart
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-service.onrender.com
APP_KEY=base64:your-generated-key

DB_CONNECTION=mysql
DB_HOST=your-mysql-host
DB_PORT=your-mysql-port
DB_DATABASE=your-mysql-database
DB_USERNAME=your-mysql-username
DB_PASSWORD=your-mysql-password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
LOG_LEVEL=info
```

Generate an application key locally with:

```bash
php artisan key:generate --show
```

### Deploy steps

1. Push this repository to GitHub.
2. Create your MySQL database.
3. In Render, create a new Web Service from this repository.
4. Select the Docker runtime.
5. Add the environment variables listed above.
6. Deploy the service.

The startup script will attempt to run migrations automatically on boot.

## Notes

- Free Render services sleep after inactivity, so the first request may be slow.
- Do not commit your real `.env` file.
- For production, change the default seeded admin passwords.
