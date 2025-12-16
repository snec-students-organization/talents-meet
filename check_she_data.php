<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$event = App\Models\Event::where('stream', 'she')->latest()->first();
echo "Latest She Event:\n";
if ($event) {
    echo "ID: " . $event->id . "\n";
    echo "Name: " . $event->name . "\n";
    echo "Stream: " . $event->stream . "\n";
    echo "Level: " . var_export($event->level, true) . "\n";
} else {
    echo "No She Event found.\n";
}

$user = App\Models\User::where('stream', 'she')->first();
echo "\nShe Institution:\n";
if ($user) {
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Stream: " . $user->stream . "\n";
    echo "Levels: " . var_export($user->levels, true) . "\n";
} else {
    echo "No She Institution found.\n";
}
