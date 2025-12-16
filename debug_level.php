<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$events = App\Models\Event::where('stream', 'she')->get();
echo "Events Count: " . $events->count() . "\n";
foreach($events as $e) {
    echo "ID: {$e->id}, Level type: " . gettype($e->level) . ", Value: ";
    var_dump($e->level);
}
