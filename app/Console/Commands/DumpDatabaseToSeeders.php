<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\DatabaseDumpSeeder;

class DumpDatabaseToSeeders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dump-to-seeders {--table= : Dump specific table only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump current database data to seeder files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database dump to seeder files...');
        
        if ($table = $this->option('table')) {
            $this->info("Dumping specific table: {$table}");
            // For single table dump, we'll modify the seeder behavior
        }
        
        $seeder = new DatabaseDumpSeeder();
        $seeder->setCommand($this);
        $seeder->run();
        
        $this->info('Database dump completed successfully!');
        $this->info('Check the database/seeders/ directory for the generated files.');
        
        return Command::SUCCESS;
    }
}
