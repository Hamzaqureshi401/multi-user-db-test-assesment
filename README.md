
# Multi-User Database Test Assessment

## Clone the Project
Clone the repository from GitHub:
```bash
https://github.com/Hamzaqureshi401/multi-user-db-test-assesment
```

## Setup Instructions
1. **Copy and Configure Environment File**
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```

2. **Install Dependencies**
   - Run the following command to install project dependencies:
     ```bash
     composer update
     ```

3. **Create Databases**
   - Create the following databases in phpMyAdmin or your preferred MySQL client:
     - `shared_db`
     - `standard_db`

4. **Run Migrations**
   - Run the migrations for both databases:
     ```bash
     php artisan migrate:both
     ```

5. **Start the Development Server**
   - Start the Laravel server on a custom port:
     ```bash
     php artisan serve --port=8080
     ```

6. **Import Postman Collection**
   - Open Postman and import the API collection from:
     ```
     /public/Test Assesment.postman_collection.json
     ```

## API Testing Instructions

### Register User
- **Endpoint**: `/api/register`
- **Method**: `POST`
- **Description**: Registers a user in the `shared_db`.
- **Request Body**:
  ```json
  {
    "name": "John Doe",
    "email": "johndoe@example.com",
    "password": "password"
  }
  ```

### Login User
- **Endpoint**: `/api/login`
- **Method**: `POST`
- **Description**: Logs in a user based on middleware conditions.
- **Request Body**:
  ```json
  {
    "email": "johndoe@example.com",
    "password": "password"
  }
  ```

## Middleware Conditions

### Standard Database Check
- If the user exists in `standard_db` and `subscription_id = 2`, they are logged in from `standard_db`.

### Shared Database Check
- If the user exists in `shared_db` and `subscription_id = 1`, they are logged in from `shared_db`.

### Login Failure
- If neither condition is met, the login will fail with an error response.

## Middleware Functionality

### Demo Middleware
- Restricts access to Demo APIs only.

### Standard Middleware
- Grants access to both Demo and Standard APIs.

## API Endpoints Overview

### Register User
- Registers a user in the `shared_db`.

### Login User
- Logs in a user based on middleware checks.

## Conclusion
This project demonstrates multi-database user management in Laravel, with middleware-based API filtering. Follow the setup and testing instructions to explore its functionality. For further development, you can customize the middleware logic or database configurations as needed.
