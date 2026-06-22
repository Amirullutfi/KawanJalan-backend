<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('tour_packages')->onDelete('cascade');
            $table->string('customer_name', 255);
            $table->string('customer_email', 255);
            $table->string('customer_phone', 50);
            $table->date('travel_date');
            $table->integer('num_people');
            $table->decimal('total_price', 15, 2);
            $table->decimal('dp_amount', 15, 2);
            $table->dateTime('payment_deadline');
            $table->enum('payment_status', ['waiting_payment', 'paid_dp', 'completed', 'cancelled'])->default('waiting_payment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
