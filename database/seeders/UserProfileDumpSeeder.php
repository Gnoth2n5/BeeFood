<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UserProfile;

class UserProfileDumpSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('user_profiles')->truncate();

        // Insert data
        $data = [
            [
                'id' => 1,
                'user_id' => 1,
                'phone' => 0123456789,
                'address' => 'Hà Nội, Việt Nam',
                'city' => 'Hà Nội',
                'country' => 'Vietnam',
                'timezone' => 'Asia/Ho_Chi_Minh',
                'language' => 'vi',
                'dietary_preferences' => '"[\"none\"]"',
                'allergies' => '"[]"',
                'health_conditions' => '"[]"',
                'cooking_experience' => 'advanced',
                'created_at' => '2025-08-08 05:52:39',
                'updated_at' => '2025-08-08 05:52:39',
                'isVipAccount' => 0,
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'phone' => 0987654321,
                'address' => 'TP.HCM, Việt Nam',
                'city' => 'TP.HCM',
                'country' => 'Vietnam',
                'timezone' => 'Asia/Ho_Chi_Minh',
                'language' => 'vi',
                'dietary_preferences' => '"[\"vegetarian\"]"',
                'allergies' => '"[\"nuts\"]"',
                'health_conditions' => '"[]"',
                'cooking_experience' => 'intermediate',
                'created_at' => '2025-08-08 05:52:39',
                'updated_at' => '2025-08-08 05:52:39',
                'isVipAccount' => 0,
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'phone' => null,
                'address' => null,
                'city' => 'Hà Nội',
                'country' => 'Vietnam',
                'timezone' => 'Asia/Ho_Chi_Minh',
                'language' => 'vi',
                'dietary_preferences' => '"[\"none\"]"',
                'allergies' => '"[]"',
                'health_conditions' => '"[]"',
                'cooking_experience' => 'beginner',
                'created_at' => '2025-08-08 05:52:40',
                'updated_at' => '2025-08-08 05:52:40',
                'isVipAccount' => 0,
            ],
            [
                'id' => 4,
                'user_id' => 4,
                'phone' => null,
                'address' => null,
                'city' => 'Hà Nội',
                'country' => 'Vietnam',
                'timezone' => 'Asia/Ho_Chi_Minh',
                'language' => 'vi',
                'dietary_preferences' => '"[\"vegan\"]"',
                'allergies' => '"[\"dairy\"]"',
                'health_conditions' => '"[]"',
                'cooking_experience' => 'advanced',
                'created_at' => '2025-08-08 05:52:40',
                'updated_at' => '2025-08-08 05:52:40',
                'isVipAccount' => 0,
            ],
            [
                'id' => 5,
                'user_id' => 5,
                'phone' => null,
                'address' => null,
                'city' => 'Hà Nội',
                'country' => 'Vietnam',
                'timezone' => 'Asia/Ho_Chi_Minh',
                'language' => 'vi',
                'dietary_preferences' => '"[\"gluten-free\"]"',
                'allergies' => '"[\"shellfish\"]"',
                'health_conditions' => '"[]"',
                'cooking_experience' => 'intermediate',
                'created_at' => '2025-08-08 05:52:40',
                'updated_at' => '2025-08-08 05:52:40',
                'isVipAccount' => 0,
            ],
            [
                'id' => 6,
                'user_id' => 6,
                'phone' => '(580) 421-2442',
                'address' => '8731 William Pike Apt. 270
Wolffton, PA 91738',
                'city' => 'New Jeramieborough',
                'country' => 'Vietnam',
                'timezone' => 'Asia/Ho_Chi_Minh',
                'language' => 'vi',
                'dietary_preferences' => '[]',
                'allergies' => '[]',
                'health_conditions' => '[]',
                'cooking_experience' => 'intermediate',
                'created_at' => '2025-08-08 05:52:42',
                'updated_at' => '2025-08-21 03:31:12',
                'isVipAccount' => 0,
            ],
            [
                'id' => 7,
                'user_id' => 7,
                'phone' => '',
                'address' => '',
                'city' => '',
                'country' => 'Vietnam',
                'timezone' => 'Asia/Ho_Chi_Minh',
                'language' => 'vi',
                'dietary_preferences' => '["halal", "low_carb", "keto", "dairy_free", "paleo"]',
                'allergies' => '["đậu phộng", "hải sản", "sữa"]',
                'health_conditions' => '["tiểu đường"]',
                'cooking_experience' => 'beginner',
                'created_at' => '2025-08-13 11:18:43',
                'updated_at' => '2025-08-21 06:06:06',
                'isVipAccount' => 0,
            ],
            [
                'id' => 8,
                'user_id' => 8,
                'phone' => null,
                'address' => null,
                'city' => null,
                'country' => 'Vietnam',
                'timezone' => 'Asia/Ho_Chi_Minh',
                'language' => 'vi',
                'dietary_preferences' => '[]',
                'allergies' => '[]',
                'health_conditions' => '[]',
                'cooking_experience' => 'beginner',
                'created_at' => '2025-08-20 04:52:04',
                'updated_at' => '2025-08-20 04:54:57',
                'isVipAccount' => 1,
            ],
            [
                'id' => 9,
                'user_id' => 9,
                'phone' => null,
                'address' => null,
                'city' => null,
                'country' => 'Vietnam',
                'timezone' => 'Asia/Ho_Chi_Minh',
                'language' => 'vi',
                'dietary_preferences' => '[]',
                'allergies' => '[]',
                'health_conditions' => '[]',
                'cooking_experience' => 'beginner',
                'created_at' => '2025-08-21 06:01:17',
                'updated_at' => '2025-08-21 06:02:32',
                'isVipAccount' => 1,
            ],
        ];

        DB::table('user_profiles')->insert($data);
    }
}
