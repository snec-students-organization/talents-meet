<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$events = App\Models\Event::where('stream', 'she')->latest()->take(3)->get();

echo "--- SHE EVENTS ---\n";
foreach($events as $e) {
    echo "ID: {$e->id}, Name: {$e->name}\n";
    echo "  Stream: '{$e->stream}'\n";
    echo "  Type: '{$e->type}' (individual/group)\n";
    echo "  StageType: '{$e->stage_type}' (stage/non_stage)\n";
    echo "  Level: " . var_export($e->level, true) . "\n";
    echo "------------------\n";
}

$u = App\Models\User::where('stream', 'she')->first();
echo "--- SHE INSTITUTION ---\n";
if ($u) {
    echo "Name: {$u->name}, Stream: '{$u->stream}'\n";
    echo "Levels: " . json_encode($u->levels) . "\n";
} else {
    echo "Not found.\n";
}
