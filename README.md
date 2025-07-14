

# Food Delivery API

## Overview
This is a backend API for a food delivery platform built with Laravel 12 and MySQL. It supports restaurant delivery zones (polygon/radius), order validation, delivery assignment, notifications, and authentication via Laravel Sanctum.

## Setup Instructions

1. **Clone the repository**
2. **Install dependencies**
   ```
   composer install
   
   ```
3. **Configure environment**
   - Copy `.env.example` to `.env` and set your database credentials.
   - Example MySQL config:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=food
     DB_USERNAME=root
     DB_PASSWORD=yourpassword
     ```
4. **Generate app key**
   ```
   php artisan key:generate
   ```
5. **Run migrations and seeders**
   ```
   php artisan migrate --seed
   ```
6. **Install Sanctum**
   ```
   composer require laravel/sanctum
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   php artisan migrate
   ```
7. **Run the server**
   ```
   php artisan serve
   ```

## API Endpoints
- `POST /register` and `POST /login`: User authentication
- `POST /orders`: Place an order (requires authentication)
- `apiResource /delivery-zones`: Manage delivery zones (polygon/radius)
- `PUT /delivery-assignments/{assignment}/respond`: Delivery man responds to assignment

## Assumptions
- Latitude/longitude are validated and must be within valid ranges.
- Delivery zones can be polygons (array of coordinates) or radius (center + radius).
- Only available delivery men within a specified radius are assigned.
- Notifications are sent to delivery men on assignment (database + broadcast).
- Orders are validated against delivery zones before creation.

## Design Choices
- **Service Layer**: Business logic is separated into service classes for maintainability.
- **Custom Exceptions**: Used for delivery zone validation errors.
- **Sanctum**: Chosen for API authentication due to simplicity and SPA/mobile support.
- **Notifications**: Laravel notifications for assignment events.
- **API Resources**: Used for consistent response formatting.
- **Testing**: PHPUnit feature tests cover core flows.

## How to Extend
- Add more notification channels (email, SMS, etc.)
- Integrate real-time location updates for delivery men
- Add admin/user roles and permissions
- Expand order and restaurant models as needed

## Contact
For questions or contributions, contact the repository owner.
