## Introduction
This project is a RESTful API for managing a Book Library with Borrowing and Rating functionalities built using Laravel. It supports basic CRUD operations for books, users, and borrowing records, along with advanced features such as filtering, sorting, and rating. The API follows RESTful best practices, including data validation, exception handling, and proper use of HTTP status codes.

## Prerequisites

- [PHP](https://www.php.net/) >= 8.0
- [Composer](https://getcomposer.org/)
- [Laravel](https://laravel.com/) >= 9.0
- [MySQL](https://www.mysql.com/) or any other database supported by Laravel
- [Postman](https://www.postman.com/) for testing API endpoints

## Setup

1. **Clone the project:**
   git clone https://github.com/SafaaNahhas/LibraryManegment.git
   cd movie-library
## Install backend dependencies:
composer install
Create the .env file:
Copy the .env.example file to .env:
cp .env.example .env
## modify the .env file to set up your database connection:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
## Generate the application key:
php artisan key:generate
## Run migrations:
php artisan migrate
## Start the local server:
php artisan serve
You can now access the project at http://localhost:8000.

## Project Structure
- `BookController.php`: Handles API requests related to books, such as creating, updating, deleting, and retrieving books.
- `BorrowRecordController.php`: Manages the API requests related to borrowing records, including creating and updating borrow records.
- `RatingController.php`: Manages API requests related to book ratings, such as adding, updating, and retrieving ratings.
- `UserController.php` : Handles API requests related to user management, including creating and updating user profiles.
- `AuthController.php`: Manages API requests related to user authentication, including registration, login, and token management.
-`BookService.php`: Contains the business logic for managing books.
BorrowRecordService.php: Contains the business logic for managing borrow records.
- `RatingService.php`: Contains the business logic for managing book ratings.
- `UserService.php`: Contains the business logic for managing users.
- `AuthService.php`: Contains the business logic for managing user authentication, including validating credentials and generating JWT tokens.
- `ApiResponseService.php`: A service class responsible for formatting and returning standardized API responses.
- `StoreBookRequest.php`: A Form Request class for validating data when creating books.
- `UpdateBookRequest.php`: A Form Request class for validating data when updating books.
- `StoreBorrowRecordRequest.php`: A Form Request class for validating data when creating borrow records.
- `UpdateBorrowRecordRequest.php`: A Form Request class for validating data when updating borrow records.
- `StoreRatingRequest.php`: A Form Request class for validating data when creating ratings.
- `UpdateRatingRequest.php`: A Form Request class for validating data when updating ratings.
- `RegisterRequest.php`: A Form Request class for validating data during user registration.
- `LoginRequest.php`: A Form Request class for validating data during user login.
- `StoreUserRequest.php`: A Form Request class for validating data when creating users.
- `UpdateUserRequest.php`: A Form Request class for validating data when updating user profiles.
- `api.php`: Contains route definitions representing the API endpoints, mapping HTTP requests to the appropriate controllers.
## Advanced Features

1. Filtering

Books can be filtered by author, genre, or availability using query parameters.
Sorting

Books can be sorted by publication year or title using the sort_by query parameter.
Borrowing System



## A Postman collection
is provided to easily test the API endpoints. You can import it into your Postman application and run the requests.
## Postman Documentation
https://documenter.getpostman.com/view/34501481/2sAXjNXqmy
