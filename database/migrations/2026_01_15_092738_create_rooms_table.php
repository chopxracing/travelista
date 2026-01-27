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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('room_type_id')->constrained('room_types')->references('id')->on('room_types');
            $table->foreignId('hotel_id')->constrained('hotels')->references('id')->on('hotels');
            $table->string('room_number');
            $table->integer('floor');
            $table->string('view_type');
            $table->boolean('is_smoking_available')->default(false);
            $table->foreignId('room_status_id')->constrained('room_statuses')->references('id')->on('room_statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
