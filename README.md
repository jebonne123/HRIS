# HRIS - Human Resource Information System

A full-stack HRIS application built with Laravel 10+ (Backend API) and Vue 3 (Frontend SPA).

## Features

- **Authentication & Authorization**: Laravel Sanctum with role-based permissions
- **Employee Management**: CRUD operations with photo upload, fingerprint ID, card ID, and device password
- **Shift Management**: Flexible shift scheduling with overtime rules
- **Department Management**: Organizational structure management
- **Attendance Tracking**: Time clock with check-in/out, break management
- **Payroll System**: Basic payroll generation and management
- **Admin Setup Wizard**: First-time setup for system configuration
- **Responsive UI**: Modern interface built with Tailwind CSS

## Tech Stack

### Backend
- **PHP 8.1+** with **Laravel 10+**
- **MySQL** database
- **Laravel Sanctum** for API authentication
- **Spatie Laravel Permission** for role-based access control
- **File storage** for employee photos

### Frontend
- **Vue 3** with Composition API
- **Vite** for build tooling
- **Pinia** for state management
- **Vue Router** for navigation
- **Tailwind CSS** for styling
- **Axios** for API communication

## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js 18+ and npm
- Git

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd HRIS
```

### 2. Backend Setup

```bash
# Install PHP dependencies
composer install

# Copy environment file
cp env.example .env

# Edit .env file with your database credentials
# Update these values:
# DB_DATABASE=hris
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Generate application key
php artisan key:generate

# Run database migrations and seeders
php artisan migrate --seed

# Create storage link for file uploads
php artisan storage:link

# Start the development server
php artisan serve
```

### 3. Frontend Setup

```bash
# Install Node.js dependencies
npm install

# Start the development server
npm run dev
```

The frontend will be available at `http://localhost:5173` and will proxy API requests to the Laravel backend at `http://localhost:8000`.

## Default Credentials

After running the seeders, you can login with:

- **Email**: `admin@hris.com`
- **Password**: `password`

## First-Time Setup

When an admin user logs in for the first time, they will be automatically redirected to the setup wizard where they must:

1. **Configure Company Information**: Company name, address, contact details, timezone
2. **Review Default Shift**: The system comes with a pre-configured default day shift (8:00-12:00, 13:00-17:00)
3. **Complete Setup**: Mark the system as configured

Until setup is completed, non-admin users cannot access the system.

## Database Structure

### Core Tables

- **users**: User accounts with roles
- **employees**: Employee information and details
- **departments**: Organizational departments
- **shifts**: Work shift definitions with overtime rules
- **employee_shifts**: Employee-shift assignments with effective dates
- **attendances**: Daily attendance records
- **attendance_breaks**: Break time tracking
- **payrolls**: Payroll periods and summaries
- **payroll_items**: Individual employee payroll items
- **settings**: System configuration
- **audit_logs**: Change tracking and audit trail

### Default Shift Configuration

The system comes with a pre-configured default day shift:

- **Name**: Default Day Shift
- **Schedule**: 8:00-12:00, 13:00-17:00
- **Bell**: Disabled
- **Late Allowance**: 0 minutes
- **Early Leave Allowance**: 0 minutes
- **Rest Days**: 2 per week
- **Overtime**: Enabled
- **OT Start**: 60 minutes after scheduled end
- **OT Validation**: 30-minute blocks

## API Endpoints

### Authentication
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `GET /api/user` - Get current user info

### Setup
- `GET /api/setup/status` - Check setup completion status
- `POST /api/setup/complete` - Complete system setup

### Dashboard
- `GET /api/dashboard` - Get dashboard metrics

### Employees
- `GET /api/employees` - List employees
- `POST /api/employees` - Create employee
- `GET /api/employees/{id}` - Get employee details
- `PUT /api/employees/{id}` - Update employee
- `DELETE /api/employees/{id}` - Delete employee

### Shifts
- `GET /api/shifts` - List shifts
- `POST /api/shifts` - Create shift
- `GET /api/shifts/{id}` - Get shift details
- `PUT /api/shifts/{id}` - Update shift
- `DELETE /api/shifts/{id}` - Delete shift

### Attendance
- `GET /api/attendance` - List attendance records
- `POST /api/attendance/check-in` - Employee check-in
- `POST /api/attendance/check-out` - Employee check-out
- `POST /api/attendance` - Create attendance record
- `PUT /api/attendance/{id}` - Update attendance

### Payroll
- `GET /api/payroll` - List payroll records
- `POST /api/payroll/generate` - Generate payroll for period
- `GET /api/payroll/{id}` - Get payroll details

## Business Logic

### Attendance Rules

- **Late Calculation**: If check-in time > shift start + allowed late minutes
- **Early Leave**: If check-out time < shift end - allowed early minutes
- **Overtime**: Calculated in blocks based on configured rules
- **Break Time**: Subtracted from total work minutes

### Shift Management

- Employees can be assigned multiple shifts with effective dates
- Current shift is determined by effective date ranges
- Shift rules apply to attendance calculations

## Development

### Running Tests

```bash
php artisan test
```

### Code Quality

```bash
# Laravel Pint (PHP)
./vendor/bin/pint

# Frontend linting
npm run lint
```

### Database Seeding

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=AdminUserSeeder
```

## File Structure

```
HRIS/
├── app/                    # Laravel application code
│   ├── Http/             # Controllers, Middleware, Requests
│   ├── Models/           # Eloquent models
│   └── ...
├── database/              # Migrations and seeders
├── routes/                # API routes
├── src/                   # Vue frontend source
│   ├── components/        # Vue components
│   ├── views/            # Page components
│   ├── stores/           # Pinia stores
│   ├── router/           # Vue Router configuration
│   └── api/              # API client
├── composer.json          # PHP dependencies
├── package.json           # Node.js dependencies
└── README.md             # This file
```

## Environment Variables

Key environment variables in `.env`:

```env
APP_NAME=HRIS
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hris
DB_USERNAME=root
DB_PASSWORD=secret

SANCTUM_STATEFUL_DOMAINS=localhost:5173,localhost:3000
SESSION_DOMAIN=localhost
```

## Troubleshooting

### Common Issues

1. **CORS Errors**: Ensure `SANCTUM_STATEFUL_DOMAINS` includes your frontend URL
2. **Database Connection**: Verify MySQL credentials and database exists
3. **File Uploads**: Ensure storage link is created with `php artisan storage:link`
4. **Permission Issues**: Check that seeders ran successfully

### Logs

- Laravel logs: `storage/logs/laravel.log`
- Frontend errors: Browser console

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please open an issue in the repository.




