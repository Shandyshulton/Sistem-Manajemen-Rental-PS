Sistem Manajemen Rental PS (PlayStation Rental Management System).

Core Features:

Authentication System: Login, logout, forgot/reset password functionality for staff
Staff Management: Admin and operator roles with CRUD operations
Console Management: Track PS3, PS4, and PS5 consoles with availability status and pricing
Booking System: Customer reservations with time slots, game selection, payment tracking
Dashboard: Transaction statistics and overview

Technical Stack:

Framework: Laravel 12 (PHP 8.2+)
Database: SQLite (default) with support for MySQL/PostgreSQL
Frontend: Tailwind CSS 4.0, Vite for asset compilation
Authentication: Custom staff-based auth system

Key Models:

Staff - Admin/operator users
Console - Gaming console inventory
Booking - Customer reservations with scheduling conflict detection

Notable Features:

Time slot management with conflict prevention
CSV export functionality for bookings
Playing status tracking ("Play"/"Not Play")
Multiple payment methods (Cash, Transfer, QRIS)
Responsive design with dark mode support

Here's how to install and set up this Laravel PlayStation rental management system:

## Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm
- SQLite (default) or MySQL/PostgreSQL

## Installation Steps

1. **Clone/Download the project**
```bash
git clone [repository-url]
cd [project-folder]
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install JavaScript dependencies**
```bash
npm install
```

4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Database setup**
```bash
# Create SQLite database file
touch database/database.sqlite

# Run migrations
php artisan migrate
```

6. **Seed initial data** (optional but recommended)
```bash
php artisan db:seed --class=StaffSeeder
```
This creates default admin and operator accounts:
- Admin: `adminps@gmail.com` / `admin123`
- Operator: `operatorps@gmail.com` / `operator123`

7. **Build frontend assets**
```bash
npm run build
# or for development with hot reload:
npm run dev
```

8. **Start the application**
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Alternative Development Setup
For a full development environment with queue processing and logs:
```bash
npm run dev
# This runs: server, queue worker, logs, and Vite simultaneously
```

## Database Configuration
If you prefer MySQL/PostgreSQL instead of SQLite, update your `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Troubleshooting
- Ensure proper file permissions for `storage/` and `bootstrap/cache/`
- If using MySQL, create the database before running migrations
- For production, run `php artisan config:cache` and `php artisan route:cache`

The system should now be ready for managing PlayStation console rentals.
