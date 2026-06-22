<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TourPackage;

$packages = TourPackage::where('title', 'like', '%Bali Motor - Sewa Motor%')->get();
foreach ($packages as $pkg) {
    echo "Updating: {$pkg->title}\n";
    echo "  Old price: {$pkg->price}\n";
    if (strpos($pkg->title, 'NMAX') !== false) {
        $pkg->price = 175000;
    } else {
        $pkg->price = 125000; // Vario, Fazzio, Beat, Scoopy
    }
    $pkg->save();
    echo "  New price: {$pkg->price}\n";
}

echo "Database updated successfully!\n";
