<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('buyer_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', [
                'pending_payment',      // Menunggu pembayaran dari pembeli
                'payment_confirmed',    // Pembayaran sudah diterima
                'shipped',              // Barang sudah dikirim
                'delivered',            // Barang sudah diterima pembeli
                'completed',            // Transaksi selesai, dana sudah dicairkan
                'cancelled',            // Transaksi dibatalkan
                'disputed'              // Ada dispute/komplain
            ])->default('pending_payment');
            $table->text('notes')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('shipped_date')->nullable();
            $table->timestamp('delivered_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
