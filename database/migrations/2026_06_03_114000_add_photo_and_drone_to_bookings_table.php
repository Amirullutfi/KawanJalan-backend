<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('photo_package_name', 50)->nullable()->after('unique_code');
            $table->decimal('photo_package_price', 15, 2)->default(0)->after('photo_package_name');
            $table->boolean('addon_drone')->default(false)->after('photo_package_price');
            $table->decimal('addon_drone_price', 15, 2)->default(0)->after('addon_drone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['photo_package_name', 'photo_package_price', 'addon_drone', 'addon_drone_price']);
        });
    }
};
