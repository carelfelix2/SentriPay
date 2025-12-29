<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductBrowser extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sortBy = 'newest';
    public $products = [];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::where('status', 'available');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        $query = match($this->sortBy) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderBy('sold', 'desc'),
            default => $query->latest(),
        };

        $products = $query->paginate(12);

        return view('livewire.product-browser', [
            'products' => $products,
            'categories' => Product::distinct('category')->pluck('category'),
        ]);
    }
}
