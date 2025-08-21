<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Favorite;

class FavoriteDumpSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('favorites')->truncate();

        // Insert data
        $data = [
            [
                'id' => 1,
                'user_id' => 7,
                'recipe_id' => 1,
                'created_at' => '2025-08-14 02:58:30',
                'updated_at' => '2025-08-14 02:58:30',
            ],
        ];

        DB::table('favorites')->insert($data);
    }
}
