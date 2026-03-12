<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Merchant\Models\Parcel;

$parcels = Parcel::orderBy('id', 'desc')->take(2)->get();
foreach($parcels as $p) { 
    echo 'Parcel ' . $p->id . ' status: ' . $p->status . "\n";
    echo "Logs: \n";
    print_r($p->logs);
}
