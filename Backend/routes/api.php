<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RestaurantController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Routes that do not require authentication.
*/

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Restaurants (Public View)
Route::get('/restaurants', [RestaurantController::class, 'index']);
Route::get('/restaurants/{id}', [RestaurantController::class, 'show']);

// Categories & Products (Menu)
Route::get('/restaurants/{id}/categories', [CategoryController::class, 'index']);
Route::get('/restaurants/{id}/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);


/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
| Routes that require a valid Sanctum token.
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // User Profile & Logout
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Restaurant Management (Create)
    Route::post('/restaurants', [RestaurantController::class, 'store']);

    // Menu Management (Create)
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::post('/products', [ProductController::class, 'store']);

    // Orders (Place Order)
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);// Get user orders (My Orders)
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::get('/restaurant/orders', [OrderController::class, 'getRestaurantOrders']);
});