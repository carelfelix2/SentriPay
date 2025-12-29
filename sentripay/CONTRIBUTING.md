# 🤝 Contributing to SentriPay

Terima kasih atas minat Anda untuk berkontribusi pada SentriPay! Dokumen ini berisi panduan untuk membantu Anda memulai.

---

## 📋 Table of Contents
1. [Code of Conduct](#code-of-conduct)
2. [Getting Started](#getting-started)
3. [Development Workflow](#development-workflow)
4. [Coding Standards](#coding-standards)
5. [Testing Guidelines](#testing-guidelines)
6. [Commit Guidelines](#commit-guidelines)
7. [Pull Request Process](#pull-request-process)
8. [Bug Reports](#bug-reports)
9. [Feature Requests](#feature-requests)

---

## 📜 Code of Conduct

### Our Pledge
Kami berkomitmen untuk menjaga lingkungan yang terbuka dan ramah untuk semua kontributor, terlepas dari:
- Tingkat pengalaman
- Gender identity dan expression
- Orientasi seksual
- Disabilitas
- Penampilan fisik
- Ras, etnisitas, atau agama

### Expected Behavior
- ✅ Gunakan bahasa yang ramah dan inklusif
- ✅ Hargai pendapat dan pengalaman yang berbeda
- ✅ Terima kritik konstruktif dengan baik
- ✅ Fokus pada yang terbaik untuk komunitas
- ✅ Tunjukkan empati terhadap anggota komunitas lain

### Unacceptable Behavior
- ❌ Penggunaan bahasa atau gambar seksual
- ❌ Trolling, komentar menghina, atau serangan personal
- ❌ Harassment publik atau pribadi
- ❌ Mempublikasikan informasi pribadi orang lain
- ❌ Perilaku tidak profesional lainnya

---

## 🚀 Getting Started

### Prerequisites
Pastikan Anda sudah menginstall:
- PHP 8.2 atau lebih tinggi
- Composer 2.x
- Node.js 18.x atau lebih tinggi
- MySQL 8.0 atau MariaDB 10.3
- Git

### Fork & Clone Repository

1. **Fork repository** ke akun GitHub Anda
2. **Clone fork** Anda:
```bash
git clone https://github.com/YOUR_USERNAME/sentripay.git
cd sentripay
```

3. **Add upstream remote:**
```bash
git remote add upstream https://github.com/ORIGINAL_OWNER/sentripay.git
```

4. **Setup project** mengikuti GETTING_STARTED.md:
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run build
```

5. **Create feature branch:**
```bash
git checkout -b feature/your-feature-name
```

---

## 🔄 Development Workflow

### Branch Strategy

Kami menggunakan **Git Flow** workflow:

```
main (production)
  └── develop (latest development)
       ├── feature/* (new features)
       ├── bugfix/* (bug fixes)
       ├── hotfix/* (urgent production fixes)
       └── release/* (release preparation)
```

### Branch Naming Convention

- **Feature:** `feature/add-seller-ratings`
- **Bugfix:** `bugfix/fix-payment-upload`
- **Hotfix:** `hotfix/critical-security-patch`
- **Release:** `release/v1.0.0`

### Workflow Steps

1. **Sync dengan upstream:**
```bash
git fetch upstream
git checkout develop
git merge upstream/develop
```

2. **Buat branch baru:**
```bash
git checkout -b feature/your-feature
```

3. **Make changes & commit:**
```bash
git add .
git commit -m "Add: feature description"
```

4. **Push ke fork:**
```bash
git push origin feature/your-feature
```

5. **Create Pull Request** di GitHub

---

## 📐 Coding Standards

### PHP Standards

Kami mengikuti **PSR-12** coding standard.

#### Class Structure
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Example extends Model
{
    // Constants
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    
    // Properties
    protected $fillable = ['name', 'status'];
    
    protected $casts = [
        'created_at' => 'datetime',
    ];
    
    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // Accessors & Mutators
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    // Methods
    public function activate(): void
    {
        $this->update(['status' => self::STATUS_ACTIVE]);
    }
}
```

#### Naming Conventions

| Type | Convention | Example |
|------|-----------|---------|
| Classes | PascalCase | `UserController` |
| Methods | camelCase | `getUserBalance()` |
| Variables | camelCase | `$totalAmount` |
| Constants | UPPER_SNAKE_CASE | `MAX_UPLOAD_SIZE` |
| Database Tables | snake_case (plural) | `user_transactions` |
| Database Columns | snake_case | `created_at` |

### Livewire Standards

#### Component Structure
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ExampleComponent extends Component
{
    use WithPagination;
    
    // Public properties (bound to view)
    public string $search = '';
    public string $filter = 'all';
    
    // Protected/Private properties
    protected $listeners = ['refreshComponent' => '$refresh'];
    
    // Lifecycle hooks
    public function mount(): void
    {
        // Initialize component
    }
    
    // Updated hooks
    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    
    // Actions
    public function applyFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }
    
    // Render
    public function render()
    {
        return view('livewire.example-component', [
            'items' => $this->getItems(),
        ]);
    }
    
    // Helper methods (private)
    private function getItems()
    {
        // Implementation
    }
}
```

### Blade Standards

#### Template Structure
```blade
<div>
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold">{{ $title }}</h2>
    </div>
    
    {{-- Filters --}}
    @if($showFilters)
        <div class="mb-4">
            <!-- Filter components -->
        </div>
    @endif
    
    {{-- Content --}}
    <div class="space-y-4">
        @forelse($items as $item)
            <x-item-card :item="$item" />
        @empty
            <p class="text-gray-500">No items found.</p>
        @endforelse
    </div>
    
    {{-- Pagination --}}
    <div class="mt-6">
        {{ $items->links() }}
    </div>
</div>
```

### Alpine.js Standards

```html
<div 
    x-data="{ 
        open: false,
        selected: null,
        init() {
            // Initialization
        }
    }"
    x-init="init()"
>
    <button 
        @click="open = !open"
        :class="{ 'bg-blue-500': open }"
    >
        Toggle
    </button>
    
    <div 
        x-show="open"
        x-transition
    >
        Content
    </div>
</div>
```

### JavaScript Standards

```javascript
// Use ES6+ syntax
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
};

// Use descriptive names
const calculateTotalWithTax = (subtotal, taxRate) => {
    const tax = subtotal * taxRate;
    return subtotal + tax;
};

// Use JSDoc comments
/**
 * Format date to Indonesian locale
 * @param {Date} date - The date to format
 * @returns {string} Formatted date string
 */
const formatDate = (date) => {
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
```

---

## 🧪 Testing Guidelines

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ProductTest.php

# Run with coverage
php artisan test --coverage

# Run specific test method
php artisan test --filter testUserCanCreateProduct
```

### Writing Tests

#### Feature Test Example
```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_create_product(): void
    {
        // Arrange
        $user = User::factory()->create(['role' => 'seller']);
        $this->actingAs($user);
        
        $productData = [
            'name' => 'Test Product',
            'price' => 100000,
            'stock' => 10,
            'category' => 'electronics',
        ];
        
        // Act
        $response = $this->post(route('products.store'), $productData);
        
        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'user_id' => $user->id,
        ]);
    }
    
    public function test_buyer_cannot_create_product(): void
    {
        $user = User::factory()->create(['role' => 'buyer']);
        $this->actingAs($user);
        
        $response = $this->post(route('products.store'), []);
        
        $response->assertForbidden();
    }
}
```

#### Unit Test Example
```php
<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_calculates_total_correctly(): void
    {
        $product = Product::factory()->create(['price' => 50000]);
        $order = Order::factory()->create([
            'product_id' => $product->id,
            'quantity' => 3,
        ]);
        
        $this->assertEquals(150000, $order->total_price);
    }
}
```

### Livewire Testing
```php
<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ProductBrowser;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductBrowserTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_can_search_products(): void
    {
        Product::factory()->create(['name' => 'iPhone']);
        Product::factory()->create(['name' => 'Samsung']);
        
        Livewire::test(ProductBrowser::class)
            ->set('search', 'iPhone')
            ->assertSee('iPhone')
            ->assertDontSee('Samsung');
    }
}
```

---

## 📝 Commit Guidelines

### Commit Message Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- **feat:** New feature
- **fix:** Bug fix
- **docs:** Documentation changes
- **style:** Code style changes (formatting)
- **refactor:** Code refactoring
- **test:** Adding or updating tests
- **chore:** Maintenance tasks

### Examples

```
feat(payment): add Midtrans integration

Implement Midtrans Snap API for payment processing.
Includes payment callback handler and transaction recording.

Closes #123
```

```
fix(checkout): resolve quantity validation issue

Fixed bug where users could checkout with 0 quantity.
Added validation in CheckoutOrder component.
```

```
docs(readme): update installation steps

Added missing steps for Laravel scheduler setup.
```

### Commit Best Practices

- ✅ Write in present tense ("Add feature" not "Added feature")
- ✅ Use imperative mood ("Move cursor" not "Moves cursor")
- ✅ Limit first line to 72 characters
- ✅ Reference issues/PRs in footer
- ✅ Make atomic commits (one logical change per commit)

---

## 🔀 Pull Request Process

### Before Creating PR

1. ✅ Ensure all tests pass: `php artisan test`
2. ✅ Run code style check: `./vendor/bin/pint`
3. ✅ Update documentation if needed
4. ✅ Rebase on latest develop: `git rebase upstream/develop`
5. ✅ Squash commits if necessary

### PR Title Format

```
[Type] Short description (#issue-number)
```

Examples:
- `[Feature] Add seller rating system (#45)`
- `[Fix] Resolve payment upload issue (#78)`
- `[Docs] Update API documentation (#91)`

### PR Description Template

```markdown
## Description
Brief description of changes.

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Related Issue
Closes #(issue number)

## Changes Made
- Change 1
- Change 2
- Change 3

## Screenshots (if applicable)
![Screenshot](url)

## Testing
- [ ] Unit tests pass
- [ ] Feature tests pass
- [ ] Manual testing completed

## Checklist
- [ ] Code follows project style guidelines
- [ ] Self-review completed
- [ ] Comments added for complex code
- [ ] Documentation updated
- [ ] No new warnings generated
```

### Review Process

1. **Automated Checks:** CI/CD must pass
2. **Code Review:** At least 1 approval required
3. **Testing:** Reviewer tests changes locally
4. **Merge:** Maintainer merges to develop

### After Merge

- Delete feature branch
- Update local repository
- Close related issues

---

## 🐛 Bug Reports

### Before Reporting

1. Check existing issues
2. Try to reproduce in clean environment
3. Collect error logs and screenshots

### Bug Report Template

```markdown
**Bug Description**
Clear description of the bug.

**To Reproduce**
Steps to reproduce:
1. Go to '...'
2. Click on '...'
3. Scroll down to '...'
4. See error

**Expected Behavior**
What should happen.

**Actual Behavior**
What actually happens.

**Screenshots**
Add screenshots if applicable.

**Environment**
- OS: [e.g. Windows 11]
- Browser: [e.g. Chrome 120]
- PHP Version: [e.g. 8.2.0]
- Laravel Version: [e.g. 12.0]

**Additional Context**
Any other relevant information.

**Error Logs**
```
Paste error logs here
```
```

---

## 💡 Feature Requests

### Feature Request Template

```markdown
**Feature Description**
Clear description of the feature.

**Problem Statement**
What problem does this solve?

**Proposed Solution**
How should it work?

**Alternatives Considered**
What other solutions did you consider?

**Additional Context**
Mockups, examples, or references.

**Priority**
- [ ] Low
- [ ] Medium
- [ ] High
- [ ] Critical
```

---

## 🎨 Design Guidelines

### UI/UX Principles

1. **Consistency:** Use existing components and patterns
2. **Simplicity:** Keep interfaces clean and intuitive
3. **Feedback:** Provide clear feedback for user actions
4. **Accessibility:** Follow WCAG 2.1 guidelines
5. **Responsiveness:** Mobile-first approach

### Tailwind CSS Usage

```html
<!-- Good: Use Tailwind utility classes -->
<button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
    Submit
</button>

<!-- Bad: Avoid inline styles -->
<button style="padding: 8px 16px; background: blue;">
    Submit
</button>
```

### Component Reusability

Create reusable Blade components:

```bash
php artisan make:component Button
```

```blade
{{-- resources/views/components/button.blade.php --}}
<button {{ $attributes->merge(['class' => 'px-4 py-2 rounded']) }}>
    {{ $slot }}
</button>
```

Usage:
```blade
<x-button class="bg-blue-600 text-white">
    Click Me
</x-button>
```

---

## 📚 Resources

### Documentation
- [Laravel Docs](https://laravel.com/docs)
- [Livewire Docs](https://livewire.laravel.com)
- [Alpine.js Docs](https://alpinejs.dev)
- [Tailwind CSS Docs](https://tailwindcss.com)

### Learning
- [Laracasts](https://laracasts.com)
- [Laravel Daily](https://laraveldaily.com)
- [Livewire Screencasts](https://livewire.laravel.com/screencasts)

### Tools
- [Laravel Pint](https://laravel.com/docs/pint) - Code styling
- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar) - Debugging
- [Laravel IDE Helper](https://github.com/barryvdh/laravel-ide-helper) - IDE support

---

## 🏆 Recognition

Contributors will be recognized in:
- README.md contributors section
- Release notes
- Project website (when launched)

Top contributors may receive:
- Committer status
- Special badges
- Invitation to maintainer team

---

## 📧 Contact

Questions? Reach out to:
- **Email:** dev@sentripay.com
- **Discord:** [Join our server](#)
- **Twitter:** [@SentriPay](#)

---

## ⚖️ License

By contributing, you agree that your contributions will be licensed under the same license as the project (MIT License).

---

**Thank you for contributing to SentriPay! 🙏**

Together, we're building a better escrow platform for everyone.
