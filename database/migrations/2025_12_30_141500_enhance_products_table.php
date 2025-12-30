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
        Schema::table('products', function (Blueprint $table) {
            // Media Management
            $table->text('images_json')->nullable()->after('image_path'); // JSON array of image paths
            
            // Logistics
            $table->decimal('weight', 8, 2)->nullable()->after('stock'); // Berat dalam kg
            $table->string('length')->nullable()->after('weight'); // Panjang
            $table->string('width')->nullable()->after('length');  // Lebar
            $table->string('height')->nullable()->after('width');  // Tinggi
            
            // Shipping Couriers
            $table->text('enabled_couriers')->nullable()->after('height'); // JSON array of enabled couriers
            
            // Wholesale Price
            $table->decimal('wholesale_price', 15, 2)->nullable()->after('price');
            $table->integer('wholesale_min_qty')->default(0)->after('wholesale_price');
            
            // Video
            $table->string('video_path')->nullable()->after('images_json');
            
            // Specifications
            $table->text('specifications_json')->nullable()->after('description'); // JSON for brand, material, etc
            
            // Status enhancements
            $table->enum('archive_status', ['active', 'archived'])->default('active')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'images_json',
                'weight',
                'length',
                'width',
                'height',
                'enabled_couriers',
                'wholesale_price',
                'wholesale_min_qty',
                'video_path',
                'specifications_json',
                'archive_status',
            ]);
        });
    }
};
