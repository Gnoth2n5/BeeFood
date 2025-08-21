<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\RecipeCategory;

class RecipeCategoryDumpSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('recipe_categories')->truncate();

        // Insert data
        $data = [
            [
                'recipe_id' => 3,
                'category_id' => 3,
            ],
            [
                'recipe_id' => 8,
                'category_id' => 3,
            ],
            [
                'recipe_id' => 9,
                'category_id' => 3,
            ],
            [
                'recipe_id' => 8,
                'category_id' => 5,
            ],
            [
                'recipe_id' => 4,
                'category_id' => 6,
            ],
            [
                'recipe_id' => 5,
                'category_id' => 6,
            ],
            [
                'recipe_id' => 1,
                'category_id' => 9,
            ],
            [
                'recipe_id' => 4,
                'category_id' => 9,
            ],
            [
                'recipe_id' => 5,
                'category_id' => 9,
            ],
            [
                'recipe_id' => 3,
                'category_id' => 10,
            ],
            [
                'recipe_id' => 7,
                'category_id' => 12,
            ],
            [
                'recipe_id' => 1,
                'category_id' => 13,
            ],
            [
                'recipe_id' => 7,
                'category_id' => 13,
            ],
            [
                'recipe_id' => 7,
                'category_id' => 14,
            ],
            [
                'recipe_id' => 2,
                'category_id' => 15,
            ],
            [
                'recipe_id' => 7,
                'category_id' => 15,
            ],
        ];

        DB::table('recipe_categories')->insert($data);
    }
}
