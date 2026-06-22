<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TourPackage;

$packages = TourPackage::where('title', 'like', '%Motor%')->get(['id', 'title', 'price']);
print_r($packages->toArray());
