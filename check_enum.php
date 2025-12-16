<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = DB::select("SHOW COLUMNS FROM events WHERE Field = 'level'");
foreach ($columns as $column) {
    echo "Column: " . $column->Field . "\n";
    echo "Type: " . $column->Type . "\n";
}
