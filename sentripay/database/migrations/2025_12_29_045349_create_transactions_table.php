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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('from_user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('to_user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('type', [
                'deposit',              // Pembeli transfer ke rekber
                'hold',                 // Dana ditahan saat order dibuat
                'release',              // Dana dilepas ke seller
                'refund',               // Pengembalian dana ke pembeli
                'commission'            // Komisi platform (jika ada)
            ])->default('hold');
            $table->enum('status', [
                'pending',              // Menunggu konfirmasi
                'processing',           // Sedang diproses
                'completed',            // Selesai
                'failed',               // Gagal
                'cancelled'             // Dibatalkan
            ])->default('pending');
            $table->text('description')->nullable();
            $table->text('bank_proof')->nullable();        // Path bukti transfer
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
