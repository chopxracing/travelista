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
        Schema::create('booking_tourists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tourist_id')->constrained('tourists')->references('id')->on('tourists');
            $table->foreignId('booking_id')->constrained('bookings')->references('id')->on('bookings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_tourists');
    }
};
