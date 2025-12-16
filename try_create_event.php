<?php

use App\Models\Event;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Attempting to create Bayyinath event...\n";
    $event = Event::create([
        'name' => 'Test Bayyinath Event',
        'category' => 'A',
        'type' => 'individual',
        'stage_type' => 'stage',
        'stream' => 'bayyinath',
        'level' => 'Sanaviyya Ulya',
        'created_by' => 1 // Assuming admin user exists with ID 1
    ]);
    echo "Event created successfully: " . $event->id . "\n";
} catch (\Exception $e) {
    echo "Error creating event: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
