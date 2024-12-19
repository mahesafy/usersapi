A Laravel-based RESTful API for user management with search, pagination, and email notifications.

## Features

- User listing with search and pagination
- User creation with email notifications
- Order tracking per user
- RESTful API endpoints
- Email notifications system

## Requirements

- PHP >= 8.1
- Composer
- MySQL/PostgreSQL
- Laravel 11.x
- SMTP server for emails

## First Installation

1. Clone the repository
   ```bash
   git clone [repository-url]
   cd [project-name]
   ```

2. Install dependencies
   ```bash
   composer install
   ```

3. Environment setup
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure your `.env` file with:
   - Database credentials
   - SMTP settings for email
   - Other necessary environment variables

5. Run migrations
   ```bash
   php artisan migrate
   ```

6. Start the local development server
   ```bash
   php artisan serve
   ```

## API Documentation

### List Users
```http
GET /api/users
```

#### Query Parameters
- `search` (optional) - Search by name or email
- `page` (optional) - Page number for pagination (default: 1)
- `sortBy` (optional) - Sort results by field (possible values: name, email, created_at)

#### Response Example
```json
{
    "page": 1,
    "users": [
        {
            "id": 123,
            "email": "example@example.com",
            "name": "John Doe",
            "created_at": "2024-11-25T12:34:56Z",
            "orders_count": 10
        },
        {
            "id": 124,
            "email": "another@example.com",
            "name": "Jane Smith",
            "created_at": "2024-11-24T11:20:30Z",
            "orders_count": 5
        }
    ]
}
```

### Create User
```http
POST /api/users
```

#### Request Body
```json
{
    "email": "example@example.com",
    "password": "password123",
    "name": "John Doe"
}
```

#### Validation Rules
- `email`: required, valid email format
- `password`: required, minimum 8 characters
- `name`: required, 3-50 characters

#### Response Example
```json
{
    "id": 123,
    "email": "example@example.com",
    "name": "John Doe",
    "created_at": "2024-11-25T12:34:56Z"
}
```
