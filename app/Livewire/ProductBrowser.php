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
        $query = Product::where('status', 'available')
            ->where('stock', '>', 0)
            ->where('user_id', '!=', auth()->id());

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

        $products = $query->with('seller')->paginate(12);

        return view('livewire.product-browser', [
            'products' => $products,
            'categories' => Product::distinct('category')->pluck('category'),
        ]);
    }
}
