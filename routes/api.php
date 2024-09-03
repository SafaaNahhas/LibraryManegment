<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BorrowRecordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



/**
 * Routes for user authentication.
 */
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

/**
 * Routes within this group are protected by both 'auth:api' and 'admin' middleware.
 * The 'auth:api' middleware ensures that the user is authenticated,
 * while the 'admin' middleware restricts access to users with admin privileges.
 *
 * Note: The 'admin' middleware is applied to all routes in this group.
 *
 * The routes are:
 *
 * - **User Management**:
 *   - `GET /users` - List all users (admin only).
 *   - `GET /users/{id}` - View a specific user (admin only).
 *   - `POST /users` - Create a new user (admin only).
 *   - `PUT /users/{id}` - Update a specific user (admin only).
 *   - `DELETE /users/{id}` - Delete a specific user (admin only).
 *
 * - **Borrow Record Management**:
 *   - `DELETE /borrow-records` - Delete all borrow records (admin only).
 *   - `DELETE /borrow-records/{id}` - Delete a specific borrow record (admin only).
 *   - `GET /users/{userId}/borrow-records` - Get borrow records for a specific user (admin only).
 *
 * - **Book Ratings**:
 *   - `GET /books/{bookId}/ratings` - Retrieve ratings for a specific book (admin only).
 */
Route::group(['middleware' => ['auth:api', 'admin']], function() {

    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::delete('/borrow-records', [BorrowRecordController::class, 'destroyAll']);
    Route::delete('/borrow-records/{id}', [BorrowRecordController::class, 'destroy']);
    Route::get('/users/{userId}/borrow-records', [BorrowRecordController::class, 'getUserBorrowRecords']);

    Route::get('books/{bookId}/ratings', [RatingController::class, 'index']);
});
/**
 * Defines routes for the Book resource.
 *
 * Routes are protected by the 'auth' and 'admin' middleware,
 * which are applied in the BookController constructor.
 * The 'index' and 'show' methods are exempt from the 'admin' middleware.
 */
Route::apiResource('books', BookController::class);

/**
 * Routes protected by the 'auth:api' middleware.
 *
 * The 'auth:api' middleware ensures that users must be authenticated
 * to access these routes. Authenticated users can perform the following actions:
 *
 * - **Authentication Actions**:
 *   - `POST /logout` - Logs the user out.
 *   - `POST /refresh` - Refreshes the user's authentication token.
 *
 * - **Borrow Record Actions**:
 *   - `POST /borrow-records` - Creates a new borrow record for a book.
 *   - `POST /borrow-records/return` - Marks a book as returned.
 *
 * - **Rating Actions**:
 *   - `POST /books/{bookId}/ratings` - Adds a rating for a specific book.
 *   - `PUT /ratings/{id}` - Updates an existing rating.
 *   - `DELETE /ratings/{id}` - Deletes a specific rating.
 */
Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::post('/borrow-records', [BorrowRecordController::class, 'store']);
    Route::post('/borrow-records/return', [BorrowRecordController::class, 'returnBook']);

    Route::post('books/{bookId}/ratings', [RatingController::class, 'store']);
    Route::put('ratings/{id}', [RatingController::class, 'update']);
    Route::delete('ratings/{id}', [RatingController::class, 'destroy']);

});


