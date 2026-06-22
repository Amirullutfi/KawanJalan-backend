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
            $table->string('booking_code')->nullable()->unique()->after('id');
        });

        // Populate existing bookings with unique codes to prevent NULL issues
        $bookings = \App\Models\Booking::whereNull('booking_code')->get();
        foreach ($bookings as $booking) {
            do {
                $code = 'EXP-' . strtoupper(\Illuminate\Support\Str::random(6));
            } while (\App\Models\Booking::where('booking_code', $code)->exists());

            $booking->booking_code = $code;
            $booking->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('booking_code');
        });
    }
};
