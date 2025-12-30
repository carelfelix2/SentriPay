<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class OrderHistory extends Component
{
    public $activeTab = 'buyer'; // buyer or seller
    public $filterStatus = 'all';
    public $search = '';

    public function render()
    {
        $userId = auth()->id();
        
        // Query untuk pesanan pembeli (yang saya beli)
        $buyerOrdersQuery = Order::where('buyer_id', $userId)
            ->with(['product', 'seller']);
        
        // Query untuk pesanan penjual (yang masuk dari pembeli)
        $sellerOrdersQuery = Order::where(function ($q) use ($userId) {
            $q->where('seller_id', $userId)
              ->orWhereHas('product', function ($p) use ($userId) {
                  $p->where('user_id', $userId);
              });
        })->with(['product', 'buyer']);
        
        // Filter status
        if ($this->filterStatus !== 'all') {
            $buyerOrdersQuery->where('status', $this->filterStatus);
            $sellerOrdersQuery->where('status', $this->filterStatus);
        }
        
        // Search
        if ($this->search) {
            $buyerOrdersQuery->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('product', function ($p) {
                      $p->where('name', 'like', '%' . $this->search . '%');
                  });
            });
            
            $sellerOrdersQuery->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('product', function ($p) {
                      $p->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('buyer', function ($b) {
                      $b->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }
        
        $buyerOrders = $buyerOrdersQuery->orderBy('created_at', 'desc')->get();
        $sellerOrders = $sellerOrdersQuery->orderBy('created_at', 'desc')->get();
        
        // Hitung statistik
        $buyerStats = [
            'total' => Order::where('buyer_id', $userId)->count(),
            'pending' => Order::where('buyer_id', $userId)->where('status', 'pending_payment')->count(),
            'completed' => Order::where('buyer_id', $userId)->where('status', 'completed')->count(),
        ];
        
        $sellerStats = [
            'total' => Order::where('seller_id', $userId)->count(),
            'pending' => Order::where('seller_id', $userId)->where('status', 'pending_payment')->count(),
            'completed' => Order::where('seller_id', $userId)->where('status', 'completed')->count(),
        ];
        
        return view('livewire.order-history', [
            'buyerOrders' => $buyerOrders,
            'sellerOrders' => $sellerOrders,
            'buyerStats' => $buyerStats,
            'sellerStats' => $sellerStats,
        ]);
    }
}
