<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM events WHERE Field = 'level'");
foreach ($columns as $column) {
    echo "Field: " . $column->Field . "\n";
    echo "Type: " . $column->Type . "\n";
    echo "Null: " . $column->Null . "\n"; // YES or NO
    echo "Default: " . $column->Default . "\n";
}
