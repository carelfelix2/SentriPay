# 🚀 SentriPay - Quick Reference Guide for Developers

Panduan cepat untuk mengembangkan dan menggunakan aplikasi SentriPay.

---

## 📌 Quick Links

- [Project Structure](#-project-structure)
- [Common Commands](#-common-commands)
- [Component Usage](#-component-usage)
- [Database Operations](#-database-operations)
- [Debugging Tips](#-debugging-tips)
- [Deployment](#-deployment)

---

## 📁 Project Structure

```bash
# Navigate ke project
cd c:\laragon\www\rill-store\sentripay

# Key folders
app/                    # Business logic (Models, Livewire, Middleware)
database/              # Migrations & seeders
resources/views/       # Blade templates
resources/js/          # Alpine.js & frontend
routes/                # All application routes
storage/               # File uploads, logs
tests/                 # Test files
```

---

## ⚡ Common Commands

### Development Setup

```bash
# Start development server
php artisan serve                  # Terminal 1
npm run dev                        # Terminal 2

# Fresh database
php artisan migrate:fresh --seed

# Generate key
php artisan key:generate

# Link storage
php artisan storage:link
```

### Database

```bash
# Create migration
php artisan make:migration create_table_name

# Create model with migration
php artisan make:model ModelName -m

# Run migrations
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh

# Seed database
php artisan db:seed
php artisan db:seed --class=UserSeeder
```

### Livewire

```bash
# Create component
php artisan make:livewire ComponentName

# Discover components
php artisan livewire:discover

# Clear cache
php artisan livewire:cache:clear
```

### Artisan Helpers

```bash
# Tinker (interactive shell)
php artisan tinker

# Clear cache
php artisan cache:clear
php artisan config:cache

# List routes
php artisan route:list

# Make request/controller
php artisan make:request OrderRequest
php artisan make:controller ProductController
```

---

## 🎨 Component Usage

### Using Livewire Component in Blade

```blade
<!-- Simple component -->
<livewire:dashboard />

<!-- Component with parameters -->
<livewire:checkout-order :productId="$product->id" />

<!-- With Alpine.js interaction -->
<div x-data="{ open: false }">
    <livewire:product-browser wire:key="products-{{ $page }}" />
</div>
```

### Creating New Livewire Component

```php
<?php
namespace App\Livewire;

use Livewire\Component;

class MyComponent extends Component
{
    public $data = '';
    
    public function mount()
    {
        // Initialize
    }
    
    public function updatedData($value)
    {
        // Called when $data changes
    }
    
    public function myMethod()
    {
        // Component method
        $this->data = 'new value';
    }
    
    public function render()
    {
        return view('livewire.my-component');
    }
}
```

### Alpine.js Patterns

```javascript
// Basic Alpine component
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open">Content</div>
</div>

// With Livewire integration
<div x-data="{ search: @entangle('search') }">
    <input x-model="search" />
</div>

// Format currency
<div>{{ formatCurrency(order.total_amount) }}</div>

// Conditional classes
<div :class="{ 'text-red-500': error, 'text-green-500': !error }">
    Status
</div>

// Event listeners
<button @click="$dispatch('notify', {message: 'Success'})">
    Click me
</button>
```

---

## 🗄️ Database Operations

### Model Queries

```php
// In tinker or controller
php artisan tinker

// Get all users
User::all();

// Find by ID
User::find(1);

// First user
User::first();

// Get with eager loading
Order::with('buyer', 'seller', 'product')->get();

// Filter
Product::where('status', 'available')->get();

// Create
User::create([
    'name' => 'John',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
    'role' => 'buyer'
]);

// Update
$user = User::find(1);
$user->update(['balance' => 100000]);

// Delete
Order::find(1)->delete();

// Pagination
Product::paginate(12);
```

### Creating Migration

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('table_name', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 15, 2);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('table_name');
    }
};
```

---

## 🔐 Authentication & Authorization

### Check User Role

```php
// In Blade
@if(auth()->user()->role === 'seller')
    <p>You are a seller</p>
@endif

// In Controller/Component
if (auth()->user()->role === 'admin') {
    // Admin only
}

// Using Middleware (route)
Route::middleware('seller')->group(function () {
    // Only sellers
});

// Check if authenticated
@auth
    Logged in
@endauth

@guest
    Not logged in
@endguest
```

### Create Middleware

```bash
php artisan make:middleware CheckRole
```

```php
<?php
namespace App\Http\Middleware;

use Closure;

class CheckRole {
    public function handle($request, Closure $next, $role)
    {
        if (auth()->check() && auth()->user()->role === $role) {
            return $next($request);
        }
        
        return redirect('/');
    }
}
```

---

## 🐛 Debugging Tips

### Enable Debug Mode

```env
# .env
APP_DEBUG=true
```

### Use Log

```php
// Log message
\Log::info('User created', ['user_id' => $user->id]);
\Log::error('Payment failed', ['order' => $order]);

// View logs
tail -f storage/logs/laravel.log
```

### Dump & Die

```php
// In controller/component
dd($variable);     // Dump & die
dump($variable);   // Dump (continue execution)
var_dump($var);    // PHP dump
```

### Query Debugging

```php
// Enable query logging
\DB::listen(function($query) {
    \Log::info($query->sql, $query->bindings);
});

// Get all executed queries
$queries = \DB::getQueryLog();
```

### Livewire Debugging

```php
// In component
public function mount()
{
    \Log::info('Component mounted', ['class' => static::class]);
}

// Trace events
<livewire:dashboard wire:loading>Loading...</livewire:loading>
```

---

## 📊 Useful File Locations

| What | Where |
|------|-------|
| Models | `app/Models/` |
| Migrations | `database/migrations/` |
| Livewire Components | `app/Livewire/` |
| Views | `resources/views/` |
| Routes | `routes/web.php` |
| Middleware | `app/Http/Middleware/` |
| Config | `config/` |
| Logs | `storage/logs/` |
| Uploads | `storage/app/public/` |

---

## 🚀 Deployment Checklist

Before deploying to production:

```bash
# [ ] Run tests
php artisan test

# [ ] Build assets
npm run build

# [ ] Set environment
.env: APP_ENV=production, APP_DEBUG=false

# [ ] Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# [ ] Set permissions
chmod -R 775 storage/ bootstrap/cache/

# [ ] Run migrations
php artisan migrate --force

# [ ] Optimize autoloader
composer install --optimize-autoloader --no-dev

# [ ] Set APP_KEY
php artisan key:generate
```

---

## 💾 Database Backup & Restore

```bash
# Backup
mysqldump -u root -p sentripay > backup.sql

# Restore
mysql -u root -p sentripay < backup.sql

# Using Laravel
php artisan migrate:fresh --seed  # Caution: destructive!
```

---

## 🔗 Route Definition Quick Reference

### Web Routes (routes/web.php)

```php
// Simple route
Route::get('/', HomeController::class)->name('home');

// Named route
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Route with parameter
Route::get('/product/{id}', ProductController::class)->name('product.show');

// Route group with middleware
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});

// Livewire component route
Route::get('/checkout/{id}', CheckoutOrder::class)->name('checkout');

// Prefix & middleware
Route::prefix('seller')->middleware('seller')->group(function () {
    Route::get('/products', [SellerController::class, 'products']);
});
```

---

## 🎯 Model Relationships Quick Reference

### Define Relationships

```php
class User extends Model {
    // One to Many
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    // One to Many (reverse)
    public function sellerOrders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }
    
    // Many to One (reverse)
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
```

### Use Relationships

```php
// Get related models
$user->products;           // All products
$user->products()->where('status', 'available')->get();  // Filtered

// Create related
$user->products()->create(['name' => 'Product']);

// Eager loading
User::with('products', 'orders')->get();

// Lazy eager loading
$users = User::all();
User::with('products')->get();  // Better than above
```

---

## 📝 Form & Validation

### Create Form Request

```bash
php artisan make:request StoreProductRequest
```

```php
<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->role === 'seller';
    }
    
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'Nama produk harus diisi',
            'price.numeric' => 'Harga harus berupa angka',
        ];
    }
}
```

### Validate in Livewire

```php
$this->validate([
    'email' => 'required|email|unique:users',
    'password' => 'required|min:8|confirmed',
], [
    'email.required' => 'Email wajib diisi',
    'email.unique' => 'Email sudah terdaftar',
]);
```

---

## 🎨 Blade Template Tips

```blade
<!-- Comment -->
{{-- This is a comment --}}

