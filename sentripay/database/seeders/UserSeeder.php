<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin SentriPay',
            'email' => 'admin@sentripay.com',
            'phone' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'balance' => 0,
            'kyc_verified' => true,
            'status' => 'active',
        ]);

        // Seller User
        User::create([
            'name' => 'John Seller',
            'email' => 'seller@sentripay.com',
            'phone' => '081234567891',
            'role' => 'seller',
            'password' => Hash::make('password'),
            'balance' => 1000000,
            'kyc_verified' => true,
            'status' => 'active',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'John Seller',
        ]);

        // Buyer User
        User::create([
            'name' => 'Jane Buyer',
            'email' => 'buyer@sentripay.com',
            'phone' => '081234567892',
            'role' => 'buyer',
            'password' => Hash::make('password'),
            'balance' => 0,
            'kyc_verified' => true,
            'status' => 'active',
        ]);
    }
}
