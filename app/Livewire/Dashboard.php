<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;

class Dashboard extends Component
{
    public $user;
    public $totalOrders = 0;
    public $totalSales = 0;
    public $totalPurchases = 0;
    public $balance = 0;
    public $recentOrders = [];
    public $availableProducts = [];
    public $myProducts = [];

    public function mount()
    {
        $this->user = auth()->user();
        
        if ($this->user) {
            $this->loadData();
        }
    }

    public function loadData()
    {
        // Load orders data
        if ($this->user->role === 'seller') {
            $this->totalOrders = Order::where('seller_id', $this->user->id)->count();
            $this->totalSales = Order::where('seller_id', $this->user->id)
                ->where('status', 'completed')
                ->sum('total_amount');
            $this->recentOrders = Order::where('seller_id', $this->user->id)
                ->latest()
                ->take(5)
                ->get();
        } else {
            $this->totalOrders = Order::where('buyer_id', $this->user->id)->count();
            $this->totalPurchases = Order::where('buyer_id', $this->user->id)
                ->where('status', 'completed')
                ->sum('total_amount');
            $this->recentOrders = Order::where('buyer_id', $this->user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        $this->balance = $this->user->balance;

        // Load products
        $this->availableProducts = Product::where('status', 'available')
            ->where('user_id', '!=', $this->user->id)
            ->where('stock', '>', 0)
            ->with('seller')
            ->latest()
            ->take(6)
            ->get();

        $this->myProducts = Product::where('user_id', $this->user->id)
            ->latest()
            ->take(6)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
