<?php
try {
    file_put_contents(__DIR__.'/update_prices.log', "Script started at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    use App\Models\TourPackage;

    $packages = TourPackage::where('title', 'like', '%Bali Motor - Sewa Motor%')->get();
    if ($packages->isEmpty()) {
        file_put_contents(__DIR__.'/update_prices.log', "No motor packages found!\n", FILE_APPEND);
    }
    foreach ($packages as $pkg) {
        $old = $pkg->price;
        if (strpos($pkg->title, 'NMAX') !== false) {
            $pkg->price = 175000;
        } else {
            $pkg->price = 125000; // Vario, Fazzio, Beat, Scoopy
        }
        $pkg->save();
        file_put_contents(__DIR__.'/update_prices.log', "Updated {$pkg->title}: {$old} -> {$pkg->price}\n", FILE_APPEND);
    }
    file_put_contents(__DIR__.'/update_prices.log', "Finished successfully at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
} catch (\Exception $e) {
    file_put_contents(__DIR__.'/update_prices.log', "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
}
