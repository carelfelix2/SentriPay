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
        Schema::table('users', function (Blueprint $table) {
            // Add SentriPay specific columns
            $table->enum('role', ['buyer', 'seller', 'admin'])->default('buyer')->after('email_verified_at');
            $table->string('phone')->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('province')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('province');
            $table->string('bank_name')->nullable()->after('postal_code');
            $table->string('bank_account')->nullable()->after('bank_name');
            $table->string('account_holder')->nullable()->after('bank_account');
            $table->decimal('balance', 15, 2)->default(0)->after('account_holder');
            $table->enum('status', ['active', 'suspended', 'blocked'])->default('active')->after('balance');
            $table->timestamp('verified_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'phone', 'address', 'city', 'province', 'postal_code',
                'bank_name', 'bank_account', 'account_holder', 'balance', 'status', 'verified_at'
            ]);
        });
    }
};
