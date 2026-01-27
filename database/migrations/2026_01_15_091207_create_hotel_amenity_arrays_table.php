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
        Schema::create('hotel_amenity_arrays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_amenity_id')->constrained('hotel_amenities')->references('id')->on('hotel_amenities');
            $table->foreignId('hotel_id')->constrained('hotels')->references('id')->on('hotels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_amenity_arrays');
    }
};
