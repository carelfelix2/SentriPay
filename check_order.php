<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$order = App\Models\Order::where('order_number', 'ORD-20251230-69533C5E113DE')->with('chatGroup')->first();

if ($order) {
    echo "Order Number: " . $order->order_number . "\n";
    echo "Order Status: " . $order->status . "\n";
    echo "Order Delivered Date: " . ($order->delivered_date ?? 'null') . "\n";
    echo "Order Completed Date: " . ($order->completed_date ?? 'null') . "\n";
    echo "\n";
    
    if ($order->chatGroup) {
        echo "Chat Group Status: " . $order->chatGroup->status . "\n";
        echo "Buyer Confirmed: " . ($order->chatGroup->buyer_confirmed ? 'Yes' : 'No') . "\n";
        echo "Closed At: " . ($order->chatGroup->closed_at ?? 'null') . "\n";
    } else {
        echo "No chat group found\n";
    }
} else {
    echo "Order not found\n";
}
