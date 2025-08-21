<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ShopItem;

class ShopItemDumpSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('shop_items')->truncate();

        // Insert data
        $data = [
            [
                'id' => 1,
                'user_shop_id' => 1,
                'name' => 'iste non veritatis architecto',
                'description' => 'Tenetur fugit corporis esse est aspernatur sequi. Nostrum inventore perferendis occaecati consectetur soluta rem.',
                'embedding' => null,
                'price' => 186.36,
                'featured_image' => 'https://via.placeholder.com/400x300.png/00aaaa?text=products+suscipit',
                'is_active' => 1,
                'stock_quantity' => 69,
                'sku' => 'SKU-EY61ES',
                'created_at' => '2025-08-19 06:43:28',
                'updated_at' => '2025-08-19 06:43:28',
            ],
            [
                'id' => 2,
                'user_shop_id' => 1,
                'name' => 'ut quia',
                'description' => 'Dolor totam cupiditate nesciunt et sequi fuga. In debitis expedita eum facilis autem.',
                'embedding' => null,
                'price' => 281.50,
                'featured_image' => 'https://via.placeholder.com/400x300.png/002222?text=products+nulla',
                'is_active' => 0,
                'stock_quantity' => 3,
                'sku' => 'SKU-SM23ZP',
                'created_at' => '2025-08-19 06:43:28',
                'updated_at' => '2025-08-19 06:43:28',
            ],
            [
                'id' => 3,
                'user_shop_id' => 1,
                'name' => 'odit omnis',
                'description' => 'Inventore vel non perferendis. Perspiciatis dolore mollitia iusto velit. Accusantium beatae maxime dolores rerum. Aut officiis consequuntur quo enim. Corrupti assumenda sint aut quibusdam rerum.',
                'embedding' => null,
                'price' => 160.54,
                'featured_image' => 'https://via.placeholder.com/400x300.png/0055bb?text=products+voluptatem',
                'is_active' => 1,
                'stock_quantity' => 76,
                'sku' => 'SKU-IY88IZ',
                'created_at' => '2025-08-19 06:43:28',
                'updated_at' => '2025-08-19 06:43:28',
            ],
            [
                'id' => 31,
                'user_shop_id' => 3,
                'name' => 'Phở bò Điện Biên',
                'description' => null,
                'embedding' => null,
                'price' => 30000.00,
                'featured_image' => null,
                'is_active' => 1,
                'stock_quantity' => 0,
                'sku' => null,
                'created_at' => '2025-08-20 04:32:15',
                'updated_at' => '2025-08-20 04:32:15',
            ],
            [
                'id' => 32,
                'user_shop_id' => 3,
                'name' => 'Bánh mì Điện Biên',
                'description' => null,
                'embedding' => null,
                'price' => 15000.00,
                'featured_image' => null,
                'is_active' => 1,
                'stock_quantity' => 0,
                'sku' => null,
                'created_at' => '2025-08-20 04:32:15',
                'updated_at' => '2025-08-20 04:32:15',
            ],
            [
                'id' => 113,
                'user_shop_id' => 4,
                'name' => 'Thịt lợn ( 1kg )',
                'description' => null,
                'embedding' => null,
                'price' => 120000.00,
                'featured_image' => 'shop_items/shop_4_1755740724_RMlIOyKB.jpg',
                'is_active' => 1,
                'stock_quantity' => 0,
                'sku' => null,
                'created_at' => '2025-08-21 01:45:24',
                'updated_at' => '2025-08-21 01:45:24',
            ],
            [
                'id' => 114,
                'user_shop_id' => 4,
                'name' => 'Rau',
                'description' => null,
                'embedding' => null,
                'price' => null,
                'featured_image' => 'shop_items/shop_4_1755740724_GCRCOBad.jpg',
                'is_active' => 1,
                'stock_quantity' => 0,
                'sku' => null,
                'created_at' => '2025-08-21 01:45:24',
                'updated_at' => '2025-08-21 01:45:24',
            ],
            [
                'id' => 115,
                'user_shop_id' => 4,
                'name' => 'Thịt gà',
                'description' => null,
                'embedding' => null,
                'price' => 90000.00,
                'featured_image' => 'shop_items/shop_4_1755740724_IVMlN8eO.jpg',
                'is_active' => 1,
                'stock_quantity' => 0,
                'sku' => null,
                'created_at' => '2025-08-21 01:45:24',
                'updated_at' => '2025-08-21 01:45:24',
            ],
            [
                'id' => 124,
                'user_shop_id' => 5,
                'name' => 'Bánh xèo',
                'description' => null,
                'embedding' => null,
                'price' => 30000.00,
                'featured_image' => null,
                'is_active' => 1,
                'stock_quantity' => 0,
                'sku' => null,
                'created_at' => '2025-08-21 06:42:05',
                'updated_at' => '2025-08-21 06:42:05',
            ],
        ];

        DB::table('shop_items')->insert($data);
    }
}
