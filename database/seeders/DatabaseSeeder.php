<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin users
        User::firstOrCreate(
            ['email' => 'admin@aquaheart.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'manager@aquaheart.com'],
            [
                'name' => 'Manager',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create water bottle products
        Product::firstOrCreate(
            ['name' => '5L Bottle'],
            [
                'price' => 2.50,
                'stock_quantity' => 120,
                'reorder_level' => 20,
                'description' => 'Standard 5 liter water bottle refill',
                'is_active' => true,
            ]
        );

        Product::firstOrCreate(
            ['name' => '10L Bottle'],
            [
                'price' => 4.50,
                'stock_quantity' => 80,
                'reorder_level' => 15,
                'description' => 'Large 10 liter water bottle refill',
                'is_active' => true,
            ]
        );
    }
}
