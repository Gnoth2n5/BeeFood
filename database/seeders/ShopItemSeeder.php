<?php

namespace Database\Seeders;

use App\Models\ShopItem;
use App\Models\UserShop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ShopItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all user shops
        $userShops = UserShop::all();
        
        if ($userShops->isEmpty()) {
            $this->command->info('No user shops found. Please run UserShop seeder first.');
            return;
        }

        foreach ($userShops as $userShop) {
            // Create 3-8 shop items for each user shop
            $itemCount = rand(3, 8);
            
            for ($i = 0; $i < $itemCount; $i++) {
                ShopItem::create([
                    'user_shop_id' => $userShop->id,
                    'name' => $faker->words(rand(2, 4), true),
                    'description' => $faker->paragraph(rand(1, 3)),
                    'price' => $faker->randomFloat(2, 5, 500),
                    'featured_image' => $faker->imageUrl(400, 300, 'products'),
                    'is_active' => $faker->boolean(80), // 80% chance of being active
                    'stock_quantity' => $faker->numberBetween(0, 100),
                    'sku' => 'SKU-' . strtoupper($faker->bothify('??##??')),
                ]);
            }
        }

        $this->command->info('Shop items seeded successfully!');
    }
}
