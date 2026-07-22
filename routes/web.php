<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ArticleCatalogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductCatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/products', [ProductCatalogController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductCatalogController::class, 'show'])->name('products.show');
Route::get('/articles', [ArticleCatalogController::class, 'index'])->name('articles.index');
Route::get('/articles/{article:slug}', [ArticleCatalogController::class, 'show'])->name('articles.show');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store')->middleware('throttle:5,1');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store')->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store')->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/orders/{order}/confirm-payment', [CheckoutController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [AccountController::class, 'showOrder'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [AccountController::class, 'invoice'])->name('orders.invoice');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,superadmin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::delete('/product-images/{image}', [AdminProductController::class, 'deleteImage'])->name('product-images.destroy');
    Route::resource('articles', AdminArticleController::class)->except('show');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/approve', [AdminOrderController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{order}/reject', [AdminOrderController::class, 'reject'])->name('orders.reject');
    Route::post('/orders/{order}/complete', [AdminOrderController::class, 'complete'])->name('orders.complete');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('faqs', AdminFaqController::class)->except('show');
    Route::resource('users', UserController::class)->except(['show', 'destroy']);
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contacts.index');
    Route::get('/contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contacts.show');
    Route::delete('/contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contacts.destroy');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});
