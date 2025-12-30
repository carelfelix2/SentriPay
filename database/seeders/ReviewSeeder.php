<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get completed orders that don't have reviews yet
        $completedOrders = Order::where('status', 'completed')
            ->whereDoesntHave('review')
            ->with(['product', 'buyer', 'seller'])
            ->get();

        $comments = [
            5 => [
                'Produk sangat bagus! Sesuai deskripsi dan pengiriman cepat.',
                'Puas banget! Kualitas top dan pelayanan seller ramah.',
                'Recommended! Produk ori dan packing rapi.',
                'Mantap! Worth it banget, bakal beli lagi.',
                'Excellent! Fast response dan produk berkualitas.',
            ],
            4 => [
                'Bagus, cuma pengiriman agak lama.',
                'Produk oke, tapi packing bisa lebih rapi.',
                'Sesuai ekspektasi, recommended!',
                'Barang bagus, hanya sedikit berbeda dari foto.',
            ],
            3 => [
                'Lumayan, sesuai harga.',
                'Standar aja, tidak terlalu istimewa.',
                'Oke lah untuk harga segini.',
            ],
            2 => [
                'Agak mengecewakan, tidak sesuai ekspektasi.',
                'Kualitas kurang memuaskan.',
            ],
            1 => [
                'Sangat mengecewakan, tidak sesuai deskripsi.',
            ],
        ];

        foreach ($completedOrders as $order) {
            // Random rating with bias towards higher ratings
            $rand = rand(1, 100);
            if ($rand <= 60) {
                $rating = 5;
            } elseif ($rand <= 85) {
                $rating = 4;
            } elseif ($rand <= 95) {
                $rating = 3;
            } elseif ($rand <= 98) {
                $rating = 2;
            } else {
                $rating = 1;
            }

            $comment = $comments[$rating][array_rand($comments[$rating])];

            Review::create([
                'order_id' => $order->id,
                'product_id' => $order->product_id,
                'user_id' => $order->buyer_id,
                'seller_id' => $order->seller_id ?? $order->product->user_id,
                'rating' => $rating,
                'comment' => $comment,
            ]);
        }

        $this->command->info('Reviews seeded successfully!');
    }
}
