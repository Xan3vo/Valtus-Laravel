<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Robux Packages
            [
                'name' => '100 Robux Package',
                'description' => 'Perfect for small purchases and testing',
                'category' => 'robux',
                'game_type' => 'Roblox',
                'price' => 10000,
                'tax_rate' => 30,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => '200 Robux Package',
                'description' => 'Great value for regular players',
                'category' => 'robux',
                'game_type' => 'Roblox',
                'price' => 20000,
                'tax_rate' => 30,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => '500 Robux Package',
                'description' => 'Most popular choice for serious players',
                'category' => 'robux',
                'game_type' => 'Roblox',
                'price' => 50000,
                'tax_rate' => 30,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => '1000 Robux Package',
                'description' => 'Best value for bulk purchases',
                'category' => 'robux',
                'game_type' => 'Roblox',
                'price' => 100000,
                'tax_rate' => 30,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => '2000 Robux Package',
                'description' => 'Premium package for dedicated players',
                'category' => 'robux',
                'game_type' => 'Roblox',
                'price' => 200000,
                'tax_rate' => 30,
                'is_active' => true,
                'sort_order' => 5,
            ],
            
            // Blox Fruits Items
            [
                'name' => 'Fruit Notifier',
                'description' => 'Get notified when any fruit spawns in the game',
                'category' => 'gamepass',
                'game_type' => 'Blox Fruits',
                'price' => 25000,
                'tax_rate' => 30,
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => '2x Money',
                'description' => 'Double your money earnings for 1 hour',
                'category' => 'gamepass',
                'game_type' => 'Blox Fruits',
                'price' => 15000,
                'tax_rate' => 30,
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'name' => '2x Experience',
                'description' => 'Double your experience gains for 1 hour',
                'category' => 'gamepass',
                'game_type' => 'Blox Fruits',
                'price' => 15000,
                'tax_rate' => 30,
                'is_active' => true,
                'sort_order' => 12,
            ],
            
            // Other Game Items
            [
                'name' => 'VIP Server (7 Days)',
                'description' => 'Private server for you and your friends',
                'category' => 'item',
                'game_type' => 'Roblox',
                'price' => 5000,
                'tax_rate' => 30,
                'is_active' => true,
                'sort_order' => 20,
            ],
            [
                'name' => 'Premium Membership',
                'description' => 'Unlock premium features and exclusive content',
                'category' => 'item',
                'game_type' => 'Roblox',
                'price' => 30000,
                'tax_rate' => 30,
                'is_active' => true,
                'sort_order' => 21,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}