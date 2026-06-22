<?php
try {
    file_put_contents(__DIR__.'/update_overtime.log', "Script started at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    use App\Models\TourPackage;

    // 1. Update Bali Lepas Kunci
    $lepasKunci = TourPackage::where('title', 'like', 'Bali Lepas Kunci%')->get();
    foreach ($lepasKunci as $pkg) {
        if (strpos($pkg->description, 'Overtime:') === false) {
            $oldDesc = $pkg->description;
            // Replace the closing </div> with the overtime markup and </div>
            $newDesc = str_replace('</div>', '<p><strong>Overtime:</strong> Rp 50.000 / jam</p></div>', $oldDesc);
            $pkg->description = $newDesc;
            $pkg->save();
            file_put_contents(__DIR__.'/update_overtime.log', "Updated {$pkg->title} with Rp 50.000 / jam overtime.\n", FILE_APPEND);
            echo "Updated {$pkg->title} successfully.\n";
        } else {
            file_put_contents(__DIR__.'/update_overtime.log', "Skipped {$pkg->title} (already has overtime info).\n", FILE_APPEND);
            echo "Skipped {$pkg->title}.\n";
        }
    }

    // 2. Update Bali All In
    $allIn = TourPackage::where('title', 'like', 'Bali All In%')->get();
    foreach ($allIn as $pkg) {
        if (strpos($pkg->description, 'Overtime:') === false) {
            $oldDesc = $pkg->description;
            // Replace the closing </div> with the overtime markup and </div>
            $newDesc = str_replace('</div>', '<p><strong>Overtime:</strong> 10% per jam dari tarif sewa</p></div>', $oldDesc);
            $pkg->description = $newDesc;
            $pkg->save();
            file_put_contents(__DIR__.'/update_overtime.log', "Updated {$pkg->title} with 10% per hour overtime.\n", FILE_APPEND);
            echo "Updated {$pkg->title} successfully.\n";
        } else {
            file_put_contents(__DIR__.'/update_overtime.log', "Skipped {$pkg->title} (already has overtime info).\n", FILE_APPEND);
            echo "Skipped {$pkg->title}.\n";
        }
    }

    file_put_contents(__DIR__.'/update_overtime.log', "Finished successfully at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    echo "Done.\n";
} catch (\Exception $e) {
    file_put_contents(__DIR__.'/update_overtime.log', "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
    echo "Error: " . $e->getMessage() . "\n";
}