<!-- Echo with escaping (default) -->
{{ $variable }}

<!-- Echo without escaping -->
{!! $html !!}

<!-- Conditionals -->
@if($condition)
    
@elseif($other)
    
@else
    
@endif

<!-- Loops -->
@foreach($items as $item)
    {{ $item->name }}
@endforeach

@forelse($items as $item)
    {{ $item }}
@empty
    No items
@endforelse

<!-- Auth checks -->
@auth
    You are logged in
@endauth

@guest
    You are not logged in
@endguest

<!-- Include -->
@include('shared.errors')

<!-- Components -->
<x-button>Click me</x-button>
```

---

## 🔧 Environment Variables

Key `.env` settings for SentriPay:

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sentripay
DB_USERNAME=root

# Email
MAIL_MAILER=smtp
MAIL_FROM_ADDRESS=noreply@sentripay.com

# Bank Info
BANK_NAME=Bank Mandiri
BANK_ACCOUNT=1234567890
BANK_ACCOUNT_NAME=PT. SentriPay

# Commission
PLATFORM_COMMISSION=2
```

---

## 📞 Troubleshooting

### Issue: Migrations not running
```bash
# Solution: Check database connection
php artisan migrate:status
php artisan migrate
```

### Issue: Livewire component not found
```bash
# Solution: Clear and discover
php artisan livewire:discover
php artisan cache:clear
```

### Issue: Assets not loading
```bash
# Solution: Rebuild
npm install
npm run dev
php artisan storage:link
```

### Issue: Permission denied errors
```bash
# Solution: Fix permissions
chmod -R 775 storage/ bootstrap/cache/
sudo chown -R www-data:www-data .
```

---

## 🎓 Learning Resources

- **Laravel Docs**: https://laravel.com/docs
- **Livewire**: https://livewire.laravel.com/docs
- **Alpine.js**: https://alpinejs.dev
- **Tailwind CSS**: https://tailwindcss.com/docs
- **Blade**: https://laravel.com/docs/blade

---

## 💡 Quick Tips

1. **Always use migrations** for DB changes
2. **Use eager loading** to prevent N+1 queries
3. **Cache expensive queries** with Redis
4. **Log important events** for debugging
5. **Test locally** before deploying
6. **Use .env** for sensitive data
7. **Keep components focused** and reusable
8. **Document complex logic** with comments

---

**Happy Coding! 🚀**

Last Updated: December 29, 2025
