<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$order = App\Models\Order::where('order_number', 'ORD-20251230-69533C5E113DE')->first();

if ($order && $order->chatGroup && $order->chatGroup->buyer_confirmed && $order->chatGroup->status === 'closed') {
    // Update order status to completed
    $order->update([
        'status' => 'completed',
        'delivered_date' => $order->chatGroup->closed_at,
        'completed_date' => $order->chatGroup->closed_at
    ]);
    
    // Create transaction if not exists
    $existingTransaction = App\Models\Transaction::where('order_id', $order->id)
        ->where('type', 'release')
        ->where('to_user_id', $order->seller_id)
        ->first();
    
    if (!$existingTransaction) {
        App\Models\Transaction::create([
            'transaction_number' => (new App\Models\Transaction())->generateTransactionNumber(),
            'order_id' => $order->id,
            'from_user_id' => $order->buyer_id,
            'to_user_id' => $order->seller_id,
            'amount' => $order->total_amount,
            'type' => 'release',
            'status' => 'completed',
            'description' => "Pembayaran untuk pesanan {$order->order_number} - {$order->chatGroup->name}",
            'confirmed_at' => $order->chatGroup->closed_at
        ]);
        echo "Transaction created successfully!\n";
    } else {
        echo "Transaction already exists\n";
    }
    
    echo "Order status updated to completed!\n";
    echo "Order Status: " . $order->fresh()->status . "\n";
} else {
    echo "Order tidak memenuhi syarat untuk diupdate\n";
}
