<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\SecurityController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');

// Courses
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

// Personal Training
Route::get('/training', [TrainingController::class, 'index'])->name('training.index');
Route::get('/training/{program}', [TrainingController::class, 'show'])->name('training.show');
Route::get('/training/{program}/book', [TrainingController::class, 'book'])->name('training.book');
Route::post('/training/{program}/book', [TrainingController::class, 'submitBooking'])->name('training.book.submit');
Route::get('/training/booked', [TrainingController::class, 'booked'])->name('training.booked');

// Cart & Checkout
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [CartController::class, 'placeOrder'])->name('order.place');
Route::get('/order/{orderNumber}', [CartController::class, 'confirmation'])->name('order.confirmation');

// Payment
Route::get('/pay/{order:order_number}', [PaymentController::class, 'selectMethod'])->name('payment.select');
Route::post('/pay/{order:order_number}/method', [PaymentController::class, 'showInstructions'])->name('payment.instructions');
Route::post('/pay/{order:order_number}/submit', [PaymentController::class, 'submitPayment'])->name('payment.submit');
Route::get('/pay/{order:order_number}/pending', [PaymentController::class, 'pending'])->name('payment.pending');
Route::get('/pay/{order:order_number}/success', [PaymentController::class, 'success'])->name('payment.success');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// OpenID Connect Providers
Route::get('/auth/{provider}', [AuthController::class, 'redirectToProvider'])->name('auth.provider');
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth.callback');

// Account (protected)
Route::middleware('auth')->group(function () {
    Route::get('/account', [AuthController::class, 'account'])->name('account');
    Route::put('/account', [AuthController::class, 'updateProfile'])->name('account.update');
    Route::put('/account/password', [AuthController::class, 'updatePassword'])->name('account.password');
    Route::put('/account/set-password', [AuthController::class, 'setPassword'])->name('account.set-password');
});

// Admin - Security Dashboard
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [SecurityController::class, 'dashboard'])->name('dashboard');
    Route::get('/activity-log', [SecurityController::class, 'activityLog'])->name('activity-log');
    Route::get('/security-events', [SecurityController::class, 'securityEvents'])->name('security-events');
    Route::patch('/security-events/{event}/resolve', [SecurityController::class, 'resolveEvent'])->name('resolve-event');
    Route::get('/users', [SecurityController::class, 'users'])->name('users');
    Route::get('/users/{user}', [SecurityController::class, 'userDetail'])->name('user-detail');
    Route::patch('/users/{user}/toggle', [SecurityController::class, 'toggleUser'])->name('toggle-user');
    Route::get('/blocked-ips', [SecurityController::class, 'blockedIps'])->name('blocked-ips');
    Route::post('/blocked-ips', [SecurityController::class, 'blockIp'])->name('block-ip');
    Route::delete('/blocked-ips/{ip}', [SecurityController::class, 'unblockIp'])->name('unblock-ip');
});

// Pages
Route::get('/{page:slug}', [PageController::class, 'show'])->name('page.show');
