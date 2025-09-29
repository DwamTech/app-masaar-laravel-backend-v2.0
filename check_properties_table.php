<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Checking properties table structure:\n";

// Get column listing
$columns = Schema::getColumnListing('properties');
echo "Columns in properties table:\n";
foreach ($columns as $column) {
    echo "- $column\n";
}

echo "\nTable structure details:\n";
$tableInfo = DB::select("DESCRIBE properties");
foreach ($tableInfo as $column) {
    echo "Column: {$column->Field}, Type: {$column->Type}, Null: {$column->Null}, Default: {$column->Default}\n";
}