<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ComprehensiveDatabaseDumpSeeder extends Seeder
{
    /**
     * Tables to dump in order (respecting foreign key constraints)
     */
    private array $tableOrder = [
        'users',
        'user_profiles',
        'categories',
        'tags',
        'vietnam_cities',
        'weather_condition_rules',
        'weather_data',
        'moderation_rules',
        'collections',
        'recipes',
        'recipe_images',
        'posts',
        'comments',
        'ratings',
        'favorites',
        'shop_items',
        'user_shops',
        'payments',
    ];

    /**
     * Run the comprehensive database dump seeder.
     */
    public function run(): void
    {
        $this->command->info('Starting comprehensive database dump...');
        
        // Create a main seeder file
        $this->createMainSeeder();
        
        // Dump each table in order
        foreach ($this->tableOrder as $table) {
            if ($this->tableExists($table)) {
                $this->dumpTableToSeeder($table);
            }
        }
        
        // Create a restore seeder
        $this->createRestoreSeeder();
        
        $this->command->info('Comprehensive database dump completed!');
    }
    
    /**
     * Check if table exists
     */
    private function tableExists(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Create main seeder file
     */
    private function createMainSeeder(): void
    {
        $content = "<?php\n\n";
        $content .= "namespace Database\\Seeders;\n\n";
        $content .= "use Illuminate\\Database\\Seeder;\n\n";
        $content .= "class ComprehensiveDatabaseRestoreSeeder extends Seeder\n";
        $content .= "{\n";
        $content .= "    /**\n";
        $content .= "     * Run the seeder.\n";
        $content .= "     */\n";
        $content .= "    public function run(): void\n";
        $content .= "    {\n";
        $content .= "        // Clear all data first\n";
        $content .= "        \$this->clearAllData();\n\n";
        $content .= "        // Restore data in order\n";
        
        foreach ($this->tableOrder as $table) {
            if ($this->tableExists($table)) {
                $className = Str::studly(Str::singular($table)) . 'DumpSeeder';
                $content .= "        \$this->call({$className}::class);\n";
            }
        }
        
        $content .= "    }\n\n";
        $content .= "    /**\n";
        $content .= "     * Clear all data from tables\n";
        $content .= "     */\n";
        $content .= "    private function clearAllData(): void\n";
        $content .= "    {\n";
        $content .= "        // Disable foreign key checks\n";
        $content .= "        if (config('database.default') === 'mysql') {\n";
        $content .= "            DB::statement('SET FOREIGN_KEY_CHECKS = 0');\n";
        $content .= "        }\n\n";
        
        foreach (array_reverse($this->tableOrder) as $table) {
            if ($this->tableExists($table)) {
                $content .= "        DB::table('{$table}')->truncate();\n";
            }
        }
        
        $content .= "\n        // Re-enable foreign key checks\n";
        $content .= "        if (config('database.default') === 'mysql') {\n";
        $content .= "            DB::statement('SET FOREIGN_KEY_CHECKS = 1');\n";
        $content .= "        }\n";
        $content .= "    }\n";
        $content .= "}\n";
        
        File::put(database_path('seeders/ComprehensiveDatabaseRestoreSeeder.php'), $content);
        $this->command->info('Created main restore seeder: ComprehensiveDatabaseRestoreSeeder.php');
    }
    
    /**
     * Create restore seeder
     */
    private function createRestoreSeeder(): void
    {
        $content = "<?php\n\n";
        $content .= "namespace Database\\Seeders;\n\n";
        $content .= "use Illuminate\\Database\\Seeder;\n";
        $content .= "use Illuminate\\Support\\Facades\\DB;\n\n";
        $content .= "class DatabaseRestoreSeeder extends Seeder\n";
        $content .= "{\n";
        $content .= "    /**\n";
        $content .= "     * Run the database restore seeder.\n";
        $content .= "     */\n";
        $content .= "    public function run(): void\n";
        $content .= "    {\n";
        $content .= "        \$this->call(ComprehensiveDatabaseRestoreSeeder::class);\n";
        $content .= "    }\n";
        $content .= "}\n";
        
        File::put(database_path('seeders/DatabaseRestoreSeeder.php'), $content);
        $this->command->info('Created restore seeder: DatabaseRestoreSeeder.php');
    }
    
    /**
     * Dump a specific table to a seeder file
     */
    private function dumpTableToSeeder(string $table): void
    {
        $this->command->info("Dumping table: {$table}");
        
        try {
            $data = DB::table($table)->get();
            
            if ($data->isEmpty()) {
                $this->command->warn("Table {$table} is empty, skipping...");
                return;
            }
            
            $seederContent = $this->generateSeederContent($table, $data);
            $filename = $this->getSeederFilename($table);
            
            File::put(database_path("seeders/{$filename}"), $seederContent);
            
            $this->command->info("Created seeder: {$filename} with " . $data->count() . " records");
            
        } catch (\Exception $e) {
            $this->command->error("Error dumping table {$table}: " . $e->getMessage());
        }
    }
    
    /**
     * Generate seeder content for a table
     */
    private function generateSeederContent(string $table, $data): string
    {
        $className = Str::studly(Str::singular($table)) . 'DumpSeeder';
        $modelName = Str::studly(Str::singular($table));
        
        $content = "<?php\n\n";
        $content .= "namespace Database\\Seeders;\n\n";
        $content .= "use Illuminate\\Database\\Seeder;\n";
        $content .= "use Illuminate\\Support\\Facades\\DB;\n";
        $content .= "use App\\Models\\{$modelName};\n\n";
        
        $content .= "class {$className} extends Seeder\n";
        $content .= "{\n";
        $content .= "    /**\n";
        $content .= "     * Run the seeder.\n";
        $content .= "     */\n";
        $content .= "    public function run(): void\n";
        $content .= "    {\n";
        $content .= "        // Insert data\n";
        $content .= "        \$data = [\n";
        
        foreach ($data as $row) {
            $content .= "            [\n";
            foreach ((array) $row as $key => $value) {
                if ($value === null) {
                    $content .= "                '{$key}' => null,\n";
                } elseif (is_bool($value)) {
                    $content .= "                '{$key}' => " . ($value ? 'true' : 'false') . ",\n";
                } elseif (is_numeric($value)) {
                    $content .= "                '{$key}' => {$value},\n";
                } else {
                    $escapedValue = str_replace("'", "\\'", $value);
                    $content .= "                '{$key}' => '{$escapedValue}',\n";
                }
            }
            $content .= "            ],\n";
        }
        
        $content .= "        ];\n\n";
        $content .= "        DB::table('{$table}')->insert(\$data);\n";
        $content .= "    }\n";
        $content .= "}\n";
        
        return $content;
    }
    
    /**
     * Get the filename for the seeder
     */
    private function getSeederFilename(string $table): string
    {
        $className = Str::studly(Str::singular($table)) . 'DumpSeeder';
        return $className . '.php';
    }
}
