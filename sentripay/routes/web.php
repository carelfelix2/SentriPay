<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\ProductBrowser;
use App\Livewire\CheckoutOrder;
use App\Livewire\PaymentProcess;

// Home
Route::get('/', function () {
    return view('home');
})->name('home');

// Product Browsing
Route::get('/products', ProductBrowser::class)->name('products');
Route::get('/checkout/{productId}', CheckoutOrder::class)->name('checkout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    
    // Orders
    Route::get('/orders', function () {
        return view('orders.index');
    })->name('orders');
    
    Route::get('/order/{orderId}', function ($orderId) {
        return view('orders.detail', ['orderId' => $orderId]);
    })->name('order.detail');
    
    Route::get('/order/{orderId}/payment', PaymentProcess::class)->name('order.payment');
    
    // Profile
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile');
    
    // Wallet/Saldo
    Route::get('/wallet', function () {
        return view('wallet.index');
    })->name('wallet');
    
    // Seller Routes
    Route::middleware('seller')->prefix('seller')->group(function () {
        Route::get('/products', function () {
            return view('seller.products');
        })->name('seller.products');
        
        Route::get('/product/create', function () {
            return view('seller.product-create');
        })->name('seller.product.create');
        
        Route::get('/earnings', function () {
            return view('seller.earnings');
        })->name('seller.earnings');
    });
    
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        
        Route::get('/transactions', function () {
            return view('admin.transactions');
        })->name('admin.transactions');
        
        Route::get('/disputes', function () {
            return view('admin.disputes');
        })->name('admin.disputes');
        
        Route::get('/users', function () {
            return view('admin.users');
        })->name('admin.users');
    });
    
    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');
});

