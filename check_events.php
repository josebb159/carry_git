<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Events\Models\Event;

$events = Event::orderBy('id', 'desc')->take(2)->get();
foreach($events as $e) { 
    echo 'Type: ' . ($e->type->value ?? $e->type) . ' - ' . $e->description . "\n";
}
