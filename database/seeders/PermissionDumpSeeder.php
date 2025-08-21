<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class PermissionDumpSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('permissions')->truncate();

        // Insert data
        $data = [
            [
                'id' => 1,
                'name' => 'recipe.view',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 2,
                'name' => 'recipe.create',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 3,
                'name' => 'recipe.edit',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 4,
                'name' => 'recipe.delete',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 5,
                'name' => 'recipe.approve',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 6,
                'name' => 'recipe.reject',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 7,
                'name' => 'category.view',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 8,
                'name' => 'category.create',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 9,
                'name' => 'category.edit',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 10,
                'name' => 'category.delete',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 11,
                'name' => 'tag.view',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 12,
                'name' => 'tag.create',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 13,
                'name' => 'tag.edit',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 14,
                'name' => 'tag.delete',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 15,
                'name' => 'user.view',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 16,
                'name' => 'user.create',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 17,
                'name' => 'user.edit',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 18,
                'name' => 'user.delete',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 19,
                'name' => 'user.ban',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 20,
                'name' => 'article.view',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 21,
                'name' => 'article.create',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 22,
                'name' => 'article.edit',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 23,
                'name' => 'article.delete',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 24,
                'name' => 'article.publish',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 25,
                'name' => 'collection.view',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 26,
                'name' => 'collection.create',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 27,
                'name' => 'collection.edit',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 28,
                'name' => 'collection.delete',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 29,
                'name' => 'rating.create',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 30,
                'name' => 'rating.edit',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 31,
                'name' => 'rating.delete',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 32,
                'name' => 'system.settings',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 33,
                'name' => 'system.backup',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 34,
                'name' => 'system.logs',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 35,
                'name' => 'system.analytics',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
        ];

        DB::table('permissions')->insert($data);
    }
}
