<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Livewire\Component;

class AdminDashboard extends Component
{
    public $totalUsers = 0;
    public $totalOrders = 0;
    public $totalProducts = 0;
    public $totalRevenue = 0;
    public $recentOrders = [];
    public $recentUsers = [];
    public $topProducts = [];
    public $usersByRole = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Statistics
        $this->totalUsers = User::count();
        $this->totalOrders = Order::count();
        $this->totalProducts = Product::count();
        $this->totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // Recent orders
        $this->recentOrders = Order::with(['buyer', 'seller', 'product'])
            ->latest()
            ->take(10)
            ->get();

        // Recent users
        $this->recentUsers = User::latest()
            ->take(10)
            ->get();

        // Top products by sales
        $this->topProducts = Product::orderBy('sold', 'desc')
            ->take(5)
            ->get();

        // Users by role
        $this->usersByRole = [
            'buyer' => User::where('role', 'buyer')->count(),
            'seller' => User::where('role', 'seller')->count(),
            'admin' => User::where('role', 'admin')->count(),
        ];
    }

    public function render()
    {
        return view('admin.dashboard');
    }
}
