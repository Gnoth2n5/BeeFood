<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DatabaseDumpSeeder extends Seeder
{
    /**
     * Run the database dump seeder.
     */
    public function run(): void
    {
        $this->command->info('Starting database dump to seeder files...');
        
        // Get all tables
        $tables = $this->getTables();
        
        foreach ($tables as $table) {
            $this->dumpTableToSeeder($table);
        }
        
        $this->command->info('Database dump completed!');
    }
    
    /**
     * Get all tables from the database
     */
    private function getTables(): array
    {
        $connection = config('database.default');
        
        if ($connection === 'sqlite') {
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            return array_map(fn($table) => $table->name, $tables);
        }
        
        if ($connection === 'mysql') {
            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . config('database.connections.mysql.database');
            return array_map(fn($table) => $table->$tableKey, $tables);
        }
        
        if ($connection === 'pgsql') {
            $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
            return array_map(fn($table) => $table->tablename, $tables);
        }
        
        return [];
    }
    
    /**
     * Dump a specific table to a seeder file
     */
    private function dumpTableToSeeder(string $table): void
    {
        // Skip certain tables
        if (in_array($table, ['migrations', 'failed_jobs', 'password_reset_tokens', 'personal_access_tokens'])) {
            return;
        }
        
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
            
            $this->command->info("Created seeder: {$filename}");
            
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
        $content .= "        // Clear existing data\n";
        $content .= "        DB::table('{$table}')->truncate();\n\n";
        
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
