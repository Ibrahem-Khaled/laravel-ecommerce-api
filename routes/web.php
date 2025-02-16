<?php

use App\Http\Controllers\dashboardControllers\CategoryController;
use App\Http\Controllers\dashboardControllers\homeController;
use App\Http\Controllers\dashboardControllers\LiveChatController;
use App\Http\Controllers\dashboardControllers\NotificationController;
use App\Http\Controllers\dashboardControllers\OrderController;
use App\Http\Controllers\dashboardControllers\ProductController;
use App\Http\Controllers\dashboardControllers\subCategoryController;
use App\Http\Controllers\dashboardControllers\usersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect(route('home.dashboard'));
})->name('home');

Route::group(['middleware' => 'auth', 'prefix' => 'dashboard'], function () {

    Route::get('/dashboard', [homeController::class, 'index'])->name('home.dashboard');
    Route::resource('users', usersController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('subCategories', subCategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('notifications', NotificationController::class);

    Route::resource('orders', OrderController::class)->except(['edit', 'create']);
    Route::post('orders/{id}/update-status', [OrderController::class, 'updateStatus'])
        ->name('orders.update-status');

    Route::resource('live-chat', LiveChatController::class)->only(['index', 'store']);

    Route::get('live-chat/{user}/messages', [LiveChatController::class, 'getMessages'])
        ->name('live-chat.messages');

    Route::get('live-chat/{user}/unread-count', [LiveChatController::class, 'getUnreadCount'])
        ->name('live-chat.unread-count');

    Route::post('live-chat/{chat}/update-status', [LiveChatController::class, 'updateStatus'])
        ->name('live-chat.update-status');

});





require __DIR__ . '/web/auth.php';