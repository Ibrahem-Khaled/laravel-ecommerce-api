<?php

use App\Http\Controllers\api\apiV1\adminControllers\CategoryController;
use App\Http\Controllers\api\apiV1\adminControllers\NotificationController;
use App\Http\Controllers\api\apiV1\adminControllers\ProductController;
use App\Http\Controllers\api\apiV1\adminControllers\SubCategoryController;
use App\Http\Controllers\api\apiV1\adminControllers\usersController;
use App\Http\Controllers\api\apiV1\adminControllers\OrderController as AdminOrderController;
use App\Http\Controllers\api\apiV1\authController;
use App\Http\Controllers\api\apiV1\customerController\homeController;
use App\Http\Controllers\api\apiV1\customerController\LiveChatController;
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
Route::get('/app-settings', [homeController::class, 'appSettings']);
Route::get('/get-all-categories', [homeController::class, 'categories']);
Route::get('/category/{id}', [homeController::class, 'category']);

Route::get('/get-all-sub-categories', [homeController::class, 'getAllSubCategories']);
Route::get('/sub-categories/{id}', [homeController::class, 'subCategories']);

Route::get('/get-hot-products', [homeController::class, 'getHotProducts']);
Route::get('/get-products/{subCategory}', [HomeController::class, 'Products']);
Route::get('/get-notification', [homeController::class, 'notification']);
Route::get('/search', [homeController::class, 'search']);

Route::get('/user-cart', [orderController::class, 'cart']);
Route::post('/checkout', [orderController::class, 'checkout']);
Route::get('/user-orders', [orderController::class, 'userOrders']);
Route::get('/order-details/{id}', [orderController::class, 'orderDetails']);

Route::post('/add-product-to-cart', [orderController::class, 'addProduct']);
Route::post('/remove-product-cart', [orderController::class, 'removeProduct']);
Route::post('/update-quantity-product-cart', [orderController::class, 'updateQuantity']);

Route::get('/live-chat', [LiveChatController::class, 'index']);
Route::post('/live-chat', [LiveChatController::class, 'store']);

//this admin panel routes && CRUD

Route::apiResource('users', usersController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('sub-categories', SubCategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('notifications', NotificationController::class);

Route::get('/orders', [AdminOrderController::class, 'index']);
Route::get('/order/{id}', [AdminOrderController::class, 'show']);
Route::put('/order/{id}', [AdminOrderController::class, 'update']);

Route::get('/chats', [\App\Http\Controllers\api\apiV1\adminControllers\LiveChatController::class, 'index']);
Route::post('/replay/{chatId}', [\App\Http\Controllers\api\apiV1\adminControllers\LiveChatController::class, 'replay']);
Route::delete('/chat/{chatId}', [\App\Http\Controllers\api\apiV1\adminControllers\LiveChatController::class, 'destroy']);