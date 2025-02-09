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
    Route::get('user', [authController::class, 'user']);
    Route::post('logout', [authController::class, 'logout']);
});

Route::get('/categories', [homeController::class, 'categories']);
Route::get('/category/{id}', [homeController::class, 'category']);
Route::get('/hot-market', [homeController::class, 'hotProducts']);

Route::get('/cart', [orderController::class, 'cart']);
Route::post('/add-product-to-cart', [orderController::class, 'addProduct']);
Route::post('/remove-product-cart', [orderController::class, 'removeProduct']);
Route::post('/update-quantity-product-cart', [orderController::class, 'updateQuantity']);
