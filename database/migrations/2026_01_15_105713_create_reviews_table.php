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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->references('id')->on('bookings');
            $table->foreignId('tour_id')->nullable()->constrained('tours')->references('id')->on('tours');
            $table->foreignId('hotel_id')->constrained('hotels')->references('id')->on('hotels');
            $table->foreignId('user_id')->nullable()->constrained('users')->references('id')->on('users');
            $table->integer('rating');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
