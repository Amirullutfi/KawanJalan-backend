<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title', 150);
            $table->string('slug', 170)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->string('location', 150)->nullable();
            $table->boolean('featured')->default(false);
            $table->string('main_image', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
