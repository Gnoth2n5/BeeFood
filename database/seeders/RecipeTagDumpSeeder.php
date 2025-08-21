<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\RecipeTag;

class RecipeTagDumpSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('recipe_tags')->truncate();

        // Insert data
        $data = [
            [
                'recipe_id' => 8,
                'tag_id' => 1,
            ],
            [
                'recipe_id' => 9,
                'tag_id' => 2,
            ],
            [
                'recipe_id' => 2,
                'tag_id' => 3,
            ],
            [
                'recipe_id' => 8,
                'tag_id' => 4,
            ],
            [
                'recipe_id' => 3,
                'tag_id' => 5,
            ],
            [
                'recipe_id' => 7,
                'tag_id' => 5,
            ],
            [
                'recipe_id' => 8,
                'tag_id' => 5,
            ],
            [
                'recipe_id' => 1,
                'tag_id' => 6,
            ],
            [
                'recipe_id' => 8,
                'tag_id' => 6,
            ],
            [
                'recipe_id' => 3,
                'tag_id' => 7,
            ],
            [
                'recipe_id' => 4,
                'tag_id' => 7,
            ],
            [
                'recipe_id' => 7,
                'tag_id' => 7,
            ],
            [
                'recipe_id' => 8,
                'tag_id' => 7,
            ],
            [
                'recipe_id' => 7,
                'tag_id' => 11,
            ],
            [
                'recipe_id' => 5,
                'tag_id' => 12,
            ],
            [
                'recipe_id' => 8,
                'tag_id' => 14,
            ],
            [
                'recipe_id' => 7,
                'tag_id' => 15,
            ],
            [
                'recipe_id' => 3,
                'tag_id' => 16,
            ],
            [
                'recipe_id' => 7,
                'tag_id' => 16,
            ],
            [
                'recipe_id' => 3,
                'tag_id' => 17,
            ],
            [
                'recipe_id' => 5,
                'tag_id' => 17,
            ],
            [
                'recipe_id' => 7,
                'tag_id' => 17,
            ],
            [
                'recipe_id' => 5,
                'tag_id' => 19,
            ],
            [
                'recipe_id' => 8,
                'tag_id' => 19,
            ],
            [
                'recipe_id' => 4,
                'tag_id' => 20,
            ],
            [
                'recipe_id' => 3,
                'tag_id' => 24,
            ],
            [
                'recipe_id' => 1,
                'tag_id' => 25,
            ],
            [
                'recipe_id' => 4,
                'tag_id' => 25,
            ],
            [
                'recipe_id' => 8,
                'tag_id' => 26,
            ],
            [
                'recipe_id' => 2,
                'tag_id' => 27,
            ],
            [
                'recipe_id' => 5,
                'tag_id' => 27,
            ],
            [
                'recipe_id' => 4,
                'tag_id' => 29,
            ],
            [
                'recipe_id' => 2,
                'tag_id' => 32,
            ],
            [
                'recipe_id' => 4,
                'tag_id' => 32,
            ],
            [
                'recipe_id' => 8,
                'tag_id' => 32,
            ],
            [
                'recipe_id' => 4,
                'tag_id' => 33,
            ],
            [
                'recipe_id' => 7,
                'tag_id' => 33,
            ],
            [
                'recipe_id' => 4,
                'tag_id' => 35,
            ],
            [
                'recipe_id' => 8,
                'tag_id' => 35,
            ],
        ];

        DB::table('recipe_tags')->insert($data);
    }
}
