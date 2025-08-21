<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleDumpSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('roles')->truncate();

        // Insert data
        $data = [
            [
                'id' => 1,
                'name' => 'user',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 2,
                'name' => 'manager',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 3,
                'name' => 'admin',
                'guard_name' => 'web',
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
        ];

        DB::table('roles')->insert($data);
    }
}
