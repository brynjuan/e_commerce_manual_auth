<?php

use App\Http\Controllers\Admin\ProductController as AdminProductController; // Alias untuk Admin ProductController
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Untuk cek auth di route '/'
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

// Halaman utama sekarang adalah daftar produk
Route::get('/', [ProductController::class, 'index'])->name('products.index');

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.show')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit')->middleware('guest');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profile Routes (User)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfileForm'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password.update');
});

// Rute untuk detail produk tetap menggunakan prefix /products
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show'); // Menggunakan Route Model Binding

Route::middleware('auth')->group(function () {
    // Cart Routes (Memerlukan login)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store'); // Untuk "Add to Cart"
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process'); // Memproses pesanan
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success'); // Halaman sukses
    // Order History Routes (User)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'showInvoice'])->name('orders.invoice'); // Rute untuk nota

    // checkout waitting
    Route::get('/checkout/waiting-verification/{order}', [App\Http\Controllers\CheckoutController::class, 'waitingVerification'])->name('checkout.waiting_verification');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalProducts = Product::count();
        $totalUsers = User::where('role', 'user')->count(); // Hanya user biasa
        $totalOrders = Order::count();
        // Anda bisa menambahkan data lain seperti pesanan terbaru, dll.
        return view('admin.dashboard', compact('totalProducts', 'totalUsers', 'totalOrders'));
    })->name('dashboard');
    Route::resource('products', AdminProductController::class);

       // Admin User Management
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');


// Admin Order Management
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    //baru ni
     Route::post('/orders/{order}/verify-payment', [App\Http\Controllers\Admin\OrderController::class, 'verifyPayment'])->name('orders.verify_payment');
});
