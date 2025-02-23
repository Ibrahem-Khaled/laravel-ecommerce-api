<?php

use App\Http\Controllers\dashboardControllers\AppSettingsController;
use App\Http\Controllers\dashboardControllers\CategoryController;
use App\Http\Controllers\dashboardControllers\CouponController;
use App\Http\Controllers\dashboardControllers\homeController;
use App\Http\Controllers\dashboardControllers\LiveChatController;
use App\Http\Controllers\dashboardControllers\NotificationController;
use App\Http\Controllers\dashboardControllers\OrderController;
use App\Http\Controllers\dashboardControllers\ProductController;
use App\Http\Controllers\dashboardControllers\SelectStoreController;
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

Route::group(['middleware' => ['auth', 'checkRole:admin'], 'prefix' => 'dashboard'], function () {

    Route::get('/dashboard', [homeController::class, 'index'])->name('home.dashboard');
    Route::resource('users', usersController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('subCategories', subCategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('notifications', NotificationController::class);
    Route::resource('coupons', CouponController::class);
    Route::resource('app-settings', AppSettingsController::class);



    Route::resource('orders', OrderController::class)->except(['edit', 'create']);
    Route::post('orders/{id}/update-status', [OrderController::class, 'updateStatus'])
        ->name('orders.update-status');

    // صفحة اختيار المستخدم لبدء الدردشة
    Route::get('live-chat', [LiveChatController::class, 'index'])->name('live-chat.index');

    // عرض دردشة المستخدم المحدد
    Route::get('live-chat/user/{userId}', [LiveChatController::class, 'showUserChat'])->name('dashboard.live-chat.user');

    // جلب الرسائل الخاصة بالمستخدم المحدد (AJAX)
    Route::get('live-chat/fetch/{userId}', [LiveChatController::class, 'fetchUserChatMessages'])->name('dashboard.live-chat.fetch.user');

    // إرسال رسالة للمستخدم المحدد (AJAX)
    Route::post('live-chat/send/{userId}', [LiveChatController::class, 'sendMessage'])->name('dashboard.live-chat.send');

    Route::resource('stores', SelectStoreController::class);


});





require __DIR__ . '/web/auth.php';