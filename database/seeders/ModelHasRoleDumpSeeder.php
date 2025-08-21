<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ModelHasRole;

class ModelHasRoleDumpSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('model_has_roles')->truncate();

        // Insert data
        $data = [
            [
                'role_id' => 3,
                'model_type' => 'App\Models\User',
                'model_id' => 1,
            ],
            [
                'role_id' => 2,
                'model_type' => 'App\Models\User',
                'model_id' => 2,
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => 3,
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => 4,
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => 5,
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => 7,
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => 8,
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => 9,
            ],
        ];

        DB::table('model_has_roles')->insert($data);
    }
}
