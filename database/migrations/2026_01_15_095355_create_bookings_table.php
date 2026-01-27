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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->references('id')->on('users');
            $table->foreignId('hotel_id')->nullable()->constrained('hotels')->references('id')->on('hotels');
            $table->foreignId('tour_id')->nullable()->constrained('tours')->references('id')->on('tours');
            $table->foreignId('status_id')->constrained('booking_statuses')->references('id')->on('booking_statuses');
            $table->boolean('is_paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
