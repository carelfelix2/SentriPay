<?php

namespace App\Livewire\Seller;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductsManager extends Component
{
    use WithFileUploads;

    public $user;
    public $products;
    public $search = '';
    public $filterStatus = '';

    // Form fields
    public $editingId = null;
    public $name = '';
    public $description = '';
    public $price = '';
    public $stock = '';
    public $category = '';
    public $status = 'available';
    public $image;

    // Stats
    public $totalOrders = 0;
    public $completedOrders = 0;
    public $pendingOrders = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category' => 'nullable|string|max:100',
        'status' => 'required|in:available,sold_out,inactive',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadData();
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

    public function updatedFilterStatus()
    {
        $this->loadProducts();
    }

    public function loadData()
    {
        $this->loadProducts();
        $this->loadStats();
    }

    public function loadProducts()
    {
        $query = Product::where('user_id', $this->user->id)->latest();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $this->products = $query->take(50)->get();
    }

    public function loadStats()
    {
        $this->totalOrders = Order::where('seller_id', $this->user->id)->count();
        $this->completedOrders = Order::where('seller_id', $this->user->id)->where('status', 'completed')->count();
        $this->pendingOrders = Order::where('seller_id', $this->user->id)->where('status', 'pending_payment')->count();
    }

    public function startCreate()
    {
        $this->resetForm();
    }

    public function startEdit($productId)
    {
        $product = Product::where('user_id', $this->user->id)->findOrFail($productId);
        $this->editingId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->category = $product->category;
        $this->status = $product->status;
        $this->image = null;
    }

    public function save()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        if ($this->editingId) {
            $product = Product::where('user_id', $this->user->id)->findOrFail($this->editingId);
            $product->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'category' => $this->category,
                'status' => $this->status,
                'image_path' => $imagePath ?: $product->image_path,
            ]);
        } else {
            Product::create([
                'user_id' => $this->user->id,
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'category' => $this->category,
                'status' => $this->status,
                'image_path' => $imagePath,
                'sold' => 0,
            ]);
        }

        $this->resetForm();
        $this->loadProducts();
        session()->flash('message', 'Produk berhasil disimpan');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->stock = '';
        $this->category = '';
        $this->status = 'available';
        $this->image = null;
    }

    public function render()
    {
        return view('livewire.seller.products-manager')
            ->layout('components.layouts.app');
    }
}
