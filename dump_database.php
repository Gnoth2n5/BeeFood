<?php

/**
 * Database Dump Script
 * 
 * This script will dump your current database data to seeder files.
 * Run this from your Laravel project root directory.
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Starting database dump process...\n";

try {
    // Run the comprehensive database dump seeder
    $seeder = new \Database\Seeders\ComprehensiveDatabaseDumpSeeder();
    
    // Create a mock command object for output
    $command = new class {
        public function info($message) { echo "INFO: {$message}\n"; }
        public function warn($message) { echo "WARN: {$message}\n"; }
        public function error($message) { echo "ERROR: {$message}\n"; }
    };
    
    $seeder->setCommand($command);
    $seeder->run();
    
    echo "\nDatabase dump completed successfully!\n";
    echo "Check the database/seeders/ directory for the generated files.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
