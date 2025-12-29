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
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->string('dispute_number')->unique();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('complained_by')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('complained_against')->constrained('users', 'id')->onDelete('cascade');
            $table->enum('reason', [
                'item_not_received',    // Barang tidak sampai
                'item_not_as_described',// Barang tidak sesuai deskripsi
                'item_damaged',         // Barang rusak
                'seller_not_responding',// Penjual tidak merespons
                'payment_not_confirmed',// Pembayaran tidak dikonfirmasi
                'quality_issue',        // Masalah kualitas
                'other'                 // Lainnya
            ]);
            $table->text('complaint_description');
            $table->enum('status', [
                'open',                 // Baru dibuka
                'in_review',            // Sedang ditinjau admin
                'resolved',             // Selesai
                'appeal',               // Ada banding
                'closed'                // Ditutup
            ])->default('open');
            $table->text('evidence')->nullable();           // Path bukti/gambar
            $table->text('admin_notes')->nullable();        // Catatan admin
            $table->enum('resolution', [
                'refund_to_buyer',      // Kembalikan uang ke pembeli
                'keep_with_seller',     // Uang tetap ke penjual
                'split',                // Bagi dua
                'pending'               // Belum ditentukan
            ])->default('pending')->after('admin_notes');
            $table->foreignId('reviewed_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
