<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;

class CheckoutOrder extends Component
{
    public $productId;
    public $product;
    public $quantity = 1;
    public $totalAmount = 0;
    public $showConfirmation = false;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->product = Product::findOrFail($productId);
        $this->calculateTotal();
    }

    public function updatedQuantity()
    {
        if ($this->quantity < 1) {
            $this->quantity = 1;
        }
        if ($this->quantity > $this->product->stock) {
            $this->quantity = $this->product->stock;
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalAmount = $this->product->price * $this->quantity;
    }

    public function confirmOrder()
    {
        $this->showConfirmation = true;
    }

    public function placeOrder()
    {
        $user = auth()->user();

        $order = Order::create([
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'buyer_id' => $user->id,
            'seller_id' => $this->product->user_id,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'unit_price' => $this->product->price,
            'total_amount' => $this->totalAmount,
            'status' => 'pending_payment',
        ]);

        return redirect()->route('order.payment', $order->order_number);
    }

    public function render()
    {
        return view('livewire.checkout-order');
    }
}
