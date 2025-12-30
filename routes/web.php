<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\ProductBrowser;
use App\Livewire\CheckoutOrder;
use App\Livewire\PaymentProcess;
use App\Livewire\OrderHistory;
use App\Livewire\Seller\ProductsManager;
use App\Livewire\Admin\AdminDashboard;
use App\Http\Controllers\ProductController;
use App\Models\Order;

// Home
Route::get('/', function () {
    return view('home');
})->name('home');

// Product Browsing
Route::get('/products', ProductBrowser::class)->name('products');
Route::get('/checkout/{productId}', CheckoutOrder::class)->name('checkout');
// Product Detail (public)
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    
    // Orders - Unified History (Buyer + Seller)
    Route::get('/orders', OrderHistory::class)->name('orders');
    
    Route::get('/order/{order:order_number}', function (Order $order) {
        abort_if($order->buyer_id !== auth()->id(), 403);
        return view('orders.detail', ['order' => $order]);
    })->name('order.detail');
    
    Route::get('/order/{order:order_number}/payment', PaymentProcess::class)->name('order.payment');
    
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
        Route::get('/products', ProductsManager::class)->name('seller.products');
        
        // Product Editor - Livewire component
        Route::get('/product/create', \App\Livewire\Seller\ProductEditor::class)->name('seller.product.create');
        Route::get('/product/{productId}/edit', \App\Livewire\Seller\ProductEditor::class)->name('seller.product.edit');
        
        // Seller Orders - Redirect to unified history
        Route::get('/orders', function () {
            return redirect()->route('orders');
        })->name('seller.orders');
        
        Route::get('/order/{order:order_number}', function (Order $order) {
            $userId = auth()->id();
            abort_unless($order->seller_id === $userId || optional($order->product)->user_id === $userId, 403);
            return view('seller.orders.detail', ['order' => $order]);
        })->name('seller.order.detail');
        
        Route::get('/earnings', function () {
            return redirect()->route('seller.products');
        })->name('seller.earnings');
    });
    
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
        
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
    
    // Chat Group Routes
    Route::prefix('chat')->group(function () {
        Route::post('/order/{order}/start', [\App\Http\Controllers\ChatGroupController::class, 'startChat'])->name('chat.start');
        Route::get('/group/{chatGroup}', [\App\Http\Controllers\ChatGroupController::class, 'show'])->name('chat.show');
        Route::post('/group/{chatGroup}/send', [\App\Http\Controllers\ChatGroupController::class, 'sendMessage'])->name('chat.send');
        Route::post('/group/{chatGroup}/confirm', [\App\Http\Controllers\ChatGroupController::class, 'confirmAccountReceived'])->name('chat.confirm');
    });
    
    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');
});

