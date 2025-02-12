<?php

use App\Http\Controllers\api\apiV1\authController;
use App\Http\Controllers\api\apiV1\customerController\homeController;
use App\Http\Controllers\api\apiV1\customerController\orderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [authController::class, 'register']);
Route::post('login', [authController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('update-profile', [authController::class, 'update']);
    Route::get('me', [authController::class, 'user']);
    Route::post('logout', [authController::class, 'logout']);
    Route::post('change-password', [authController::class, 'changePassword']);
    Route::post('delete-account', [authController::class, 'delete']);
});

//this routes for customer main
Route::get('/get-all-categories', [homeController::class, 'categories']);
Route::get('/category/{id}', [homeController::class, 'category']);

Route::get('/get-all-sub-categories', [homeController::class, 'getAllSubCategories']);
Route::get('/sub-categories/{id}', [homeController::class, 'subCategories']);

Route::get('/get-products', [HomeController::class, 'Products']);
Route::get('/get-notification', [homeController::class, 'notification']);
Route::get('/search', [homeController::class, 'search']);

Route::get('/user-cart', [orderController::class, 'cart']);
Route::post('/checkout', [orderController::class, 'checkout']);
Route::get('/user-orders', [orderController::class, 'userOrders']);
Route::get('/order-details/{id}', [orderController::class, 'orderDetails']);

Route::post('/add-product-to-cart', [orderController::class, 'addProduct']);
Route::post('/remove-product-cart', [orderController::class, 'removeProduct']);
Route::post('/update-quantity-product-cart', [orderController::class, 'updateQuantity']);
